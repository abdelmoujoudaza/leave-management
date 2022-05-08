<?php

namespace App\Exports;

use App\Models\Station;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class StationsExport implements FromQuery, WithColumnFormatting, ShouldAutoSize, WithMapping, WithHeadings
{
    use Exportable;

    protected $query;
    protected $headers = [];
    protected $dates   = [];
    protected $model   = Station::class;

    public function __construct(Builder $query, $headers = [], $dates = [])
    {
        $this->query = $query ?? $this->model::query();
        $this->headers = $headers ?? $this->headers;
        $this->dates = $dates ?? $this->dates;
    }

    /**
    * @return \Illuminate\Database\Query\Builder
    */
    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return collect($this->headers)->values()->toArray();
    }

    public function map($station): array
    {
        return collect($this->headers)->keys()->map(function ($column, $index) use ($station) {
            if (in_array($column, $this->dates)) {
                return Date::stringToExcel($station->$column);
            } elseif ($column == 'status') {
                return __($station->$column ? 'Enable' : 'Disable');
            } else {
                return $station->$column;
            }
        })->toArray();
    }

    public function columnFormats(): array
    {
        return [];
    }
}
