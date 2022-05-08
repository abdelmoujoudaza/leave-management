<?php

namespace App\Http\Livewire\Station;

use App\Models\Direction;
use Livewire\Component;
use App\Models\Station;
use Illuminate\Support\Facades\DB;

class StoreStation extends Component
{
    public $station;
    public $directions;
    protected $query;
    protected $model = Station::class;
    protected $rules = [
        'station.name'         => 'required|string|max:255',
        'station.address'      => 'required|string|max:255',
        'station.latitude'     => 'required|between:-90,90',
        'station.longitude'    => 'required|between:-180,180',
        'station.status'       => 'required|boolean',
        'station.direction_id' => 'required|exists:directions,id',
    ];

    public function render()
    {
        return view('livewire.station.store-station');
    }

    public function mount(Station $station)
    {
        $this->directions = Direction::active()->get();
        $this->station    = $station;
        $this->station->status = true;
        $this->station->direction()->associate($this->directions->first());
    }

    public function updated($property, $value)
    {
        $this->validateOnly($property);
    }

    public function toggleStatus()
    {
        $this->station->status = !$this->station->status;
    }

    public function back()
    {
        return redirect()->route('station.list');
    }

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();
            $this->station->save();
            session()->flash('message', __('The station was created successfully'));
            DB::commit();
            return $this->back();
        } catch (\Exception $exception) {
            DB::rollback();
        }
    }
}
