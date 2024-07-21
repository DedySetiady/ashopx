<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // Method untuk memastikan hanya admin yang dapat mengakses
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

        $products = Product::all();

        return view('dashboard.produk.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->safety();

        $categories = Category::all();

        return view('dashboard.produk.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->safety();

        // Validasi input
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'brand' => 'required',
            'price' => 'required',
            'description' => 'required',
            'stock' => 'required',
            'photo' => 'required|image',  // Validasi untuk file gambar
            'harga_awal' => 'nullable|numeric'
        ]);

        // Ambil nama produk dari input form
        $productName = $request->name;

        // Simpan foto dengan nama produk sebagai nama file
        $photo = $request->file('photo');
        $photoName = $productName . '.' . $photo->getClientOriginalExtension();
        $photo->storeAs('public/photos', $photoName);

        $status = $request->status ?? 'default_value_if_empty';

        // Simpan produk ke database
        Product::create([
            'photo' => $photoName,
            'name' => $request->name,
            'category_id' => $request->category_id,
            'brand' => $request->brand,
            'price' => $request->price,
            'expired' => $request->expired,
            'description' => $request->description,
            'stock' => $request->stock,
            'status' => $request->status,
            'harga_awal' => $request->harga_awal,
        ]);

        return redirect()->route('product.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        $products = Product::all()->except($id);

        return view('dashboard.produk.show', compact('product', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $this->safety();

        $categories = Category::all();

        return view('dashboard.produk.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $this->safety();

        // Validasi input
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'brand' => 'required',
            'price' => 'required',
            'description' => 'required',
            'stock' => 'required',
            'harga_awal' => 'nullable|numeric'
        ]);

        // Ambil nama produk dari input form
        $productName = $request->name;

        // Update data produk
        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'brand' => $request->brand,
            'price' => $request->price,
            'expired' => $request->expired,
            'description' => $request->description,
            'stock' => $request->stock,
            'status' => $request->status ?? 'default_value_if_empty', // Set nilai default jika kosong
            'harga_awal' => $request->harga_awal,
        ]);

        // Jika ada file gambar baru diunggah
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = $productName . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('public/photos', $photoName);

            // Update nama file gambar
            $product->update(['photo' => $photoName]);
        }

        return redirect()->route('product.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->safety();

        $product->delete();

        return redirect()->route('product.index')->with('success', 'Data berhasil dihapus');
    }

}