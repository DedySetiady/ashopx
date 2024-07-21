<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembelian</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 80%;
        margin: 20px auto;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        border-radius: 8px;
        max-width: 600px;
        /* Lebar maksimum container */
    }

    .title {
        text-align: center;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th,
    td {
        border: 1px solid #dddddd;
        padding: 10px;
        text-align: left;
        font-size: 14px;
    }

    th {
        background-color: #f2f2f2;
        width: 40%;
        /* Lebar kolom header */
    }

    td {
        width: 60%;
        /* Lebar kolom isi */
    }

    .footer {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="title">
            <h2>Struk Pembelian</h2>
        </div>
        <table>
            <tr>
                <th>Nama Pelanggan</th>
                <td>{{ $order->name }}</td>
            </tr>
            <tr>
                <th>Nomor Telepon</th>
                <td>{{ Auth::user()->telepon }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $order->address }}</td>
            </tr>
            <tr>
                <th>Metode Pembayaran</th>
                <td>{{ $order->method }}</td>
            </tr>
            <tr>
                <th>Nama Pengirim</th>
                <td>{{ $order->asal_pengirim }}</td>
            </tr>
            <tr>
                <th>Produk</th>
                <td>
                    <ul>
                        @foreach ($order->product_names as $key => $productName)
                        <li>{{ $productName }} - {{ $order->product_quantities[$key] }} pcs</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            <tr>
                <th>Total Harga</th>
                <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
            </tr>
        </table>
        <div class="footer">
            <p>Terima kasih atas pembeliannya!</p>
        </div>
    </div>
</body>

</html>