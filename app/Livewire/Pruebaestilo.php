<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Cliente;

class Pruebaestilo extends Component
{
    public $usuario;
    public $cliente;
    public $nuevo_correo;
    public $nueva_password;
    public $nueva_password_confirm;

    protected $rules = [
        'nuevo_correo' => [
            'required',
            'string',
            'min:4',
            'max:20',
            'regex:/^[A-Za-z0-9]+$/',
            'unique:users,email',
        ],
        'nueva_password' => 'nullable|min:6|same:nueva_password_confirm',
    ];

    public function mount()
    {
        $this->usuario = Auth::user();
        $this->cliente = $this->usuario && $this->usuario->cliente ? $this->usuario->cliente : null;
        $this->nuevo_correo = $this->usuario->email;
    }

    public function actualizarCorreo()
    {
        $this->validateOnly('nuevo_correo');

        $this->usuario->email = $this->nuevo_correo;
        $this->usuario->save();

        session()->flash('mensaje', 'Usuario actualizado correctamente.');
    }

    public function actualizarPassword()
    {
        $this->validateOnly('nueva_password');

        if ($this->nueva_password) {
            $this->usuario->password = Hash::make($this->nueva_password);
            $this->usuario->save();

            $this->nueva_password = '';
            $this->nueva_password_confirm = '';

            session()->flash('mensaje', 'Contraseña actualizada correctamente.');
        }
    }

    public function render()
    {
        return view('livewire.pruebaestilo', [
            'usuario' => $this->usuario,
            'cliente' => $this->cliente,
        ]);
    }
}
