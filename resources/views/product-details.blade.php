<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->nama_produk }} - PAJAJAP</title>
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f8fafc;
            color: #1a1a1a;
            line-height: 1.6;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 20px 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            z-index: 1000;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.8rem;
            font-weight: 800;
            color: #0ea5e9;
            text-decoration: none;
        }

        .logo-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #0ea5e9 0%, #10b981 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .back-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #4a5568;
            text-decoration: none;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 25px;
            transition: all 0.3s;
        }

        .back-btn:hover {
            background: #f0f0f0;
            color: #0ea5e9;
        }

        /* ===== CONTAINER ===== */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 30px;
        }

        /* ===== PRODUCT DETAIL SECTION ===== */
        .product-detail-section {
            padding: 120px 0 60px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
            font-size: 0.9rem;
            color: #718096;
        }

        .breadcrumb a {
            color: #0ea5e9;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .product-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin-bottom: 80px;
        }

        /* ===== PRODUCT IMAGE ===== */
        .product-image-section {
            position: sticky;
            top: 120px;
            height: fit-content;
        }

        .main-image-wrapper {
            position: relative;
            width: 100%;
            height: 500px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .main-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #e0f2fe 0%, #d1fae5 100%);
            color: #0ea5e9;
            font-size: 8rem;
        }

        .category-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: linear-gradient(135deg, #0ea5e9 0%, #10b981 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stock-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: white;
            color: #10b981;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        /* ===== PRODUCT INFO ===== */
        .product-info-section {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }

        .product-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .product-price-wrapper {
            display: flex;
            align-items: baseline;
            gap: 15px;
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 2px solid #f0f0f0;
        }

        .product-price {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #0ea5e9 0%, #10b981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .product-unit {
            font-size: 1.2rem;
            color: #718096;
            font-weight: 600;
        }

        .product-meta {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 12px;
        }

        .meta-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #0ea5e9 0%, #10b981 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
        }

        .meta-content h6 {
            font-size: 0.75rem;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .meta-content p {
            font-size: 1.1rem;
            color: #1a1a1a;
            font-weight: 600;
        }

        .product-description {
            margin-bottom: 30px;
        }

        .product-description h3 {
            font-size: 1.2rem;
            color: #1a1a1a;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .product-description p {
            font-size: 1rem;
            color: #4a5568;
            line-height: 1.8;
        }

        /* ===== QUANTITY SELECTOR ===== */
        .quantity-section {
            margin-bottom: 30px;
        }

        .quantity-label {
            font-size: 0.9rem;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 0;
            background: #f8fafc;
            border-radius: 12px;
            overflow: hidden;
        }

        .qty-btn {
            width: 50px;
            height: 50px;
            border: none;
            background: transparent;
            color: #0ea5e9;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }

        .qty-btn:hover {
            background: #e0f2fe;
        }

        .qty-input {
            width: 70px;
            height: 50px;
            border: none;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 700;
            color: #1a1a1a;
            background: transparent;
        }

        .qty-input:focus {
            outline: none;
        }

        /* ===== ACTION BUTTONS ===== */
        .action-buttons {
            display: flex;
            gap: 15px;
        }

        .btn-add-cart {
            flex: 1;
            background: linear-gradient(135deg, #0ea5e9 0%, #10b981 100%);
            color: white;
            padding: 18px 35px;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-add-cart:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(14, 165, 233, 0.3);
        }

        .btn-wishlist {
            width: 60px;
            height: 60px;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            color: #0ea5e9;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-wishlist:hover {
            background: #0ea5e9;
            color: white;
            border-color: #0ea5e9;
        }

        /* ===== SELLER INFO ===== */
        .seller-info {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #f0f0f0;
        }

        .seller-card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
        }

        .seller-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #0ea5e9 0%, #10b981 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .seller-content h6 {
            font-size: 0.75rem;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .seller-content p {
            font-size: 1.1rem;
            color: #1a1a1a;
            font-weight: 600;
        }

        /* ===== RELATED PRODUCTS ===== */
        .related-section {
            padding: 60px 0 100px;
        }

        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-label {
            color: #0ea5e9;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1a1a1a;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }

        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 1px solid #f0f0f0;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 45px rgba(0,0,0,0.15);
        }

        .product-image-wrapper {
            position: relative;
            width: 100%;
            height: 220px;
            overflow: hidden;
            background: linear-gradient(135deg, #e0f2fe 0%, #d1fae5 100%);
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0ea5e9;
            font-size: 4rem;
        }

        .product-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(135deg, #0ea5e9 0%, #10b981 100%);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .product-content {
            padding: 20px;
        }

        .product-name {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 10px;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 2px solid #f0f0f0;
        }

        .product-price-small {
            font-size: 1.3rem;
            font-weight: 800;
            background: linear-gradient(135deg, #0ea5e9 0%, #10b981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .product-stock {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.85rem;
            color: #10b981;
            font-weight: 600;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .product-grid {
                gap: 40px;
            }
        }

        @media (max-width: 768px) {
            .nav-container {
                padding: 0 20px;
            }

            .logo {
                font-size: 1.4rem;
            }

            .logo-icon {
                width: 38px;
                height: 38px;
            }

            .product-detail-section {
                padding: 100px 0 40px;
            }

            .product-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .product-image-section {
                position: relative;
                top: 0;
            }

            .main-image-wrapper {
                height: 350px;
            }

            .product-info-section {
                padding: 25px;
            }

            .product-title {
                font-size: 1.8rem;
            }

            .product-price {
                font-size: 2rem;
            }

            .product-meta {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-wishlist {
                width: 100%;
                height: 60px;
            }

            .section-title {
                font-size: 2rem;
            }

            .products-grid {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 0 20px;
            }
        }

        @media (max-width: 480px) {
            .main-image-wrapper {
                height: 280px;
            }

            .product-title {
                font-size: 1.5rem;
            }

            .product-price {
                font-size: 1.8rem;
            }

            .quantity-selector {
                flex-direction: column;
                align-items: stretch;
            }

            .quantity-controls {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('landingpage') }}" class="logo">
                <div class="logo-icon">🛒</div>
                <span>PAJAJAP</span>
            </a>
            <a href="{{ route('landingpage') }}" class="back-btn">
                <i class="ti ti-arrow-left"></i>
                Kembali
            </a>
        </div>
    </nav>

    <!-- Product Detail Section -->
    <section class="product-detail-section">
        <div class="container">
            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="{{ route('landingpage') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('landingpage') }}#produk">Produk</a>
                <span>/</span>
                <span>{{ $product->kategori->nama_kategori ?? 'Produk' }}</span>
                <span>/</span>
                <span>{{ $product->nama_produk }}</span>
            </div>

            <!-- Product Grid -->
            <div class="product-grid">
                <!-- Product Image -->
                <div class="product-image-section">
                    <div class="main-image-wrapper">
                        @if($product->foto)
                            <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama_produk }}" class="main-image">
                        @else
                            <div class="image-placeholder">
                                <i class="ti ti-shopping-bag"></i>
                            </div>
                        @endif
                        
                        <span class="category-badge">{{ $product->kategori->nama_kategori ?? 'Umum' }}</span>
                        <span class="stock-badge">
                            <i class="ti ti-package"></i>
                            Stok: {{ $product->stok }}
                        </span>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="product-info-section">
                    <h1 class="product-title">{{ $product->nama_produk }}</h1>
                    
                    <div class="product-price-wrapper">
                        <div class="product-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                        @if($product->berat_satuan)
                        <div class="product-unit">/ {{ $product->berat_satuan }}</div>
                        @endif
                    </div>

                    <!-- Meta Info -->
                    <div class="product-meta">
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="ti ti-category"></i>
                            </div>
                            <div class="meta-content">
                                <h6>Kategori</h6>
                                <p>{{ $product->kategori->nama_kategori ?? 'Umum' }}</p>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="ti ti-package"></i>
                            </div>
                            <div class="meta-content">
                                <h6>Ketersediaan</h6>
                                <p>{{ $product->stok > 0 ? 'Tersedia' : 'Habis' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="product-description">
                        <h3>Deskripsi Produk</h3>
                        <p>{{ $product->deskripsi ?? 'Produk segar berkualitas tinggi dengan harga terjangkau. Langsung dari pedagang terpercaya di pasar tradisional kami.' }}</p>
                    </div>

                    <!-- Quantity Selector -->
                    <div class="quantity-section">
                        <div class="quantity-label">Jumlah</div>
                        <div class="quantity-selector">
                            <div class="quantity-controls">
                                <button class="qty-btn" onclick="decreaseQty()">−</button>
                                <input type="number" id="quantity" class="qty-input" value="1" min="1" max="{{ $product->stok }}" readonly>
                                <button class="qty-btn" onclick="increaseQty()">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button class="btn-add-cart" onclick="addToCart()">
                            <i class="ti ti-shopping-cart-plus"></i>
                            Tambah ke Keranjang
                        </button>
                        <button class="btn-wishlist">
                            <i class="ti ti-heart"></i>
                        </button>
                    </div>

                    <!-- Seller Info -->
                    @if($product->kios)
                    <div class="seller-info">
                        <div class="seller-card">
                            <div class="seller-icon">
                                <i class="ti ti-store"></i>
                            </div>
                            <div class="seller-content">
                                <h6>Penjual</h6>
                                <p>{{ $product->kios->nama_kios ?? 'Kios Pasar' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
    <section class="related-section">
        <div class="container">
            <div class="section-header">
                <div class="section-label">REKOMENDASI</div>
                <h2 class="section-title">Produk Serupa</h2>
            </div>

            <div class="products-grid">
                @foreach($relatedProducts as $related)
                <a href="{{ route('detail-produk', $related->id) }}" style="text-decoration: none;">
                    <div class="product-card">
                        <div class="product-image-wrapper">
                            @if($related->foto)
                                <img src="{{ asset('storage/' . $related->foto) }}" alt="{{ $related->nama_produk }}" class="product-image">
                            @else
                                <div class="product-image-placeholder">
                                    <i class="ti ti-shopping-bag"></i>
                                </div>
                            @endif
                            <span class="product-badge">{{ $related->kategori->nama_kategori ?? 'Umum' }}</span>
                        </div>
                        
                        <div class="product-content">
                            <h3 class="product-name">{{ $related->nama_produk }}</h3>
                            <div class="product-footer">
                                <div class="product-price-small">
                                    Rp {{ number_format($related->harga, 0, ',', '.') }}
                                </div>
                                <div class="product-stock">
                                    <i class="ti ti-package"></i>
                                    <span>{{ $related->stok }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <script>
        const maxStock = {{ $product->stok }};
        const qtyInput = document.getElementById('quantity');

        function increaseQty() {
            let currentQty = parseInt(qtyInput.value);
            if (currentQty < maxStock) {
                qtyInput.value = currentQty + 1;
            }
        }

        function decreaseQty() {
            let currentQty = parseInt(qtyInput.value);
            if (currentQty > 1) {
                qtyInput.value = currentQty - 1;
            }
        }

        function addToCart() {
            const qty = parseInt(qtyInput.value);
            alert(`Menambahkan ${qty} {{ $product->nama_produk }} ke keranjang!`);
            // Implementasi AJAX untuk add to cart
        }
    </script>

</body>
</html>