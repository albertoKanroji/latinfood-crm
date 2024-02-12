<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use App\Mail\NewSale;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Models\Denomination;
use App\Models\SaleDetail;
use Livewire\Component;
use App\Models\Customer;
use App\Models\User;
use App\Traits\CartTrait;
use Illuminate\Support\Facades\Mail;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Lotes;
use App\Traits\Utils;
use DB;
use App\Models\Inspectors;
use Illuminate\Http\Request;

class PosController extends Component
{
	use Utils;
	use CartTrait;

	public $total, $itemsQuantity, $efectivo, $change, $cliente;
	public $searchTerm = '';
	public $buscar = '';
	public function comando(Request $request)
	{
		// Obtener las ventas que cumplen con las condiciones
		$salesToUpdate = Sale::where('status', 'PAID')
			->where('status_envio', 'PENDIENTE')
			->get();

		foreach ($salesToUpdate as $sale) {
			// Obtener los detalles de la venta
			$saleDetails = SaleDetail::where('sale_id', $sale->id)->get();

			// Verificar si todos los detalles tienen scanned = 1
			$allDetailsScanned = $saleDetails->every(function ($detail) {
				return $detail->scanned == 1;
			});

			if ($allDetailsScanned) {
				// Actualizar el estado de envío de la venta
				$sale->update(['status_envio' => 'ACTUAL']);
			}
		}

		return response()->json(['message' => 'Proceso completado'], 200);

	}

	public function mount()
	{
		$cliente = Customer::find($this->cliente);
		$this->efectivo = 0;
		$this->change = 0;
		$this->cliente = 'Elegir';
		$this->total = Cart::getTotal();
		$this->itemsQuantity = Cart::getTotalQuantity();
	}


	public function payWithCreditApi(Request $request)
	{
		$data = $request->validate([
			'cliente' => 'required|integer',
			'total' => 'required|numeric',
			'efectivo' => 'required|numeric',
			'change' => 'required|numeric',
			'items' => 'required|array',
			'items.*.id' => 'required|integer',
			'items.*.quantity' => 'required|integer|min:1',
		]);


		$cliente = Customer::find($data['cliente']);

		if (!$cliente) {
			return response()->json(['error' => 'SELECCIONA UN CLIENTE'], 400);
		}

		if ($cliente->saldo >= $data['total']) {
			// Actualizar el saldo del cliente
			$cliente->saldo -= $data['total'];
			$cliente->save();

			// Crear la venta
			$sale = Sale::create([
				'total' => $data['total'],
				'items' => array_sum(array_column($data['items'], 'quantity')),
				'cash' => $data['efectivo'],
				'change' => $data['change'],

				'CustomerID' => $data['cliente'],
			]);

			if ($sale) {
				foreach ($data['items'] as $item) {
					$product = Product::find($item['id']);

					SaleDetail::create([
						'price' => $product->price,
						'quantity' => $item['quantity'],
						'product_id' => $product->id,
						'sale_id' => $sale->id,
						'CustomerID' => $data['cliente'],
					]);

					$product->stock -= $item['quantity'];
					$product->save();

					// $this->updateWooCommerceStock($product->barcode, $product->stock);
				}
			}

			// Enviar correo electrónico al cliente
			/*   $customer = Customer::find($data['cliente']);
				 $emailData = [
					 'customer' => $customer,
					 'sale' => $sale,
					 'items' => $data['items'],
				 ];
				 Mail::to($customer->email)->send(new NewSale($emailData));

				*/
			// Después de completar la compra con éxito
			event(new \App\Events\CompraRealizada($sale));

			return response()->json(['message' => 'Compra realizada con éxito'], 200);

		} else {
			return response()->json(['error' => 'Saldo insuficiente para realizar la compra'], 400);
		}
	}
	private function showNotification($message)
	{
		// Lógica para mostrar la notificación usando Push.js
		echo '<script>Push.create("Compra Realizada", { body: "' . $message . '", timeout: 4000 });</script>';
	}
	public function payWithCredit()
	{
		$cliente = Customer::find($this->cliente);

		if (!$cliente) {
			// Manejar el caso en el que no se encuentre el cliente seleccionado
			$this->emit('sale-error', 'SELECCIONA UN CLIENTE');
			return;
		}

		if ($cliente->saldo >= $this->total) {
			// Actualizar el saldo del cliente
			$cliente->saldo -= $this->total;
			$cliente->save();

			// Crear la venta
			$sale = Sale::create([
				'total' => $this->total,
				'items' => $this->itemsQuantity,
				'cash' => $this->efectivo,
				'change' => $this->change,
				'user_id' => Auth()->user()->id,
				'CustomerID' => $this->cliente,
			]);

			if ($sale) {
				$items = Cart::getContent();

				foreach ($items as $item) {

					$product = Product::where('id', $item->id)->first();

					$cliente = Customer::find($this->cliente);


					SaleDetail::create([
						'price' => $item->price,
						'quantity' => $item->quantity,
						'product_id' => $item->id,
						'sale_id' => $sale->id,

						'CustomerID' => $this->cliente,



					]);

					$product = Product::find($item->id);
					$product->stock -= $item->quantity;
					$product->save();

					$this->updateWooCommerceStock($product->barcode, $product->stock);
				}
			}
			// Enviar correo electrónico al cliente
			$customer = Customer::find($this->cliente);
			$emailData = [
				'customer' => $customer,
				'sale' => $sale,
				'items' => $items,
			];
			Mail::to($customer->email)->send(new NewSale($emailData));
			// Limpiar el carrito y restablecer los valores
			Cart::clear();
			$this->efectivo = 0;
			$this->change = 0;
			$this->total = Cart::getTotal();
			$this->itemsQuantity = Cart::getTotalQuantity();

			// Emitir evento de éxito de la venta
			$this->emit('sale-ok', 'Venta registrada con éxito');
			$user = Auth()->user()->name;
			$inspector = Inspectors::create([
				'user' => $user,
				'action' => 'Registro una venta ',
				'seccion' => 'Sales'
			]);
			$ticket = $this->buildTicket($sale);
			$d = $this->Encrypt($ticket);
			$this->emit('print-ticket', $d);
		} else {
			// Manejar el caso en el que el saldo del cliente sea insuficiente
			$this->emit('sale-error', 'Saldo insuficiente para realizar la compra');
		}
	}
	private function updateWooCommerceStock($barcode, $stock)
	{
		// Configurar la URL y los datos para la solicitud a la API de WooCommerce
		$url = 'https://kdlatinfood.com/wp-json/wc/v3/products';
		$productId = null;

		// Buscar el producto en WooCommerce por SKU ($barcode)
		$response = Http::withBasicAuth('ck_8e38a879e7f6ce0d56e34c525de194a60c2e2ce5', 'cs_723eab16e53f3607fd38984b00f763310cc4f473')
			->get($url, ['sku' => $barcode]);

		if ($response->successful()) {
			$products = $response->json();
			if (!empty($products)) {
				// Obtener el ID del producto en WooCommerce
				$productId = $products[0]['id'];
			}
		}

		if ($productId) {
			// Actualizar el stock del producto en WooCommerce
			$response = Http::withBasicAuth('ck_8e38a879e7f6ce0d56e34c525de194a60c2e2ce5', 'cs_723eab16e53f3607fd38984b00f763310cc4f473')
				->put("$url/$productId", ['stock_quantity' => $stock]);

			if ($response->successful()) {
				// El stock se actualizó correctamente en WooCommerce
				// Puedes realizar alguna acción adicional si lo deseas
				// Por ejemplo, registrar una entrada en los archivos de registro

			} else {
				// Error al actualizar el stock en WooCommerce
				// Puedes manejar el error según tus necesidades

			}
		} else {
			// No se encontró el producto en WooCommerce por SKU
			// Puedes manejar esta situación según tus necesidades

		}
	}
	public function updatedSearch()
	{
		$this->resetPage();
	}
	public function render()
	{
		$data2 = Customer::all();

		$data3 = Customer::where('name', 'like', '%' . $this->buscar . '%')
			->orWhere('last_name', 'like', '%' . $this->buscar . '%')
			->get();
		$filteredClientes = Customer::where('name', 'LIKE', '%' . $this->searchTerm . '%')
			->orWhere('last_name', 'LIKE', '%' . $this->searchTerm . '%')
			->get();

		return view('livewire.pos.component', [
			'data2' => $data2,
			'data3' => $data3,
			'filteredClientes' => $filteredClientes,
			'denominations' => Denomination::orderBy('value', 'desc')->get(),
			'cart' => Cart::getContent()->sortBy('name')
		])
			->extends('layouts.theme.app')
			->section('content');
	}

	// agregar efectivo / denominations
	public function ACash($value)
	{
		$this->efectivo += ($value == 0 ? $this->total : $value);
		$this->change = ($this->efectivo - $this->total);
	}

	// escuchar eventos
	protected $listeners = [
		'scan-code' => 'ScanCode',
		'removeItem' => 'removeItem',
		'clearCart' => 'clearCart',
		'saveSale' => 'saveSale',
		'refresh' => '$refresh',
		'print-last' => 'printLast'
	];


	// buscar y agregar producto por escaner y/o manual


	public function ScanCode($barcode, $cant = 1)
	{
		$product = Product::where('barcode', $barcode)->first();

		if ($product == null || empty($product)) {
			$this->emit('scan-notfound', 'El producto no está registrado*');
		} else {
			if ($this->InCart($product->id)) {
				$this->IncreaseQuantity($product);
				return;
			}

			if ($product->stock < 1) {
				$this->emit('no-stock', 'Stock insuficiente *');
				return;
			}
			Cart::add($product->id, $product->name, $product->price, $cant, $product->imagen);
			$this->total = Cart::getTotal();
			$this->itemsQuantity = Cart::getTotalQuantity();

			$this->emit('scan-ok', 'PRODUCTO AGREGADO');
		}
	}





	// incrementar cantidad item en carrito
	public function increaseQty(Product $product, $cant = 1)
	{
		$this->IncreaseQuantity($product, $cant);
	}


	// actualizar cantidad item en carrito
	public function updateQty(Product $product, $cant = 1)
	{
		if ($cant <= 0)
			$this->removeItem($product->id);
		else
			$this->UpdateQuantity($product, $cant);
	}

	// decrementar cantidad item en carrito
	public function decreaseQty($productId)
	{
		$this->decreaseQuantity($productId);
	}

	// vaciar carrito
	public function clearCart()
	{
		$this->updateLotesEstadoToNormal();
		$this->trashCart();
	}


	public function cleanValue($value)
	{
		return number_format(str_replace(",", "", $value), 2, '.', '');
	}


	// guardar venta
	// Método saveSale()
	public function saveSale()
	{

		if ($this->total <= 0) {
			$this->emit('sale-error', 'AGREGA PRODUCTOS A LA VENTA');
			return;
		}
		if ($this->cliente <= 0) {
			$this->emit('sale-error', 'SELECCIONA UN CLIENTE');
			return;
		}
		if ($this->efectivo <= 0) {
			$this->emit('sale-error', 'INGRESA EL EFECTIVO');
			return;
		}
		if ($this->total > $this->efectivo) {
			$this->emit('sale-error', 'EL EFECTIVO DEBE SER MAYOR O IGUAL AL TOTAL');
			return;
		}
		$cliente = Customer::find($this->cliente);

		if (!$cliente) {
			// Manejar el caso en el que no se encuentre el cliente seleccionado
			$this->emit('sale-error', 'SELECCIONA UN CLIENTE');
			return;
		}
		DB::beginTransaction();

		$rules = [
			'CustomerID' => 'required|not_in:Elegir',

		];

		$messages = [
			'CustomerID.not_in' => 'Elige una opción',


		];
		try {
			$sale = Sale::create([
				'total' => $this->total,
				'items' => $this->itemsQuantity,
				'cash' => $this->efectivo,
				'change' => $this->change,
				'user_id' => Auth()->user()->id,
				'CustomerID' => $this->cliente,
			]);

			if ($sale) {
				$items = Cart::getContent();

				foreach ($items as $item) {

					$product = Product::where('id', $item->id)->first();

					$cliente = Customer::find($this->cliente);


					SaleDetail::create([
						'price' => $item->price,
						'quantity' => $item->quantity,
						'product_id' => $item->id,
						'sale_id' => $sale->id,

						'CustomerID' => $this->cliente,



					]);
					// Actualizar el estado del lote a "Apartado"

					// Actualizar el stock del producto permitiendo números negativos
					$product = Product::find($item->id);
					$product->stock -= $item->quantity;
					$product->save();
					$this->updateWooCommerceStock($product->barcode, $product->stock);
				}
			}
			// Enviar correo electrónico al cliente
			$customer = Customer::find($this->cliente);
			$emailData = [
				'customer' => $customer,
				'sale' => $sale,
				'items' => $items,
			];
			Mail::to($customer->email)->send(new NewSale($emailData));
			DB::commit();
			Cart::clear();
			$this->efectivo = 0;
			$this->change = 0;
			$this->total = Cart::getTotal();
			$this->itemsQuantity = Cart::getTotalQuantity();
			$this->emit('sale-ok', 'Venta registrada con éxito');
			$user = Auth()->user()->name;
			$inspector = Inspectors::create([
				'user' => $user,
				'action' => 'Registro una venta ',
				'seccion' => 'Sales'
			]);
			$ticket = $this->buildTicket($sale);
			$d = $this->Encrypt($ticket);
			$this->emit('print-ticket', $d);
		} catch (Exception $e) {
			DB::rollback();
			$this->emit('sale-error', $e->getMessage());
		}
	}






	public function savePurchase()
	{
		if ($this->total <= 0) {
			$this->emit('sale-error', 'AGEGA PRODUCTOS A LA VENTA');
			return;
		}
		if ($this->efectivo <= 0) {
			$this->emit('sale-error', 'INGRESA EL EFECTIVO');
			return;
		}
		if ($this->total > $this->efectivo) {
			$this->emit('sale-error', 'EL EFECTIVO DEBE SER MAYOR O IGUAL AL TOTAL');
			return;
		}

		DB::beginTransaction();

		try {

			$sale = Sale::create([
				'total' => $this->total,
				'items' => $this->itemsQuantity,
				'cash' => $this->efectivo,
				'change' => $this->change,
				'user_id' => Auth()->user()->id,
				'cliente_id' => $this->cliente,
			]);

			if ($sale) {
				$items = Cart::getContent();
				foreach ($items as $item) {
					SaleDetail::create([
						'price' => $item->price,
						'quantity' => $item->quantity,
						'product_id' => $item->id,
						'sale_id' => $sale->id,
					]);

					//update stock
					$product = Product::find($item->id);
					$product->stock = $product->stock + $item->quantity;
					$product->save();
				}
			}


			DB::commit();
			//$this->printTicket($sale->id);
			Cart::clear();
			$this->efectivo = 0;
			$this->change = 0;
			$this->total = Cart::getTotal();
			$this->itemsQuantity = Cart::getTotalQuantity();
			$this->emit('sale-ok', 'Venta registrada con éxito');
			$ticket = $this->buildTicket($sale);
			$d = $this->Encrypt($ticket);
			$this->emit('print-ticket', $d);
			//$this->emit('print-ticket', $sale->id);

		} catch (Exception $e) {
			DB::rollback();
			$this->emit('sale-error', $e->getMessage());
		}
	}


	public function printTicket($ventaId)
	{
		return \Redirect::to("print://$ventaId");
	}



	public function buildTicket($sale)
	{

		$details = SaleDetail::join('products as p', 'p.id', 'sale_details.product_id')
			->select('sale_details.*', 'p.name')
			->where('sale_id', $sale->id)
			->get();

		// opcion 1
		/*
			  $products ='';
			  $info = "folio: $sale->id|";
			  $info .= "date: $sale->created_at|";		
			  $info .= "cashier: {$sale->user->name}|";
			  $info .= "total: $sale->total|";
			  $info .= "items: $sale->items|";
			  $info .= "cash: $sale->cash|";
			  $info .= "change: $sale->change|";
			  foreach ($details as $product) {
				  $products .= $product->name .'}';
				  $products .= $product->price .'}';
				  $products .= $product->quantity .'}#';
			  }

			  $info .=$products;
			  return $info;
			  */

		// opcion 2
		$sale->user_id = $sale->user->id;
		$r = $sale->toJson() . '|' . $details->toJson();
		//$array[] = json_decode($sale, true);
		//$array[] = json_decode($details, true);
		//$result = json_encode($array, JSON_PRETTY_PRINT);

		//dd($r);
		return $r;
	}


	public function printLast()
	{
		$lastSale = Sale::latest()->first();

		if ($lastSale)
			$this->emit('print-last-id', $lastSale->id);
	}

}
