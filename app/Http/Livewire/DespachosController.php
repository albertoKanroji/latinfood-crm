<?php

namespace App\Http\Livewire;

use App\Mail\Despachos;
use App\Models\Customer;
use App\Mail\EnvioCamino;
use App\Models\Sale;
use Illuminate\Support\Facades\Mail;
use App\Models\Envio;
use Illuminate\Support\Facades\Log;
use Automattic\WooCommerce\Client;
use App\Models\Product;
use App\Models\Operario;
use App\Models\Lotes;
use App\Models\SaleDetail;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Traits\CartTrait;
use App\Models\Inspectors;
use Illuminate\Http\Request;

class DespachosController extends Component
{
    public $selected_id, $saleId, $lotCount, $sumDetails, $reportType, $countDetails, $pageTitle, $componentName, $userId;
    public $quantities = [];
    public $newProducts = [
        'sku' => '',
        'name' => '',
        'items' => 0,
    ];

    public $newRowKey = 0;


    public $selectedProducts = [];
    public $addProduct = false;
    public function render()
    {
        $prod = Product::where('TieneKey', 'si')->get();

        $data0 = Customer::all();
        $data = Customer::with('sale')->get();
        $data2 = Sale::all();
        $data3 = SaleDetail::with('sales')->get();

        $lotes = Lotes::all();
        return view('livewire.despachos.despachos', ['data' => $data, 'lotes' => $lotes, 'prod' => $prod, 'data2' => $data2, 'data0' => $data0, 'data3' => $data3])
            ->extends('layouts.theme.app')
            ->section('content');
        ;
    }
    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Despacho';
        $this->details = [];
        $this->sumDetails = 0;
        $this->countDetails = 0;
        $this->reportType = 0;
        $this->userId = 0;
        $this->saleId = 0;

    }
    public function getAllSalesPending()
    {
        try {
            $sales = Sale::with('salesDetails.product', 'customer')
                ->orderBy('id', 'desc')
                ->where('status_envio', 'PENDIENTE')
                ->where('status', 'PENDING')
                ->get();

            return response()->json(['success' => true, 'data' => $sales], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al obtener las ventas.'], 500);
        }
    }

    public function getAllSales()
    {
        try {
            // Obtén todas las ventas ordenadas por su fecha de creación en orden ascendente (de la primera a la última)
            $sales = Sale::with('salesDetails.product', 'customer')
                ->orderBy('id', 'desc')
                ->get();

            return response()->json(['success' => true, 'data' => $sales], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al obtener las ventas.'], 500);
        }
    }
    public function getSaleDetails($id)
    {
        try {
            // Obtén los detalles de la venta con el ID proporcionado
            $sale = Sale::with('salesDetails.product', 'customer')
                ->where('id', $id)
                ->first();

            if (!$sale) {
                return response()->json(['success' => false, 'message' => 'Venta no encontrada.'], 404);
            }

            return response()->json(['success' => true, 'data' => $sale], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al obtener los detalles de la venta.'], 500);
        }
    }

    public function resetUI()
    {
    }
    public function getDetails($saleId)
    {
        $this->details = SaleDetail::join('products as p', 'p.id', 'sale_details.product_id')
            ->select('sale_details.id', 'sale_details.price', 'sale_details.quantity', 'p.name as product', 'p.barcode')
            ->where('sale_details.sale_id', $saleId)
            ->get();

        $suma = $this->details->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $this->sumDetails = $suma;
        $this->countDetails = $this->details->sum('quantity');
        $this->saleId = $saleId;

        $this->emit('show-modal', 'details loaded');
    }


    protected $listeners = [
        'CargarPedido' => 'Cargar',
        'EditarPedido' => 'EditPedido',
        'GuardarEditado' => 'GuardarEditado',
        'removeProduct' => 'removeProduct',
    ];
    public function GuardarEditado($id)
    {

        $sale = Sale::findOrFail($id);
        $sale->editado = 'si';
        $sale->save();

        $cliente = $sale->customer;

        $user = Auth()->user()->name;
        $inspector = Inspectors::create([
            'user' => $user,
            'action' => 'Se edito el pedido #' . $sale->id,
            'seccion' => 'Despachos'
        ]);

        $this->emit('hidedetailsedit', 'Lote Agregado');
    }


    public function EditPedido($id)
    {
        $this->emit('hide-details-modal', 'Lote Agregado');

        $sale = Sale::findOrFail($id); // Obtener el pedido por su ID
        $this->emit('showedit', 'Lote Agregado');

        $detallesPedido = SaleDetail::join('products as p', 'p.id', 'sale_details.product_id')
            ->select('sale_details.quantity', 'sale_details.price', 'p.name as product', 'sale_details.lot_id')
            ->where('sale_details.sale_id', $sale->id)
            ->get();

        // Obtener los detalles originales de la venta
        $this->details = SaleDetail::where('sale_id', $id)->get();

        // Inicializar las listas de productos y cantidades con los detalles originales
        foreach ($this->details as $key => $detail) {
            $this->selectedProducts[$key] = $detail->product_id;
            $this->quantities[$key] = $detail->quantity;
        }

    }

    public function toggleAddProduct()
    {
        $this->addProduct = !$this->addProduct;
    }

    public function addProductRow()
    {
        // Valida que tengas una venta seleccionada
        if (!$this->saleId) {
            return;
        }

        // Crea un nuevo registro en la tabla de sale_details
        $newSaleDetail = new SaleDetail();
        $newSaleDetail->sale_id = $this->saleId; // Asigna el ID de la venta seleccionada
        $newSaleDetail->product_id = $this->getProductIdBySku($this->newProducts['sku']); // Obtén el ID del producto por SKU
        $newSaleDetail->quantity = $this->newProducts['items'];
        $newSaleDetail->price = $this->getProductPRICEBySku($this->newProducts['sku']);
        $newSaleDetail->save();

        // Recarga los detalles de la venta
        $this->details = SaleDetail::where('sale_id', $this->saleId)->get();
        $this->emit('global-msg', "Producto Agregado a la venta");
        // Restablece los valores de $newProducts
        $this->newProducts = [
            'sku' => '',
            'name' => '',
            'items' => 0,
        ];
    }
    public function addProductToSale(Request $request)
    {
        // Valida los parámetros de la solicitud
        $request->validate([
            'sale_id' => 'required|integer', // Asegúrate de que sale_id sea un número entero
            'barcode' => 'required|string', // Asegúrate de que barcode sea una cadena de texto
            'quantity' => 'required|integer', // Asegúrate de que quantity sea un número entero
        ]);

        // Obtiene los valores de la solicitud
        $saleId = $request->input('sale_id');
        $barcode = $request->input('barcode');
        $quantity = $request->input('quantity');

        // Valida que tengas una venta seleccionada (puedes hacer esto según tu lógica específica)
        if (!$saleId) {
            return response()->json(['message' => 'No se ha seleccionado una venta.'], 400);
        }

        // Obtiene el ID del producto por SKU (código de barras)
        $productId = $this->getProductIdBySku($barcode);

        if (!$productId) {
            return response()->json(['message' => 'No se encontró un producto con el código de barras proporcionado.'], 404);
        }

        // Crea un nuevo registro en la tabla de sale_details
        $newSaleDetail = new SaleDetail();
        $newSaleDetail->sale_id = $saleId;
        $newSaleDetail->product_id = $productId;
        $newSaleDetail->quantity = $quantity;
        $newSaleDetail->price = $this->getProductPRICEBySku($barcode);
        $newSaleDetail->save();
        // Actualiza el total en la tabla principal Sale
        $sale = Sale::find($saleId);
        $sale->total += ($quantity * $newSaleDetail->price); // Supongamos que tienes un campo "price" en la tabla de productos
        $sale->items += $quantity; // Actualiza la cantidad total de productos en la venta
        $sale->save();
        // Puedes retornar una respuesta de éxito si lo deseas
        return response()->json(['message' => 'Producto agregado a la venta con éxito.'], 200);
    }
    public function updateSaleAPI(Request $request)
    {
        try {
            // Validar los datos de la solicitud
            $request->validate([
                'sale_id' => 'required|exists:sales,id',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            // Obtener los datos de la solicitud
            $saleId = $request->input('sale_id');
            $productId = $request->input('product_id');
            $newQuantity = $request->input('quantity');

            // Buscar el detalle de venta correspondiente
            $saleDetail = SaleDetail::where('sale_id', $saleId)
                ->where('product_id', $productId)
                ->first();

            if (!$saleDetail) {
                return response()->json(['message' => 'Detalle de venta no encontrado'], 404);
            }
            // Calcular el cambio en la cantidad
            $oldQuantity = $saleDetail->quantity;
            $quantityChange = $newQuantity - $oldQuantity;

            // Actualizar la cantidad en el detalle de venta
            $saleDetail->quantity = $newQuantity;
            $saleDetail->save();

            // Actualizar el total en la tabla principal Sale
            $sale = Sale::find($saleId);
            $sale->total += ($quantityChange * $saleDetail->product->price); // Supongamos que tienes un campo "price" en la tabla de productos
            $sale->save();

            // Puedes devolver una respuesta de éxito
            return response()->json(['message' => 'Cantidad actualizada con éxito']);

        } catch (\Exception $e) {
            // Manejo de errores en caso de excepción
            return response()->json(['message' => 'Error al actualizar la cantidad', 'error' => $e->getMessage()], 500);
        }
    }
    public function updateSale()
    {
        // Asegúrate de tener la venta seleccionada
        if (!$this->saleId) {
            return;
        }

        // Recorre las cantidades actualizadas y actualiza los registros en la base de datos
        foreach ($this->quantities as $key => $quantity) {
            // Obtén el detalle de venta correspondiente por su índice
            $detail = $this->details[$key];

            // Actualiza la cantidad en el detalle de venta
            $detail->quantity = $quantity;
            $detail->save();
        }

        // Limpiar las cantidades después de la actualización
        // $this->quantities = [];
        $this->emit('global-msg', "Cantidades actualizadas");
        // Puedes agregar un mensaje de éxito o realizar otras acciones después de la actualización
    }

    public function getProductIdBySku($sku)
    {
        // Busca el producto por SKU en la base de datos
        $product = Product::where('barcode', $sku)->first();

        // Si se encuentra el producto, devuelve su ID; de lo contrario, devuelve null
        if ($product) {
            return $product->id;
        } else {
            return null;
        }
    }
    public function getProductPRICEBySku($sku)
    {
        // Busca el producto por SKU en la base de datos
        $product = Product::where('barcode', $sku)->first();

        // Si se encuentra el producto, devuelve su ID; de lo contrario, devuelve null
        if ($product) {
            return $product->price;
        } else {
            return null;
        }
    }


    public function removeNewProduct()
    {
        // Restablecer los valores de $newProducts
        $this->newProducts = [
            'sku' => '',
            'name' => '',
            'items' => 0,
        ];

        // Ocultar el formulario de adición
        $this->addProduct = false;
    }

    public function removeProduct($key)
    {
        // Asegúrate de tener la venta seleccionada
        if (!$this->saleId) {
            return;
        }

        // Obtén el ID del detalle de venta que deseas eliminar
        $saleDetailId = $this->details[$key]['id'];

        // Elimina el registro del detalle de venta de la base de datos
        SaleDetail::where('id', $saleDetailId)->delete();

        // Recarga los detalles de la venta
        $this->details = SaleDetail::where('sale_id', $this->saleId)->get();
        $this->emit('global-msg', "Producto Eliminado");
    }
    public function removeProductFromSale($saleDetailId)
    {


        // Busca el detalle de venta por su ID y verifica si pertenece a la venta actual
        $saleDetail = SaleDetail::where('id', $saleDetailId)->first();

        if (!$saleDetail) {
            return response()->json(['message' => 'El detalle de venta no existe o no pertenece a esta venta.'], 404);
        }

        // Elimina el registro del detalle de venta de la base de datos
        $saleDetail->delete();



        return response()->json(['message' => 'Producto eliminado de la venta con éxito.'], 200);
    }
    public function Cargar($id)
    {
        $sale = Sale::findOrFail($id); // Obtener el pedido por su ID



        $wooCommerceOrderId = $sale->woocommerce_order_id;

        // Verificar si existe el woocommerce_order_id
        if ($wooCommerceOrderId) {
            $wooCommerceClient = new \Automattic\WooCommerce\Client(
                'https://kdlatinfood.com', // URL de tu tienda WooCommerce
                'ck_8e38a879e7f6ce0d56e34c525de194a60c2e2ce5',
                'cs_723eab16e53f3607fd38984b00f763310cc4f473',
                [
                    'wp_api' => true,
                    'version' => 'wc/v3',
                ]
            );
            $wooCommerceClient->put('orders/' . $wooCommerceOrderId, ['status' => 'on-hold']);
            Log::info('estado actualizado en woocomerce');
        } else {
            Log::info('No se encontró el woocommerce_order_id');
        }

        // Actualizar el estado del pedido
        $sale->status = 'PAID';
        $sale->status_envio = 'PENDIENTE';
        $sale->save();
        // Crear un nuevo registro en la tabla "Envio"
        $envio = new Envio();
        $envio->id_sale = $sale->id;

        // Asignar aleatoriamente un ID de transportista
        $transportista = Operario::inRandomOrder()->first();
        $envio->id_transport = $transportista->id;

        $envio->save();

        // Obtener los detalles del pedido
        $detallesPedido = SaleDetail::join('products as p', 'p.id', 'sale_details.product_id')
            ->select('sale_details.quantity', 'sale_details.price', 'p.name as product', 'sale_details.lot_id')
            ->where('sale_details.sale_id', $sale->id)
            ->get();



        // Obtener el cliente asociado al pedido
        $cliente = $sale->customer;

        // Enviar el correo electrónico al cliente
        Mail::to($cliente->email)->send(new Despachos($sale, $envio, $detallesPedido, $cliente));
        $user = Auth()->user()->name;
        $inspector = Inspectors::create([
            'user' => $user,
            'action' => 'Se cargo el pedido #' . $sale->id,
            'seccion' => 'Despachos'
        ]);

        $this->emit('hide-details-modal', 'Lote Agregado');
    }
    public function cargarSale($id)
    {
        try {

            $sale = Sale::findOrFail($id);

            $wooCommerceOrderId = $sale->woocommerce_order_id;

            // Tu lógica para actualizar el estado en WooCommerce aquí...

            // Actualizar el estado del pedido
            $sale->status = 'PAID';
            $sale->status_envio = 'PENDIENTE';
            $sale->save();

            // Crear un nuevo registro en la tabla "Envio"
            $envio = new Envio();
            $envio->id_sale = $sale->id;

            // Asignar aleatoriamente un ID de transportista
            $transportista = Operario::inRandomOrder()->first();
            $envio->id_transport = $transportista->id;
            $envio->save();

            // Obtener los detalles del pedido
            $detallesPedido = SaleDetail::join('products as p', 'p.id', 'sale_details.product_id')
                ->select('sale_details.quantity', 'sale_details.price', 'p.name as product', 'sale_details.lot_id')
                ->where('sale_details.sale_id', $sale->id)
                ->get();

            // Obtener el cliente asociado al pedido
            $cliente = $sale->customer;

            // Enviar el correo electrónico al cliente
            Mail::to($cliente->email)->send(new Despachos($sale, $envio, $detallesPedido, $cliente));

            // Registrar la acción en el sistema
            /* $user = Auth()->user()->name;
        Inspectors::create([
            'user' => $user,
            'action' => 'Se cargo el pedido #' . $sale->id,
            'seccion' => 'Despachos'
        ]);*/

            return response()->json(['success' => true, 'message' => 'Pedido cargado exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al cargar el pedido: ' . $e->getMessage()], 500);
        }
    }
}