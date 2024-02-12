<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateData extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $last_name;
    public $last_name2;
    public $phone;
    public $address;
    public $document;
    public $password;
    public $saldo;
     public $email;

    /**
     * Create a new message instance.
     *
     * @param  string  $name
     * @param  string  $last_name
     * @param  string  $last_name2
     * @param  string  $phone
     * @param  string  $address
     * @param  string  $document
     * @param  string  $password
     * @param  string  $saldo
     * @param  string  $email
     * @return void
     */
    public function __construct($name, $last_name, $last_name2, $phone, $address, $document, $password, $saldo, $email)
    {
        $this->name = $name;
        $this->last_name = $last_name;
        $this->last_name2 = $last_name2;
        $this->phone = $phone;
        $this->address = $address;
        $this->document = $document;
        $this->password = $password;
        $this->saldo = $saldo;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.update')
            ->subject('Actualizacion de Datos');
    }
}