<?php

namespace App\Http\Livewire\Station;

use App\Models\Station;
use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;
use App\Exports\StationsExport;
use App\Models\Direction;
use Illuminate\Support\Facades\DB;

class ListStation extends Component
{
    use WithPagination;

    public $directions;
    public $direction  = null;
    protected $perPage = 10;
    protected $model   = Station::class;
    protected $query;

    protected $headers = [
        'id'             => 'ID',
        'name'           => 'station',
        'address'        => 'Address',
        'latitude'       => 'Latitude',
        'longitude'      => 'Longitude',
        'status'         => 'Statut',
        'direction_name' => 'Direction'
    ];

    protected $dates = [];

    protected $sort = 'id';
    protected $sortDirections = ['asc', 'desc'];

    public function mount()
    {
        $this->directions = Direction::active()->get();
    }

    public function render()
    {
        return view('livewire.station.list-station');
    }

    public function export($type = 'xlsx')
    {
        $export = $this->formattedMethodName($type, 'exportAs');

        if (! method_exists($this, $export)) {
            $export = $this->formattedMethodName('xlsx', 'exportAs');
        }

        $file = $this->$export(new StationsExport($this->getQuery(), $this->headers, $this->dates));
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

    public function getStationsProperty()
    {
        return $this->getQuery()->paginate($this->perPage);
    }

    public function builder()
    {
        return $this->model::query()
                    ->select($this->getColumns())
                    ->addSelect($this->getRaws())
                    ->join('directions', 'stations.direction_id', 'directions.id');
    }

    public function buildDatabaseQuery()
    {
        $this->query = $this->builder();

        $this->filter('direction')
            ->sort();
    }

    protected function getColumns()
    {
        return [
            'stations.id',
            'stations.name',
            'stations.address',
            'stations.latitude',
            'stations.longitude',
            'stations.status'
        ];
    }

    public function getRaws()
    {
        return [
            DB::raw('directions.name AS direction_name'),
        ];
    }

    public function getQuery()
    {
        $this->buildDatabaseQuery();
        return $this->query->toBase();
    }

    public function sort($key = 'id', $sortDirection = 'desc')
    {
        if (Arr::has($this->headers, $key)) {
            $this->query->orderBy($key, in_array($sortDirection, $this->sortDirections) ? $sortDirection : 'desc');
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

    protected function filterByDirection()
    {
        $this->query->where('directions.id', $this->direction);
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
