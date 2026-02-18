<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Lihat Semua Produk (Read)
    public function index()
    {
        $toko = Toko::where('user_id', Auth::id())->first();
        $products = Barang::where('toko_id', $toko->id)->latest()->get();
        
        return view('seller.products.index', compact('products'));
    }

    // Form Tambah Produk (Create)
    public function create()
    {
        // Daftar satuan fleksibel untuk toko bangunan
        $units = ['Kg', 'Gram', 'Meter', 'Box', 'Pcs', 'Batang', 'Sakti', 'Eceran', 'Roll'];
        return view('seller.products.create', compact('units'));
    }

    // Simpan Produk Baru (Store)
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'satuan_unit' => 'required',
            'stok' => 'required|integer',
            'foto_barang' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $toko = Toko::where('user_id', Auth::id())->first();

        $fotoPath = null;
        if ($request->hasFile('foto_barang')) {
            $fotoPath = $request->file('foto_barang')->store('products', 'public');
        }

        Barang::create([
            'toko_id' => $toko->id,
            'nama_barang' => $request->nama_barang,
            'slug' => Str::slug($request->nama_barang) . '-' . time(),
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'satuan_unit' => $request->satuan_unit,
            'stok' => $request->stok,
            'foto_barang' => $fotoPath,
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    // Ubah Produk (Update)
    public function update(Request $request, $id)
    {
        $product = Barang::findOrFail($id);
        // Logika update serupa dengan store...
    }

    // Hapus Produk (Delete)
    public function destroy($id)
    {
        $product = Barang::findOrFail($id);
        if ($product->foto_barang) {
            Storage::disk('public')->delete($product->foto_barang);
        }
        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus!');
    }
}