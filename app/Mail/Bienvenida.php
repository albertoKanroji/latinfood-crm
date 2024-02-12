<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Bienvenida extends Mailable
{
    use Queueable, SerializesModels;
public $name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->name=$name;
         public $name, $last_name, $last_name2, $phone, $address, $document, $password, $saldo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to LatinFood')->view('view.welcome');
    }
}
