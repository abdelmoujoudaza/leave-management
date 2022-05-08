<?php

namespace App\Exports;

use App\Models\Direction;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class DirectionsExport implements FromQuery, WithColumnFormatting, ShouldAutoSize, WithMapping, WithHeadings
{
    use Exportable;

    protected $query;
    protected $headers = [];
    protected $dates   = [];
    protected $model   = Direction::class;

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

    public function map($direction): array
    {
        return collect($this->headers)->keys()->map(function ($column, $index) use ($direction) {
            if (in_array($column, $this->dates)) {
                return Date::stringToExcel($direction->$column);
            } elseif ($column == 'status') {
                return __($direction->$column ? 'Enable' : 'Disable');
            } else {
                return $direction->$column;
            }
        })->toArray();
    }

    public function columnFormats(): array
    {
        return [];
    }
}
