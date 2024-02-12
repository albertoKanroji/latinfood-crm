<?php

namespace App\Http\Livewire;

use App\Models\Operario;
use Livewire\Component;

class TransportesController extends Component
{
  public $nombre, $apellido, $apellido2, $edad, $compañia, $de_Planta;
  public $selected_id, $pageTitle, $componentName;



  public function render()
  {

    $data = Operario::all();

    return view('livewire.transportes.transportes', ['data' => $data])
      ->extends('layouts.theme.app')
      ->section('content');
  }

  public function updatedEsDePlanta($value)
  {
    if ($value == 'SI') {
      $this->compañia = 'LatinFood';
      $this->emit('global-msg', 'Se Cambio el Transportista a Trabajador de planta');
    } else {
      $this->compañia = null;
    }
  }
  public function mount()
  {
    $this->pageTitle = 'List';
    $this->componentName = 'Transportistas';
    $this->de_Planta = 'Elegir';
  }
  public function Store()
  {
    $rules = [
      'nombre' => 'required|min:3',
      'apellido' => 'required|min:3',
      
      'edad' => 'required|numeric|min:18',

      'de_Planta' => 'required|not_in:Elegir'
    ];

   

    $this->validate($rules);


    $operario =  Operario::create([
      'nombre' => $this->nombre,
      'apellido' => $this->apellido,
     
      'edad' => $this->edad,
      'compañia' => $this->compañia,
      'de_Planta' => $this->de_Planta,


    ]);
    if ($this->de_Planta == 'SI') {
      $operario->compañia = 'LATINFOOD';
    } else {
      $operario->compañia = $this->compañia;
    }

    $operario->de_Planta = $this->de_Planta;
    $operario->save();
    $this->emit('global-msg', 'Transportista Agregado');
    $this->emit('trans-added', 'Transportista Agregado');
  }

  public function Edit($id)
  {
    $record = Operario::find($id, ['nombre', 'apellido', 'apellido2', 'edad', 'compañia', 'de_Planta', 'id']);
    $this->nombre = $record->nombre;
    $this->apellido = $record->apellido;
    $this->apellido2 = $record->apellido2;
    $this->edad = $record->edad;
    $this->compañia = $record->compañia;
    $this->de_Planta = $record->de_Planta;
    $this->selected_id = $record->id;
    $this->emit('modal-show', 'show Modal');
  }
  public function Update()
  {
     $rules = [
      'nombre' => 'required|min:3',
      'apellido' => 'required|min:3',
      
      'edad' => 'required|numeric|min:18',

      'de_Planta' => 'required|not_in:Elegir'
    ];

   

    $this->validate($rules);
    $operario = Operario::find($this->selected_id);

    $operario->nombre = $this->nombre;
    $operario->apellido = $this->apellido;
    $operario->apellido2 = $this->apellido2;
    $operario->edad = $this->edad;

    // Actualizar el valor del campo compañía si es de planta
    if ($this->de_Planta == 'SI') {
      $operario->compañia = 'LATINFOOD';
    } else {
      $operario->compañia = $this->compañia;
    }

    $operario->de_Planta = $this->de_Planta;
    $operario->save();
    $this->emit('global-msg', 'Transportista Actualizado');

    $this->resetUI();
    $this->emit('trans-edit', 'Transportista Actualizado');
  }




  protected $listeners = [
    'deleteRow' => 'Destroy'
  ];
  public function Destroy(Operario $operario)
  {
    if ($operario->envios()->exists()) {
      // El transportista tiene envíos asociados, emitir evento para mostrar SweetAlert de error
      $this->emit('operario-has-envios', 'El transportista tiene envíos asociados y no puede ser eliminado.');
      $this->emit('global-msg', 'No se puede eliminar ');
      return;
    }

    $operario->delete();

    $this->resetUI();
    $this->emit('trans-deleted', 'Transportista Eliminado');
  }


  public  function resetUI()
  {
    $this->nombre = '';
    $this->apellido = '';
    $this->apellido2 = '';
    $this->edad = '';
    $this->compañia = '';
    $this->de_Planta = 'Elegir';
  }
}
