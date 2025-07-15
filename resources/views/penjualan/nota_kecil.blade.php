<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota Kecil</title>

    <style>
        /* ================================================================== */
        /* PERUBAHAN: Menambahkan @page rule untuk mengatur ukuran cetak */
        /* ================================================================== */
        @page {
            size: 58mm auto; /* Lebar 80mm, tinggi otomatis sesuai konten */
            margin: 0;
        }
        /* ================================================================== */
        /* AKHIR PERUBAHAN */
        /* ================================================================== */

        * {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
        }
        body {
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .receipt-container {
            width: 300px; /* Lebar untuk tampilan di layar, mendekati 80mm */
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .header p {
            margin: 2px 0;
        }
        .separator {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .transaction-info table,
        .totals table {
            width: 100%;
        }
        .transaction-info td:nth-child(2),
        .totals td:nth-child(2) {
            text-align: right;
        }
        .items table {
            width: 100%;
            border-collapse: collapse;
        }
        .items th, .items td {
            padding: 2px 0;
        }
        .item-name {
            font-weight: bold;
        }
        .item-details {
            padding-left: 10px; /* Sedikit menjorok ke dalam */
        }
        .item-details td:last-child {
            text-align: right;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
        }
        .print-button-container {
            text-align: center;
            margin-top: 20px;
        }
        .print-button {
            padding: 10px 20px;
            border: none;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }

        @media print {
            body {
                background: none;
                padding: 0;
                margin: 0; /* Memastikan tidak ada margin dari body */
            }
            .receipt-container {
                box-shadow: none;
                width: 100%; /* Lebar penuh sesuai @page size */
                padding: 0;
                margin: 0;
            }
            .print-button-container {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <h2>{{ $setting->nama_perusahaan }}</h2>
            <p>{{ $setting->alamat }}</p>
            <p>Telp: {{ $setting->telepon }}</p>
        </div>

        <div class="separator"></div>

        <div class="transaction-info">
            <table>
                <tr>
                    <td>No Transaksi</td>
                    <td>{{ $penjualan->id_penjualan }}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>{{ tanggal_indonesia($penjualan->created_at, false) }}</td>
                </tr>
                <tr>
                    <td>Kasir</td>
                    <td>{{ $penjualan->user->name }}</td>
                </tr>
                @if ($penjualan->member)
                <tr>
                    <td>Member</td>
                    <td>{{ $penjualan->member->nama }}</td>
                </tr>
                @endif
            </table>
        </div>

        <div class="separator"></div>

        <div class="items">
            <table>
                @foreach ($detail as $item)
                    <tr class="item-name">
                        <td colspan="2">{{ $item->produk->nama_produk }}</td>
                    </tr>
                    <tr class="item-details">
                        <td colspan="2">
                            (Kadar: {{ $item->kadar }}, Gram: {{ $item->gram }} gr)
                        </td>
                    </tr>
                    <tr class="item-details">
                        <td>{{ $item->jumlah }} x {{ format_uang($item->harga_jual) }}</td>
                        <td style="text-align: right;">{{ format_uang($item->subtotal) }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="separator"></div>

        <div class="totals">
            <table>
                <tr>
                    <td>Total Harga:</td>
                    <td>{{ format_uang($penjualan->total_harga) }}</td>
                </tr>
                <tr>
                    <td>Diskon:</td>
                    <td>{{ $penjualan->diskon }}%</td>
                </tr>
                <tr>
                    <td>Total Bayar:</td>
                    <td>{{ format_uang($penjualan->bayar) }}</td>
                </tr>
                <tr>
                    <td>Diterima:</td>
                    <td>{{ format_uang($penjualan->diterima) }}</td>
                </tr>
                <tr>
                    <td>Kembali:</td>
                    <td>{{ format_uang($penjualan->diterima - $penjualan->bayar) }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>-- Terima Kasih --</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
        </div>
    </div>

    <div class="print-button-container">
        <button class="print-button" onclick="window.print()">Cetak Nota</button>
    </div>
</body>
</html>
