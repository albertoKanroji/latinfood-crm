<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SubFrom extends Component
{
        public $subform,$ids,$BAR,$CANT;

    public function __construct()
    {
        $this->subform = [
            [
                'ids' => uniqid(),
                'BAR' => '',
                'CANT' => ''
            ]
        ];
    }

public function addItem()
{
    $this->subform[] = ['BAR' => '', 'CANT' => ''];
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
  // Emitir un evento para que Livewire renderice la tabla del subformulario
}
  public function mount()
    {
         $this->subform = [
            ['ids' => uniqid(), 'BAR' => '', 'CANT' => '']
        ];
     
    }
    public function render()
    {
        return view('livewire.sub-from', [ 'subform' => $this->subform ])
            ->extends('layouts.theme.app')
            ->section('content');
    
    }
}
