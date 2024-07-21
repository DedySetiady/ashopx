<x-dashboard-layout>
    <h1 class="mt-4">Transaksi</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Transaksi</li>
    </ol>

    <div class="row mb-3">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif

                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal-Waktu</th> <!-- Add Tanggal column -->
                                <th>Nama Pelanggan</th>
                                <th>Produk</th>
                                <th>Metode</th>
                                <!-- <th>Total Harga</th>
                                <th>Ongkir</th> -->
                                <th>Total Harga</th>
                                <th>Kurir</th>
                                <th>Alamat Pelanggan</th>
                                <th>Catatan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $no = 1;
                            @endphp
                            @foreach ($orders as $order)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <!-- Ubah bagian yang menampilkan Tanggal dan Waktu Order -->
                                <td>{{ \Carbon\Carbon::parse($order->order_datetime)->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s') }}
                                </td>
                                <td>{{ $order->name }} ({{ $order->telepon }})</td>
                                <td>
                                    @php
                                    $products = explode(", ", $order->product_names);
                                    $quantities = explode(", ", $order->product_quantities);
                                    for ($i = 0; $i < count($products); $i++) { echo $products[$i]
                                        . ' (<span style="color: red;">' . $quantities[$i] . '</span>)<br>' ; } @endphp
                                        </td>
                                <td>{{ $order->method }}</td>
                                <!-- <td>{{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td>{{ number_format($order->ongkir, 0, ',', '.') }}</td> -->
                                <td>{{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                <!-- Display Total Harga -->
                                <td>{{ $order->courier }}</td>
                                <td>{{ $order->address }}</td>
                                <td>{{ $order->notes }}</td>
                                <td>
                                    @php
                                    if ($order->status == 3) {
                                    echo 'Proses Pengemasan';
                                    } elseif ($order->status == 4) {
                                    echo 'Proses Pengiriman';
                                    } elseif ($order->status == 5) {
                                    echo 'Barang telah diterima';
                                    } elseif ($order->status == 6) {
                                    echo 'Pengajuan Refund';
                                    } elseif ($order->status == 7) {
                                    echo 'Pengajuan Refund Ditolak';
                                    } elseif ($order->status == 8) {
                                    echo 'Pengajuan Refund Diterima';
                                    } elseif ($order->status == 9) {
                                    echo 'Order Ditolak';
                                    }
                                    @endphp
                                </td>
                                <td>
                                    @if ($order->status == 3)
                                    <a href="{{ route('transaction.print', $order->id) }}" class="btn btn-sm btn-info"
                                        target="_blank">
                                        Cetak Struk
                                    </a>
                                    @endif
                                    @if ($order->status == 6)
                                    <a class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#refund{{ $order->id }}">
                                        Refund
                                    </a>
                                    @elseif ($order->status >= 4)
                                    <button class="btn btn-sm btn-warning" disabled>Proses</button>
                                    @else
                                    <a href="{{ route('transaction.edit', $order->id) }}"
                                        class="btn btn-warning btn-sm">Proses</a>
                                    <form action="{{ route('transaction.reject', $order->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                    </form>
                                    @endif

                                </td>
                            </tr>

                            <!-- Modal -->
                            <div class="modal fade" id="refund{{ $order->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Refund
                                                {{ $order->product_names }}</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <input type="hidden" name="id" value="{{ $order->id }}">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Alasan Refund</label>
                                                <textarea name="alasan_refund" class="form-control"
                                                    placeholder="Alasan Refund"
                                                    disabled>{{ $order->alasan_refund }}</textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Bukti</label>
                                                <img src="/storage/photos/{{ $order->bukti_refund }}"
                                                    class="img-fluid img-thumbnail">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <a href="{{ route('refund.reject', $order->id) }}" class="btn btn-danger">
                                                Tolak Refund
                                            </a>
                                            <a href="{{ route('refund.accept', $order->id) }}" class="btn btn-primary">
                                                Terima Refund
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>