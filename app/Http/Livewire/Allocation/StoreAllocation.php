<?php

namespace App\Http\Livewire\Allocation;

use App\Models\User;
use App\Models\Leave;
use Livewire\Component;
use App\Models\LeaveType;
use Illuminate\Support\Facades\DB;

class StoreAllocation extends Component
{
    public $leave;
    public $users;
    public $leaveTypes;
    protected $query;
    protected $model = LeaveType::class;
    protected $rules = [
        'leave.user_id'       => 'required|exists:users,id',
        'leave.leave_type_id' => 'required|exists:leave_types,id',
        'leave.number'        => 'required|numeric|regex:/^\d+(\.5)?$/',
        'leave.description'   => 'nullable|string',
        'leave.status'        => 'nullable|string|in:approved',
        'leave.type'          => 'nullable|string|in:allocation',
    ];

    public function render()
    {
        return view('livewire.allocation.store-allocation');
    }

    public function mount(Leave $leave)
    {
        $this->users      = User::all();
        $this->leaveTypes = LeaveType::whereBalanced(true)->get();
        $this->leave      = $leave;
        $this->leave->user()->associate($this->users->first());
        $this->leave->leaveType()->associate($this->leaveTypes->first());
        $this->leave->status = 'approved';
        $this->leave->type   = 'allocation';
    }

    public function updated($property, $value)
    {
        $this->validateOnly($property);
    }

    public function back()
    {
        return redirect()->route('allocation.list');
    }

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();
            $this->leave->save();
            session()->flash('message', 'Your demand has been submitted successfully');
            DB::commit();
            return $this->back();
        } catch (\Exception $exception) {
            DB::rollback();
        }
    }
}
