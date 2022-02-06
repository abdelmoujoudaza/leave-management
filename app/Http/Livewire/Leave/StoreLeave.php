<?php

namespace App\Http\Livewire\Leave;

use App\Models\Leave;
use Livewire\Component;
use App\Models\LeaveType;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;

class StoreLeave extends Component
{
    public $user;
    public $leave;
    public $period   = null;
    protected $query;
    protected $model = LeaveType::class;
    protected $rules = [
        'leave.user_id'       => 'required|exists:users,id',
        'leave.leave_type_id' => 'required|exists:leave_types,id',
        'leave.number'        => 'required|numeric|regex:/^\d+(\.5)?$/',
        'leave.description'   => 'nullable|string',
        'leave.start_date'    => 'required|date',
        'leave.end_date'      => 'required|date|after:start_date',
        'leave.status'        => 'nullable|string|in:pending',
        'leave.type'          => 'nullable|string|in:leave',
    ];

    public function render()
    {
        return view('livewire.leave.store-leave');
    }

    public function mount(Leave $leave)
    {
        $this->user  = auth()->user();
        $this->leave = $leave;
        $this->leave->user()->associate($this->user);
        $this->leave->leaveType()->associate(LeaveType::first());
        $this->leave->status = 'pending';
        $this->leave->type   = 'leave';
    }

    public function updated($property, $value)
    {
        $this->validateOnly($property);
    }

    public function updatedPeriod($value)
    {
        if ( ! is_null($value) && ! empty($value)) {
            $dates     = explode(',', $value);
            $start     = Carbon::createFromFormat('Y-m-d', reset($dates))->startOfDay();
            $end       = Carbon::createFromFormat('Y-m-d', end($dates))->endOfDay();
            $days      = $start->diffInWeekdays($end);
            $leaveType = $this->leaveTypes->filter(function ($element) { return $element->id == $this->leave->leave_type_id; })->first();

            if ($end->gt($start) && (($days <= $leaveType->number && $leaveType->balanced) || ($days <= $leaveType->limit))) {
                $this->leave->start_date = $start;
                $this->leave->end_date   = $end;
                $this->leave->number     = $days;
            } else {
                $this->addError('leave.period', 'Add a period with a set of days that you have in your balance');
            }
        } else {
            // $this->addError('leave.period', 'Add a valid period');
        }
    }

    public function getLeaveTypesProperty()
    {
        return $this->getQuery()->get();
    }

    public function builder()
    {
        return $this->model::query()
                    ->select($this->getColumns())
                    ->addSelect($this->getRaws())
                    ->leftJoin('leaves', function (JoinClause $join) {
                        $join->on('leaves.leave_type_id', 'leave_types.id')
                            ->where('leaves.user_id', $this->user->id);
                    })
                    ->groupBy('leave_types.id');
    }

    public function buildDatabaseQuery()
    {
        $this->query = $this->builder();
    }

    protected function getColumns()
    {
        return [
            'leave_types.id',
            'leave_types.name',
            'leave_types.limited',
            'leave_types.balanced',
            'leave_types.limit',
        ];
    }

    public function getRaws()
    {
        return [
            DB::raw("
                SUM(
                    CASE
                        WHEN leaves.leave_type_id IS NOT NULL AND leaves.type = 'leave' AND leaves.status in ('approved', 'pending') THEN (leaves.number * -1)
                        WHEN leaves.leave_type_id IS NOT NULL AND leaves.type = 'allocation' AND leaves.status ='approved' THEN leaves.number
                        ELSE 0
                    END
                ) AS number
            "),
        ];
    }

    public function getQuery()
    {
        $this->buildDatabaseQuery();
        return $this->query->toBase();
    }

    public function back()
    {
        return redirect()->route('leave.list');
    }

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();
            $this->leave->save();
            session()->flash('message', __('Your demand has been submitted successfully'));
            DB::commit();
            return $this->back();
        } catch (\Exception $exception) {
            DB::rollback();
        }
    }
}
