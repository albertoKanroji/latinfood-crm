<?php

namespace App\Mail;

use App\Models\Sale;
use App\Models\Envio;
use App\Models\SaleDetail;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Despachos extends Mailable
{
    use Queueable, SerializesModels;

    public $sale;
    public $envio;
    public $detallesPedido;
    public $cliente;

    /**
     * Create a new message instance.
     *
     * @param Sale $sale
     * @param Envio $envio
     * @param SaleDetail $detallesPedido
     * @param Customer $cliente
     */
    public function __construct(Sale $sale, Envio $envio, $detallesPedido, Customer $cliente)
    {
        $this->sale = $sale;
        $this->envio = $envio;
        $this->detallesPedido = $detallesPedido;
        $this->cliente = $cliente;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.Despachos')
            ->subject('Notificación de despacho')
            ->with([
                'sale' => $this->sale,
                'envio' => $this->envio,
                'detallesPedido' => $this->detallesPedido,
                'cliente' => $this->cliente,
            ]);
    }
}
