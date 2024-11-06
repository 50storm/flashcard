<?php

namespace App\Exports;

use App\Models\FlashCard;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class FlashCardsExport implements FromCollection, WithHeadings, WithCustomCsvSettings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function collection()
    {
        return FlashCard::with(['pairs.frontContent', 'pairs.backContent'])
            ->where('id', $this->id)
            ->get()
            ->map(function ($flashCard) {
                return $flashCard->pairs->map(function ($pair) use ($flashCard) {
                    return [
                        'Front Content' => $pair->frontContent->content ?? 'N/A',
                        'Front Language' => $pair->frontContent->language->language_code ?? 'N/A',
                        'Back Content' => $pair->backContent->content ?? 'N/A',
                        'Back Language' => $pair->backContent->language->language_code ?? 'N/A',
                    ];
                });
            })->flatten(1);
    }

    public function headings(): array
    {
        return [
            'Front Content',
            'Front Language',
            'Back Content',
            'Back Language',
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'use_bom' => true,
        ];
    }
}
