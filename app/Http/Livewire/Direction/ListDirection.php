<?php

namespace App\Http\Livewire\Direction;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;
use App\Exports\DirectionsExport;
use App\Models\Direction;
use Illuminate\Support\Facades\DB;

class ListDirection extends Component
{
    use WithPagination;

    public $users;
    public $driver     = null;
    protected $perPage = 10;
    protected $model   = Direction::class;
    protected $query;

    protected $headers = [
        'id'          => 'ID',
        'name'        => 'Direction',
        'time'        => 'Heure de début',
        'status'      => 'Statut',
        'driver_name' => 'Chauffeur',
    ];

    protected $dates = [];

    protected $sort = 'id';
    protected $SortDirections = ['asc', 'desc'];

    public function mount()
    {
        $this->drivers = User::role('driver')->active()->get();
    }

    public function render()
    {
        return view('livewire.direction.list-direction');
    }

    public function export($type = 'xlsx')
    {
        $export = $this->formattedMethodName($type, 'exportAs');

        if (! method_exists($this, $export)) {
            $export = $this->formattedMethodName('xlsx', 'exportAs');
        }

        $file = $this->$export(new DirectionsExport($this->getQuery(), $this->headers, $this->dates));
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

    public function getDirectionsProperty()
    {
        return $this->getQuery()->paginate($this->perPage);
    }

    public function builder()
    {
        return $this->model::query()
                    ->select($this->getColumns())
                    ->addSelect($this->getRaws())
                    ->join('users', 'directions.driver_id', 'users.id');
    }

    public function buildDatabaseQuery()
    {
        $this->query = $this->builder();

        $this->filter('driver')
            ->sort();
    }

    protected function getColumns()
    {
        return [
            'directions.id',
            'directions.name',
            'directions.time',
            'directions.status',
        ];
    }

    public function getRaws()
    {
        return [
            DB::raw("CONCAT(users.firstname, ' ', users.lastname) AS driver_name"),
        ];
    }

    public function getQuery()
    {
        $this->buildDatabaseQuery();
        return $this->query->toBase();
    }

    public function sort($key = 'id', $SortDirection = 'desc')
    {
        if (Arr::has($this->headers, $key)) {
            $this->query->orderBy($key, in_array($SortDirection, $this->SortDirections) ? $SortDirection : 'desc');
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

    protected function filterByDriver()
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
