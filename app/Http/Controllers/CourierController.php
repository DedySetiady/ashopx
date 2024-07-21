<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CourierController extends Controller
{
    public function safety()
    {
        if (Auth::user()->role != 'admin') {
            abort('401');
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->safety();

        $couriers = Courier::all();

        return view('dashboard.kurir.index', compact('couriers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->safety();

        return view('dashboard.kurir.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->safety();

        $request->validate([
            'name' => 'required',
            'motor' => 'required',
            'gender' => 'required',
            'no_motor' => 'required',
            'telepon' => 'required',
            'photo' => 'required'
        ]);

        $photo = $request->file('photo');
        $photo->storeAs('public/photos', $photo->hashName());

        Courier::create([
            'name' => $request->name,
            'motor' => $request->motor,
            'gender' => $request->gender,
            'no_motor' => $request->no_motor,
            'telepon' => $request->telepon,
            'photo' => $photo->hashName()
        ]);

        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => strtolower($request->name) . '@ashopx.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'kurir',
        //     'telepon' => $request->telepon
        // ]);

        return redirect()->route('courier.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Courier $courier)
    {
        return view('dashboard.kurir.edit', compact('courier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Courier $courier)
    {
        $this->safety();

        $request->validate([
            'name' => 'required',
            'motor' => 'required',
            'gender' => 'required',
            'no_motor' => 'required',
            'telepon' => 'required',
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photo->storeAs('public/photos', $photo->hashName());

            $courier->update([
                'name' => $request->name,
                'motor' => $request->motor,
                'gender' => $request->gender,
                'no_motor' => $request->no_motor,
                'telepon' => $request->telepon,
                'photo' => $photo->hashName()
            ]);
        } else {
            $courier->update([
                'name' => $request->name,
                'motor' => $request->motor,
                'gender' => $request->gender,
                'no_motor' => $request->no_motor,
                'telepon' => $request->telepon,
            ]);
        }

        return redirect()->route('courier.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Courier $courier)
    {
        $this->safety();

        $courier->delete();

        return redirect()->route('courier.index')->with('success', 'Data berhasil disimpan');
    }

    public function paket()
    {
        $items = DB::table('orders')
            ->leftJoin('carts', 'carts.id', '=', 'orders.cart_id')
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->leftJoin('users', 'users.id', '=', 'carts.user_id')
            ->select(
                'orders.nama_customer',
                'orders.tanggal',
                'orders.method',
                'users.address',
                'users.telepon',
                'products.name as name',
                'orders.qty as qty',
                'orders.notes',
                DB::raw('GROUP_CONCAT(products.name SEPARATOR ", ") as product_names'),
                DB::raw('SUM(products.price * orders.qty) as total_price'),
                'carts.status'
            )
            ->where('orders.status', 1)
            ->groupBy('orders.nama_customer', 'orders.tanggal', 'products.name', 'orders.qty', 'users.address', 'orders.method', 'users.telepon', 'orders.notes', 'carts.status')
            ->get();

        return view('dashboard.kurir.paket', compact('items'));
    }
}