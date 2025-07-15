<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;

class PenjualanDetailController extends Controller
{
    public function index()
    {
        $produk = Produk::orderBy('nama_produk')->get();
        $member = Member::orderBy('nama')->get();
        $diskon = Setting::first()->diskon ?? 0;

        $penjualan = null;
        // Coba ambil ID dari session
        if ($id_penjualan = session('id_penjualan')) {
            // Cari transaksi berdasarkan ID tersebut
            $penjualan = Penjualan::find($id_penjualan);
        }

        // Jika transaksi di session tidak valid (sudah dihapus), atau tidak ada session sama sekali
        if (! $penjualan) {
            // Lupakan session yang salah jika ada
            session()->forget('id_penjualan');
            
            // Hanya admin (level 1) yang bisa membuat transaksi baru secara otomatis
            if (auth()->user()->level == 1) {
                // Buat transaksi baru
                $penjualan = new Penjualan();
                $penjualan->id_member = null;
                $penjualan->total_item = 0;
                $penjualan->total_harga = 0;
                $penjualan->diskon = 0;
                $penjualan->bayar = 0;
                $penjualan->diterima = 0;
                $penjualan->id_user = auth()->id();
                $penjualan->save();

                // Simpan ID baru ke session
                session(['id_penjualan' => $penjualan->id_penjualan]);
            } else {
                // Jika bukan admin, arahkan ke halaman utama untuk menghindari error
                return redirect()->route('home');
            }
        }

        // Pada titik ini, kita pasti memiliki objek $penjualan yang valid
        $memberSelected = $penjualan->member ?? new Member();
        // Pastikan variabel id_penjualan selalu ada untuk dikirim ke view
        $id_penjualan = $penjualan->id_penjualan;

        return view('penjualan_detail.index', compact('produk', 'member', 'diskon', 'id_penjualan', 'penjualan', 'memberSelected'));
    }

    public function data($id)
    {
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_produk'] = '<span class="label label-success">'. $item->produk['kode_produk'] .'</span>';
            $row['nama_produk'] = $item->produk['nama_produk'];
            $row['kadar']       = $item->kadar ?? '-';
            $row['gram']        = ($item->gram ? $item->gram . ' gr' : '-');
            $row['harga_jual']  = 'Rp. '. format_uang($item->harga_jual);
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id_penjualan_detail .'" value="'. $item->jumlah .'">';
            $row['diskon']      = $item->diskon . '%';
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                        <button onclick="deleteData(`'. route('transaksi.destroy', $item->id_penjualan_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                    </div>';
            $data[] = $row;

            $total += $item->harga_jual * $item->jumlah - (($item->diskon * $item->jumlah) / 100 * $item->harga_jual);;
            $total_item += $item->jumlah;
        }
        $data[] = [
            'kode_produk' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'nama_produk' => '',
            'kadar'       => '',
            'gram'        => '',
            'harga_jual'  => '',
            'jumlah'      => '',
            'diskon'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_produk', 'jumlah'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $produk = Produk::where('id_produk', $request->id_produk)->first();
        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        if ($produk->stok < 1) {
            return response()->json('Stok produk telah habis', 400);
        }

        $detail = new PenjualanDetail();
        $detail->id_penjualan = $request->id_penjualan;
        $detail->id_produk = $produk->id_produk;
        $detail->kadar = $produk->kadar;
        $detail->gram = $produk->gram;
        $detail->harga_jual = $produk->harga_jual;
        $detail->jumlah = 1;
        $detail->diskon = $produk->diskon;
        $detail->subtotal = $produk->harga_jual - ($produk->diskon / 100 * $produk->harga_jual);;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = PenjualanDetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_jual * $request->jumlah - (($detail->diskon * $request->jumlah) / 100 * $detail->harga_jual);;
        $detail->update();
    }

    public function destroy($id)
    {
        $detail = PenjualanDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($diskon = 0, $total = 0, $diterima = 0)
    {
        $bayar   = $total - ($diskon / 100 * $total);
        $kembali = ($diterima != 0) ? $diterima - $bayar : 0;
        $data    = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar). ' Rupiah'),
            'kembalirp' => format_uang($kembali),
            'kembali_terbilang' => ucwords(terbilang($kembali). ' Rupiah'),
        ];

        return response()->json($data);
    }
}
