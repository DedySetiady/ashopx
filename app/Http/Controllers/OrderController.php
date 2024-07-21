<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
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
            'orders.tanggal as order_date', // Add this line
            DB::raw('GROUP_CONCAT(products.name SEPARATOR ", ") as product_names'),
            DB::raw('GROUP_CONCAT(carts.qty SEPARATOR ", ") as product_quantities'), // Tambahkan ini untuk jumlah barang
            DB::raw('SUM(products.price * carts.qty) as total_price'),
            'carts.status',
            'orders.notes as notes',
            'couriers.name as courier',
            'orders.method as method',
            'couriers.telepon as courier_telepon',
            'orders.ongkir', // Add this line
            'orders.created_at as order_time' // Tambahkan baris ini
        )
        ->where('carts.status', '>=', '3')
        ->groupBy('orders.batch', 'users.name', 'users.address', 'orders.tanggal', 'carts.status', 'orders.notes', 'couriers.name', 'orders.method', 'couriers.telepon', 'orders.ongkir', 'orders.created_at') // Add orders.tanggal here
        ->orderBy('orders.created_at', 'desc') // Add this line to sort by date in descending order
        ->get();

    return view('dashboard.order.index', compact('orders'));
}


public function destroy($id)
{
    $orders = Order::where('batch', $id)->get();

    foreach ($orders as $order) {
        $product = Product::where('id', $order->product_id)->first();

        $product->update([
            'stock' => $product->stock + $order->qty
        ]);

        $deleteItem = Cart::findOrFail($order->cart_id);
        $deleteItem->delete();
        Order::where('batch', $id)->delete();
    }

    return back()->with('success', 'Pesanan berhasil dibatalkan');
}

public function confirmation($id)
{
    $orders = Order::where('batch', $id)->get();

    foreach ($orders as $order) {
        $orders = Cart::where('id', $order->cart_id)->update([
            'status' => 5
        ]);

        Order::where('batch', $id)->update([
            'status' => 1
        ]);
    }

    return back()->with('success', 'Barang telah diterima');
}

public function create()
{
    $products = Product::where('status', 1)->where('stock', '>', 0)->get();

    return view('dashboard.order.create', compact('products'));
}

public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|array',
        'tanggal' => 'required|date',
        'qty' => 'required|array',
        'bukti' => 'required|image',
        'nama_customer' => 'required|string|max:255',
        'source' => 'required|in:shopee,offline'
    ]);

    $image = $request->file('bukti');
    $image->storeAs('public/photos', $image->hashName());

    $batchId = Order::max('batch') + 1;

    foreach ($request->product_id as $index => $productId) {
        $product = Product::findOrFail($productId);

        $product->update([
            'stock' => $product->stock - $request->qty[$index]
        ]);

        Order::create([
            'nama_customer' => $request->nama_customer,
            'method' => 'QRIS',
            'product_id' => $productId,
            'tanggal' => $request->tanggal,
            'qty' => $request->qty[$index],
            'source' => $request->source,
            'bukti' => $image->hashName(),
            'status' => 1,
            'batch' => $batchId
        ]);
    }

    return redirect()->route('product.index')->with('success', 'Data berhasil disimpan');
}
}