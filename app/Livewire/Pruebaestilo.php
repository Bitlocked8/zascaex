<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Cliente;

class Pruebaestilo extends Component
{
    public $usuario;
    public $cliente;
    public $nuevo_correo;
    public $nueva_password;
    public $nueva_password_confirm;
    protected $rules = [
        'nuevo_correo' => 'required|email|unique:users,email',
        'nueva_password' => 'nullable|min:6|same:nueva_password_confirm',
    ];

    public function mount()
    {
        $this->usuario = Auth::user();

        // Obtener el cliente asociado al usuario (si existe)
        $this->cliente = $this->usuario && $this->usuario->cliente ? $this->usuario->cliente : null;

        // Inicializar el correo
        $this->nuevo_correo = $this->usuario->email;
    }

    // Actualizar correo
    public function actualizarCorreo()
    {
        $this->validateOnly('nuevo_correo');

        $this->usuario->email = $this->nuevo_correo;
        $this->usuario->save();

        session()->flash('mensaje', 'Correo actualizado correctamente.');
    }

    // Actualizar contraseña
    public function actualizarPassword()
    {
        $this->validateOnly('nueva_password');

        if ($this->nueva_password) {
            $this->usuario->password = $this->nueva_password; // Laravel lo encripta automáticamente
            $this->usuario->save();

            // Limpiar campos
            $this->nueva_password = '';
            $this->nueva_password_confirm = '';

            session()->flash('mensaje', 'Contraseña actualizada correctamente.');
        }
    }

    // Renderizar vista
    public function render()
    {
        return view('livewire.pruebaestilo', [
            'usuario' => $this->usuario,
            'cliente' => $this->cliente,
        ]);
    }
}
