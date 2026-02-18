<div class="space-y-8">

    <!-- FILTER + BUTTON -->
    <div class="flex flex-wrap items-center gap-4">

        <div class="flex gap-3">
            <input type="date" wire:model.live="startDate"
                class="border border-gray-300 p-2 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">

            <input type="date" wire:model.live="endDate"
                class="border border-gray-300 p-2 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
        </div>

        <button wire:click="create"
            class="ml-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl shadow-lg transition">
            + Add Sale
        </button>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">

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

                    <td class="text-gray-600">
                        {{ $sale->product }}
                    </td>

                    <td>
                        <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-xs font-semibold">
                            {{ $sale->qty }}
                        </span>
                    </td>

                    <td>Rp {{ number_format($sale->price) }}</td>

                    <td class="font-bold text-green-600 text-base">
                        Rp {{ number_format($sale->qty * $sale->price) }}
                    </td>

                    <td class="text-center space-x-2">

                        <button wire:click="edit({{ $sale->id }})"
                            class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-lg transition">
                            Edit
                        </button>

                        <button wire:click="delete({{ $sale->id }})"
                            wire:confirm="Delete this transaction?"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg transition">
                            Delete
                        </button>

                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>
        @if($isOpen)
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">

                <div class="bg-white w-full max-w-md p-8 rounded-2xl shadow-2xl space-y-4">

                    <h2 class="text-xl font-bold">
                        {{ $sale_id ? 'Edit Sale' : 'New Sale' }}
                    </h2>

                    <input type="text"
                        wire:model.live="customer_name"
                        placeholder="Customer Name"
                        class="border p-3 w-full rounded-xl">

                    <input type="text"
                        wire:model.live="product"
                        placeholder="Product Name"
                        class="border p-3 w-full rounded-xl">

                    <div class="grid grid-cols-2 gap-4">
                        <input type="number"
                            wire:model.live="qty"
                            placeholder="Qty"
                            class="border p-3 rounded-xl">

                        <input type="number"
                            wire:model.live="price"
                            placeholder="Price"
                            class="border p-3 rounded-xl">
                    </div>

                    <input type="date"
                        value="{{ now()->format('Y-m-d') }}"
                        disabled
                        class="border p-3 w-full rounded-xl bg-gray-100">

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

        <div class="p-6 border-t">
            {{ $this->sales->links() }}
        </div>

    </div>

</div>
