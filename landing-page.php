<?php
// Landing page - tidak perlu session/login
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>SPK SAW - Sistem Pendukung Keputusan Pemilihan Pemain Terbaik</title>
<link rel="stylesheet" href="assets/css/landing-page.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<!-- NAVIGATION -->
<nav class="navbar">
  <div class="nav-container">
    <div class="logo">
      <span class="logo-icon">⚽</span>
      <span class="logo-text">SPK<span class="logo-highlight">SAW</span></span>
    </div>
    <div class="nav-links">
      <a href="#home" class="nav-link">Beranda</a>
      <a href="#features" class="nav-link">Fitur</a>
      <a href="#about" class="nav-link">Tentang</a>
      <a href="login.php" class="btn-login">Login</a>
    </div>
    <div class="menu-toggle" id="menuToggle">
      <span></span><span></span><span></span>
    </div>
  </div>
</nav>

<!-- HERO SECTION -->
<section id="home" class="hero">
  <div class="hero-container">
    <div class="hero-content">
      <div class="hero-badge">⭐ Sistem Pendukung Keputusan</div>
      <h1 class="hero-title">
        Metode <span class="gradient-text">SAW</span><br>
        Untuk Pemilihan Pemain Terbaik
      </h1>
      <p class="hero-desc">
        Sistem pendukung keputusan berbasis web yang menerapkan metode Simple Additive Weighting (SAW)
        untuk membantu tim manajemen dalam memilih pemain terbaik berdasarkan berbagai kriteria objektif.
      </p>
      <div class="hero-buttons">
        <a href="login.php" class="btn btn-primary">🚀 Mulai Sekarang</a>
        <a href="#features" class="btn btn-outline">📖 Pelajari Lebih</a>
      </div>
    </div>
    <div class="hero-image">
      <div class="floating-card card-1">🏆 Pemain Terbaik</div>
      <div class="floating-card card-2">📊 Perhitungan SAW</div>
      <div class="floating-card card-3">⚡ Akurat & Cepat</div>
      <div class="hero-illustration">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
        <div class="circle circle-3"></div>
        <div class="trophy-icon">🏆</div>
      </div>
    </div>
  </div>
</section>

<!-- STATISTICS -->
<section class="stats-section">
  <div class="stats-container">
    <div class="stat-item">
      <div class="stat-number" id="statPemain">0</div>
      <div class="stat-label">Total Pemain</div>
    </div>
    <div class="stat-item">
      <div class="stat-number" id="statKriteria">0</div>
      <div class="stat-label">Kriteria Penilaian</div>
    </div>
    <div class="stat-item">
      <div class="stat-number" id="statPenilaian">0</div>
      <div class="stat-label">Penilaian Terealisasi</div>
    </div>
    <div class="stat-item">
      <div class="stat-number" id="statMetode">SAW</div>
      <div class="stat-label">Metode Terbaik</div>
    </div>
  </div>
</section>

<!-- FEATURES SECTION -->
<section id="features" class="features">
  <div class="section-container">
    <div class="section-header">
      <span class="section-tag">Fitur Unggulan</span>
      <h2 class="section-title">Mengapa Memilih <span class="gradient-text">SPK SAW</span>?</h2>
      <p class="section-desc">
        Sistem kami menyediakan berbagai fitur canggih untuk mendukung proses pengambilan keputusan
        yang lebih objektif dan transparan.
      </p>
    </div>
    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon">📋</div>
        <h3>Manajemen Kriteria</h3>
        <p>Kelola bobot dan kriteria penilaian dengan mudah. Setiap kriteria dapat diaktifkan/nonaktifkan sesuai kebutuhan.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">👥</div>
        <h3>Data Pemain</h3>
        <p>Input data pemain lengkap termasuk nama, posisi, dan tim asal untuk database yang terstruktur.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">📝</div>
        <h3>Input Penilaian</h3>
        <p>Antarmuka intuitif untuk memasukkan nilai setiap pemain berdasarkan kriteria yang telah ditentukan.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">⚙️</div>
        <h3>Perhitungan SAW</h3>
        <p>Proses normalisasi matriks dan perankingan otomatis menggunakan metode Simple Additive Weighting.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">📊</div>
        <h3>Visualisasi Data</h3>
        <p>Grafik batang interaktif untuk melihat perbandingan nilai preferensi setiap pemain secara visual.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">🏆</div>
        <h3>Top Player</h3>
        <p>Tampilkan 3 pemain terbaik berdasarkan hasil perankingan SAW dengan nilai preferensi tertinggi.</p>
      </div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="how-it-works">
  <div class="section-container">
    <div class="section-header">
      <span class="section-tag">Cara Kerja</span>
      <h2 class="section-title">Proses Metode <span class="gradient-text">SAW</span></h2>
    </div>
    <div class="steps-container">
      <div class="step">
        <div class="step-number">1</div>
        <div class="step-content">
          <h3>Menentukan Kriteria & Bobot</h3>
          <p>Tentukan kriteria penilaian beserta bobot kepentingan masing-masing kriteria (∑ bobot = 1).</p>
        </div>
      </div>
      <div class="step">
        <div class="step-number">2</div>
        <div class="step-content">
          <h3>Normalisasi Matriks</h3>
          <p>Menormalisasi nilai setiap alternatif terhadap setiap kriteria berdasarkan jenis benefit/cost.</p>
        </div>
      </div>
      <div class="step">
        <div class="step-number">3</div>
        <div class="step-content">
          <h3>Perhitungan Preferensi</h3>
          <p>Menghitung nilai preferensi (Vi) dengan mengalikan matriks ternormalisasi dengan bobot kriteria.</p>
        </div>
      </div>
      <div class="step">
        <div class="step-number">4</div>
        <div class="step-content">
          <h3>Perankingan</h3>
          <p>Mengurutkan nilai preferensi dari terbesar ke terkecil untuk menentukan pemain terbaik.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TEAM / ABOUT SECTION -->
<section id="about" class="about">
  <div class="section-container">
    <div class="about-grid">
      <div class="about-content">
        <span class="section-tag">Tentang Kami</span>
        <h2 class="section-title">Dibangun Untuk <span class="gradient-text">Keputusan Terbaik</span></h2>
        <p>
          Sistem Pendukung Keputusan (SPK) berbasis web ini menggunakan metode Simple Additive Weighting (SAW)
          untuk membantu tim manajemen dalam mengevaluasi dan memilih pemain terbaik secara objektif.
        </p>
        <p>
          Dengan antarmuka yang intuitif dan proses perhitungan yang transparan, sistem ini memudahkan
          proses seleksi pemain berdasarkan kriteria-kriteria yang telah ditentukan.
        </p>
       <div class="developer-info">
  <div class="developer-info">
  <h3>👨‍💻 Pengembang</h3>
  
  <!-- Coba 4 cara berbeda, salah satu pasti muncul -->
  
  <!-- Cara 1: Langsung dari URL -->
  <img src="https://upgris.ac.id/upload/logo_upgris.png" alt="" style="width: 80px; margin: 10px auto; display: block;">
  
  <!-- Cara 2: Relative path dari file ini -->
  <img src="../assets/images/logo.png" alt="Logo" style="width: 80px; margin: 10px auto; display: block;" onerror="this.style.display='none'">
  
  <!-- Cara 3: Pakai base_url jika ada -->
  <img src="<?= isset($base_url) ? $base_url : '' ?>assets/images/logo.png" alt="Logo" style="width: 80px; margin: 10px auto; display: block;">
  
  <div class="dev-details">
    <p><strong>Nama:</strong> AFFAN AFFANDI</p>
    <p><strong>NPM:</strong> 24670067</p>
    <p><strong>Program Studi:</strong> INFORMATIKA</p>
    <p><strong>Universitas:</strong> UNIVERSITAS PGRI SEMARANG</p>
  </div>
</div>
      <div class="about-illustration">
        <div class="code-card">
          <div class="code-header">
            <span class="dot red"></span>
            <span class="dot yellow"></span>
            <span class="dot green"></span>
          </div>
          <div class="code-body">
            <pre><code>
$normalized = [];
foreach($matrix as $alt) {
  $pref = 0;
  foreach($alt as $k=>$v) {
    $norm = $v / $max[$k];
    $pref += $norm * $bobot[$k];
  }
  $results[] = $pref;
}
            </code></pre>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CALL TO ACTION -->
<section class="cta">
  <div class="cta-container">
    <h2>Siap Memilih Pemain Terbaik?</h2>
    <p>Mulai gunakan sistem SPK SAW sekarang dan dapatkan keputusan yang lebih objektif.</p>
    <a href="login.php" class="btn btn-primary btn-large">🔐 Akses Dashboard →</a>
  </div>
</section>

<!-- FOOTER -->
<footer class="footer">
  <div class="footer-container">
    <div class="footer-brand">
      <div class="logo">
        <span class="logo-icon">⚽</span>
        <span class="logo-text">SPK<span class="logo-highlight">SAW</span></span>
      </div>
      <p>Sistem Pendukung Keputusan Pemilihan Pemain Terbaik menggunakan metode Simple Additive Weighting (SAW).</p>
    </div>
    <div class="footer-links">
      <h4>Menu</h4>
      <a href="#home">Beranda</a>
      <a href="#features">Fitur</a>
      <a href="#about">Tentang</a>
    </div>
    <div class="footer-links">
      <h4>Sistem</h4>
      <a href="login.php">Login</a>
    </div>
    <div class="footer-contact">
      <h4>Kontak</h4>
      <p>📧 email@example.com</p>
      <p>📱 +62 812-3456-7890</p>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; 2024 SPK SAW - Sistem Pendukung Keputusan. All rights reserved.</p>
  </div>
</footer>

<script>
// Menu Toggle for Mobile
document.getElementById('menuToggle').addEventListener('click', function() {
  document.querySelector('.nav-links').classList.toggle('active');
  this.classList.toggle('active');
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if(target) {
      target.scrollIntoView({ behavior: 'smooth' });
      document.querySelector('.nav-links')?.classList.remove('active');
    }
  });
});

// Animated counter for statistics (fetch from API or static)
async function fetchStats() {
  try {
    // Jika ingin fetch data real dari database, uncomment kode berikut
    // const response = await fetch('api/stats.php');
    // const data = await response.json();
    
    // Untuk demo, gunakan data statis
    const data = { pemain: 24, kriteria: 6, penilaian: 18 };
    
    animateNumber('statPemain', 0, data.pemain, 1500);
    animateNumber('statKriteria', 0, data.kriteria, 1200);
    animateNumber('statPenilaian', 0, data.penilaian, 1300);
  } catch(e) {
    console.log('Statistik tidak dapat dimuat');
    document.getElementById('statPemain').innerText = '24';
    document.getElementById('statKriteria').innerText = '6';
    document.getElementById('statPenilaian').innerText = '18';
  }
}

function animateNumber(elementId, start, end, duration) {
  const element = document.getElementById(elementId);
  if(!element) return;
  const increment = (end - start) / (duration / 16);
  let current = start;
  const timer = setInterval(() => {
    current += increment;
    if(current >= end) {
      element.innerText = end;
      clearInterval(timer);
    } else {
      element.innerText = Math.floor(current);
    }
  }, 16);
}

// Intersection Observer for scroll animations
const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if(entry.isIntersecting) {
      entry.target.classList.add('animate-in');
      observer.unobserve(entry.target);
    }
  });
}, observerOptions);

document.querySelectorAll('.feature-card, .step, .about-content, .about-illustration').forEach(el => {
  observer.observe(el);
});

// Start animations
document.addEventListener('DOMContentLoaded', () => {
  fetchStats();
  
  // Add scroll class to navbar
  window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    if(window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });
});
</script>
</body>
</html>