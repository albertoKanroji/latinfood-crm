<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewSale extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $sale;
    public $items;

    /**
     * Create a new message instance.
     *
     * @param  array  $emailData
     * @return void
     */
    public function __construct($emailData)
    {
        $this->customer = $emailData['customer'];
        $this->sale = $emailData['sale'];
        $this->items = $emailData['items'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Detalles de la compra')
                    ->view('emails.Sales.NewSale');
    }
}
