<?php

namespace App\Http\Livewire\Leave;

use App\Models\Leave;
use Livewire\Component;
use App\Models\LeaveType;
use Illuminate\Support\Facades\DB;

class StoreLeave extends Component
{
    public $leave;
    protected $query;
    protected $model = LeaveType::class;
    protected $rules = [
        'leave.user_id'       => 'required|exists:users,id',
        'leave.leave_type_id' => 'required|exists:leave_types,id',
        'leave.number'        => 'required|numeric|regex:/^\d+(\.5)?$/',
        'leave.description'   => 'nullable|string',
        'leave.start_date'    => 'required|date|date_format:Y-m-d',
        'leave.end_date'      => 'required|date|date_format:Y-m-d',
        'leave.status'        => 'nullable|string|in:pending',
        'leave.type'          => 'nullable|string|in:leave',
    ];

    public function render()
    {
        return view('livewire.leave.store-leave');
    }

    public function mount(Leave $leave)
    {
        $this->leave = $leave;
        $this->leave->user()->associate(auth()->user());
    }

    public function updated($property, $value)
    {
        $this->validateOnly($property);
    }

    public function getLeaveTypesProperty()
    {
        return $this->getQuery();
    }

    public function builder()
    {
        return $this->model::query()
                    ->select($this->getColumns())
                    ->addSelect($this->getRaws())
                    ->join('leaves', 'leaves.user_id', 'users.id')
                    ->join('leave_types', 'leaves.leave_type_id', 'leave_types.id');
                    // ->leftJoin('users', 'leaves.approved_by', 'users.id');
    }

    public function buildDatabaseQuery()
    {
        $this->query = $this->builder();
    }

    protected function getColumns()
    {
        return [
            'leaves.id',
            'leaves.number',
            'leaves.description',
            'leaves.start_date',
            'leaves.end_date',
            'leaves.status',
            'leaves.type',
            'leave_types.name as leave_type_name',
        ];
    }

    public function getRaws()
    {
        return [
            DB::raw('CONCAT(users.firstname, " ", users.lastname) AS fullname'),
        ];
    }

    public function getQuery()
    {
        $this->buildDatabaseQuery();
        return $this->query->toBase();
    }

    public function back()
    {
        return redirect()->route("leave.list");
    }

    public function submit()
    {
        $this->validate();
        try {
            DB::beginTransaction();
            $this->leave->save();
            // session()->flash("message", "Post successfully updated.");
            DB::commit();
            return $this->back();
        } catch (\Exception $exception) {
            DB::rollback();
        }

    }
}
