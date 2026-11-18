<?php
session_start();
if(!isset($_SESSION['login'])){ header("Location: ../login.php"); exit; }
include '../config/koneksi.php';

$data = mysqli_query($conn, "
    SELECT h.ranking, h.nilai_preferensi, h.posisi, 
           p.nama_pemain, p.tim, p.posisi AS pos_pemain 
    FROM hasil_saw h 
    JOIN pemain p ON h.id_pemain = p.id_pemain 
    ORDER BY h.ranking ASC
");
$rows = [];
while($r = mysqli_fetch_assoc($data)) $rows[] = $r;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Ranking Pemain - SPK SAW</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/ranking.css">
</head>
<body>
<?php include '../partials/sidebar.php'; ?>
<div class="main-content">
<?php include '../partials/header.php'; ?>
<div class="content">

<?php if(empty($rows)): ?>
<div class="empty-state">
    <div class="empty-icon">🏆</div>
    <h3>Belum Ada Hasil Perhitungan SAW</h3>
    <p>Silakan jalankan perhitungan SAW untuk melihat ranking pemain terbaik.</p>
    <a href="saw.php" class="btn-hitung">⚙️ Hitung SAW Sekarang</a>
</div>
<?php else: ?>

<!-- Page Title -->
<div class="page-title">
    <h1>🏆 Ranking Pemain Terbaik</h1>
    <p>Berdasarkan perhitungan metode SAW (Simple Additive Weighting)</p>
</div>

<!-- Champion Card (Peringkat 1) -->
<?php if(!empty($rows[0])): 
    $champion = $rows[0];
?>
<div class="champion-card">
    <div class="champion-strip">
        <span class="champion-icon">👑</span>
        <span class="champion-label">PEMAIN TERBAIK</span>
    </div>
    <div class="champion-body">
        <div class="champion-rank">#1</div>
        <div class="champion-name"><?= htmlspecialchars($champion['nama_pemain']) ?></div>
        <div class="champion-detail">
            <span class="detail-pos">⚽ <?= $champion['pos_pemain'] ?></span>
            <span class="detail-team">🏛️ <?= htmlspecialchars($champion['tim']) ?></span>
        </div>
        <div class="champion-score">
            <div class="score-number"><?= number_format($champion['nilai_preferensi'], 4) ?></div>
            <div class="score-text">Nilai Preferensi SAW</div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Top 3 Section Title -->
<div class="section-title">
    <h2>🥇 Top 3 Pemain Terbaik</h2>
    <p>Pemain dengan nilai preferensi tertinggi</p>
</div>

<!-- Top 3 Grid -->
<div class="top3-grid">
    <?php 
    $top3 = array_slice($rows, 0, 3);
    $medals = ['🥇', '🥈', '🥉'];
    $titles = ['Juara 1', 'Juara 2', 'Juara 3'];
    foreach($top3 as $idx => $r):
    ?>
    <div class="top3-item">
        <div class="top3-medal"><?= $medals[$idx] ?></div>
        <div class="top3-rank">#<?= $r['ranking'] ?></div>
        <div class="top3-name"><?= htmlspecialchars($r['nama_pemain']) ?></div>
        <div class="top3-pos"><?= $r['pos_pemain'] ?></div>
        <div class="top3-team"><?= htmlspecialchars($r['tim']) ?></div>
        <div class="top3-score">
            <span class="score-value"><?= number_format($r['nilai_preferensi'], 4) ?></span>
            <span class="score-label">Nilai SAW</span>
        </div>
        <div class="top3-badge"><?= $titles[$idx] ?></div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Tabel Lengkap -->
<div class="table-wrapper">
    <div class="table-header">
        <h3>📋 Tabel Lengkap Ranking</h3>
        <span class="total-count">Total <?= count($rows) ?> Pemain</span>
    </div>
    <div class="table-responsive">
        <table class="ranking-table">
            <thead>
                <tr>
                    <th width="80">Ranking</th>
                    <th>Nama Pemain</th>
                    <th width="120">Posisi</th>
                    <th>Tim</th>
                    <th width="150">Nilai SAW</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rows as $r): 
                    $rank = $r['ranking'];
                    $rowClass = '';
                    if($rank == 1) $rowClass = 'row-champion';
                    elseif($rank == 2) $rowClass = 'row-silver';
                    elseif($rank == 3) $rowClass = 'row-bronze';
                ?>
                <tr class="<?= $rowClass ?>">
                    <td class="rank-col">
                        <?php if($rank == 1): ?>
                            <span class="rank-medal">🏆</span> <?= $rank ?>
                        <?php elseif($rank == 2): ?>
                            <span class="rank-medal">🥈</span> <?= $rank ?>
                        <?php elseif($rank == 3): ?>
                            <span class="rank-medal">🥉</span> <?= $rank ?>
                        <?php else: ?>
                            <span class="rank-number"><?= $rank ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="name-col"><?= htmlspecialchars($r['nama_pemain']) ?></td>
                    <td><?= $r['pos_pemain'] ?></td>
                    <td><?= htmlspecialchars($r['tim']) ?></td>
                    <td class="score-col"><?= number_format($r['nilai_preferensi'], 4) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
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