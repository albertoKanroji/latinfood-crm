<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Barcode;
use Livewire\Component;
use App\Models\Product;
use App\Models\User;
use App\Models\Insumo;
use App\Models\Sabores;
use App\Models\Category;
use App\Models\Lotes;




class LotesController extends Component
{


    public $Nombre_Lote, $search, $CodigoBarras, $User, $Cantidad_Articulos,
        $Fecha_Vencimiento, $SKU, $selected_id, $pageTitle,
        $componentName;

    public $categoryid;

    public $producto = [], $sabor, $productos;
    public $cantidad;


    private $pagination = 5;
    public $buscar = '';
    public function agregarProducto()
    {
        // Validar que se haya seleccionado un producto y se haya ingresado una cantidad
        if ($this->producto && $this->cantidad) {
            // Agregar el producto a la tabla o colección
            // Por ejemplo, si tienes una propiedad $productos de tipo array:
            $this->productos[] = [
                'producto' => $this->producto,
                'cantidad' => $this->cantidad
            ];

            // Reiniciar los campos del subformulario
            $this->producto = null;
            $this->cantidad = null;
        }
    }

    public function eliminarProducto($index)
    {
        unset($this->productos[$index]);
    }


    public function renderTable()
    {
        $this->emit('tableRendered'); // Emitir un evento para que Livewire renderice la tabla del subformulario
    }
    public function updateProductStock($productId, $addedStock)
    {
        $product = Product::findOrFail($productId);
        $currentStock = $product->stock;
        $newStock = $currentStock + $addedStock;
        $product->update(['stock' => $newStock]);
        $this->emit('global-msg', "SE ACTUALIZO EL STOCK");
    }


    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->subform = [
            ['id' => uniqid(), 'BAR' => '', 'CANT' => '']
        ];
        $this->pageTitle = 'Listado';
        $this->componentName = 'Lotes';
        $this->lot = 'Elegir';

        $this->flavor = 'Elegir';
    }

    public function updateSKU()
    {

        $producto = Product::where('sku', $this->barcode)->first();
        if ($producto) {
            $this->Nombre_Lote = $producto->name;
        } else {
            $this->Nombre_Lote = "";
        }
    }
    //$barcodeNumber=rand(pow(10,9),pow(10,10)-1);
    public function updatedSearch()
    {
        $this->resetPage();
    }


    public function render()
    {

        $dataC = Category::all();
        $data2 = Product::all();
        $prod = Product::all();
        $data2 = Product::with('categoria')->get();
        $data3 = Product::where('barcode', 'like', '%' . $this->search . '%')
            ->orWhere('name', 'like', '%' . $this->search . '%')
            ->where('estado', 'CRUDO')
            ->get();
        $lotes_insumo = Insumo::all();
        $filteredLots = $lotes_insumo->where('idSabor', $this->sabor);
        $insumo = Sabores::whereIn('id', $data2->pluck('sabor_id'))->get();

        $flavorsData = Sabores::all();

        $data = Lotes::with('producto')->get();


        return view('livewire.lotes.component', [
            'data' => $data,  'subform' => $this->subform, 'data2' => $data2, 'filteredLots' => $filteredLots, 'lotes_insumo' => $lotes_insumo, 'flavorsData' => $flavorsData, 'insumo' => $insumo, 'prod' => $prod, 'data3' => $data3, 'dataC' => $dataC, 'lotes' => Lotes::orderBy('Nombre_Lote', 'asc')->get(),
            'user' => old('User', Auth()->user()->name)
        ])->extends('layouts.theme.app')
            ->section('content');
    }




    public function Store()
    {

        $rules = [
            'Cantidad_Articulos' => 'required|not_in:Elegir',
            'SKU' => 'required|not_in:Elegir',
        ];

        $messages = [
            'Cantidad_Articulos.not_in' => 'Elige una opción',
            'SKU.not_in' => 'Elige una opción',

        ];

        $this->validate($rules, $messages);

        $barcodeNumber = "770" . str_pad(mt_rand(0, 99999), 6, '0', STR_PAD_LEFT);
        $num = $barcodeNumber;
        $parte_num = substr($num, 3);
        $nuevo = "770" . str_pad($parte_num + 1, strlen($parte_num), "0", STR_PAD_LEFT);

        $user = Auth()->user()->name;
        $texto = strval($user);
        $texL = str_replace('$', '', trim($texto));

        $this->updateProductStock($this->SKU, $this->Cantidad_Articulos);


        $this->emit('lote-added', 'Lote Agregado');
    }




    public function Edit($id)
    {
        $record = Lotes::find($id, ['id', 'Nombre_Lote', 'Fecha_Vencimiento', 'Cantidad_Articulos', 'Cantidad_Articulos', 'SKU', 'CodigoBarras']);
        $cantidad = Product::find($id);
        $this->Nombre_Lote = $record->Nombre_Lote;
        $this->Fecha_Vencimiento = $record->Fecha_Vencimiento;
        $this->Cantidad_Articulos = $record->Cantidad_Articulos;
        $this->SKU = $record->SKU;
        $this->CodigoBarras = $record->CodigoBarras;
        $this->selected_id = $record->id;
        $this->Cantidad_Articulos;
        $this->emit('modal-show', 'show Modal');
    }

    public function Update()
    { //actualizar
        $rules = [
            'Cantidad_Articulos' => 'required|not_in:Elegir',
            'SKU' => 'required|not_in:Elegir',
        ];

        $messages = [
            'Cantidad_Articulos.not_in' => 'Elige una opción',
            'SKU.not_in' => 'Elige una opción',

        ];

        $this->validate($rules, $messages);

        $lot = Lotes::find($this->selected_id);
        $lot->update([
            'SKU' => $this->SKU,
            'Cantidad_Articulos' => $this->Cantidad_Articulos



        ]);
        $this->resetUI();
        $this->emit('lote-edit', 'Lote Actualizado');
    }

    public function Print($id)
    {

        echo "detalle lote";
    }


    public  function resetUI()
    {
        $this->Nombre_Lote = '';
        $this->flavor = 'Elegir';
        $this->lot = 'Elegir';
    }
    protected $listeners = [
        'deleteRow' => 'Destroy',
        'Cambio' => 'updateEstado'
    ];
    public function updateEstado(Lotes $id)
    {
        $sku = $id->SKU;
        $product = Product::where('id', $sku)->first();

        if ($product) {
            $barcode = $product->barcode;
            $newBarcode = sprintf('%06d', intval($barcode) + 1);

            $newProduct = Product::where('barcode', $newBarcode)->first();
            $newProductId = $newProduct ? $newProduct->id : null;

            if ($newProductId) {
                $addedStock = $id->Cantidad_Articulos;
                $this->updateProductStock($newProductId, $addedStock);


                $id->SKU = $newProductId;
                $id->save();
            }
        } else {
            $newBarcode = null;
            $newProductId = null;
        }

        $this->emit('global-msg', "Paso de Crudo a Precocido");
        $this->emit('product-barcode', $newBarcode);
        $this->emit('product-id', $newProductId);
    }





    public function Destroy(Lotes $id)
    {

        $lote = $id;
        $product = Product::find($lote->SKU);
        $product->stock -= $lote->Cantidad_Articulos;
        $product->save();
        $id->delete();



        $this->resetUI();
        $this->emit('lote-deleted', 'Lote Eliminado');
    }
}
