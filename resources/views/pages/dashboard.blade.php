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

    <x-ui.card class="w-full my-3">
        <form method="GET" action="{{ route('dashboard.index') }}" id="filterForm" class="flex flex-wrap items-end gap-4">
            <div>
                <x-ui.label for="pocketSelect" class="text-sm font-medium leading-none mb-1 inline-block">Kantong:</x-ui.label>
                <div>
                    <x-ui.select name="pocket_id" id="pocketSelect">
                        <x-ui.option value="" :selectedValue="$filter['pocket_id'] ?? ''">Semua Kantong</x-ui.option>
                        @foreach ($pockets as $p)
                            <x-ui.option value="{{ $p->id }}" :selectedValue="$filter['pocket_id'] ?? ''">{{ $p->name }}</x-ui.option>
                        @endforeach
                    </x-ui.select>
                </div>
            </div>

            <div>
                <x-ui.label for="rangeSelect" class="text-sm font-medium leading-none mb-1 inline-block">Rentang Waktu:</x-ui.label>
                <div>
                    <x-ui.select name="range" id="rangeSelect" onchange="toggleCustomDates()">
                        <x-ui.option value="this_month" :selectedValue="$filter['range'] ?? 'this_month'">Bulan Ini</x-ui.option>
                        <x-ui.option value="today" :selectedValue="$filter['range'] ?? ''">Hari Ini</x-ui.option>
                        <x-ui.option value="this_week" :selectedValue="$filter['range'] ?? ''">Minggu Ini</x-ui.option>
                        <x-ui.option value="this_year" :selectedValue="$filter['range'] ?? ''">Tahun Ini</x-ui.option>
                        <x-ui.option value="all" :selectedValue="$filter['range'] ?? ''">Semua</x-ui.option>
                        <x-ui.option value="custom" :selectedValue="$filter['range'] ?? ''">Custom</x-ui.option>
                    </x-ui.select>
                </div>
            </div>

            <div id="customDateInputs" class="flex items-end gap-3 {{ ($filter['range'] ?? '') === 'custom' ? '' : 'hidden' }}">
                <div>
                    <x-ui.label for="start_date" class="text-sm font-medium leading-none mb-1 inline-block">Start Date:</x-ui.label>
                    <div>
                        <x-ui.input type="date" name="start_date" id="start_date" value="{{ $filter['start_date'] ?? '' }}" />
                    </div>
                </div>
                <div>
                    <x-ui.label for="end_date" class="text-sm font-medium leading-none mb-1 inline-block">End Date:</x-ui.label>
                    <div>
                        <x-ui.input type="date" name="end_date" id="end_date" value="{{ $filter['end_date'] ?? '' }}" />
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <x-ui.button type="submit" variant="primary">
                    Filter
                </x-ui.button>
                @if (($filter['range'] ?? 'this_month') !== 'this_month' || !empty($filter['start_date']) || !empty($filter['end_date']) || !empty($filter['pocket_id']))
                    <a href="{{ route('dashboard.index') }}">
                        <x-ui.button type="button" variant="secondary">
                            Reset
                        </x-ui.button>
                    </a>
                @endif
            </div>
        </form>
    </x-ui.card>

    <section class="grid grid-cols-12 gap-3 my-3">
        <x-ui.card class="col-span-12 md:col-span-3">
            <p>Total Saldo ({{ $countCard['selectedPocketName'] }})</p>
            <b class="text-2xl">Rp{{ number_format($countCard['total'], 0, ',', '.') }}</b>
            <p class="text-xs text-gray-500 mt-1">{{ $countCard['rangeLabel'] }}</p>
        </x-ui.card>
        <x-ui.card class="col-span-12 md:col-span-3">
            <p>Total Income</p>
            <b class="text-2xl text-green-500">+Rp{{ number_format($countCard['income'], 0, ',', '.') }}</b>
            <p class="text-xs text-gray-500 mt-1">{{ $countCard['rangeLabel'] }}</p>
        </x-ui.card>
        <x-ui.card class="col-span-12 md:col-span-3">
            <p>Total Expense</p>
            <b class="text-2xl text-red-500">-Rp{{ number_format($countCard['expense'], 0, ',', '.') }}</b>
            <p class="text-xs text-gray-500 mt-1">{{ $countCard['rangeLabel'] }}</p>
        </x-ui.card>
        <x-ui.card class="col-span-12 md:col-span-3">
            <p>Total Transfer</p>
            <b class="text-2xl text-blue-500">Rp{{ number_format($countCard['transfer'], 0, ',', '.') }}</b>
            <p class="text-xs text-gray-500 mt-1">{{ $countCard['rangeLabel'] }}</p>
        </x-ui.card>
    </section>


    <section class="grid grid-cols-12 gap-3 my-3">
        <x-ui.card class="col-span-12 md:col-span-8">
            <p class="font-semibold text-xl mb-3">Grafik Transaksi</p>
            <div class="relative w-full h-64 md:h-80">
                <canvas id="transactionChart"></canvas>
            </div>
        </x-ui.card>

        <x-ui.card class="col-span-12 md:col-span-4">
            <p class="font-semibold text-xl mb-3">Perbandingan Transaksi</p>
            <div class="relative w-full h-64 md:h-80">
                <canvas id="doughnutChart"></canvas>
            </div>
        </x-ui.card>

        <x-ui.card class="col-span-12 md:col-span-4">
            <p class="font-semibold text-xl mb-3">Saldo Seluruh Kantong</p>
            <div class="relative w-full h-64 md:h-80">
                <canvas id="pieChart"></canvas>
            </div>
        </x-ui.card>


        <x-ui.card class="col-span-12 md:col-span-8">
            <p class="font-semibold text-xl mb-3">Top 5 Kantong Nilai Transaksi</p>
            <div class="relative w-full h-64 md:h-80">
                <canvas id="barChart"></canvas>
            </div>
        </x-ui.card>
    </section>

    <section class="my-3">
        <x-ui.card class="w-full">
            <p class="font-semibold text-xl mb-3">Perbandingan Nominal Transaksi Berdasarkan Kategori</p>
            <div class="relative w-full h-64 md:h-96">
                <canvas id="categoryBarChart"></canvas>
            </div>
        </x-ui.card>
    </section>
</x-layout.workspace>

<script>
function toggleCustomDates() {
    const rangeSelect = document.getElementById('rangeSelect');
    const customDateInputs = document.getElementById('customDateInputs');
    if (rangeSelect.value === 'custom') {
        customDateInputs.classList.remove('hidden');
    } else {
        customDateInputs.classList.add('hidden');
    }
}

document.addEventListener("DOMContentLoaded", function() {
        const formatRupiah = (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value);

        const chartLabels = @json($transactionChart['label']);
        const incomeData = @json($transactionChart['income']);
        const expenseData = @json($transactionChart['expense']);
        const transferData = @json($transactionChart['transfer']);

        const ctxLine = document.getElementById('transactionChart');
        if (ctxLine) {
            new Chart(ctxLine, {
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
                        label: 'Expense',
                        data: expenseData,
                        borderColor: 'rgba(239, 68, 68, 1)',
                        backgroundColor: 'rgba(239, 68, 68, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    },{
                        label: 'Transfer',
                        data: transferData,
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
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
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: (context) => `${context.dataset.label}: ${formatRupiah(context.raw)}`
                            }
                        }
                    }
                }
            });
        }


        const doughnutLabels = @json($doughnutChart['labels']);
        const doughnutData = @json($doughnutChart['data']);

        const ctxDoughnut = document.getElementById('doughnutChart');
        if (ctxDoughnut) {
            new Chart(ctxDoughnut, {
                type: 'doughnut',
                data: {
                    labels: doughnutLabels,
                    datasets: [{
                        data: doughnutData,
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(59, 130, 246, 0.8)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: (context) => `${context.label}: ${formatRupiah(context.raw)}`
                            }
                        }
                    }
                }
            });
        }

        const pieLabels = @json($pieChart['labels']);
        const pieData = @json($pieChart['data']);

        const ctxPie = document.getElementById('pieChart');
        if (ctxPie) {
            new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: pieLabels.length > 0 ? pieLabels : ['Tidak Ada Data'],
                    datasets: [{
                        data: pieData.length > 0 ? pieData : [0],
                        backgroundColor: [
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(236, 72, 153, 0.8)',
                            'rgba(20, 184, 166, 0.8)',
                            'rgba(99, 102, 241, 0.8)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: (context) => `${context.label}: ${formatRupiah(context.raw)}`
                            }
                        }
                    }
                }
            });
        }

        const barLabels = @json($barChart['labels']);
        const barIncome = @json($barChart['income']);
        const barExpense = @json($barChart['expense']);
        const barTransfer = @json($barChart['transfer']);

        const ctxBar = document.getElementById('barChart');
        if (ctxBar) {
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: barLabels,
                    datasets: [
                        {
                            label: 'Income',
                            data: barIncome,
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            maxBarThickness: 28
                        },
                        {
                            label: 'Expense',
                            data: barExpense,
                            backgroundColor: 'rgba(239, 68, 68, 0.8)',
                            maxBarThickness: 28
                        },
                        {
                            label: 'Transfer',
                            data: barTransfer,
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            maxBarThickness: 28
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    },
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: (context) => `${context.dataset.label}: ${formatRupiah(context.raw)}`
                            }
                        }
                    }
                }
            });
        }

        const catLabels = @json($categoryBarChart['labels']);
        const catData = @json($categoryBarChart['data']);

        const ctxCatBar = document.getElementById('categoryBarChart');
        if (ctxCatBar) {
            new Chart(ctxCatBar, {
                type: 'bar',
                data: {
                    labels: catLabels.length > 0 ? catLabels : ['Tidak Ada Data'],
                    datasets: [{
                        label: 'Total Transaksi',
                        data: catData.length > 0 ? catData : [0],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1,
                        maxBarThickness: 36

                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (context) => `Total: ${formatRupiah(context.raw)}`
                            }
                        }
                    }
                }
            });
        }

    });
</script>



