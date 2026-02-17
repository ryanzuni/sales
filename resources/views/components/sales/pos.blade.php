<?php

use Livewire\Volt\Component;
use App\Models\Sale;

new class extends Component {

    public $customer_name = '';
    public $product = '';
    public $qty = 1;
    public $price = 0;
    public $sale_date;
    public $sale_id = null;
    public $isOpen = false;

    public function mount()
    {
        $this->sale_date = now()->format('Y-m-d');
    }

    public function getSalesProperty()
    {
        return Sale::latest()->get();
    }

    public function getLiveTotalProperty()
    {
        return (float)$this->qty * (float)$this->price;
    }

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

        Sale::create([
            'customer_name' => $this->customer_name,
            'product' => $this->product,
            'qty' => (int)$this->qty,
            'price' => (float)$this->price,
            'sale_date' => $this->sale_date,
        ]);

        $this->isOpen = false;
        $this->resetForm();
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

<div class="min-h-screen bg-gray-100 p-8">

    <div class="flex justify-between mb-6">
        <h1 class="text-3xl font-bold">POS Kasir</h1>
        <a href="/sales/dashboard"
           class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
            Dashboard
        </a>
    </div>

    <button wire:click="create"
        class="bg-blue-600 text-white px-6 py-3 rounded-lg mb-6">
        + New Transaction
    </button>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-4">Date</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->sales as $sale)
                <tr class="border-t">
                    <td class="p-4">{{ $sale->sale_date }}</td>
                    <td>{{ $sale->customer_name }}</td>
                    <td>{{ $sale->product }}</td>
                    <td>{{ $sale->qty }}</td>
                    <td>Rp {{ number_format($sale->price) }}</td>
                    <td class="font-bold text-green-600">
                        Rp {{ number_format($sale->qty * $sale->price) }}
                    </td>
                    <td>
                        <button
                            onclick="if(confirm('Delete this transaction?')) { $wire.delete({{ $sale->id }}) }"
                            class="bg-red-500 text-white px-3 py-1 rounded">
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
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-xl w-96 space-y-4">
            <h2 class="text-xl font-bold">New Transaction</h2>

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

            <div class="text-right font-bold text-lg">
                Total: Rp {{ number_format($this->liveTotal) }}
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
