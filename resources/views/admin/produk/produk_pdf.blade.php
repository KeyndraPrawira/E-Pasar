<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Produk</title>
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
            <th>Kategori</th>
            <th>Harga</th>
            <th>Kios</th>
            <th>Gambar</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($produks as $p)
        <tr>
            <td>{{ $p->nama_produk }}</td>
            <td>{{ $p->kategori->nama_kategori ?? '-' }}</td>
            <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
            <td>{{ $p->kios->nama_kios ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
