<?php

namespace App\Http\Livewire;

use App\Models\Leave;
use Carbon\Carbon;
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
                            ->whereDate('start_date', '>=', Carbon::now()->startOfWeek()->format('Y-m-d'))
                            ->whereDate('start_date', '<=', Carbon::now()->endOfWeek()->format('Y-m-d'))
                            ->with('user')
                            ->get()
            ],
            [
                'title'  => __('Next week'),
                'leaves' => Leave::whereType('leave')
                            ->whereDate('start_date', '>=', Carbon::now()->nextWeekday()->startOfWeek()->format('Y-m-d'))
                            ->whereDate('start_date', '<=', Carbon::now()->nextWeekday()->endOfWeek()->format('Y-m-d'))
                            ->with('user')
                            ->get()
            ],
            [
                'title'  => __('This month'),
                'leaves' => Leave::whereType('leave')
                            ->whereDate('start_date', '>=', Carbon::now()->startOfMonth()->format('Y-m-d'))
                            ->whereDate('start_date', '<=', Carbon::now()->endOfMonth()->format('Y-m-d'))
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
