<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk seller
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // 1. Ambil Data Toko
        $toko = DB::table('tb_toko')->where('user_id', $user->id)->first();

        if (!$toko) {
            return redirect()->route('seller.dashboard')->with('error', 'Data Toko tidak ditemukan.');
        }

        // 2. Query Produk
        $query = DB::table('tb_barang')->where('toko_id', $toko->id);

        // 3. Filter Pencarian
        if ($request->has('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        // 4. Filter Status
        if ($request->has('status') && $request->status != '') {
            $status = $request->status;
            if ($status == 'active') {
                $query->where('is_active', 1)->where('status_moderasi', 'approved');
            } elseif ($status == 'inactive') {
                $query->where('is_active', 0)->where('status_moderasi', 'approved');
            } elseif ($status == 'pending') {
                $query->where('status_moderasi', 'pending');
            } elseif ($status == 'rejected') {
                $query->where('status_moderasi', 'rejected');
            }
        }

        // 5. Pagination
        $products = $query->orderByDesc('created_at')->paginate(10);

        return view('seller.products.index', compact('products'));
    }

    /**
     * Menampilkan Form Tambah Produk
     */
    public function create()
    {
        // FIX: Ambil data kategori agar tidak error di view
        $categories = DB::table('tb_kategori')->orderBy('nama_kategori', 'ASC')->get();
        
        // Kirim 'product' sebagai null karena ini mode Tambah (bukan Edit)
        return view('seller.products.create', [
            'categories' => $categories,
            'product' => null 
        ]);
    }

    /**
     * Menyimpan Produk Baru
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Lengkap
        $request->validate([
            'nama_barang' => 'required|string|min:25|max:255',
            'kategori_id' => 'required|exists:tb_kategori,id',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'berat_kg'    => 'required|numeric|min:0.01',
            'satuan_unit' => 'required|string',
            'deskripsi'   => 'required|string|min:100',
            'gambar'      => 'required|image|mimes:jpeg,png,jpg|max:2048', // Wajib saat tambah
            
            // Validasi Opsional
            'merk_barang' => 'nullable|string|max:100',
            'kode_barang' => 'nullable|string|max:50',
            'tipe_diskon' => 'nullable|in:NOMINAL,PERSEN',
            'nilai_diskon'=> 'nullable|numeric|min:0',
            'diskon_mulai'=> 'nullable|date',
            'diskon_berakhir' => 'nullable|date|after_or_equal:diskon_mulai',
        ]);

        // 2. Proses Upload Gambar
        $gambarName = 'default.jpg';
        if ($request->hasFile('gambar')) {
            $gambarName = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('assets/uploads/products'), $gambarName);
        }

        // 3. Ambil ID Toko
        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();

        // 4. Simpan ke Database
        DB::table('tb_barang')->insert([
            'toko_id'         => $toko->id,
            'kategori_id'     => $request->kategori_id,
            'nama_barang'     => $request->nama_barang,
            'merk_barang'     => $request->merk_barang, // Menyimpan Merek
            'kode_barang'     => $request->kode_barang, // Menyimpan SKU
            
            'harga'           => $request->harga,
            'stok'            => $request->stok,
            'berat_kg'        => $request->berat_kg,
            'satuan_unit'     => $request->satuan_unit,
            'deskripsi'       => $request->deskripsi,
            
            // Menyimpan Data Diskon
            'tipe_diskon'     => $request->tipe_diskon,
            'nilai_diskon'    => $request->nilai_diskon,
            'diskon_mulai'    => $request->diskon_mulai,
            'diskon_berakhir' => $request->diskon_berakhir,

            'gambar_utama'    => $gambarName,
            'status_moderasi' => 'pending',
            'is_active'       => 1,
            'created_at'      => now(),
            'updated_at'      => now()
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Menampilkan Form Edit
     */
    public function edit($id)
    {
        $product = DB::table('tb_barang')->where('id', $id)->first();
        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();
        
        // Security Check: Pastikan produk milik toko user yang login
        if (!$product || $product->toko_id !== $toko->id) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit produk ini.');
        }

        $categories = DB::table('tb_kategori')->orderBy('nama_kategori', 'ASC')->get();

        // Gunakan view yang sama dengan create, tapi kirim data product
        return view('seller.products.create', compact('product', 'categories'));
    }

    /**
     * Update Produk
     */
    public function update(Request $request, $id)
    {
        // 1. Validasi (Gambar nullable/opsional saat update)
        $request->validate([
            'nama_barang' => 'required|string|min:25|max:255',
            'kategori_id' => 'required|exists:tb_kategori,id',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'berat_kg'    => 'required|numeric|min:0.01',
            'satuan_unit' => 'required|string',
            'deskripsi'   => 'required|string|min:100',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            'merk_barang' => 'nullable|string|max:100',
            'kode_barang' => 'nullable|string|max:50',
            'tipe_diskon' => 'nullable|in:NOMINAL,PERSEN',
            'nilai_diskon'=> 'nullable|numeric|min:0',
            'diskon_mulai'=> 'nullable|date',
            'diskon_berakhir' => 'nullable|date|after_or_equal:diskon_mulai',
        ]);

        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();
        $existingProduct = DB::table('tb_barang')->where('id', $id)->where('toko_id', $toko->id)->first();

        // Pastikan produk ada dan milik user
        if (!$existingProduct) { abort(404); }

        $gambarName = $existingProduct->gambar_utama;

        // 2. Cek Upload Gambar Baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika bukan default
            if ($gambarName && $gambarName != 'default.jpg' && file_exists(public_path('assets/uploads/products/' . $gambarName))) {
                unlink(public_path('assets/uploads/products/' . $gambarName));
            }
            
            // Upload gambar baru
            $gambarName = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('assets/uploads/products'), $gambarName);
        }

        // 3. Update Database
        DB::table('tb_barang')->where('id', $id)->update([
            'kategori_id'     => $request->kategori_id,
            'nama_barang'     => $request->nama_barang,
            'merk_barang'     => $request->merk_barang,
            'kode_barang'     => $request->kode_barang,
            'harga'           => $request->harga,
            'stok'            => $request->stok,
            'berat_kg'        => $request->berat_kg,
            'satuan_unit'     => $request->satuan_unit,
            'deskripsi'       => $request->deskripsi,
            
            'tipe_diskon'     => $request->tipe_diskon,
            'nilai_diskon'    => $request->nilai_diskon,
            'diskon_mulai'    => $request->diskon_mulai,
            'diskon_berakhir' => $request->diskon_berakhir,
            
            'gambar_utama'    => $gambarName,
            'updated_at'      => now()
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus Produk
     */
    public function destroy($id)
    {
        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();
        
        $product = DB::table('tb_barang')->where('id', $id)->where('toko_id', $toko->id)->first();
        
        if ($product) {
            // Hapus file gambar fisik
            if ($product->gambar_utama && $product->gambar_utama != 'default.jpg' && file_exists(public_path('assets/uploads/products/' . $product->gambar_utama))) {
                unlink(public_path('assets/uploads/products/' . $product->gambar_utama));
            }

            // Hapus record database
            DB::table('tb_barang')->where('id', $id)->delete();
            return redirect()->route('seller.products.index')->with('success', 'Produk berhasil dihapus.');
        }

        return redirect()->route('seller.products.index')->with('error', 'Produk tidak ditemukan.');
    }
    
    /**
     * Toggle Status (Aktif/Nonaktif) via AJAX
     */
    public function toggleStatus(Request $request)
    {
        $toko = DB::table('tb_toko')->where('user_id', Auth::id())->first();
        
        $updated = DB::table('tb_barang')
            ->where('id', $request->product_id)
            ->where('toko_id', $toko->id)
            ->update(['is_active' => $request->is_active]);

        return response()->json(['success' => $updated]);
    }
}