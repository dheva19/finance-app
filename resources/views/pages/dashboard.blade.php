@php
    $breadcrumps = [
        [
            'label' => 'Dashboard',
            'url' => route('dashboard.index')
        ]
    ]
@endphp

<x-layout.workspace :breadcrumps="$breadcrumps" title="Dashboard">
    <x-ui.card class="w-full h-fit">
        <p>Selamat datang, <b>{{ request()->user()->name }}!</b> Berikut adalah laporan keuangan anda.</p>
    </x-ui.card>
    <section class="grid grid-cols-12 gap-3 my-3">
        <x-ui.card class="col-span-6 md:col-span-3">
            <p>Total Saldo</p>
            <b class="text-2xl">Rp{{ number_format($countCard['total'], 0, ',', '.') }}</b>
            <p>Bulan Ini</p>
        </x-ui.card>
        <x-ui.card class="col-span-6 md:col-span-3">
            <p>Saldo Kantong Utama</p>
            <b class="text-2xl">Rp{{ number_format($countCard['pocket'], 0, ',', '.') }}</b>
            <p>Bulan Ini</p>
        </x-ui.card>
        <x-ui.card class="col-span-6 md:col-span-3">
            <p>Total Income</p>
            <b class="text-2xl text-green-500">+Rp{{ number_format($countCard['income'], 0, ',', '.') }}</b>
            <p>Bulan Ini</p>
        </x-ui.card>
        <x-ui.card class="col-span-6 md:col-span-3">
            <p>Total Expense</p>
            <b class="text-2xl text-red-500">-Rp{{ number_format($countCard['expense'], 0, ',', '.') }}</b>
            <p>Bulan Ini</p>
        </x-ui.card>
    </section>

    <section class="grid grid-cols-12 gap-3 my-3">
        <x-ui.card class="col-span-12 md:col-span-8">
            <p class="font-semibold text-xl mb-3">Grafik Transaksi</p>
            <div class="relative w-full h-64 md:h-96">
                <canvas id="transactionChart"></canvas>
            </div>
        </x-ui.card>
        <x-ui.card class="col-span-12 md:col-span-4"></x-ui.card>
        <x-ui.card class="col-span-12 md:col-span-4">
        </x-ui.card>
        <x-ui.card class="col-span-12 md:col-span-8"></x-ui.card>
    </section>
</x-layout.workspace>

<script type="module">
document.addEventListener("DOMContentLoaded", function() {

        const chartLabels = @json($transactionChart['label']);
        const incomeData = @json($transactionChart['income']);
        const expenseData = @json($transactionChart['expense']);

        const ctx = document.getElementById('transactionChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Income',
                    data: incomeData,
                    borderColor: 'rgba(16, 185, 129, 1)',
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                },{
                    label: 'expense',
                    data: expenseData,
                    borderColor: 'rgba(239, 68, 68, 1)',
                    backgroundColor: 'rgba(239, 68, 68, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>

