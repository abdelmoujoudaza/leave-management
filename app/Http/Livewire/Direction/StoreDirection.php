<?php

namespace App\Http\Livewire\Direction;

use App\Models\Direction;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StoreDirection extends Component
{
    public $drivers;
    public $direction;
    public $period   = null;
    protected $query;
    protected $model = Direction::class;
    protected $rules = [
        'direction.driver_id' => 'required|exists:users,id',
        'direction.name'      => 'required|string|max:255',
        'direction.time'      => 'required|date_format:H:i',
        'direction.status'    => 'nullable|boolean',
    ];

    public function render()
    {
        return view('livewire.direction.store-direction');
    }

    public function mount(Direction $direction)
    {
        $this->drivers = User::role('driver')->active()->get();
        $this->direction = $direction;
        $this->direction->driver()->associate($this->drivers->first());
        $this->direction->status = true;
    }

    public function updated($property, $value)
    {
        $this->validateOnly($property);
    }

    public function toggleStatus()
    {
        $this->direction->status = !$this->direction->status;
    }

    public function back()
    {
        return redirect()->route('direction.list');
    }

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();
            $this->direction->save();
            session()->flash('message', __('The direction has been created successfully'));
            DB::commit();
            return $this->back();
        } catch (\Exception $exception) {
            DB::rollback();
        }
    }
}
