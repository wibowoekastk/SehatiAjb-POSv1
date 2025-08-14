<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Cetak Kartu Member</title>
    <style>
        /* Mengatur ukuran halaman PDF menjadi seukuran kartu KTP/ATM */
        @page {
            size: 85.60mm 53.98mm;
            margin: 0;
        }
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
        }
        .card-container {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
            /* Latar belakang diatur langsung di sini */
            background-image: url('{{ public_path($setting->path_kartu_member) }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .logo-text {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 14px;
            font-weight: bold;
            color: #fff !important;
            text-align: right;
        }
        .nama {
            position: absolute;
            bottom: 50px;
            left: 15px;
            font-size: 14px;
            font-weight: bold;
            color: #fff !important; /* Warna diubah kembali ke putih */
            text-align: left;
        }
        .telepon {
            position: absolute;
            bottom: 30px;
            left: 15px;
            font-size: 11px;
            color: #fff !important; /* Warna diubah kembali ke putih */
        }
        .barcode {
            position: absolute;
            bottom: 15px;
            right: 15px;
            padding: 3px;
            background: #fff;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    {{-- Ambil data member pertama dari array --}}
    @php
        $item = $datamember[0][0];
    @endphp

    {{-- Strukturnya disederhanakan, tidak perlu lapisan terpisah --}}
    <div class="card-container">
        <div class="logo-text">
            <p>{{ $setting->nama_perusahaan }}</p>
        </div>

        <div class="nama">{{ $item->nama }}</div>
        <div class="telepon">{{ $item->telepon }}</div>

        <div class="barcode">
            <img src="data:image/png;base64, {{ DNS2D::getBarcodePNG((string) $item->kode_member, 'QRCODE') }}" alt="qrcode"
                height="50"
                width="50">
        </div>
    </div>
</body>
</html>
