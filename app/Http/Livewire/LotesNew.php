<?php

namespace App\Http\Livewire;

use Livewire\Component;

//Modelos
use App\Models\Product;
use App\Models\User;
use App\Models\Inspectors;
use App\Models\Insumo;
use App\Models\Sabores;
use App\Models\Category;
use App\Models\Lotes;

//Extras
use Carbon\Carbon;
use Illuminate\Support\Facades\Barcode;
use Illuminate\Support\Facades\Auth;

//api
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class LotesNew extends Component
{
    //Componente
    public $componentName;
    public $selected_id;
    public $Sabor;
    public $LoteInsumo;
    public $search;

    //Variables para Formulario
    public   $Fecha_Vencimiento;
    public   $User;


    //SubFormulario
    public   $subform;

    //Extras

    public function mount()
    {
        $this->subform = [
            [
                'id' => uniqid(),
                'BAR' => 'Elegir',
                'CANT' => ''
            ]
        ];

        $this->User = Auth()->user()->name;
        $this->pageTitle = 'Listado';
        $this->componentName = 'Lotes';
        $this->LoteInsumo = 'Elegir';
        $this->Sabor = 'Elegir';
    }

    public function render()
    {
        //  $sabores = Sabores::orderBy('nombre')->get();
        $sabores = Sabores::orderBy('nombre')
            ->when($this->search, function ($query) {
                $query->where('nombre', $this->search);
            })
            ->get();
        $lotesAsociados = [];

        foreach ($sabores as $sabor) {
            $lotes = Lotes::where('sabor_id', $sabor->id)->get();

            $lotesAsociados[$sabor->id] = $lotes->groupBy('CodigoBarras');
        }

        $insumo = Insumo::where('idSabor', $this->Sabor)->get();
        $product = Product::where('sabor_id', $this->Sabor)->get();

        return view('livewire.LotesNew.lotes-new', [
            'product' => $product,
            'sabor' => $sabores,
            'lotesAsociados' => $lotesAsociados,
            'insumo' => $insumo,
            'subform' => $this->subform
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }



    public function Store()
    {
        //User en Sesion
        $user = Auth()->user()->name;

        $rules = [
            'LoteInsumo' => 'required|not_in:Elegir',
            'Sabor' => 'required|not_in:Elegir',
        ];
        foreach ($this->subform as $index => $item) {
            $rules['subform.' . $index . '.BAR'] = 'required|not_in:Elegir';
            $rules['subform.' . $index . '.CANT'] = 'required';
        }

        $this->validate($rules);

        foreach ($this->subform as $item) {
            // Guardar cada fila como un registro independiente
            $lote = Lotes::create([
                'User' => $user,
                'sabor_id' => $this->Sabor,
                'Fecha_Vencimiento' => $this->Fecha_Vencimiento = Carbon::now()->addMonths(6),
                'CodigoBarras' => $this->LoteInsumo,
                'SKU' => $item['BAR'],
                'Cantidad_Articulos' => $item['CANT'],
            ]);
            // Obtener el nombre del producto
            $product = Product::findOrFail($lote->SKU);
            $productName = $product->name;

            // Actualizar el stock del producto
            $this->updateProductStock($lote->SKU, $lote->Cantidad_Articulos, $productName);
            // Actualizar el stock del sabor
            $sabor = Sabores::findOrFail($this->Sabor);
            $sabor->stock -= $lote->Cantidad_Articulos;
            $sabor->save();
            // Actualizar la cantidad de artículos en el modelo Insumo
            $insumo = Insumo::where('CodigoBarras', $this->LoteInsumo)->first();
            $insumo->Cantidad_Articulos -= $lote->Cantidad_Articulos;
            $insumo->save();
        }



        //inspectors
        $inspector = Inspectors::create([
            'user' => $user,
            'action' => 'Creo lote de productos, Codigo de barras: ' . $this->LoteInsumo,
            'seccion' => 'Lotes | Products'
        ]);


        $this->emit('lote-added', 'Lote Agregado');
         $this->emit('global-msg', 'Lote de productos CREADO');
        $this->resetUI();
    }

    public function updateProductStock($productId, $addedStock, $productName)
    {
        $product = Product::findOrFail($productId);
        $currentStock = $product->stock;
        $newStock = $currentStock + $addedStock;
        $product->update(['stock' => $newStock]);
        $this->emit('global-msg', "SE ACTUALIZÓ EL STOCK DE: $productName");
    }

    public  function resetUI()
    {
        $this->subform = [
            [
                'id' => uniqid(),
                'BAR' => 'Elegir',
                'CANT' => ''
            ]
        ];
        $this->LoteInsumo = 'Elegir';
        $this->Sabor = 'Elegir';
    }

    //SubFormulario
    public function __construct()
    {
        $this->subform = [
            [
                'id' => uniqid(),
                'BAR' => '',
                'CANT' => ''
            ]
        ];
    }

    public function addItem()
    {
        $this->subform[] = [
            'id' => uniqid(),
            'BAR' => '',
            'CANT' => ''
        ];

        $this->emit('tableRendered'); // Emitir el evento para actualizar la tabla del subformulario
    }

    public function removeItem($index)
    {
        unset($this->subform[$index]);
        $this->subform = array_values($this->subform);
        $this->emit('tableRendered'); // Emitir el evento para actualizar la tabla del subformulario
    }

    public function renderTable()
    {
        $this->emit('tableRendered'); // Emitir un evento para que Livewire renderice la tabla del subformulario
    }
    //Fin Subformulario

    //funciones del blade
    protected $listeners = [

        'Cambio' => 'updateEstado'
    ];

public function updateEstado(Product $id, $cantidadPrecocido, $id_lote)
{
        if (empty($cantidadPrecocido)) {
        $this->emit('global-msg', 'Introduzca una cantidad');
        return;
    }
    $sku = $id->barcode;
    $stockCrudo = $id->stock;

    // Calcular el barcode del producto precocido sumando 1 al barcode actual
    $barcodePrecocido = substr($sku, 0, -1) . (intval(substr($sku, -1)) + 1);

    // Buscar el producto precocido por su barcode
    $nextProduct = Product::where('barcode', $barcodePrecocido)->first();

    if ($nextProduct) {
        // Actualizar el stock del producto precocido sumando la cantidadPrecocido
        $nextProduct->stock += $cantidadPrecocido;
        $nextProduct->save();

        // Actualizar el stock del producto actual restando la cantidadPrecocido
        $id->stock -= $cantidadPrecocido;
        $id->save();

        // Buscar el lote por su ID
        $lote = Lotes::find($id_lote);

        if ($lote) {
            if ($cantidadPrecocido <= $lote->Cantidad_Articulos) {
                // Restar la cantidadPrecocido de Cantidad_Articulos del lote
                $lote->Cantidad_Articulos -= $cantidadPrecocido;
                $lote->save();
                $this->emit('global-msg', 'Paso de Crudo a Precocido');
            } else {
                // La cantidadPrecocido es mayor que Cantidad_Articulos
                $this->emit('global-msg', 'Excedió la cantidad disponible en el lote');
            }
        } else {
            $this->emit('global-msg', 'No se encontró el lote');
        }
    } else {
        $this->emit('global-msg', 'No se encontró el producto precocido');
    }
}


    //api
    public function CreateApi(Request $request)
    {
        try {
            $rules = [
                'LoteInsumo' => 'required|not_in:Elegir',
                'Sabor' => 'required|not_in:Elegir',
            ];
            foreach ($request->input('subform') as $index => $item) {
                $rules['subform.' . $index . '.BAR'] = 'required|not_in:Elegir';
                $rules['subform.' . $index . '.CANT'] = 'required';
            }

            $this->validate($request, $rules);

            $user = Auth()->user()->name;

            foreach ($request->input('subform') as $item) {
                // Guardar cada fila como un registro independiente
                $lote = Lotes::create([
                    'User' => $user,
                    'sabor_id' => $request->input('Sabor'),
                    'Fecha_Vencimiento' => $this->Fecha_Vencimiento = Carbon::now()->addMonths(6),
                    'CodigoBarras' => $request->input('LoteInsumo'),
                    'SKU' => $item['BAR'],
                    'Cantidad_Articulos' => $item['CANT'],
                ]);

                // Obtener el nombre del producto
                $product = Product::findOrFail($lote->SKU);
                $productName = $product->name;

                // Actualizar el stock del producto
                $this->updateProductStock($lote->SKU, $lote->Cantidad_Articulos, $productName);

                // Actualizar el stock del sabor
                $sabor = Sabores::findOrFail($request->input('Sabor'));
                $sabor->stock -= $lote->Cantidad_Articulos;
                $sabor->save();
            }

            // Actualizar la cantidad de artículos en el modelo Insumo
            $insumo = Insumo::where('CodigoBarras', $request->input('LoteInsumo'))->first();
            $insumo->Cantidad_Articulos -= $lote->Cantidad_Articulos;
            $insumo->save();

            //inspectors
            $inspector = Inspectors::create([
                'user' => $user,
                'action' => 'Creo lote de productos, Codigo de barras: ' . $request->input('LoteInsumo'),
                'seccion' => 'Lotes | Products'
            ]);

            return response()->json([
                'message' => 'Lote added successfully',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Model not found',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function FindApi($barcode)
    {
        try {
            $lotes = Lotes::where('CodigoBarras', $barcode)->get();

            if ($lotes->isEmpty()) {
                return response()->json([
                    'message' => 'No lots found for the given barcode',
                ], Response::HTTP_NOT_FOUND);
            }

            $sabores = Sabores::orderBy('nombre')
                ->when($this->search, function ($query) {
                    $query->where('nombre', $this->search);
                })
                ->get();

            $lotesAsociados = [];

            foreach ($sabores as $sabor) {
                $lotesAsociados[$sabor->id] = $lotes->where('sabor_id', $sabor->id)->groupBy('CodigoBarras');
            }

            $insumo = Insumo::where('idSabor', $this->Sabor)->get();
            $product = $lotes->map(function ($lote) {
                return $lote->producto;
            })->unique();

            return response()->json([

                'Lotes Econtrados' => $lotesAsociados,

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function ShowAll()
    {
        try {
            $sabores = Sabores::orderBy('nombre')
                ->when($this->search, function ($query) {
                    $query->where('nombre', $this->search);
                })
                ->get();

            $lotesAsociados = [];

            foreach ($sabores as $sabor) {
                $lotes = Lotes::where('sabor_id', $sabor->id)->get();

                $lotesAsociados[$sabor->id] = $lotes->groupBy('CodigoBarras');
            }

            $insumo = Insumo::where('idSabor', $this->Sabor)->get();
            $product = Product::where('sabor_id', $this->Sabor)->get();

            return response()->json([

                'lotesAsociados' => $lotesAsociados,
                'insumo' => $insumo,
                'product' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
