<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Courier;
use App\Models\Order;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;

class TransactionController extends Controller
{
    public function index()
    {
        $orders = DB::table('carts')
            ->join('orders', 'orders.cart_id', '=', 'carts.id')
            ->join('products', 'carts.product_id', '=', 'products.id')
            ->leftJoin('couriers', 'couriers.id', '=', 'carts.courier_id')
            ->join('users', 'users.id', '=', 'carts.user_id')
            ->select(
                'orders.batch as id',
                'users.name as name',
                'users.address as address',
                'users.telepon as telepon',
                DB::raw('CONCAT(orders.tanggal, " ", DATE_FORMAT(orders.created_at, "%H:%i:%s")) as order_datetime'),
                DB::raw('GROUP_CONCAT(products.name SEPARATOR ", ") as product_names'),
                DB::raw('GROUP_CONCAT(carts.qty SEPARATOR ", ") as product_quantities'),
                DB::raw('SUM(products.price * carts.qty) as total_price'),
                'carts.status',
                'orders.notes as notes',
                'couriers.name as courier',
                'orders.method as method',
                'carts.bukti_refund',
                'carts.alasan_refund',
                'orders.ongkir',
                DB::raw('SUM(products.price * carts.qty) + orders.ongkir as total_harga')
            )
            ->where('carts.status', '>=', '3')
            ->groupBy('orders.batch', 'users.name', 'users.address', 'users.telepon', 'order_datetime', 'carts.status', 'orders.notes', 'couriers.name', 'orders.method', 'carts.alasan_refund', 'carts.bukti_refund', 'orders.ongkir') // Menggunakan order_datetime
            ->orderBy('orders.created_at', 'desc')
            ->get();
    
        return view('dashboard.transaksi.index', compact('orders'));
    }
    


    public function edit($id)
    {
        $couriers = Courier::all();
        $cart = Order::where('batch', $id)->first();

        return view('dashboard.transaksi.edit', compact('id', 'couriers', 'cart'));
    }

    public function update(Request $request, $id)
    {

        $orders = Order::where('batch', $id)->get();

        foreach ($orders as $order) {
            Cart::where('id', intval($order->cart_id))->update([
                'status' => 4,
                'courier_id' => $request->courier_id
            ]);
        }

        return redirect()->route('transaction.index')->with('Success', 'Data berhasil disimpan');
    }

    public function confirmation($id)
{
    $orders = Order::where('batch', $id)->get();

    foreach ($orders as $order) {
        $product = Product::findOrFail($order->product_id);

        // Kurangi stok produk
        $product->update([
            'stock' => $product->stock - $order->qty
        ]);

        // Update status pesanan dan item di keranjang
        Cart::where('id', $order->cart_id)->update([
            'status' => 5 // Status untuk pesanan yang dikonfirmasi
        ]);

        Order::where('batch', $id)->update([
            'status' => 2 // Misalnya, status 2 untuk pesanan yang dikonfirmasi
        ]);
    }

    return back()->with('success', 'Pesanan berhasil dikonfirmasi.');
}

    
public function rejectOrder(Request $request, $id)
{
    $orders = Order::where('batch', $id)->get();

    foreach ($orders as $order) {
        $product = Product::findOrFail($order->product_id);

        // Kembalikan stok produk
        $product->update([
            'stock' => $product->stock + $order->qty
        ]);

        // Update status pesanan dan item di keranjang
        Cart::where('id', $order->cart_id)->update([
            'status' => 9 // Status untuk pesanan yang ditolak
        ]);
    }

    return back()->with('success', 'Pesanan berhasil ditolak.');
}

public function print($id)
{
    // Ambil data order berdasarkan ID
    $order = DB::table('carts')
        ->join('orders', 'orders.cart_id', '=', 'carts.id')
        ->join('products', 'carts.product_id', '=', 'products.id')
        ->leftJoin('couriers', 'couriers.id', '=', 'carts.courier_id')
        ->join('users', 'users.id', '=', 'carts.user_id')
        ->select(
            'users.name as name',
            'users.address as address',
            'orders.method as method',
            'orders.ongkir',
            DB::raw('SUM(products.price * carts.qty) + orders.ongkir as total_harga'),
            DB::raw('GROUP_CONCAT(products.name) as product_names'),
            DB::raw('GROUP_CONCAT(carts.qty) as product_quantities'),
            // DB::raw('SUM(products.price * carts.qty) as total_price'),
            DB::raw("'AshopX' as asal_pengirim")
        )
        ->where('orders.batch', $id)
        ->groupBy('users.name', 'users.address', 'orders.method', 'orders.ongkir') // Tambahkan klausa GROUP BY
        ->first();

    // Validasi jika pesanan tidak ditemukan
    if (!$order) {
        abort(404);
    }

    // Ubah kolom GROUP_CONCAT menjadi array
    $order->product_names = explode(',', $order->product_names);
    $order->product_quantities = explode(',', $order->product_quantities);

    // Load view untuk tampilan struk dalam format HTML
    return view('dashboard.transaksi.print', compact('order'));
}


}