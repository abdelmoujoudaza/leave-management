<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Exports\UsersExport;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\DB;

class ListUser extends Component
{
    use WithPagination;

    public $user       = null;
    protected $perPage = 10;
    protected $model   = User::class;
    protected $query;
    protected $headers = [
        'id'          => 'ID',
        'national_id' => "Carte d'identité",
        'fullname'    => 'Employé',
        'birth'       => 'Date de naissance',
        'gender'      => 'Sexe',
        'civil_status' => 'État civil',
        'position'    => 'Poste',
        // 'status'      => 'Statut',
        'email'       => 'E-mail',
        'address'     => 'Adresse',
    ];

    protected $dates = [
        'birth',
    ];

    protected $sort = 'id';
    protected $directions = ['asc', 'desc'];

    public function render()
    {
        return view('livewire.user.list-user');
    }

    public function archivedUser($id)
    {
        $this->user = User::find($id);

        if ( ! is_null($this->user)) {
            try {
                DB::beginTransaction();
                // $this->user->delete();
                $this->user->update(['status' => 'archived']);
                session()->flash('message', 'The user was successfully archived');
                DB::commit();
                $this->dispatchBrowserEvent('user-archived');
            } catch (\Exception $exception) {
                dd($exception);
                DB::rollback();
            }
        }

        $this->user = null;
    }


    public function export($type = 'xlsx')
    {
        $export = $this->formattedMethodName($type, 'exportAs');

        if ( ! method_exists($this, $export)) {
            $export = $this->formattedMethodName('xlsx', 'exportAs');
        }

        $file = $this->$export(new UsersExport($this->getQuery(), $this->headers, $this->dates));
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

    public function getUsersProperty()
    {
        return $this->getQuery()->paginate($this->perPage);
    }

    public function builder()
    {
        return $this->model::query()
                    ->select($this->getColumns())
                    ->addSelect($this->getRaws())
                    ->when(auth()->user()->hasRole('manager'), function ($query) {
                        return $query->role(['employee', 'manager']);
                    })
                    ->active();
    }

    public function buildDatabaseQuery()
    {
        $this->query = $this->builder();

        $this->filter('user')
            ->sort();
    }

    protected function getColumns()
    {
        return [
            'users.id',
            'users.national_id',
            'users.gender',
            'users.birth',
            'users.civil_status',
            'users.address',
            'users.position',
            'users.status',
            'users.email',
        ];
    }

    public function getRaws()
    {
        return [
            DB::raw("CONCAT(users.firstname, ' ', users.lastname) AS fullname"),
        ];
    }

    public function getQuery()
    {
        $this->buildDatabaseQuery();
        return $this->query->toBase();
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

    protected function filter($name)
    {
        $filter = $this->formattedMethodName($name);

        if ((property_exists($this, $name) && ! is_null($this->$name)) && method_exists($this, $filter)) {
            $this->$filter();
        }

        return $this;
    }

    protected function filterByUser()
    {
        $this->query->where('users.id', $this->user);
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
