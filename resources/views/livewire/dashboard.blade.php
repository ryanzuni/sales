<div class="space-y-8">

    <!-- STATS -->
    <div class="grid md:grid-cols-2 gap-8">

        <div class="bg-white rounded-2xl shadow p-8">
            <p class="text-gray-400 text-sm">Total Transactions</p>
            <h2 class="text-5xl font-bold text-blue-600 mt-4">
                {{ $this->totalTransaction }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow p-8">
            <p class="text-gray-400 text-sm">Total Revenue</p>
            <h2 class="text-5xl font-bold text-green-600 mt-4">
                Rp {{ number_format($this->totalRevenue) }}
            </h2>
        </div>

    </div>

    <!-- CHART -->
    <div class="bg-white rounded-2xl shadow p-8" wire:ignore>
        <h3 class="text-lg font-semibold mb-6">Revenue Trend</h3>
        <canvas id="revenueChart"></canvas>
    </div>

</div>

<script>
document.addEventListener('livewire:init', () => {

    let chart;

    function renderChart(data) {

        const ctx = document.getElementById('revenueChart');

        if (chart) {
            chart.destroy();
        }

        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(d => d.date),
                datasets: [{
                    label: 'Revenue',
                    data: data.map(d => d.total),
                    borderWidth: 3,
                    tension: 0.4
                }]
            }
        });
    }

    renderChart(@json($this->chartData));

    Livewire.hook('message.processed', () => {
        renderChart(@json($this->chartData));
    });

});
</script>
