<?php

namespace App\Http\Livewire\Leave;

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

class ListLeave extends Component
{
    use WithPagination;

    public $users;
    public $leaveTypes;
    public $demandTypes;
    public $user       = null;
    public $leave      = null;
    public $leaveType  = null;
    public $demandType = null;
    public $period     = null;
    protected $perPage = 10;
    protected $model   = User::class;
    protected $query;
    protected $rules = [
        'leave'             => 'required|exists:leaves,id',
        'leave.status'      => 'required|in:pending',
    ];

    protected $headers = [
        'id'              => 'ID',
        'fullname'        => 'Employé',
        // 'gender'          => 'Sexe',
        // 'birth'           => 'Date de naissance',
        'number'          => 'Nombre de jours',
        'start_date'      => 'Date de début',
        'end_date'        => 'Date de fin',
        'status'          => 'statut',
        'type'            => 'Type de demande',
        'description'     => 'Description',
        // 'phone_number'    => 'Téléphone',
        // 'email'           => 'Adresse e-mail',
        'leave_type_name' => 'Type de congé',
    ];

    protected $dates = [
        // 'birth',
        // 'created_at',
        'start_date',
        'end_date',
    ];

    protected $sort = 'id';
    protected $directions = ['asc', 'desc'];

    public function mount()
    {
        $this->users       = User::all();
        $this->leaveTypes  = LeaveType::all();
        $this->demandTypes = [
            [
                'id'   => 'leave',
                'name' => 'leave'
            ],
            [
                'id'   => 'allocation',
                'name' => 'allocation'
            ]
        ];
    }

    public function render()
    {
        return view('livewire.leave.list-leave');
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
                    ->join('leave_types', 'leaves.leave_type_id', 'leave_types.id');
                    // ->leftJoin('users', 'leaves.approved_by', 'users.id');
    }

    public function buildDatabaseQuery()
    {
        $this->query = $this->builder();

        $this->filter('user')
            ->filter('period')
            ->filter('leaveType')
            ->filter('demandType')
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
            'leaves.type',
            // 'users.national_id',
            // 'users.gender',
            // 'users.birth',
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
        $this->validate();

        $this->leave = Leave::find($leave);

        if ( ! is_null($this->leave)) {
            // $this->dispatchBrowserEvent('leave-removed');
            try {
                DB::beginTransaction();
                $this->status = $status;
                $this->approvedBy()->associate(auth()->user());
                // session()->flash("message", "Post successfully updated.");
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
        $start = reset($this->period);
        $end   = end($this->period);
        $this->query->whereDate('leaves.start_date', '<=', $start);
        $this->query->whereDate('leaves.end_date', '>=', $end);
    }

    protected function filterByUser()
    {
        $this->query->where('users.id', $this->user);
    }

    protected function filterByLeaveType()
    {
        $this->query->where('leaves.leave_type_id', $this->leaveType);
    }

    protected function filterByDemandType()
    {
        $this->query->where('leaves.type', $this->demandType);
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