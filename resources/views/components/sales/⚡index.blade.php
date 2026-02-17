<?php

use Livewire\Component;
use App\Models\Sale;

new class extends Component
{
    public $customer_name = '';
    public $product = '';
    public $qty = 1;
    public $price = 0;
    public $sale_date;
    public $sale_id = null;

    public $startDate;
    public $endDate;
    public $isOpen = false;

    public function mount()
    {
        $this->sale_date = now()->format('Y-m-d');
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    // ======================
    // DATA LIST
    // ======================

    public function getSalesProperty()
    {
        return Sale::query()
            ->whereBetween('sale_date', [$this->startDate, $this->endDate])
            ->latest()
            ->get();
    }

    public function getTotalTransactionProperty()
    {
        return $this->sales->count();
    }

    public function getTotalRevenueProperty()
    {
        return $this->sales->sum(fn($sale) => $sale->grand_total);
    }

    public function getLiveTotalProperty()
    {
        return (float)$this->qty * (float)$this->price;
    }

    // ======================
    // CRUD
    // ======================

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
            'sale_date' => 'required|date',
        ]);

        Sale::updateOrCreate(
            ['id' => $this->sale_id],
            [
                'customer_name' => $this->customer_name,
                'product' => $this->product,
                'qty' => (int)$this->qty,
                'price' => (float)$this->price,
                'sale_date' => $this->sale_date,
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
        $this->sale_date = $sale->sale_date->format('Y-m-d');

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
        $this->sale_date = now()->format('Y-m-d');
        $this->sale_id = null;
    }
};
?>

<div class="min-h-screen bg-gray-100 p-8 space-y-6">

    <h1 class="text-2xl font-bold">Sales Dashboard</h1>

    {{-- DASHBOARD --}}
    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded shadow">
            <p>Total Transaction</p>
            <h2 class="text-2xl font-bold">{{ $this->totalTransaction }}</h2>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <p>Total Revenue</p>
            <h2 class="text-2xl font-bold">
                Rp {{ number_format($this->totalRevenue) }}
            </h2>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="flex gap-4 items-end">
        <input type="date" wire:model.live="startDate" class="border p-2 rounded">
        <input type="date" wire:model.live="endDate" class="border p-2 rounded">

        <button wire:click="create"
            class="ml-auto bg-blue-600 text-white px-4 py-2 rounded">
            + Add Sale
        </button>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3">Date</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($this->sales as $sale)
                    <tr class="border-t">
                        <td class="p-3">{{ $sale->sale_date }}</td>
                        <td>{{ $sale->customer_name }}</td>
                        <td>{{ $sale->product }}</td>
                        <td>{{ $sale->qty }}</td>
                        <td>{{ number_format($sale->price) }}</td>
                        <td class="font-semibold">
                            Rp {{ number_format($sale->grand_total) }}
                        </td>
                        <td class="flex gap-2 p-3">
                            <button wire:click="edit({{ $sale->id }})"
                                class="bg-yellow-400 px-3 py-1 rounded text-white">
                                Edit
                            </button>

                            <button wire:click="delete({{ $sale->id }})"
                                class="bg-red-500 px-3 py-1 rounded text-white">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- MODAL --}}
    @if($isOpen)
    <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">
        <div class="bg-white p-6 rounded w-96 space-y-3">

            <h2 class="text-lg font-bold">
                {{ $sale_id ? 'Edit Sale' : 'Create Sale' }}
            </h2>

            <input type="text" wire:model="customer_name"
                   placeholder="Customer"
                   class="border p-2 w-full rounded">

            <input type="text" wire:model="product"
                   placeholder="Product"
                   class="border p-2 w-full rounded">

            <input type="number" wire:model.live="qty"
                   class="border p-2 w-full rounded">

            <input type="number" wire:model.live="price"
                   class="border p-2 w-full rounded">

            <input type="date" wire:model="sale_date"
                   class="border p-2 w-full rounded">

            <div class="text-right font-semibold">
                Total:
                Rp {{ number_format($this->liveTotal) }}
            </div>

            <div class="flex justify-end gap-2">
                <button wire:click="$set('isOpen', false)"
                        class="bg-gray-400 text-white px-4 py-2 rounded">
                    Cancel
                </button>

                <button wire:click="store"
                        class="bg-green-600 text-white px-4 py-2 rounded">
                    Save
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
