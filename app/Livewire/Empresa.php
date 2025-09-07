<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Empresa as ModelEmpresa;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Empresa extends Component
{
    public $empresa;

    public $nombre = '';
    public $slogan = '';
    public $mision = '';
    public $vision = '';
    public $nroContacto = '';
    public $facebook = '';
    public $instagram = '';
    public $tiktok = '';

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'slogan' => 'nullable|string|max:255',
        'mision' => 'nullable|string',
        'vision' => 'nullable|string',
        'nroContacto' => 'required|string|max:15',
        'facebook' => 'nullable|string|max:255',
        'instagram' => 'nullable|string|max:255',
        'tiktok' => 'nullable|string|max:255',
    ];

    public function mount()
    {
        $this->empresa = ModelEmpresa::first();

        if ($this->empresa) {
            $this->nombre = $this->empresa->nombre;
            $this->slogan = $this->empresa->slogan;
            $this->mision = $this->empresa->mision;
            $this->vision = $this->empresa->vision;
            $this->nroContacto = $this->empresa->nroContacto;
            $this->facebook = $this->empresa->facebook;
            $this->instagram = $this->empresa->instagram;
            $this->tiktok = $this->empresa->tiktok;
        }
    }

    public function actualizarEmpresa()
    {
        $this->validate();

        try {
            if ($this->empresa) {
                $this->empresa->update([
                    'nombre' => $this->nombre,
                    'slogan' => $this->slogan,
                    'mision' => $this->mision,
                    'vision' => $this->vision,
                    'nroContacto' => $this->nroContacto,
                    'facebook' => $this->facebook,
                    'instagram' => $this->instagram,
                    'tiktok' => $this->tiktok,
                ]);
                LivewireAlert::title('Empresa actualizada con éxito.')->success()->show();
            } else {
                ModelEmpresa::create([
                    'nombre' => $this->nombre,
                    'slogan' => $this->slogan,
                    'mision' => $this->mision,
                    'vision' => $this->vision,
                    'nroContacto' => $this->nroContacto,
                    'facebook' => $this->facebook,
                    'instagram' => $this->instagram,
                    'tiktok' => $this->tiktok,
                ]);
                LivewireAlert::title('Empresa registrada con éxito.')->success()->show();
            }
        } catch (\Exception $e) {
            LivewireAlert::title('Ocurrió un error: ' . $e->getMessage())->error()->show();
        }
    }

    public function render()
    {
        return view('livewire.empresa');
    }
}
