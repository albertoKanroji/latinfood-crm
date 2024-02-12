<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\User;
use App\Models\Insumo;
use App\Models\Sabores;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class InsumosController extends Component
{
    public $idSabor, $search, $CodigoBarras, $User, $Cantidad_Articulos,
        $Fecha_Vencimiento, $SKU, $selected_id, $pageTitle, $componentName;
    public $name, $barcode;
    public $categoryid;
    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Lotes';
        $this->idSabor = 'Elegir';
    }

    public function render()
    {
        $sabor = Sabores::with('insumos')
            ->when($this->search, function ($query) {
                $query->where('nombre', $this->search);
            })
            ->get();
        $sabores = Sabores::with('insumos')
            ->where('nombre', $this->search)->get();
        $insumo = Insumo::all();
        return view('livewire.insumos.insumos', ['sabor' => $sabor, 'sabores' => $sabores, 'insumo' => $insumo])->extends('layouts.theme.app')
            ->section('content');
    }
    public function LoteInsumo()
    {
        $this->emit('show-modal', 'details loaded');
    }


    public function Store()
    {



        $barcodeNumber = "770" . str_pad(mt_rand(0, 99999), 6, '0', STR_PAD_LEFT);
        $num = $barcodeNumber;
        $parte_num = substr($num, 3);
        $nuevo = "770" . str_pad($parte_num + 1, strlen($parte_num), "0", STR_PAD_LEFT);

        $user = Auth()->user()->name;
        $texto = strval($user);
        $texL = str_replace('$', '', trim($texto));
        $Lot = Insumo::create([

            'Fecha_Vencimiento' => $Fecha_Vencimiento = Carbon::now()->addMonths(1),
            'Cantidad_Articulos' => $this->Cantidad_Articulos,
            'CodigoBarras' => $nuevo,
            'idSabor' => $this->idSabor,
            'User' => $User = Auth()->user()->name
        ]);
        $this->updateSaborStock($this->idSabor, $this->Cantidad_Articulos);


        $this->emit('lote-added', 'Lote Agregado');
         $this->emit('global-msg', 'Lote de insumo CREADO');
    }
    public function updateSaborStock($productId, $addedStock)
    {
        $product = Sabores::findOrFail($productId);
        $currentStock = $product->stock;
        $newStock = $currentStock + $addedStock;
        $product->update(['stock' => $newStock]);
        $this->emit('global-msg', "SE ACTUALIZO EL STOCK DEL SABOR");
    }
    public  function resetUI()
    {

        $this->Cantidad_Articulos = 0;
        $this->idSabor = 'Elegir';
    }

    //api
    public function showAll()
    {
        try {
            $insumos = Insumo::with('sabor')->get();

            return response()->json($insumos);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    //api
    public function createApi(Request $request)
    {
        try {
            $request->validate([
                'idSabor' => 'required|not_in:Elegir',
                'Cantidad_Articulos' => 'required'
            ]);

            $barcodeNumber = "770" . str_pad(mt_rand(0, 99999), 6, '0', STR_PAD_LEFT);
            $num = $barcodeNumber;
            $parte_num = substr($num, 3);
            $nuevo = "770" . str_pad($parte_num + 1, strlen($parte_num), "0", STR_PAD_LEFT);

            $insumo = Insumo::create([
                'Fecha_Vencimiento' => Carbon::now()->addMonths(1),
                'Cantidad_Articulos' => $request->Cantidad_Articulos,
                'CodigoBarras' => $nuevo,
                'idSabor' => $request->idSabor,
                'User' => Auth()->user()->name
            ]);

            $this->updateSaborStock($request->idSabor, $request->Cantidad_Articulos);

            return response()->json([
                'message' => 'Insumo created successfully',
                'data' => $insumo,
            ], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function FindApi($barcode)
    {
        try {
            $insumo = Insumo::with('sabor')->where('CodigoBarras', $barcode)->first();

            if (!$insumo) {
                return response()->json([
                    'message' => 'Insumo not found',
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'data' => $insumo,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
