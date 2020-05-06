<?php

namespace App\Exports;

use App\Pos;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Excel;

class PosExport implements WithMultipleSheets,Responsable
{
    use Exportable;

    private $No = 0;

    /**
     * It's required to define the fileName within
     * the export class when making use of Responsable.
     */
    private $fileName;

    /**
     * Optional Writer Type
     */
    private $writerType = Excel::XLSX;

    /**
     * Optional headers
     */
    private $headers = [
        'Content-Type' => 'text/xlsx',
    ];

    /*
     * Init Constructor
     * */
    public function __construct()
    {
        $this->fileName = Carbon::now()->format("d M Y") . ".xlsx";
    }

    /**
     * @inheritDoc
     */
    public function sheets(): array
    {
        return [
            new AllPosExport(),
            new ActivePosExport(),
            new IdlePosExport(),
            new MaintenancePosExport()
        ];
    }
}
