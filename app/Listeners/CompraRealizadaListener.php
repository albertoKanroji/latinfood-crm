<?php

namespace App\Listeners;

use App\Events\CompraRealizada;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CompraRealizadaListener implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CompraRealizada $event)
    {
        // Aquí puedes agregar lógica para manejar el evento
        // Por ejemplo, puedes registrar un mensaje en el log
        \Log::info('Compra realizada:', ['sale_id' => $event->sale->id]);
    }
}
