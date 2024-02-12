<?php

namespace App\Events;

use App\Models\Customer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customer;

    /**
     * Create a new event instance.
     *
     * @param  Customer  $customer
     * @return void
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }
}


