<?php

namespace App\Http\Livewire;

use App\Models\Sabores;
use Livewire\Component;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class SaboresController extends Component
{
    public $nombre, $description, $descripcion,$selected_id, $search, $pageTitle, $componentName;
    public function mount()
    {
        $this->pageTitle = 'List';
        $this->componentName = 'Sabores';
    }
    public function render()
    {
        //$data=Sabores::all();
        $data = Sabores::orderBy('nombre')
            ->when($this->search, function ($query) {
                $query->where('nombre', $this->search);
            })
            ->get();
        return view('livewire.sabores.sabores', ['data' => $data])->extends('layouts.theme.app')
            ->section('content');
    }
    public function Edit($id)
    {
        $record = Sabores::find($id);
        $this->nombre = $record->nombre;
        $this->description = $record->description;
        $this->selected_id = $record->id;


        $this->emit('show-modal', 'show modal!');
    }



    public function Store()
    {
        $rules = [
            'nombre' => 'required|unique:sabores|min:3'
        ];



        $this->validate($rules);

        $sabor = Sabores::create([
            'nombre' => $this->nombre,
            'description' => $this->description
        ]);




        $this->resetUI();
        $this->emit('global-msg', 'Sabor Creado');
        $this->emit('sabor-added', 'Categoría Registrada');
    }


    public function Update()
    {
        $rules = [
            'nombre' => "required|min:3,{$this->selected_id}"
        ];



        $this->validate($rules);


        $sabor = Sabores::find($this->selected_id);
        $sabor->update([
            'nombre' => $this->nombre,
            'description' => $this->description,
        ]);



        $this->resetUI();
        $this->emit('global-msg', 'Sabor Actualizado');
        $this->emit('sabor-updated', 'Categoría Actualizada');
    }


    public function resetUI()
    {
        $this->nombre = '';

        $this->descripcion = '';
        $this->selected_id = 0;
    }



    protected $listeners = ['deleteRow' => 'Destroy'];


    public function Destroy(Sabores $sabor)
    {

        $imageName = $sabor->image;
        $sabor->delete();

        if ($imageName != null) {
            unlink('storage/categories/' . $imageName);
        }

        $this->resetUI();
        $this->emit('category-deleted', 'Categoría Eliminada');
    }

    public function showAll()
    {
        try {
            $sabores = Sabores::orderBy('nombre')
                ->when($this->search, function ($query) {
                    $query->where('nombre', $this->search);
                })
                ->get();

            return response()->json($sabores);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createApi(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|unique:sabores|min:3',
                'description' => 'required'
            ]);

            $sabor = Sabores::create([
                'nombre' => $request->nombre,
                'description' => $request->description
            ]);

            return response()->json([
                'message' => 'Sabor created successfully',
                'data' => $sabor,
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

    public function findApi($id)
    {
        try {
            $sabor = Sabores::findOrFail($id);

            return response()->json([
                'data' => $sabor,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Sabor not found',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
