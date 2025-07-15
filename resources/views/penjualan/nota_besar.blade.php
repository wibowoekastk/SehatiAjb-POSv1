<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota Pembelian - {{ $setting->nama_perusahaan }}</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .container {
            padding: 10px;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header .logo {
            width: 80px;
            height: auto;
            float: left;
        }
        .header .store-info {
            float: left;
            margin-left: 20px;
        }
        .header .store-info h1 {
            font-size: 24px;
            margin: 0;
            color: #000;
        }
        .header .store-info p {
            margin: 2px 0;
        }
        .invoice-details {
            float: right;
            text-align: right;
        }
        .invoice-details p {
            margin: 2px 0;
        }
        .customer-info {
            margin-bottom: 20px;
        }
        .data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data th, .data td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .data th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .data .text-center {
            text-align: center;
        }
        .data .text-right {
            text-align: right;
        }
        .totals-table {
            width: 100%;
            margin-top: 20px;
        }
        .totals-table .label {
            text-align: right;
            font-weight: bold;
        }
        .totals-table .value {
            text-align: right;
            width: 150px;
        }
        .footer-table {
            width: 100%;
            margin-top: 40px;
            page-break-inside: avoid; /* Mencegah footer terpotong di halaman berbeda */
        }
        .footer-table .notes {
            width: 60%;
            font-size: 11px;
            vertical-align: top;
        }
        .footer-table .signature {
            width: 40%;
            text-align: center;
            vertical-align: top;
        }
        .footer-table .signature-space {
            height: 60px;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header clearfix">
            <img src="{{ public_path($setting->path_logo) }}" alt="Logo" class="logo">
            <div class="store-info">
                <h1>{{ $setting->nama_perusahaan }}</h1>
                <p>{{ $setting->alamat }}</p>
                <p>Telepon: {{ $setting->telepon }}</p>
            </div>
            <div class="invoice-details">
                <h2>INVOICE</h2>
                <p><strong>No. Nota:</strong> {{ $penjualan->id_penjualan }}</p>
                <p><strong>Tanggal:</strong> {{ tanggal_indonesia(date('Y-m-d')) }}</p>
            </div>
        </div>

        <div class="customer-info">
            <p><strong>Kepada Yth:</strong></p>
            <p>{{ $penjualan->member->nama ?? 'Pelanggan Umum' }}</p>
            <p>{{ $penjualan->member->alamat ?? '' }}</p>
        </div>

        <table class="data">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Nama Barang</th>
                    <th class="text-center">Kadar</th>
                    <th class="text-center">Berat (gr)</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-center">Jml</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail as $key => $item)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $item->produk->nama_produk }}</td>
                    <td class="text-center">{{ $item->kadar }}</td>
                    <td class="text-center">{{ format_uang($item->gram, 3) }}</td>
                    <td class="text-right">{{ format_uang($item->harga_jual) }}</td>
                    <td class="text-center">{{ format_uang($item->jumlah) }}</td>
                    <td class="text-right">{{ format_uang($item->subtotal) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- ================================================================== --}}
        {{-- PERBAIKAN: Menggunakan tabel untuk layout total agar tidak tumpang tindih --}}
        {{-- ================================================================== --}}
        <table class="totals-table">
            <tr>
                <td class="label">Total Harga :</td>
                <td class="value">Rp {{ format_uang($penjualan->total_harga) }}</td>
            </tr>
            <tr>
                <td class="label">Diskon ({{ $penjualan->diskon }}%) :</td>
                <td class="value">Rp {{ format_uang($penjualan->total_harga * $penjualan->diskon / 100) }}</td>
            </tr>
            <tr>
                <td class="label">Total Bayar :</td>
                <td class="value"><b>Rp {{ format_uang($penjualan->bayar) }}</b></td>
            </tr>
            <tr>
                <td class="label">Diterima :</td>
                <td class="value">Rp {{ format_uang($penjualan->diterima) }}</td>
            </tr>
            <tr>
                <td class="label">Kembali :</td>
                <td class="value">Rp {{ format_uang($penjualan->diterima - $penjualan->bayar) }}</td>
            </tr>
        </table>

        {{-- ================================================================== --}}
        {{-- PERBAIKAN: Menggunakan tabel untuk layout footer agar tidak tumpang tindih --}}
        {{-- ================================================================== --}}
        <table class="footer-table">
            <tr>
                <td class="notes">
                    <p><b>Syarat & Ketentuan:</b></p>
                    <p>1. Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan.</p>
                    <p>2. Nota ini adalah bukti sah pembelian.</p>
                </td>
                <td class="signature">
                    <p>Hormat Kami,</p>
                    <div class="signature-space"></div>
                    <p>({{ auth()->user()->name }})</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
