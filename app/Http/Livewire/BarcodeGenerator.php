<?php

namespace App\Http\Livewire;

use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;

use Livewire\Component;

class BarcodeGenerator extends Component
{
    public function render()
    {
        return view('livewire.barcode-generator');
    }
}
