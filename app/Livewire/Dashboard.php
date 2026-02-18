<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function getTotalTransactionProperty()
    {
        return Sale::whereBetween('sale_date', [$this->startDate, $this->endDate])
            ->count();
    }

    public function getTotalRevenueProperty()
    {
        return Sale::whereBetween('sale_date', [$this->startDate, $this->endDate])
            ->sum(DB::raw('qty * price'));
    }

    public function getChartDataProperty()
    {
        return Sale::whereBetween('sale_date', [$this->startDate, $this->endDate])
            ->select('sale_date', DB::raw('SUM(qty * price) as total'))
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->sale_date->format('d M'),
                    'total' => $item->total
                ];
            });
    }

    public function render()
    {
        return view('livewire.dashboard')
            ->layout('layouts.app');
    }
}
