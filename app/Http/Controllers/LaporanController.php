<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use App\Models\Setting; // 1. Tambahkan ini untuk memanggil Model Setting
use Illuminate\Http\Request;
use PDF;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        return view('laporan.index', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function getData($awal, $akhir)
    {
        $data = array();

        while (strtotime($awal) <= strtotime($akhir)) {
            $tanggal = $awal;
            $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));

            $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
            $total_pembelian = Pembelian::where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
            $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal%")->sum('nominal');
            $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;

            $row = array();
            $row['tanggal'] = $tanggal;
            $row['penjualan'] = $total_penjualan;
            $row['pembelian'] = $total_pembelian;
            $row['pengeluaran'] = $total_pengeluaran;
            $row['pendapatan'] = $pendapatan;

            $data[] = $row;
        }

        return $data;
    }

    public function data($awal, $akhir)
    {
        $rawData = $this->getData($awal, $akhir);

        $data = array();
        $no = 1;
        $total_pendapatan = 0;

        foreach ($rawData as $rawRow) {
            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['tanggal'] = tanggal_indonesia($rawRow['tanggal'], false);
            $row['penjualan'] = format_uang($rawRow['penjualan']);
            $row['pembelian'] = format_uang($rawRow['pembelian']);
            $row['pengeluaran'] = format_uang($rawRow['pengeluaran']);
            $row['pendapatan'] = format_uang($rawRow['pendapatan']);
            $data[] = $row;
            $total_pendapatan += $rawRow['pendapatan'];
        }
        // Menambahkan baris total untuk tampilan Datatables
        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => '',
            'penjualan' => '',
            'pembelian' => '',
            'pengeluaran' => '<b>Total Pendapatan</b>',
            'pendapatan' => '<b>'. format_uang($total_pendapatan) .'</b>',
        ];

        return datatables()
            ->of($data)
            ->rawColumns(['pengeluaran', 'pendapatan']) // Mengizinkan tag HTML (bold)
            ->make(true);
    }

    public function exportPDF($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);
        $setting = Setting::first(); // 2. Ambil data setting dari database

        // 3. Tambahkan 'setting' ke dalam compact agar dikirim ke view
        $pdf  = PDF::loadView('laporan.pdf', compact('awal', 'akhir', 'data', 'setting'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-pendapatan-'. date('Y-m-d-his') .'.pdf');
    }
}