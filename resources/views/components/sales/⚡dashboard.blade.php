<?php

use Livewire\Volt\Component;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

new class extends Component {

    public function getTotalRevenueProperty()
    {
        return Sale::sum(DB::raw('qty * price'));
    }

    public function getTotalTransactionProperty()
    {
        return Sale::count();
    }
};
?>

<div class="min-h-screen bg-gray-50 p-8">

    <div class="flex justify-between mb-6">
        <h1 class="text-3xl font-bold">Sales Dashboard</h1>
        <a href="/sales"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            Back to POS
        </a>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-xl shadow">
            <p>Total Transaction</p>
            <h2 class="text-3xl font-bold text-blue-600">
                {{ $this->totalTransaction }}
            </h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <p>Total Revenue</p>
            <h2 class="text-3xl font-bold text-green-600">
                Rp {{ number_format($this->totalRevenue) }}
            </h2>
        </div>
    </div>

</div>
