<?php

namespace App\Http\Livewire;
use App\Models\User;
use App\Models\Message;
use Livewire\Component;

class SendMessageForm extends Component
{




    public function render()
    {
         $users = User::all(); // Obtener la lista de usuarios registrados
        $selectedUser = User::first(); // Obtener el primer usuario por defecto
        $messages = $selectedUser->messages;
        return view('livewire.Mensajes.send-message-form',['users'=>$users,'selectedUser'=>$selectedUser,'messages'=>$messages])->extends('layouts.theme.app')
        ->section('content')
        ;
    }



}
