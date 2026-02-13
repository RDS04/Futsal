<?php

namespace App\Exports;

use App\Models\Boking;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BookingsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Boking::query()
            ->with(['customer', 'lapanganData'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['region'])) {
            $query->where('region', $this->filters['region']);
        }

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('tanggal', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('tanggal', '<=', $this->filters['date_to']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Customer',
            'Lapangan',
            'Tanggal',
            'Jam Mulai',
            'Jam Selesai',
            'Region',
            'Harga Total',
            'Harga Per Jam',
            'Durasi (Jam)',
            'Status',
            'Tanggal Dibuat',
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->id,
            $booking->customer->nama ?? $booking->nama ?? 'N/A',
            $booking->lapanganData->namaLapangan ?? 'N/A',
            $booking->tanggal,
            $booking->jam_mulai ?? 'N/A',
            $booking->jam_selesai ?? 'N/A',
            $booking->region ?? 'N/A',
            'Rp ' . number_format($booking->total_harga, 0, ',', '.'),
            'Rp ' . number_format($booking->harga_per_jam, 0, ',', '.'),
            $booking->durasi ?? 'N/A',
            $booking->status,
            $booking->created_at,
        ];
    }
}
