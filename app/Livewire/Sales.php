<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sale;

class Sales extends Component
{
    use WithPagination;

    public $customer_name;
    public $product;
    public $qty = 1;
    public $price = 0;
    public $sale_id;
    public $isOpen = false;

    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function getSalesProperty()
    {
        return Sale::whereBetween('sale_date', [$this->startDate, $this->endDate])
            ->latest()
            ->paginate(5);
    }

    public function updatingStartDate() { $this->resetPage(); }
    public function updatingEndDate() { $this->resetPage(); }

    public function create()
    {
        $this->resetForm();
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate([
            'customer_name' => 'required',
            'product' => 'required',
            'qty' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        Sale::updateOrCreate(
            ['id' => $this->sale_id],
            [
                'customer_name' => $this->customer_name,
                'product' => $this->product,
                'qty' => $this->qty,
                'price' => $this->price,
                'sale_date' => now()->format('Y-m-d'), // no backdate
            ]
        );

        $this->isOpen = false;
        $this->resetForm();
    }

    public function edit($id)
    {
        $sale = Sale::findOrFail($id);

        $this->sale_id = $sale->id;
        $this->customer_name = $sale->customer_name;
        $this->product = $sale->product;
        $this->qty = $sale->qty;
        $this->price = $sale->price;

        $this->isOpen = true;
    }

    public function delete($id)
    {
        Sale::findOrFail($id)->delete();
    }

    private function resetForm()
    {
        $this->customer_name = '';
        $this->product = '';
        $this->qty = 1;
        $this->price = 0;
        $this->sale_id = null;
    }

    public function getLiveTotalProperty()
    {
        $qty = is_numeric($this->qty) ? $this->qty : 0;
        $price = is_numeric($this->price) ? $this->price : 0;

        return $qty * $price;
    }

    public function render()
    {
        return view('livewire.sales')
            ->layout('layouts.app');
    }
}
