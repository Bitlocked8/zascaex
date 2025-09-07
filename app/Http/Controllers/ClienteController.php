<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // Este método se encarga de retornar la vista de los clientes
    public function index()
    {
        return view('clientes.cliente'); // Asegúrate de que la vista esté en resources/views/clientes/cliente.blade.php
    }
}
