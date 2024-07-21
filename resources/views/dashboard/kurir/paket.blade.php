<x-dashboard-layout>
    <h1 class="mt-4">Daftar Paket</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Daftar Paket</li>
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
                                <th>Nama Customer</th>
                                <th>Alamat</th>
                                <th>No HP</th>
                                <th>Barang</th>
                                <th>Catatan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td>{{$item->nama_customer}}</td>
                                    <td>{{$item->address}}</td>
                                    <td>{{$item->telepon}}</td>
                                    <td>{{$item->product_names}}</td>
                                    <td>{{$item->notes}}</td>
                                    <td>
                                        @if ($item->status == 4)
                                            Proses Pengiriman
                                        @else
                                            Barang telah diterima
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
