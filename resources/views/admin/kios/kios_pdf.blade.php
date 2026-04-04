<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Kios {{ $pasar->nama_pasar }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            padding: 30px 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 16px;
        }

        .header h2 {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 10px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 10px;
        }

        col.col-no      { width: 5%; }
        col.col-nama    { width: 25%; }
        col.col-jam     { width: 20%; }
        col.col-produk  { width: 50%; }

        th {
            background-color: #ddd;
            color: #000;
            font-weight: bold;
            font-size: 11px;
            padding: 6px 8px;
            text-align: center;
            border: 1px solid #999;
        }

        td {
            border: 1px solid #999;
            padding: 5px 8px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        td.col-center {
            text-align: center;
        }

        td.produk-list {
            padding: 0;
        }

        .produk-item {
            padding: 5px 8px;
            border-bottom: 1px dotted #ccc;
        }

        .produk-item:last-child {
            border-bottom: none;
        }

        .footer {
            margin-top: 16px;
            font-size: 10px;
            color: #444;
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>Data Kios Pasar {{ $pasar->nama_pasar }}</h2>
    <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
</div>

<table>
    <colgroup>
        <col class="col-no">
        <col class="col-nama">
        <col class="col-jam">
        <col class="col-produk">
    </colgroup>
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama Kios</th>
            <th>Jam Operasional</th>
            <th>Produk</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($kios as $index => $k)
        <tr>
            <td class="col-center">{{ $index + 1 }}</td>
            <td>{{ $k->nama_kios }}</td>
            <td class="col-center">
                @if ($k->jam_buka && $k->jam_tutup)
                    {{ \Carbon\Carbon::parse($k->jam_buka)->format('H:i') }} – {{ \Carbon\Carbon::parse($k->jam_tutup)->format('H:i') }}
                @else
                    –
                @endif
            </td>
            <td class="produk-list">
                @forelse($k->produk as $p)
                    <div class="produk-item">{{ $p->nama_produk ?? '-' }}</div>
                @empty
                    <div class="produk-item">–</div>
                @endforelse
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center">Tidak ada data produk</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    Total Kios: {{ count($kios) }}
</div>

</body>
</html>