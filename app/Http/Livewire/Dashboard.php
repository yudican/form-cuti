<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function mount()
    {
        return redirect()->route('data-form-pengajuan');
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
