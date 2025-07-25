<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pendapatan</title>

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
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header img {
            width: 100px;
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
        }
        .header h3 {
            margin: 5px 0;
            font-size: 16px;
            font-weight: normal;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th, .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .data-table thead th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .data-table td.text-center {
            text-align: center;
        }
        .data-table td.text-right {
            text-align: right;
        }
        .data-table tfoot td {
            font-weight: bold;
            background-color: #f2f2f2;
        }
        .data-table tfoot .label-total {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('img/logo-20250701080805.png') }}" alt="Logo Toko">
            <h2>Laporan Pendapatan</h2>
            <h3>Periode: {{ tanggal_indonesia($awal, false) }} s/d {{ tanggal_indonesia($akhir, false) }}</h3>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Tanggal</th>
                    <th>Penjualan</th>
                    <th>Pembelian</th>
                    <th>Pengeluaran</th>
                    <th>Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $total_penjualan = 0;
                    $total_pembelian = 0;
                    $total_pengeluaran = 0;
                    $total_pendapatan = 0;
                @endphp
                @foreach ($data as $row)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ tanggal_indonesia($row['tanggal'], false) }}</td>
                    <td class="text-right">{{ format_uang($row['penjualan']) }}</td>
                    <td class="text-right">{{ format_uang($row['pembelian']) }}</td>
                    <td class="text-right">{{ format_uang($row['pengeluaran']) }}</td>
                    <td class="text-right">{{ format_uang($row['pendapatan']) }}</td>
                </tr>
                @php
                    // Menjumlahkan data mentah untuk total
                    $total_penjualan += $row['penjualan'];
                    $total_pembelian += $row['pembelian'];
                    $total_pengeluaran += $row['pengeluaran'];
                    $total_pendapatan += $row['pendapatan'];
                @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="label-total">Total</td>
                    <td class="text-right">{{ format_uang($total_penjualan) }}</td>
                    <td class="text-right">{{ format_uang($total_pembelian) }}</td>
                    <td class="text-right">{{ format_uang($total_pengeluaran) }}</td>
                    <td class="text-right">{{ format_uang($total_pendapatan) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
