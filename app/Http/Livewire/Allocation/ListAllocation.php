<?php

namespace App\Http\Livewire\Allocation;

use App\Models\User;
use App\Models\Leave;
use Livewire\Component;
use App\Models\LeaveType;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;
use App\Exports\LeavesExport;
use Illuminate\Support\Facades\DB;

class ListAllocation extends Component
{
    use WithPagination;

    public $users;
    public $leaveTypes;
    public $user       = null;
    public $leave      = null;
    public $leaveType  = null;
    public $demandType = null;
    public $period     = null;
    protected $perPage = 10;
    protected $model   = User::class;
    protected $query;
    protected $rules   = [
        'leave'        => 'required|exists:leaves,id',
        'leave.status' => 'required|in:pending',
    ];

    protected $headers = [
        'id'              => 'ID',
        'fullname'        => 'Employé',
        'number'          => 'Nombre de jours',
        'start_date'      => 'Date de début',
        'end_date'        => 'Date de fin',
        'status'          => 'statut',
        'description'     => 'Description',
        'leave_type_name' => 'Type de congé',
    ];

    protected $dates = [
        'created_at',
    ];

    protected $sort = 'id';
    protected $directions = ['asc', 'desc'];

    public function mount()
    {
        $this->users      = User::all();
        $this->leaveTypes = LeaveType::whereBalanced(true)->get();
    }

    public function render()
    {
        return view('livewire.allocation.list-allocation');
    }

    public function export($type = 'xlsx')
    {
        $export = $this->formattedMethodName($type, 'exportAs');

        if ( ! method_exists($this, $export)) {
            $export = $this->formattedMethodName('xlsx', 'exportAs');
        }

        $file = $this->$export(new LeavesExport($this->getQuery(), $this->headers, $this->dates));
        $this->dispatchBrowserEvent('file-exported');
        return $file;
    }

    protected function exportAsXlsx($file)
    {
        return $file->download('DatatableExport.xlsx', Excel::XLSX);
    }

    protected function exportAsCsv($file)
    {
        return $file->download('DatatableExport.csv', Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function getLeavesProperty()
    {
        return $this->getQuery()->paginate($this->perPage);
    }

    public function builder()
    {
        return $this->model::query()
                    ->select($this->getColumns())
                    ->addSelect($this->getRaws())
                    ->join('leaves', 'leaves.user_id', 'users.id')
                    ->join('leave_types', 'leaves.leave_type_id', 'leave_types.id')
                    ->where('leaves.type', 'allocation');
    }

    public function buildDatabaseQuery()
    {
        $this->query = $this->builder();

        $this->filter('user')
            ->filter('period')
            ->filter('leaveType')
            ->sort();
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
            'leaves.created_at',
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

    public function updateLeave($leave, $status)
    {
        $this->leave = Leave::find($leave);

        if ( ! is_null($this->leave) && in_array($status, ['approved', 'refused'])) {
            try {
                DB::beginTransaction();
                $this->leave->status = $status;
                $this->leave->approvedBy()->associate(auth()->user());
                $this->leave->save();
                session()->flash('message', 'The leave status was successfully change');
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollback();
            }
        }

        $this->leave = null;
    }

    public function sort($key = 'id', $direction = 'asc')
    {
        if (Arr::has($this->headers, $key)) {
            $this->query->orderBy($key, in_array($direction, $this->directions) ? $direction : 'asc');
        }

        return $this;
    }

    public function doSelectFilter($name, $value)
    {
        $this->$name = ( ! is_null($value) && ! empty($value)) ? $value : null;
        $this->resetPage();
    }

    public function doDateFilter($name, $value)
    {
        if ( ! is_null($value) && ! empty($value)) {
            // $dates = array_filter(explode(',', $value), array(Helper::class, 'validateDate'));
            $dates = explode(',', $value);
        }

        $this->$name = ! empty($dates) ? $dates : null;
        $this->resetPage();
    }

    protected function filter($name)
    {
        $filter = $this->formattedMethodName($name);

        if ((property_exists($this, $name) && ! is_null($this->$name)) && method_exists($this, $filter)) {
            $this->$filter();
        }

        return $this;
    }

    protected function filterByPeriod()
    {
        $this->query->whereBetween('leaves.created_at', $this->period);
    }

    protected function filterByUser()
    {
        $this->query->where('users.id', $this->user);
    }

    protected function filterByLeaveType()
    {
        $this->query->where('leaves.leave_type_id', $this->leaveType);
    }

    protected function formattedMethodName($name, $prefix = 'filterBy')
    {
        $name = ucfirst(Str::camel($name));
        return "{$prefix}{$name}";
    }

    public function paginationView()
    {
        return 'widgets.pagination';
    }
}