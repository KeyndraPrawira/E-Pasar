<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAJAJAP - Pasar Digital Modern</title>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
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
            background: #ffffff;
            color: #1a1a1a;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* ===== HAMBURGER MENU (Tambahin setelah .nav-btn) ===== */
.hamburger {
    display: none;
    flex-direction: column;
    gap: 5px;
    cursor: pointer;
    z-index: 1001;
}

.hamburger span {
    width: 28px;
    height: 3px;
    background: #0ea5e9;
    border-radius: 3px;
    transition: all 0.3s;
}

.hamburger.active span:nth-child(1) {
    transform: rotate(45deg) translate(8px, 8px);
}

.hamburger.active span:nth-child(2) {
    opacity: 0;
}

.hamburger.active span:nth-child(3) {
    transform: rotate(-45deg) translate(7px, -7px);
}

/* Mobile Menu Overlay */
.mobile-menu {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background: rgba(0, 0, 0, 0.95);
    z-index: 999;
    padding-top: 100px;
}

.mobile-menu.active {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 30px;
}

.mobile-menu a {
    color: white;
    font-size: 1.5rem;
    text-decoration: none;
    font-weight: 600;
}

        /* ===== NAVBAR ===== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 20px 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.05);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 15px 0;
            box-shadow: 0 2px 30px rgba(0,0,0,0.1);
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

        .nav-menu {
            display: flex;
            gap: 35px;
            list-style: none;
            align-items: center;
        }

        .nav-menu a {
            color: #4a5568;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            font-size: 0.95rem;
        }

        .nav-menu a:hover {
            color: #0ea5e9;
        }

        .nav-btn {
            background: linear-gradient(135deg, #0ea5e9 0%, #10b981 100%);
            color: white;
            padding: 12px 28px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(14, 165, 233, 0.3);
        }

        /* ===== HERO SECTION ===== */
        .hero-section {
            background: linear-gradient(135deg, #0ea5e9 0%, #10b981 100%);
            padding: 140px 30px 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -200px;
            right: -200px;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            bottom: -150px;
            left: -150px;
        }

        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .hero-content {
            max-width: 700px;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            color: white;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 35px;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: white;
            color: #0ea5e9;
            padding: 16px 35px;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            padding: 16px 35px;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-3px);
        }

        /* ===== MARKET HERO IMAGE ===== */
        .market-hero {
            position: relative;
            width: 100%;
            height: 500px;
            margin-top: -60px;
            overflow: hidden;
        }

        .market-hero-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.8);
        }

        .market-hero-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0284c7 0%, #059669 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10rem;
            opacity: 0.3;
        }

        .market-hero-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 60px 30px;
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
        }

        .market-hero-title {
            max-width: 1200px;
            margin: 0 auto;
            color: white;
            font-size: 3.5rem;
            font-weight: 800;
            text-shadow: 2px 2px 20px rgba(0,0,0,0.5);
            margin-bottom: 300px;
        }

        /* ===== CONTAINER ===== */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 30px;
        }

        /* ===== INFO CARDS ===== */
        .info-section {
            margin-top: -100px;
            position: relative;
            z-index: 10;
            margin-bottom: 80px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .info-card {
            background: white;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            transition: all 0.3s;
            border: 1px solid #f0f0f0;
        }

        .info-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.12);
        }

        .info-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #0ea5e9 0%, #10b981 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        .info-title {
            font-size: 0.85rem;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .info-text {
            font-size: 1.2rem;
            color: #1a1a1a;
            font-weight: 600;
            line-height: 1.5;
        }

        /* ===== ABOUT CARD ===== */
        .about-card {
            background: linear-gradient(135deg, #f0f9ff 0%, #ecfdf5 100%);
            padding: 40px;
            border-radius: 20px;
            margin-top: 50px;
            margin-bottom: 50px;
            border-left: 5px solid #0ea5e9;
        }

        .about-card h3 {
            color: #0ea5e9;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .about-card p {
            color: #1a1a1a;
            font-size: 1.1rem;
            line-height: 1.8;
        }

        /* ===== MAP SECTION ===== */
        .map-section {
            margin-bottom: 100px;
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
            font-size: 2.8rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 15px;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: #718096;
            max-width: 600px;
            margin: 0 auto;
        }

        .map-container {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 50px rgba(0,0,0,0.1);
            height: 500px;
        }

        #map {
            height: 100%;
            width: 100%;
        }

        /* ===== FOOTER ===== */
        .footer {
            background: #1a1a1a;
            color: white;
            padding: 60px 30px 30px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-section h3 {
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: white;
        }

        .footer-section p, .footer-section a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            line-height: 2;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: #0ea5e9;
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== RESPONSIVE ===== */
        /* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .hero-title {
        font-size: 3rem;
    }

    .market-hero-title {
        font-size: 2.5rem;
    }

    .section-title {
        font-size: 2.2rem;
    }
}

@media (max-width: 768px) {
    .hamburger {
        display: flex;
    }

    .nav-menu {
        display: none;
    }

    .nav-container {
        padding: 0 20px;
    }

    .logo {
        font-size: 1.4rem;
    }

    .logo-icon {
        width: 38px;
        height: 38px;
        font-size: 1.2rem;
    }

    .hero-section {
        padding: 120px 20px 60px;
    }

    .hero-title {
        font-size: 2.2rem;
    }

    .hero-subtitle {
        font-size: 1rem;
    }

    .hero-buttons {
        flex-direction: column;
        width: 100%;
    }

    .btn-primary, .btn-secondary {
        width: 100%;
        justify-content: center;
    }

    .market-hero {
        height: 350px;
        margin-top: -40px;
    }

    .market-hero-title {
        font-size: 1.8rem;
        padding: 0 20px;
    }

    .market-hero-overlay {
        padding: 40px 0;
    }

    .info-section {
        margin-top: -80px;
    }

    .info-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .info-card {
        padding: 25px;
    }

    .about-card {
        padding: 25px;
    }

    .section-title {
        font-size: 1.8rem;
    }

    .section-subtitle {
        font-size: 0.95rem;
    }

    .map-container {
        height: 350px;
    }

    .footer-content {
        grid-template-columns: 1fr;
        gap: 30px;
    }

    .container {
        padding: 0 20px;
    }
}

@media (max-width: 480px) {
    .hero-title {
        font-size: 1.8rem;
    }

    .hero-subtitle {
        font-size: 0.9rem;
    }

    .market-hero {
        height: 280px;
    }

    .market-hero-title {
        font-size: 1.4rem;
    }

    .info-icon {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }

    .info-text {
        font-size: 1rem;
    }

    .section-title {
        font-size: 1.5rem;
    }

}
    </style>
</head>
<body>

    <!-- Navbar -->
    <!-- Navbar -->
<nav class="navbar">
    <div class="nav-container">
        <a href="#" class="logo">
            <div class="logo-icon">
                <img src="{{ asset('template/images/logos/pajajap_logo-white.png') }}" alt="logo-pajajap" width=30px>
            </div>
            <span>PAJAJAP</span>
        </a>
        
        <!-- Desktop Menu -->
        <ul class="nav-menu">
            <li><a href="#pasar">Pasar</a></li>
            <li><a href="#lokasi">Lokasi</a></li>
            <li><a href="{{ auth()->check() ? route('driver.application.create') : route('driver.login') }}" class="nav-btn">Daftar Driver</a></li>
        </ul>

        <!-- Hamburger -->
        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <a href="#pasar" onclick="toggleMenu()">Pasar</a>
        <a href="#lokasi" onclick="toggleMenu()">Lokasi</a>
        <a href="{{ auth()->check() ? route('driver.application.create') : route('driver.login') }}" onclick="toggleMenu()">Daftar Driver</a>
    </div>
</nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">✨ Solusi Belanja Modern</div>
                <h1 class="hero-title">Belanja pasar jadi lebih mudah</h1>
                <p class="hero-subtitle">Nikmati pengalaman belanja dari pasar tradisional langsung ke rumah Anda dengan layanan yang cepat, rapi, dan terpercaya.</p>
                <div class="hero-buttons">
                    <a href="{{ auth()->check() ? route('driver.application.create') : route('driver.login') }}" class="btn-primary">
                        <i class="ti ti-truck-delivery"></i>
                        Daftar Menjadi Driver
                    </a>
                    <a href="#lokasi" class="btn-secondary">
                        <i class="ti ti-map-pin"></i>
                        Lihat Lokasi
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Market Hero Image -->
    @if($pasar)
    <section class="market-hero" id="pasar">
        @if($pasar->foto_pasar)
            <img src="{{ asset('storage/' . $pasar->foto_pasar) }}" alt="{{ $pasar->nama_pasar }}" class="market-hero-img">
        @else
            <div class="market-hero-placeholder">🏪</div>
        @endif
        <div class="market-hero-overlay">
            <h2 class="market-hero-title">{{ $pasar->nama_pasar }}</h2>
        </div>
    </section>

    <!-- Info Cards -->
    <section class="info-section">
        <div class="container">
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="ti ti-map-pin"></i>
                    </div>
                    <div class="info-title">Alamat Pasar</div>
                    <div class="info-text">{{ $pasar->alamat }}</div>
                </div>

                <div class="info-card">
                    <div class="info-icon">
                        <i class="ti ti-phone"></i>
                    </div>
                    <div class="info-title">Hubungi Kami</div>
                    <div class="info-text">{{ $pasar->kontak ?? 'Belum Tersedia' }}</div>
                </div>

                <div class="info-card">
                    <div class="info-icon">
                        <i class="ti ti-truck-delivery"></i>
                    </div>
                    <div class="info-title">Biaya Pengiriman</div>
                    <div class="info-text">Rp {{ number_format($pasar->ongkir, 0, ',', '.') }}</div>
                </div>
            </div>

            @if($pasar->deskripsi)
            <div class="about-card">
                <h3>Tentang Pasar Kami</h3>
                <p>{{ $pasar->deskripsi }}</p>
            </div>
            @endif
        </div>
    </section>

    <!-- Map Section -->
    @if($pasar->latitude && $pasar->longitude)
    <section class="map-section" id="lokasi">
        <div class="container">
            <div class="section-header">
                <div class="section-label">LOKASI</div>
                <h2 class="section-title">Temukan Kami</h2>
                <p class="section-subtitle">Pesan online untuk pengiriman ke rumah</p>
            </div>
            <div class="map-container">
                <div id="map"></div>
            </div>
        </div>
    </section>
    @endif

    
    @endif

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>PAJAJAP</h3>
                <p>Solusi belanja modern untuk pasar tradisional yang lebih cepat, praktis, dan terpercaya.</p>
            </div>
            <div class="footer-section">
                <h3>Menu</h3>
                <p><a href="#pasar">Tentang Pasar</a></p>
                <p><a href="#lokasi">Lokasi</a></p>
                <p><a href="{{ auth()->check() ? route('driver.application.create') : route('driver.login') }}">Daftar Driver</a></p>
            </div>
            <div class="footer-section">
                <h3>Kontak</h3>
                <p>{{ $pasar->kontak ?? 'Belum Tersedia' }}</p>
                <p>{{ $pasar->alamat ?? '' }}</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 PAJAJAP. All Rights Reserved. Made with 💚 for Traditional Markets</p>
        </div>
    </footer>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Navbar Scroll Effect
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Initialize Map
        @if($pasar && $pasar->latitude && $pasar->longitude)
        let map = L.map('map', {
            center: [{{ $pasar->latitude }}, {{ $pasar->longitude }}],
            zoom: 15,
            zoomControl: true,
            scrollWheelZoom: true,
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        // Custom marker
        let customIcon = L.divIcon({
            html: '<div style="background: linear-gradient(135deg, #0ea5e9 0%, #10b981 100%); width: 40px; height: 40px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); display: flex; align-items: center; justify-content: center; box-shadow: 0 5px 15px rgba(0,0,0,0.3);"><i class="ti ti-map-pin" style="color: white; font-size: 22px; transform: rotate(45deg);"></i></div>',
            className: 'custom-marker',
            iconSize: [40, 40],
            iconAnchor: [20, 40],
        });

        let marker = L.marker([{{ $pasar->latitude }}, {{ $pasar->longitude }}], {
            icon: customIcon
        }).addTo(map);

        marker.bindPopup(`
            <div style="text-align: center; padding: 15px;">
                <h4 style="margin: 0 0 8px 0; color: #0ea5e9; font-size: 1.1rem;">📍 {{ $pasar->nama_pasar }}</h4>
                <p style="margin: 0; font-size: 0.9rem; color: #4a5568;">{{ $pasar->alamat }}</p>
            </div>
        `).openPopup();
        @endif

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
    <script>
    // Hamburger Menu Toggle
const hamburger = document.getElementById('hamburger');
const mobileMenu = document.getElementById('mobileMenu');

function toggleMenu() {
    hamburger.classList.toggle('active');
    mobileMenu.classList.toggle('active');
    document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : 'auto';
}

hamburger.addEventListener('click', toggleMenu);
    </script>

</body>
</html>
