<?php

namespace App\Http\Livewire\Direction;

use App\Models\Direction;
use Livewire\Component;

class ShowDirection extends Component
{
    public $direction;
    public $stations;

    public function render()
    {
        return view('livewire.direction.show-direction');
    }

    public function mount(Direction $direction)
    {
        $this->direction = $direction->load('driver', 'stations');
        $this->stations  = [];
    }

    public function setStations($value)
    {
        if (is_string($value)) {
            $this->stations = json_decode($value, true);
        }
    }
}
