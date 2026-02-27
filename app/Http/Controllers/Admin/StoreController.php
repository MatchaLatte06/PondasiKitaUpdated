<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $status_filter = $request->get('status', 'pending');
        $search = $request->get('search');

        // Statistik Cepat untuk Top Cards
        $stats = [
            'total' => DB::table('tb_toko')->count(),
            'pending' => DB::table('tb_toko')->where('status', 'pending')->count(),
            'active' => DB::table('tb_toko')->where('status', 'active')->count(),
            'suspended' => DB::table('tb_toko')->where('status', 'suspended')->count(),
        ];

        // Query Utama
        $query = DB::table('tb_toko as t')
            ->join('tb_user as u', 't.user_id', '=', 'u.id')
            ->leftJoin('cities as c', 't.city_id', '=', 'c.id')
            ->select(
                't.*', 
                'u.nama as nama_pemilik', 
                'u.email as email_pemilik',
                'c.name as nama_kota'
            );

        if ($status_filter !== 'semua') {
            $query->where('t.status', $status_filter);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('t.nama_toko', 'LIKE', "%$search%")
                  ->orWhere('u.nama', 'LIKE', "%$search%");
            });
        }

        $stores = $query->latest('t.created_at')->paginate(10)->withQueryString();

        return view('admin.stores.index', compact('stores', 'status_filter', 'search', 'stats'));
    }

    public function verify(Request $request, $id)
    {
        $action = $request->action; // 'setujui' atau 'tolak'
        $status = ($action === 'setujui') ? 'active' : 'suspended';

        DB::table('tb_toko')->where('id', $id)->update([
            'status' => $status,
            'updated_at' => now()
        ]);

        $msg = ($action === 'setujui') ? 'Toko berhasil diaktifkan!' : 'Pendaftaran toko telah ditolak.';
        return back()->with('success', $msg);
    }
}