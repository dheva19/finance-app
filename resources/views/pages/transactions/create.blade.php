@php
    $breadcrumps = [
        [
            'label' => 'Transaksi',
            'url' => route('transactions.index')
        ],
        [
            'label' => 'Buat Transaksi',
            'url' => route('transactions.create')
        ]
    ]
@endphp

<x-layout.workspace :breadcrumps="$breadcrumps" title="Buat Transaksi">
    <x-ui.card class="w-full h-fit">
        <h3 class="font-semibold text-xl">Buat Transaksi</h3>

        <form action="{{ route('transactions.store') }}" method="POST" class="w-full">
            @csrf
            <section class="w-full grid grid-cols-12 gap-3 my-5">
                <div class="mb-3 col-span-12 md:col-span-4">
                    <x-ui.label for="amount" required>Nominal (Rp)</x-ui.label>
                    <x-ui.input
                        type="number"
                        id="amount"
                        name="amount"
                        required
                        placeholder="0"
                        class="w-full"
                        min=0
                        value="{{ old('amount') }}"
                    />
                </div>

                <div class="mb-3 col-span-12 md:col-span-4">
                    <x-ui.label for="type" required>Tipe</x-ui.label>
                    <x-ui.select id="type" name="type" class="w-full">
                        <x-ui.option disabled selected>--Pilih Tipe--</x-ui.option>
                        <x-ui.option value="income">Income (Pemasukan)</x-ui.option>
                        <x-ui.option value="expense">Expense (Pengeluaran)</x-ui.option>
                        <x-ui.option value="transfer">Transfer</x-ui.option>
                    </x-ui.select>
                </div>

                <div class="mb-3 col-span-12 md:col-span-4">
                    <x-ui.label for="category" required>Kategori</x-ui.label>
                    <x-ui.select id="category" name="category_id" class="w-full">
                        <x-ui.option value="" disabled selected>--Pilih Kategori--</x-ui.option>
                        @foreach ($categories as $item)
                            <x-ui.option value="{{ $item->id }}" class="option-{{ $item->type }} hidden">{{ $item->name }}</x-ui.option>
                        @endforeach
                    </x-ui.select>
                </div>

                <div id="from-pocket-input" class="mb-3 col-span-12 md:col-span-4 hidden">
                    <x-ui.label for="from_pocket" required>Dari Kantong (Keluar)</x-ui.label>
                    <x-ui.select id="from_pocket" name="from_pocket_id" class="w-full">
                        <x-ui.option disabled selected>--Pilih Kantong--</x-ui.option>
                        @foreach ($pockets as $item)
                            <x-ui.option value="{{ $item->id }}">{{ $item->name }} (Rp{{ number_format($item->amount,0, ',', '.') }})</x-ui.option>
                        @endforeach
                    </x-ui.select>
                </div>

                <div id="to-pocket-input" class="mb-3 col-span-12 md:col-span-4 hidden">
                    <x-ui.label for="to_pocket" required>Untuk Kantong (Masuk)</x-ui.label>
                    <x-ui.select id="to_pocket" name="to_pocket_id" class="w-full">
                        <x-ui.option disabled selected>--Pilih Kantong--</x-ui.option>
                        @foreach ($pockets as $item)
                            <x-ui.option value="{{ $item->id }}">{{ $item->name }} (Rp{{ number_format($item->amount,0, ',', '.') }})</x-ui.option>
                        @endforeach
                    </x-ui.select>
                </div>

                <div class="mb-3 col-span-12 md:col-span-12">
                    <x-ui.label for="note">Catatan</x-ui.label>
                    <x-ui.textarea
                        id="note"
                        name="note"
                        class="w-full"
                        rows="7"
                    ></x-ui.textarea>
                </div>
            </section>

            <section class="flex justify-between">
                <x-ui.button type="reset" variant="secondary">Reset</x-ui.button>
                <div class="flex items-center gap-3">
                    <x-ui.button onclick="window.location.href='{{ route('transactions.index') }}'" type="button" variant="outline">Kembali</x-ui.button>
                    <x-ui.button type="submit" variant="primary">Kirim</x-ui.button>
                </div>
            </section>
        </form>
    </x-ui.card>
</x-layout.workspace>

<script>
    document.querySelector('#type').addEventListener('change', (event) => {
        const value = event.target.value;
        const fromPocketInput = document.querySelector('#from-pocket-input');
        const toPocketInput = document.querySelector('#to-pocket-input');

        const categorySelect = document.querySelector('#category');

        const incomeOptions = document.querySelectorAll('.option-income');
        const expenseOptions = document.querySelectorAll('.option-expense');
        const transferOptions = document.querySelectorAll('.option-transfer');

        if(value == 'income'){
            fromPocketInput.classList.add('hidden');
            toPocketInput.classList.remove('hidden');

            categorySelect.value = "";

            incomeOptions.forEach(e => {
                e.classList.remove('hidden');
            });

            expenseOptions.forEach(e => {
                e.classList.add('hidden');
            });

            expenseOptions.forEach(e => {
                e.classList.add('hidden');
            });

        }else if(value == 'expense'){
            fromPocketInput.classList.remove('hidden');
            toPocketInput.classList.add('hidden');

            categorySelect.value = "";

            incomeOptions.forEach(e => {
                e.classList.add('hidden');
            });

            expenseOptions.forEach(e => {
                e.classList.remove('hidden');
            });

            expenseOptions.forEach(e => {
                e.classList.add('hidden');
            });
        }else if(value == 'transfer'){
            fromPocketInput.classList.remove('hidden');
            toPocketInput.classList.remove('hidden');

            categorySelect.value = "";

            incomeOptions.forEach(e => {
                e.classList.add('hidden');
            });

            expenseOptions.forEach(e => {
                e.classList.add('hidden');
            });

            expenseOptions.forEach(e => {
                e.classList.remove('hidden');
            });
        }
    });
</script>

