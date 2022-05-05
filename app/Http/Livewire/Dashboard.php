<?php

namespace App\Http\Livewire;

use App\Models\Leave;
use Livewire\Component;

class Dashboard extends Component
{
    public $leavesCollections;

    public function mount()
    {
        $this->leavesCollections = [
            [
                'title'  => __('This week'),
                'leaves' =>  []
            ],
            [
                'title'  => __('Next week'),
                'leaves' => []
            ],
            [
                'title'  => __('This month'),
                'leaves' => []
            ],
        ];
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
