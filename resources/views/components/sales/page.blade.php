<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

new class extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $customer_name = '';
    public $product = '';
    public $qty = 1;
    public $price = 0;
    public $sale_date;
    public $sale_id = null;

    public $startDate;
    public $endDate;
    public $isOpen = false;

    protected $listeners = ['confirmDelete'];

    public function mount()
    {
        $this->sale_date = now()->format('Y-m-d');
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    // ======================
    // DATA
    // ======================

    public function getSalesProperty()
    {
        return Sale::query()
            ->whereBetween('sale_date', [$this->startDate, $this->endDate])
            ->latest()
            ->paginate(5);
    }

    public function getTotalTransactionProperty()
    {
        return Sale::whereBetween('sale_date', [$this->startDate, $this->endDate])->count();
    }

    public function getTotalRevenueProperty()
    {
        return Sale::whereBetween('sale_date', [$this->startDate, $this->endDate])
            ->select(DB::raw('SUM(qty * price) as total'))
            ->value('total') ?? 0;
    }

    public function getChartDataProperty()
    {
        return Sale::whereBetween('sale_date', [$this->startDate, $this->endDate])
            ->select(
                'sale_date',
                DB::raw('SUM(qty * price) as total')
            )
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get();
    }

    public function updatingStartDate() { $this->resetPage(); }
    public function updatingEndDate() { $this->resetPage(); }

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
                'qty' => $this->qty,
                'price' => $this->price,
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

    public function confirmDelete($id)
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

    public function getLiveTotalProperty()
    {
        return $this->qty * $this->price;
    }
};
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="min-h-screen bg-gray-100 p-8 space-y-8">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Sales Dashboard</h1>
            <p class="text-gray-500 text-sm">Monitor your daily transactions</p>
        </div>

        <button wire:click="create"
            class="bg-gradient-to-r from-blue-600 to-blue-500 hover:scale-105 transition transform text-white px-6 py-3 rounded-xl shadow-lg">
            + Add Sale
        </button>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid md:grid-cols-2 gap-6">

        <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition">
            <p class="text-gray-500 text-sm">Total Transactions</p>
            <h2 class="text-4xl font-bold text-blue-600 mt-2">
                {{ $this->totalTransaction }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition">
            <p class="text-gray-500 text-sm">Total Revenue</p>
            <h2 class="text-4xl font-bold text-green-600 mt-2">
                Rp {{ number_format($this->totalRevenue) }}
            </h2>
        </div>

    </div>

    {{-- FILTER --}}
    <div class="bg-white p-6 rounded-2xl shadow flex flex-wrap gap-4 items-end">
        <div>
            <label class="text-sm text-gray-500">Start Date</label>
            <input type="date" wire:model="startDate"
                class="border p-2 rounded-lg focus:ring-2 focus:ring-blue-400">
        </div>

        <div>
            <label class="text-sm text-gray-500">End Date</label>
            <input type="date" wire:model="endDate"
                class="border p-2 rounded-lg focus:ring-2 focus:ring-blue-400">
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="p-4 text-left">Date</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($this->sales as $sale)
                <tr class="border-t hover:bg-gray-50 transition">

                    <td class="p-4 font-medium">
                        {{ $sale->sale_date->format('d M Y') }}
                    </td>

                    <td>{{ $sale->customer_name }}</td>

                    <td>{{ $sale->product }}</td>

                    <td>
                        <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-xs">
                            {{ $sale->qty }}
                        </span>
                    </td>

                    <td>Rp {{ number_format($sale->price) }}</td>

                    <td class="font-bold text-green-600">
                        Rp {{ number_format($sale->qty * $sale->price) }}
                    </td>

                    <td class="text-center space-x-2">

                        <button wire:click="edit({{ $sale->id }})"
                            class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-lg transition">
                            Edit
                        </button>

                        <button wire:click="confirmDelete({{ $sale->id }})"
                            onclick="return confirm('Delete this transaction?')"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg transition">
                            Delete
                        </button>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4">
            {{ $this->sales->links() }}
        </div>
    </div>

    {{-- MODAL --}}
    @if($isOpen)
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center">

        <div class="bg-white w-full max-w-md p-8 rounded-2xl shadow-2xl space-y-4 animate-fadeIn">

            <h2 class="text-xl font-bold">
                {{ $sale_id ? 'Edit Sale' : 'New Sale' }}
            </h2>

            <input type="text" wire:model="customer_name"
                placeholder="Customer Name"
                class="border p-3 w-full rounded-xl">

            <input type="text" wire:model="product"
                placeholder="Product Name"
                class="border p-3 w-full rounded-xl">

            <div class="grid grid-cols-2 gap-4">
                <input type="number" wire:model="qty"
                    placeholder="Qty"
                    class="border p-3 rounded-xl">

                <input type="number" wire:model="price"
                    placeholder="Price"
                    class="border p-3 rounded-xl">
            </div>

            <input type="date" wire:model="sale_date"
                class="border p-3 w-full rounded-xl">

            <div class="text-right text-lg font-bold text-green-600">
                Total: Rp {{ number_format($this->liveTotal) }}
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button wire:click="$set('isOpen', false)"
                    class="px-4 py-2 bg-gray-300 rounded-xl">
                    Cancel
                </button>

                <button wire:click="store"
                    class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700">
                    Save
                </button>
            </div>

        </div>

    </div>
    @endif

</div>
