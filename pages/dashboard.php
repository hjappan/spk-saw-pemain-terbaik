<?php
session_start();
if(!isset($_SESSION['login'])){ header("Location: ../login.php"); exit; }
include '../config/koneksi.php';

$totalPemain   = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM pemain"))['total'];
$totalKriteria = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM kriteria WHERE aktif='Y'"))['total'];
$totalPenilaian= mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(DISTINCT id_pemain) AS total FROM penilaian"))['total'];

// Top 3 players
$topPlayers = mysqli_query($conn,"
  SELECT p.id_pemain, p.nama_pemain, p.posisi, p.tim, h.nilai_preferensi, h.ranking
  FROM hasil_saw h 
  JOIN pemain p ON h.id_pemain = p.id_pemain
  ORDER BY h.ranking ASC 
  LIMIT 3
");

$top3Data = [];
while($row = mysqli_fetch_assoc($topPlayers)){
    $top3Data[] = $row;
}

// Data for chart (top 10)
$dataChart = mysqli_query($conn,"
  SELECT p.nama_pemain, p.posisi, p.tim, h.nilai_preferensi, h.ranking
  FROM hasil_saw h 
  JOIN pemain p ON h.id_pemain = p.id_pemain
  ORDER BY h.ranking ASC 
  LIMIT 10
");
$labels = []; 
$values = []; 
$chartData = [];
while($r = mysqli_fetch_assoc($dataChart)){
    $labels[] = $r['nama_pemain']; 
    $values[] = round($r['nilai_preferensi'], 4);
    $chartData[] = $r;
}

// Get champion (rank 1)
$champion = !empty($top3Data) ? $top3Data[0] : null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Dashboard – SPK SAW</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/dashboard.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php include '../partials/sidebar.php'; ?>
<div class="main-content">
<?php include '../partials/header.php'; ?>
<div class="content">

<!-- Navigation Button to Landing Page -->
<div class="landing-nav">
    <a href="../landing-page.php" class="btn-landing" target="_blank">
        <span>🏠</span> Lihat Landing Page
    </a>
</div>

<!-- STAT CARDS -->
<div class="stats-grid">
    <div class="stat-card green">
        <div class="stat-icon-wrapper">
            <span class="stat-icon">👥</span>
        </div>
        <div class="stat-info">
            <h3>Total Pemain</h3>
            <h2><?= $totalPemain ?></h2>
        </div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon-wrapper">
            <span class="stat-icon">📋</span>
        </div>
        <div class="stat-info">
            <h3>Kriteria Aktif</h3>
            <h2><?= $totalKriteria ?></h2>
        </div>
    </div>
    <div class="stat-card yellow">
        <div class="stat-icon-wrapper">
            <span class="stat-icon">📝</span>
        </div>
        <div class="stat-info">
            <h3>Sudah Dinilai</h3>
            <h2><?= $totalPenilaian ?></h2>
        </div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon-wrapper">
            <span class="stat-icon">🏆</span>
        </div>
        <div class="stat-info">
            <h3>Top Player</h3>
            <h2 style="font-size:18px;margin-top:5px"><?= $champion['nama_pemain'] ?? '–' ?></h2>
        </div>
    </div>
</div>

<!-- CHAMPION SPOTLIGHT -->
<?php if($champion): ?>
<div class="champion-spotlight">
    <div class="champion-badge">
        <span class="crown">👑</span>
        <span class="champion-text">PEMAIN TERBAIK</span>
    </div>
    <div class="champion-content">
        <div class="champion-icon">
            <div class="trophy-large">🏆</div>
        </div>
        <div class="champion-info">
            <h2><?= htmlspecialchars($champion['nama_pemain']) ?></h2>
            <div class="champion-details">
                <span class="detail-badge">⚽ <?= htmlspecialchars($champion['posisi']) ?></span>
                <span class="detail-badge">🏛️ <?= htmlspecialchars($champion['tim']) ?></span>
            </div>
            <div class="champion-score">
                <div class="score-label">Nilai Preferensi</div>
                <div class="score-value"><?= number_format($champion['nilai_preferensi'], 4) ?></div>
                <div class="score-bar">
                    <div class="score-fill" style="width: <?= min(100, $champion['nilai_preferensi'] * 100) ?>%"></div>
                </div>
            </div>
            <div class="champion-quote">
                "Pemain dengan performa terbaik berdasarkan metode SAW"
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- CHART AND TOP 3 SECTION -->
<?php if(count($values) > 0): ?>
<div class="dashboard-row">
    <!-- CHART CARD -->
    <div class="chart-card">
        <div class="card-header">
            <h2>📊 Grafik Nilai SAW Pemain</h2>
            <div class="card-subtitle">Top 10 Pemain dengan Nilai Tertinggi</div>
        </div>
        <canvas id="rankingChart" height="120"></canvas>
    </div>

    <!-- TOP 3 CARD -->
    <div class="top3-card-container">
        <div class="card-header">
            <h2>🏆 TOP 3 PEMAIN TERBAIK</h2>
            <div class="card-subtitle">Berdasarkan Perhitungan SAW</div>
        </div>
        <div class="top3-grid">
            <?php 
            $medals = ['🥇', '🥈', '🥉'];
            $colors = ['#FFD700', '#C0C0C0', '#CD7F32'];
            foreach($top3Data as $i => $p): 
            ?>
            <div class="top3-card" style="border-bottom: 3px solid <?= $colors[$i] ?>">
                <div class="top3-rank"><?= $medals[$i] ?></div>
                <div class="top3-number">#<?= $i+1 ?></div>
                <h3><?= htmlspecialchars($p['nama_pemain']) ?></h3>
                <p class="position"><?= htmlspecialchars($p['posisi']) ?> • <?= htmlspecialchars($p['tim']) ?></p>
                <div class="nilai-container">
                    <span class="nilai-label">Nilai SAW</span>
                    <div class="nilai-value"><?= number_format($p['nilai_preferensi'], 4) ?></div>
                </div>
                <div class="progress-ring">
                    <svg width="60" height="60">
                        <circle cx="30" cy="30" r="25" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="3"/>
                        <circle cx="30" cy="30" r="25" fill="none" 
                                stroke="<?= $colors[$i] ?>" 
                                stroke-width="3"
                                stroke-dasharray="157" 
                                stroke-dashoffset="<?= 157 - (157 * min(1, $p['nilai_preferensi'])) ?>"
                                transform="rotate(-90 30 30)"/>
                    </svg>
                    <div class="progress-text"><?= round($p['nilai_preferensi'] * 100, 1) ?>%</div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
// Chart with Gradient
const ctx = document.getElementById('rankingChart').getContext('2d');
const gradient = ctx.createLinearGradient(0, 0, 0, 400);
gradient.addColorStop(0, 'rgba(0, 200, 83, 0.8)');
gradient.addColorStop(1, 'rgba(0, 200, 83, 0.3)');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Nilai Preferensi SAW',
            data: <?= json_encode($values) ?>,
            backgroundColor: gradient,
            borderColor: '#00c853',
            borderWidth: 2,
            borderRadius: 10,
            borderSkipped: false,
            barPercentage: 0.7,
            categoryPercentage: 0.8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                labels: { 
                    color: '#f8fafc',
                    font: { size: 12, weight: '500' }
                },
                position: 'top'
            },
            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.8)',
                titleColor: '#00c853',
                bodyColor: '#fff',
                padding: 12,
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        return `Nilai: ${context.parsed.y.toFixed(4)}`;
                    }
                }
            }
        },
        scales: {
            x: { 
                ticks: { 
                    color: '#94a3b8',
                    font: { size: 11 },
                    rotation: 45,
                    maxRotation: 45
                }, 
                grid: { color: 'rgba(255,255,255,0.05)' } 
            },
            y: { 
                beginAtZero: true, 
                ticks: { 
                    color: '#94a3b8',
                    callback: function(value) {
                        return value.toFixed(3);
                    }
                }, 
                grid: { color: 'rgba(255,255,255,0.05)' },
                title: {
                    display: true,
                    text: 'Nilai Preferensi',
                    color: '#94a3b8'
                }
            }
        },
        animation: {
            duration: 1500,
            easing: 'easeInOutQuart'
        }
    }
});
</script>

<!-- RANKINGS TABLE -->
<div class="rankings-table">
    <div class="card-header">
        <h2>📋 Detail Perankingan Pemain</h2>
        <div class="card-subtitle">10 Pemain dengan Nilai Preferensi Tertinggi</div>
    </div>
    <div class="table-responsive">
        <table class="ranking-table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Nama Pemain</th>
                    <th>Posisi</th>
                    <th>Tim</th>
                    <th>Nilai Preferensi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($chartData as $idx => $p): ?>
                <tr class="<?= $idx == 0 ? 'champion-row' : '' ?>">
                    <td class="rank-cell">
                        <span class="rank-badge rank-<?= $idx + 1 ?>">
                            <?= $idx + 1 ?>
                        </span>
                    </td>
                    <td class="player-name"><?= htmlspecialchars($p['nama_pemain']) ?></td>
                    <td><?= htmlspecialchars($p['posisi'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($p['tim'] ?? '-') ?></td>
                    <td class="score-cell"><?= number_format($p['nilai_preferensi'], 4) ?></td>
                    <td>
                        <?php if($idx == 0): ?>
                            <span class="status-badge champion">🏆 Champion</span>
                        <?php elseif($idx == 1): ?>
                            <span class="status-badge silver">🥈 Runner Up</span>
                        <?php elseif($idx == 2): ?>
                            <span class="status-badge bronze">🥉 Top 3</span>
                        <?php else: ?>
                            <span class="status-badge regular">Top <?= $idx + 1 ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php else: ?>
<div class="empty-state">
    <div class="empty-icon">⚙️</div>
    <h3>Belum Ada Hasil Perhitungan SAW</h3>
    <p>Silakan input penilaian pemain dan jalankan perhitungan SAW untuk melihat hasil ranking.</p>
    <a href="saw.php" class="btn-hitung">Hitung SAW Sekarang</a>
</div>
<?php endif; ?>

</div>
</div>

<script>
document.getElementById('sidebarToggle')?.addEventListener('click',()=>{
    document.querySelector('.sidebar').classList.toggle('open');
});
</script>
</body>
</html>