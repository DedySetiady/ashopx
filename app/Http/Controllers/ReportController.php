<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        $items = DB::table('orders')
            ->leftJoin('carts', 'carts.id', '=', 'orders.cart_id')
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->leftJoin('users', 'users.id', '=', 'carts.user_id')
            ->select(
                'orders.nama_customer',
                'orders.tanggal',
                'products.name as name',
                'orders.qty as qty',
                DB::raw('SUM(products.price * orders.qty) as total_price'),
                'orders.bukti',
                'orders.source'
            )
            ->where('orders.status', 1)
            ->groupBy('orders.nama_customer', 'orders.tanggal', 'products.name', 'orders.qty', 'orders.bukti', 'orders.source')
            ->orderBy('orders.tanggal', 'desc') // Urutkan berdasarkan tanggal dalam urutan menurun
            ->get();

        return view('dashboard.laporan.index', compact('items'));
    }

    public function pdf(Request $request)
    {
        $query = DB::table('orders')
            ->leftJoin('carts', 'carts.id', '=', 'orders.cart_id')
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->leftJoin('users', 'users.id', '=', 'carts.user_id')
            ->select(
                'orders.nama_customer',
                'orders.tanggal',
                'products.name as name',
                'orders.qty as qty',
                DB::raw('SUM(products.price * orders.qty) as total_price')
            )
            ->where('orders.status', 1)
            ->groupBy('orders.nama_customer', 'orders.tanggal', 'products.name', 'orders.qty');

        // Filter berdasarkan bulan dan tahun jika disediakan
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereYear('orders.tanggal', $request->tahun)
                  ->whereMonth('orders.tanggal', $request->bulan);
        } elseif ($request->filled('tahun')) {
            $query->whereYear('orders.tanggal', $request->tahun);
        }

        $items = $query->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        // Hitung total qty dan total price
        $totalQty = $items->sum('qty');
        $totalPrice = $items->sum('total_price');

        $pdf = Pdf::loadView('dashboard.laporan.export', [
            'items' => $items, 
            'totalQty' => $totalQty, 
            'totalPrice' => $totalPrice
        ]);

        return $pdf->download('Laporan.pdf');
    }

    public function excel(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $query = DB::table('orders')
            ->leftJoin('carts', 'carts.id', '=', 'orders.cart_id')
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->leftJoin('users', 'users.id', '=', 'carts.user_id')
            ->select(
                'orders.nama_customer',
                'orders.tanggal',
                'products.name as name',
                'orders.qty as qty',
                DB::raw('SUM(products.price * orders.qty) as total_price')
            )
            ->where('orders.status', 1)
            ->groupBy('orders.nama_customer', 'orders.tanggal', 'products.name', 'orders.qty');

        // Filter berdasarkan bulan dan tahun jika disediakan
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereYear('orders.tanggal', $request->tahun)
                  ->whereMonth('orders.tanggal', $request->bulan);
        } elseif ($request->filled('tahun')) {
            $query->whereYear('orders.tanggal', $request->tahun);
        }

        $items = $query->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        // Hitung total qty dan total price
        $totalQty = $items->sum('qty');
        $totalPrice = $items->sum('total_price');

        return Excel::download(new ReportExport($items, $totalQty, $totalPrice, $bulan, $tahun), 'Laporan.xlsx');
    }
}