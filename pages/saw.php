<?php
session_start();
if(!isset($_SESSION['login'])){ header("Location: ../login.php"); exit; }
include '../config/koneksi.php';

// Hapus hasil lama
mysqli_query($conn, "TRUNCATE TABLE hasil_saw");

// Ambil semua kriteria aktif
$kriteria = [];
$q = mysqli_query($conn, "SELECT * FROM kriteria WHERE aktif='Y' ORDER BY id_kriteria");
while ($k = mysqli_fetch_assoc($q)) {
    $kriteria[$k['id_kriteria']] = $k;
}

// NORMALISASI BOBOT
$total_bobot = array_sum(array_column($kriteria, 'bobot'));
foreach ($kriteria as $id => $k) {
    $kriteria[$id]['bobot_normal'] = $total_bobot > 0 ? $k['bobot'] / $total_bobot : 0;
}

// Ambil semua pemain
$pemain = [];
$q = mysqli_query($conn, "SELECT * FROM pemain ORDER BY id_pemain");
while ($p = mysqli_fetch_assoc($q)) {
    $pemain[$p['id_pemain']] = $p;
}

// Ambil semua penilaian
$penilaian = [];
$q = mysqli_query($conn, "SELECT * FROM penilaian");
while ($n = mysqli_fetch_assoc($q)) {
    $penilaian[$n['id_pemain']][$n['id_kriteria']] = $n['nilai'];
}

// Bangun matriks keputusan
$matriks = [];
foreach ($pemain as $id_pemain => $p) {
    foreach ($kriteria as $id_kriteria => $k) {
        $matriks[$id_pemain][$id_kriteria] = $penilaian[$id_pemain][$id_kriteria] ?? 0;
    }
}

// Hitung Max & Min per kriteria
$max_kriteria = [];
$min_kriteria = [];
foreach ($kriteria as $id_kriteria => $k) {
    $values = array_column($matriks, $id_kriteria);
    $max_kriteria[$id_kriteria] = max($values);
    $min_kriteria[$id_kriteria] = min($values) != 0 ? min($values) : 1;
}

// Normalisasi matriks
$normalisasi = [];
foreach ($pemain as $id_pemain => $p) {
    foreach ($kriteria as $id_kriteria => $k) {
        $x = $matriks[$id_pemain][$id_kriteria];
        if ($k['atribut'] == 'Benefit') {
            $normalisasi[$id_pemain][$id_kriteria] = $max_kriteria[$id_kriteria] > 0 ? $x / $max_kriteria[$id_kriteria] : 0;
        } else {
            $normalisasi[$id_pemain][$id_kriteria] = $x > 0 ? $min_kriteria[$id_kriteria] / $x : 0;
        }
    }
}

// Hitung nilai preferensi
$hasil = [];
foreach ($pemain as $id_pemain => $p) {
    $vi = 0;
    $detail = [];
    foreach ($kriteria as $id_kriteria => $k) {
        $kontribusi = $normalisasi[$id_pemain][$id_kriteria] * $k['bobot_normal'];
        $vi += $kontribusi;
        $detail[$id_kriteria] = $kontribusi;
    }
    $hasil[$id_pemain] = [
        'nama' => $p['nama_pemain'],
        'tim' => $p['tim'],
        'posisi' => $p['posisi'],
        'vi' => $vi,
        'detail' => $detail
    ];
}

// Urutkan berdasarkan Vi tertinggi
uasort($hasil, fn($a, $b) => $b['vi'] <=> $a['vi']);

// Simpan ke database
$ranking = 1;
foreach ($hasil as $id_pemain => $h) {
    $stmt = mysqli_prepare($conn, "INSERT INTO hasil_saw (id_pemain, nilai_preferensi, ranking, posisi) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'idis', $id_pemain, $h['vi'], $ranking, $h['posisi']);
    mysqli_stmt_execute($stmt);
    $ranking++;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Perhitungan SAW - SPK Pemain Terbaik</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        * {
            box-sizing: border-box;
        }

        /* Container utama */
        .saw-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 5px;
        }

        /* Header */
        .saw-header {
            background: linear-gradient(135deg, #1a1a2e, #0f3460);
            border-radius: 20px;
            padding: 25px 20px;
            margin-bottom: 25px;
            text-align: center;
        }
        .saw-header h1 { font-size: 24px; margin-bottom: 8px; }
        .saw-header p { font-size: 13px; opacity: 0.8; }

        /* Stat Cards */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        .stat-card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 15px;
            text-align: center;
        }
        .stat-card .icon { font-size: 28px; margin-bottom: 8px; }
        .stat-card .value { font-size: 22px; font-weight: 700; color: var(--accent); }
        .stat-card .label { font-size: 11px; color: var(--text-muted); margin-top: 5px; }

        /* Action Bar */
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            background: var(--bg-card);
            border-radius: 16px;
            padding: 15px 20px;
            margin-bottom: 25px;
        }
        .btn-ranking {
            background: linear-gradient(135deg, var(--accent), #008f3a);
            padding: 10px 24px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
        }
        .btn-ranking:hover { transform: translateY(-2px); box-shadow: 0 5px 20px rgba(0,200,83,0.3); }

        /* Section Card */
        .section-card {
            background: var(--bg-card);
            border-radius: 20px;
            margin-bottom: 25px;
            overflow: hidden;
        }
        .section-header {
            padding: 15px 20px;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .section-header h2 { font-size: 16px; font-weight: 600; margin: 0; }
        .section-header p { font-size: 11px; color: var(--text-muted); margin: 5px 0 0 0; }

        /* Bobot Info */
        .bobot-info {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            padding: 15px 20px;
            background: rgba(0,0,0,0.15);
        }
        .bobot-item {
            background: rgba(0,0,0,0.3);
            padding: 8px 15px;
            border-radius: 12px;
            font-size: 12px;
        }
        .bobot-item strong { color: var(--accent); }

        /* Table Responsive */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            min-width: 600px;
        }
        .data-table th {
            background: rgba(0,0,0,0.3);
            padding: 12px 10px;
            text-align: left;
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 12px;
            white-space: nowrap;
        }
        .data-table td {
            padding: 10px 10px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .data-table tr:hover { background: rgba(255,255,255,0.02); }

        /* Badge */
        .badge-benefit { background: rgba(0,200,83,0.2); color: #00c853; padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-block; }
        .badge-cost { background: rgba(255,68,68,0.2); color: #ff4444; padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-block; }
        
        /* Rank Badge */
        .rank-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            font-weight: 700;
            font-size: 12px;
        }
        .rank-1 { background: rgba(255,215,0,0.3); color: #ffd700; }
        .rank-2 { background: rgba(192,192,192,0.3); color: #c0c0c0; }
        .rank-3 { background: rgba(205,127,50,0.3); color: #cd7f32; }

        .score-highlight { color: var(--accent); font-weight: 700; }
        .text-muted { color: var(--text-muted); font-size: 11px; }

        /* Winner Row */
        .row-winner { background: rgba(255,215,0,0.08); }
        .row-winner td:first-child { border-left: 3px solid #ffd700; }

        /* Footer Info */
        .info-footer {
            padding: 20px;
            background: rgba(0,0,0,0.15);
            border-radius: 16px;
            font-size: 12px;
            line-height: 1.6;
        }
        .info-footer h4 { font-size: 14px; margin-bottom: 10px; color: var(--accent); }
        .info-footer ul, .info-footer ol { margin-left: 20px; margin-top: 5px; }
        .info-footer li { margin: 5px 0; }

        /* Responsive */
        @media (max-width: 768px) {
            .stat-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
            .saw-header h1 { font-size: 20px; }
            .stat-card .value { font-size: 18px; }
            .bobot-info { gap: 8px; }
            .bobot-item { padding: 5px 10px; font-size: 10px; }
            .data-table th, .data-table td { padding: 8px 6px; font-size: 11px; }
            .section-header { padding: 12px 15px; }
            .section-header h2 { font-size: 14px; }
        }

        @media (max-width: 480px) {
            .action-bar { flex-direction: column; text-align: center; }
            .data-table { font-size: 10px; min-width: 500px; }
            .rank-badge { width: 22px; height: 22px; font-size: 10px; }
        }
    </style>
</head>
<body>
<?php include '../partials/sidebar.php'; ?>
<div class="main-content">
    <?php include '../partials/header.php'; ?>
    <div class="content saw-container">

        <!-- HEADER -->
        <div class="saw-header">
            <h1>⚙️ Perhitungan Metode SAW</h1>
            <p>Simple Additive Weighting • Normalisasi & Perankingan Pemain Terbaik</p>
        </div>

        <!-- STATISTIK -->
        <div class="stat-grid">
            <div class="stat-card"><div class="icon">👥</div><div class="value"><?= count($pemain) ?></div><div class="label">Total Pemain</div></div>
            <div class="stat-card"><div class="icon">📊</div><div class="value"><?= count($kriteria) ?></div><div class="label">Kriteria Aktif</div></div>
            <div class="stat-card"><div class="icon">🏆</div><div class="value"><?= count($hasil) ?></div><div class="label">Telah Diranking</div></div>
            <div class="stat-card"><div class="icon">⚖️</div><div class="value"><?= number_format($total_bobot, 0) ?></div><div class="label">Total Bobot Awal</div></div>
        </div>

        <!-- ACTION -->
        <div class="action-bar">
            <div>
                <strong style="font-size: 14px;">✅ Perhitungan SAW Selesai!</strong><br>
                <span class="text-muted">Normalisasi benefit/cost dengan bobot ternormalisasi (Σ bobot = 1)</span>
            </div>
            <a href="ranking.php" class="btn-ranking">🏆 Lihat Ranking Pemain →</a>
        </div>

        <!-- BOBOT INFO -->
        <div class="section-card">
            <div class="section-header"><h2>📐 Bobot Kriteria (Ternormalisasi)</h2><p>Total bobot awal = <?= $total_bobot ?> → Bobot SAW = bobot awal / total bobot (total harus 1)</p></div>
            <div class="bobot-info">
                <?php foreach($kriteria as $k): ?>
                <div class="bobot-item">
                    <strong><?= $k['kode_kriteria'] ?></strong> (<?= $k['nama_kriteria'] ?>)<br>
                    Awal: <?= $k['bobot'] ?> → SAW: <strong><?= number_format($k['bobot_normal'], 3) ?></strong>
                    <span class="<?= $k['atribut'] == 'Benefit' ? 'badge-benefit' : 'badge-cost' ?>" style="margin-left: 6px;"><?= $k['atribut'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 1. MATRIKS KEPUTUSAN -->
        <div class="section-card">
            <div class="section-header"><h2>1️⃣ Matriks Keputusan (X)</h2><p>Data nilai awal setiap pemain terhadap setiap kriteria</p></div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead><tr><th>#</th><th>Pemain</th><th>Tim</th><th>Posisi</th><?php foreach($kriteria as $k): ?><th><?= $k['kode_kriteria'] ?><br><span class="text-muted"><?= $k['nama_kriteria'] ?></span></th><?php endforeach; ?></tr></thead>
                    <tbody><?php $no=1; foreach($hasil as $id => $h): ?><tr <?= $no==1 ? 'class="row-winner"' : '' ?>><td><span class="rank-badge <?= $no==1?'rank-1':($no==2?'rank-2':($no==3?'rank-3':'')) ?>"><?= $no++ ?></span></td><td><strong><?= htmlspecialchars($h['nama']) ?></strong></td><td><?= htmlspecialchars($h['tim']) ?></td><td><?= htmlspecialchars($h['posisi']) ?></td><?php foreach($kriteria as $id_k => $k): ?><td><?= number_format($matriks[$id][$id_k], 2) ?></td><?php endforeach; ?></tr><?php endforeach; ?></tbody>
                </table>
            </div>
        </div>

        <!-- 2. MAX & MIN -->
        <div class="section-card">
            <div class="section-header"><h2>2️⃣ Nilai Max & Min per Kriteria</h2><p>Digunakan untuk normalisasi (Benefit: X/max | Cost: min/X)</p></div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead><tr><th>Kriteria</th><th>Atribut</th><th>Max</th><th>Min</th><th>Rumus</th></tr></thead>
                    <tbody><?php foreach($kriteria as $k): ?><tr><td><strong><?= $k['kode_kriteria'] ?></strong><br><span class="text-muted"><?= $k['nama_kriteria'] ?></span></td><td><span class="<?= $k['atribut']=='Benefit'?'badge-benefit':'badge-cost' ?>"><?= $k['atribut'] ?></span></td><td class="score-highlight"><?= number_format($max_kriteria[$k['id_kriteria']], 2) ?></td><td class="score-highlight"><?= number_format($min_kriteria[$k['id_kriteria']], 2) ?></td><td class="text-muted"><?= $k['atribut']=='Benefit' ? "R = X / {$max_kriteria[$k['id_kriteria']]}" : "R = {$min_kriteria[$k['id_kriteria']]} / X" ?></td></tr><?php endforeach; ?></tbody>
                </table>
            </div>
        </div>

        <!-- 3. NORMALISASI -->
        <div class="section-card">
            <div class="section-header"><h2>3️⃣ Matriks Normalisasi (R)</h2><p>Hasil normalisasi nilai setiap pemain (range 0-1)</p></div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead><tr><th>#</th><th>Pemain</th><?php foreach($kriteria as $k): ?><th><?= $k['kode_kriteria'] ?></th><?php endforeach; ?></tr></thead>
                    <tbody><?php $no=1; foreach($hasil as $id => $h): ?><tr <?= $no==1 ? 'class="row-winner"' : '' ?>><td><span class="rank-badge <?= $no==1?'rank-1':($no==2?'rank-2':($no==3?'rank-3':'')) ?>"><?= $no++ ?></span></td><td><strong><?= htmlspecialchars($h['nama']) ?></strong></td><?php foreach($kriteria as $id_k => $k): ?><td><?= number_format($normalisasi[$id][$id_k], 4) ?></td><?php endforeach; ?></tr><?php endforeach; ?></tbody>
                </table>
            </div>
        </div>

        <!-- 4. NILAI PREFERENSI AKHIR -->
        <div class="section-card">
            <div class="section-header"><h2>4️⃣ Nilai Preferensi Akhir (Vi)</h2><p>Vi = Σ (R × Bobot_SAW) → Ranking tertinggi = pemain terbaik</p></div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead><tr><th>Rank</th><th>Pemain</th><th>Tim</th><th>Posisi</th><?php foreach($kriteria as $k): ?><th><?= $k['kode_kriteria'] ?>×W</th><?php endforeach; ?><th>Total Vi</th></tr></thead>
                    <tbody><?php $no=1; foreach($hasil as $id => $h): ?><tr style="<?= $no==1 ? 'background:rgba(255,215,0,0.1);' : '' ?>"><td><span class="rank-badge <?= $no==1?'rank-1':($no==2?'rank-2':($no==3?'rank-3':'')) ?>"><?= $no++ ?></span></td><td><strong><?= htmlspecialchars($h['nama']) ?></strong></td><td><?= htmlspecialchars($h['tim']) ?></td><td><?= htmlspecialchars($h['posisi']) ?></td><?php foreach($kriteria as $id_k => $k): ?><td><?= number_format($h['detail'][$id_k], 4) ?></td><?php endforeach; ?><td class="score-highlight" style="font-size: 15px;"><?= number_format($h['vi'], 4) ?></td></tr><?php endforeach; ?></tbody>
                </table>
            </div>
        </div>

        <!-- INFORMASI METODE -->
        <div class="section-card">
            <div class="section-header"><h2>📖 Informasi Metode SAW</h2><p>Simple Additive Weighting (SAW) juga dikenal sebagai metode penjumlahan terbobot</p></div>
            <div class="info-footer">
                <h4>Langkah-langkah SAW:</h4>
                <ol>
                    <li>Menentukan kriteria yang akan dijadikan acuan dalam pengambilan keputusan</li>
                    <li>Menentukan bobot preferensi setiap kriteria (dinormalisasi agar Σ bobot = 1)</li>
                    <li>Membangun matriks keputusan (X) dari nilai setiap alternatif terhadap kriteria</li>
                    <li>Melakukan normalisasi matriks (R) berdasarkan jenis benefit/cost</li>
                    <li>Menghitung nilai preferensi Vi = Σ (R × bobot_normal)</li>
                    <li>Perankingan berdasarkan Vi tertinggi</li>
                </ol>
                <h4 style="margin-top: 12px;">Rumus Normalisasi:</h4>
                <ul>
                    <li><strong>Benefit</strong> (semakin besar semakin baik): R = X / max(X)</li>
                    <li><strong>Cost</strong> (semakin kecil semakin baik): R = min(X) / X</li>
                </ul>
            </div>
        </div>

    </div>
</div>

<script>
document.getElementById('sidebarToggle')?.addEventListener('click', function() {
    document.querySelector('.sidebar')?.classList.toggle('open');
});
</script>
</body>
</html>