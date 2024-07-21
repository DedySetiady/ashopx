<x-app-layout>
    <section class="pt-5 pb-5">
        <div class="container">
            @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
            @endif
            <div class="card">
                <div class="card-header text-center">
                    Order
                </div>
                <div class="card-body table-responsive">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th> <!-- Add Tanggal column -->
                                <th>Waktu Order</th> <!-- Tambahkan kolom Waktu Order -->
                                <th>Barang</th>
                                <!-- <th>Total</th>
                                <th>Ongkir</th> -->
                                <th>Total Harga</th>
                                <th>Metode</th>
                                <th>Nama Kurir</th>
                                <th>Telepon Kurir</th>
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
                                <td>{{ $order->order_date }}</td>
                                <!-- Display Tanggal -->
                                <td>{{ \Carbon\Carbon::parse($order->order_time)->setTimezone('Asia/Jakarta')->format('H:i:s') }}
                                </td> <!-- Tampilkan Waktu Order -->
                                <td>
                                    @php
                                    $products = explode(", ", $order->product_names);
                                    $quantities = explode(", ", $order->product_quantities);
                                    for ($i = 0; $i < count($products); $i++) { echo $products[$i]
                                        . ' (<span style="color: red;">' . $quantities[$i] . '</span>)<br>' ; } @endphp
                                        </td>
                                        <!-- <td>Rp. {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($order->ongkir, 0, ',', '.') }}</td> -->
                                <td>IDR {{ number_format($order->total_price + $order->ongkir, 0, ',', '.') }}</td>
                                <!-- Calculate Total Harga -->
                                <td>{{ $order->method }}</td>
                                <td>{{ $order->courier }}</td>
                                <td>{{ $order->courier_telepon }}</td>
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
                                    <a href="{{ route('order.destroy', $order->id) }}" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin ingin batalkan pesanan?')">
                                        Batalkan
                                    </a>
                                    @elseif ($order->status == 4)
                                    <a href="{{ route('order.confirmation', $order->id) }}"
                                        class="btn btn-sm btn-success"
                                        onclick="return confirm('Yakin ingin terima barang?')">
                                        Terima
                                    </a>
                                    <a class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#refund{{ $order->id }}">
                                        Refund
                                    </a>

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
                                                <form action="{{ route('refund') }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $order->id }}">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Alasan Refund</label>
                                                            <textarea name="alasan_refund" class="form-control"
                                                                placeholder="Alasan Refund" required></textarea>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Bukti</label>
                                                            <input type="file" class="form-control" name="bukti_refund"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">
                                                            Refund
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#datatablesSimple').DataTable({
            responsive: true
        });
    });
    </script>


</x-app-layout>