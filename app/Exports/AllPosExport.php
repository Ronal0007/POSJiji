<?php

namespace App\Exports;

use App\Pos;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AllPosExport implements FromCollection, WithMapping, WithHeadings, WithEvents, WithCustomValueBinder
{

    private $No = 0;

    /**
     * @return Collection
     */
    public function collection()
    {
        return Pos::all();
    }


    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            [
                "ALL POS"
            ],
            [
                "#",
                "Serial No.",
                "IMEI",
                "POS Phone Number",
                "Current POS ID",
                "User",
                "User Phone No.",
                "Active Time",
                "Kata",
                "Current Status"
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function map($pos): array
    {
        $this->No++;
        return [
            $this->No,
            $pos->sno,
            $pos->imei,
            $pos->lastposnumber,
            $pos->currentposid ?? "Not Active",
            $pos->currentUsername,
            $pos->currentusernumber,
            $pos->timeInActiveSite,
            $pos->currentkata ?? "Not Active",
            $pos->currentstatus
        ];
    }

    /**
     * @inheritDoc
     */
    public function bindValue(Cell $cell, $value)
    {
        $cell->setValueExplicit($value, DataType::TYPE_STRING);
        return true;
    }

    /**
     * @inheritDoc
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                //Naming Sheet
                $event->sheet->getDelegate()->setTitle("All Pos");


                //Formatting header 1
                $cellRange = "A1";
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(20);
                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setIndent(8);
                $event->sheet->getDelegate()->getRowDimension("1")->setRowHeight(60);

                //Formatting header 2
                $cellRange = "A2:J2";
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(13)->setBold(true);
                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getRowDimension("2")->setRowHeight(30);

                //set Column SIze
                $columns = ['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
                foreach ($columns as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true);
                }


                //Align Number Cells
                $cellRange = "A3:A" . (Pos::all()->count() + 1);
                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                //Align Cells
                $cellRange = "B3:J" . (Pos::all()->count() + 1);
                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);


            },
        ];
    }
}
