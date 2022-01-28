<?php

namespace App\Http\Livewire\Leave;

use App\Models\Leave;
use Livewire\Component;
use App\Models\LeaveType;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;

class StoreLeave extends Component
{
    public $user;
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
        $this->user = auth()->user();
        $this->leave = $leave;
        $this->leave->user()->associate($this->user);
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
                    ->leftJoin('leaves', function (JoinClause $join) {
                        $join->on('leaves.leave_type_id', 'leave_types.id')
                            ->where('leaves.user_id', $this->user->id);
                    })
                    ->where(function ($query) {
                        $query->whereNotNull('leaves.leave_type_id')
                        ->where(function ($query) {
                            $query->where(function ($query) {
                                $query->where('leaves.type', 'leave')
                                    ->whereIn('leaves.status', ['approved', 'pending']);
                            })
                            ->orWhere(function ($query) {
                                $query->where('leaves.type', 'allocation')
                                ->where('leaves.status', 'approved');
                            });
                        });
                    })
                    ->orWhereNull('leaves.leave_type_id')
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
                        WHEN leaves.leave_type_id IS NOT NULL AND leaves.type = 'leave' THEN (leaves.number * -1)
                        WHEN leaves.leave_type_id IS NOT NULL AND leaves.type = 'allocation' THEN leaves.number
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
