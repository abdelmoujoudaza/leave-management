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
                'leaves' => Leave::whereType('leave')
                            ->notRefused()
                            ->whereDate('start_date', '>=', now()->startOfWeek()->format('Y-m-d'))
                            ->whereDate('start_date', '<=', now()->endOfWeek()->format('Y-m-d'))
                            ->with('user')
                            ->get()
            ],
            [
                'title'  => __('Next week'),
                'leaves' => Leave::whereType('leave')
                            ->notRefused()
                            ->whereDate('start_date', '>=', now()->addWeeks(1)->startOfWeek()->format('Y-m-d'))
                            ->whereDate('start_date', '<=', now()->addWeeks(1)->endOfWeek()->format('Y-m-d'))
                            ->with('user')
                            ->get()
            ],
            [
                'title'  => __('This month'),
                'leaves' => Leave::whereType('leave')
                            ->notRefused()
                            ->whereDate('start_date', '>=', now()->startOfMonth()->format('Y-m-d'))
                            ->whereDate('start_date', '<=', now()->endOfMonth()->format('Y-m-d'))
                            ->with('user')
                            ->get()
            ],
        ];
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
