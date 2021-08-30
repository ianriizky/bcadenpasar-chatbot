<?php

namespace App\Exports;

use App\Models\Item;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\DefaultValueBinder;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class OrderExport extends DefaultValueBinder implements
    FromCollection,
    WithColumnFormatting,
    WithCustomValueBinder,
    WithEvents,
    WithHeadings,
    ShouldAutoSize,
    Responsable
{
    use Exportable;

    /**
     * It's required to define the fileName within
     * the export class when making use of Responsable.
     *
     * @var string
     */
    protected string $fileName;

    /**
     * Optional writer type.
     *
     * @var string
     */
    protected string $writerType = Excel::XLSX;

    /**
     * Total row of the collection.
     *
     * @var int
     */
    protected int $totalRow = 0;

    /**
     * Create a new instance class.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function __construct(protected Builder $query)
    {
        $this->fileName = 'Order Report - ' . Carbon::today()->format('Y-m-d') . '.' . Str::lower(Excel::XLSX);
    }

    /**
     * {@inheritDoc}
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_TEXT,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function bindValue(Cell $cell, $value): bool
    {
        if (($this->columnFormats()[$cell->getColumn()] ?? null) === NumberFormat::FORMAT_TEXT) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $this->mergeCell($event);
            },
        ];
    }

    /**
     * Merge grand total and teller cell value of the same order data.
     *
     * @param  \Maatwebsite\Excel\Events\AfterSheet  $event
     * @return void
     */
    protected function mergeCell(AfterSheet $event)
    {
        $grandTotalIndex = array_search('Grand Total', $this->headings()) + 1;
        $tellerIndex = array_search('Teller', $this->headings()) + 1;

        $mergeRows = [];
        $currentMergeRowStart = 2;

        for ($row = $currentMergeRowStart; $row <= ($this->totalRow + 1); $row++) {
            $cell = $event->getSheet()->getDelegate()->getCellByColumnAndRow($grandTotalIndex, $row);

            if (!is_null($cell->getValue())) {
                $currentMergeRowStart = $row;

                continue;
            }

            $mergeRows[$currentMergeRowStart] = $row;
        }

        foreach ($mergeRows as $rowStart => $rowUntil) {
            $event->getSheet()->getDelegate()->mergeCellsByColumnAndRow(
                $grandTotalIndex, $rowStart,
                $grandTotalIndex, $rowUntil
            )->getStyleByColumnAndRow($grandTotalIndex, $rowStart)
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER);

            $event->getSheet()->getDelegate()->mergeCellsByColumnAndRow(
                $tellerIndex, $rowStart,
                $tellerIndex, $rowUntil
            )->getStyleByColumnAndRow($tellerIndex, $rowStart)
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function headings(): array
    {
        return [
            'No.',
            'Nama',
            'No Rekening/KTP',
            'Kode Referensi',
            'Pecahan',
            'Jumlah',
            'Rumus',
            'Grand Total',
            'Teller',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function collection(): Collection
    {
        /** @var \App\Models\Item|null */
        $lastItem = null;

        return $this->query->get()->map(function (Item $item, $index) use (&$lastItem) {
            $this->totalRow += 1;

            $showOrderTotal = is_null($lastItem) || !$item->order->is($lastItem->order);

            $lastItem = $item;

            return [
                $index + 1,
                $item->order->customer->fullname,
                ($item->order->customer->account_number ?? $item->order->customer->identitycard_number),
                $item->order->code,
                $item->denomination->value,
                $item->quantity,
                $item->total,
                $showOrderTotal ? $item->order->item_total : null,
                $item->order->user ? $item->order->user->fullname : trans('Unscheduled'),
            ];
        });
    }
}
