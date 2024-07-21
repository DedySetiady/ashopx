<x-dashboard-layout>
    <h1 class="mt-4">Tambah Laporan Penjualan (Offline)</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Tambah Laporan Penjualan (Offline)</li>
    </ol>
    <div class="row mb-5">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data"
                        id="order-form">
                        @csrf
                        <div class="col-12 mb-3">
                            <label for="nama_customer" class="form-label">Nama Customer</label>
                            <input type="text" class="form-control @error('nama_customer') is-invalid @enderror"
                                name="nama_customer" value="{{ old('nama_customer') }}" placeholder="Nama Customer">
                            @error('nama_customer')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                name="tanggal" value="{{ old('tanggal') }}" placeholder="Tanggal">
                            @error('tanggal')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div id="product-container">
                            <div class="row mb-3 product-row">
                                <div class="col-12 mb-3">
                                    <label for="product_id" class="form-label">Nama Produk</label>
                                    <select name="product_id[]"
                                        class="form-select product-select @error('product_id') is-invalid @enderror">
                                        <option selected disabled>-- Pilih Produk --</option>
                                        @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-stock="{{ $product->stock }}"
                                            data-price="{{ $product->price }}"
                                            {{ $product->id == old('product_id') ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-4 mb-3">
                                    <label for="qty" class="form-label">Jumlah</label>
                                    <select name="qty[]"
                                        class="form-select qty-select @error('qty') is-invalid @enderror">
                                    </select>
                                    @error('qty')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-2 mb-3">
                                    <label for="price" class="form-label">Harga</label>
                                    <input type="text" class="form-control price" name="price[]" readonly>
                                </div>

                                <div class="col-2 mb-3">
                                    <button type="button" class="btn btn-danger remove-product">Hapus</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <button type="button" class="btn btn-primary" id="add-product">Tambah Produk</button>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="total-price" class="form-label">Total Harga</label>
                            <input type="text" class="form-control" id="total-price" name="total_price" readonly>
                        </div>

                        <div class="col-4 mb-3">
                            <label for="bukti" class="form-label">Bukti Pembayaran</label>
                            <input type="file" class="form-control @error('bukti') is-invalid @enderror" name="bukti"
                                value="{{ old('bukti') }}" placeholder="Bukti Pembayaran">
                            @error('bukti')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="source" class="form-label">Sumber</label>
                            <select name="source" id="source" class="form-select @error('source') is-invalid @enderror">
                                <option value="offline" {{ old('source') == 'offline' ? 'selected' : '' }}>Offline
                                </option>
                                <option value="shopee" {{ old('source') == 'shopee' ? 'selected' : '' }}>Shopee</option>
                            </select>
                            @error('source')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const productContainer = document.getElementById('product-container');
        const addProductButton = document.getElementById('add-product');
        const totalPriceInput = document.getElementById('total-price');

        function updateQtyOptions(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const stock = selectedOption.getAttribute('data-stock');
            const price = selectedOption.getAttribute('data-price');
            const row = selectElement.closest('.product-row');
            const qtySelect = row.querySelector('.qty-select');
            const priceInput = row.querySelector('.price');

            qtySelect.innerHTML = '';
            priceInput.value = price;

            for (let i = 1; i <= stock; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;
                qtySelect.appendChild(option);
            }

            qtySelect.dispatchEvent(new Event('change'));
        }

        function updateTotalPrice() {
            let totalPrice = 0;
            document.querySelectorAll('.product-row').forEach(row => {
                const qty = row.querySelector('.qty-select').value;
                const price = row.querySelector('.price').value;
                totalPrice += qty * price;
            });
            totalPriceInput.value = totalPrice;
        }

        function addProductRow() {
            const newRow = document.querySelector('.product-row').cloneNode(true);
            newRow.querySelectorAll('input, select').forEach(input => {
                input.value = '';
            });
            newRow.querySelector('.remove-product').addEventListener('click', function() {
                newRow.remove();
                updateTotalPrice();
            });
            newRow.querySelector('.product-select').addEventListener('change', function() {
                updateQtyOptions(this);
            });
            newRow.querySelector('.qty-select').addEventListener('change', updateTotalPrice);
            productContainer.appendChild(newRow);
        }

        document.querySelectorAll('.product-select').forEach(select => {
            select.addEventListener('change', function() {
                updateQtyOptions(this);
            });
        });

        document.querySelectorAll('.qty-select').forEach(select => {
            select.addEventListener('change', updateTotalPrice);
        });

        document.querySelectorAll('.remove-product').forEach(button => {
            button.addEventListener('click', function() {
                button.closest('.product-row').remove();
                updateTotalPrice();
            });
        });

        addProductButton.addEventListener('click', addProductRow);

        // Initialize
        document.querySelectorAll('.product-select').forEach(select => {
            select.dispatchEvent(new Event('change'));
        });
    });
    </script>
</x-dashboard-layout>