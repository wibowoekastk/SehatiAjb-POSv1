<?php

namespace App\Http\Controllers;

// Import dari Laravel Framework
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// Import Model Aplikasi
use App\Models\Kategori;
use App\Models\Produk;

// Import dari Pihak Ketiga (Third-Party)
use Barryvdh\DomPDF\Facade\Pdf;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $kategori = Kategori::all()->pluck('nama_kategori', 'id_kategori');
        return view('produk.index', compact('kategori'));
    }

    public function data()
    {
        $produk = Produk::leftJoin('kategori', 'kategori.id_kategori', 'produk.id_kategori')
            ->select('produk.*', 'nama_kategori')
            ->get();

        return datatables()
            ->of($produk)
            ->addIndexColumn()
            ->addColumn('select_all', function ($produk) {
                return '<input type="checkbox" name="id_produk[]" value="'. $produk->id_produk .'">';
            })
            ->addColumn('kode_produk', function ($produk) {
                return '<span class="label label-success">'. $produk->kode_produk .'</span>';
            })
            ->addColumn('kadar', function ($produk) {
                return $produk->kadar ?? '-';
            })
            ->addColumn('gram', function ($produk) {
                return ($produk->gram ? $produk->gram . ' gr' : '-');
            })
            ->addColumn('harga_beli', function ($produk) {
                return format_uang($produk->harga_beli);
            })
            ->addColumn('harga_jual', function ($produk) {
                return format_uang($produk->harga_jual);
            })
            ->addColumn('stok', function ($produk) {
                return format_uang($produk->stok);
            })
            ->addColumn('aksi', function ($produk) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('produk.update', $produk->id_produk) .'`)" class="btn btn-xs btn-info"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button type="button" onclick="deleteData(`'. route('produk.destroy', $produk->id_produk) .'`)" class="btn btn-xs btn-danger"><i class="fa-solid fa-trash-can"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_produk', 'select_all'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fungsi ini tidak digunakan dalam alur AJAX, kembalikan 404
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $produk = Produk::latest()->first() ?? new Produk();
        $request['kode_produk'] = 'P'. tambah_nol_didepan((int)$produk->id_produk +1, 6);
        $produk = Produk::create($request->all());
        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        $produk = Produk::find($id);
        return response()->json($produk);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Fungsi ini tidak digunakan dalam alur AJAX, kembalikan 404
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $produk = Produk::find($id);
        $produk->update($request->all());
        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): Response
    {
        $produk = Produk::find($id);
        $produk->delete();
        return response(null, 204);
    }



    public function deleteSelected(Request $request): Response
    {
        foreach ($request->id_produk as $id) {
            $produk = Produk::find($id);
            $produk->delete();
        }
        return response(null, 204);
    }

    public function cetakBarcode(Request $request)
    {
        $dataproduk = array();
        foreach ($request->id_produk as $id) {
            $produk = Produk::find($id);
            $dataproduk[] = $produk;
        }

        $no  = 1;
        $pdf = Pdf::loadView('produk.barcode', compact('dataproduk', 'no'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('produk.pdf');
    }
}