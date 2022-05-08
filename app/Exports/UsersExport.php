<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class UsersExport implements FromQuery, WithColumnFormatting, ShouldAutoSize, WithMapping, WithHeadings
{
    use Exportable;

    protected $query;
    protected $headers = [];
    protected $dates   = [];
    protected $model   = User::class;

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

    public function map($user): array
    {
        return collect($this->headers)->keys()->map(function ($column, $index) use ($user) {
            if (in_array($column, $this->dates)) {
                return Date::stringToExcel($user->$column);
            } elseif ($column == 'status') {
                return __($user->$column ? 'Enable' : 'Disable');
            } elseif (in_array($column, ['gender'])) {
                return __(ucfirst($user->$column));
            } else {
                return $user->$column;
            }
        })->toArray();
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
}
