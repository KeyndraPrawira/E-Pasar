<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Kios {{ $pasar->nama_pasar }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background: #eee;
        }
        img {
            width: 60px;
        }
    </style>
</head>
<body>

<h3>Data Produk</h3>

<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>Jam operasional</th>
            <th>Produk</th>
     
            
        </tr>
    </thead>
    <tbody>
        @forelse ($kios as $k)
        <tr>
            <td rowspan="{{ count($k->produk) }}">{{ $k->nama_kios }}</td>
            <td>
                @if ($k->jam_buka && $k->jam_tutup)
                    {{ \Carbon\Carbon::parse($k->jam_buka)->format('H:i') }} - {{ \Carbon\Carbon::parse($k->jam_tutup)->format('H:i') }}
                @else
                    -
                @endif

            </td>
            <td>
                @forelse($k->produk as $p)
                    <tr>
                        <td>{{ $p->nama_produk ?? '-' }}</td>
                    </tr>
                @empty
                    -
                @endforelse
            </td>
            
           
        </tr>
        @empty
        <tr>
            <td colspan="4" style="text-align: center;">Tidak ada data produk</td>
        </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
