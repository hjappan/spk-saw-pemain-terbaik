<?php
session_start();
if(!isset($_SESSION['login'])){ header("Location: ../login.php"); exit; }
include '../config/koneksi.php';

$totalPemain=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM pemain"))['total'];
$topPlayer=mysqli_fetch_assoc(mysqli_query($conn,"SELECT p.nama_pemain,h.nilai_preferensi FROM hasil_saw h JOIN pemain p ON h.id_pemain=p.id_pemain ORDER BY h.ranking ASC LIMIT 1"));
$data=mysqli_query($conn,"SELECT h.ranking,h.nilai_preferensi,p.nama_pemain,p.tim,p.posisi FROM hasil_saw h JOIN pemain p ON h.id_pemain=p.id_pemain ORDER BY h.ranking ASC");
$rows=[];
while($r=mysqli_fetch_assoc($data)) $rows[]=$r;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Laporan SPK</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/laporan.css">
</head>
<body>
<?php include '../partials/sidebar.php'; ?>
<div class="main-content">
<?php include '../partials/header.php'; ?>
<div class="content">

<div class="laporan-actions no-print">
  <button onclick="window.print()" class="btn-cetak">🖨 Cetak Laporan</button>
  <a href="export_excel.php" class="btn-export">📊 Export Excel</a>
</div>

<div class="laporan-header">
  <h1 style="font-size:18px">Laporan Hasil SPK Pemain Terbaik</h1>
  <p style="font-size:12px">Metode Simple Additive Weighting (SAW) · Kompetisi Liga 1 Indonesia</p>
  <p class="laporan-meta">Tanggal Cetak: <?= date('d F Y H:i:s') ?></p>
</div>

<div class="card" style="margin-bottom:20px">
  <h3 style="font-size:13px;margin-bottom:14px">📌 Ringkasan Sistem</h3>
  <ul class="ringkasan-list">
    <li><span>Total Pemain:</span> <strong><?= $totalPemain ?></strong></li>
    <li><span>Pemain Terbaik (Ranking 1):</span> <strong><?= htmlspecialchars($topPlayer['nama_pemain']??'–') ?></strong></li>
    <li><span>Nilai SAW Tertinggi:</span> <strong><?= isset($topPlayer['nilai_preferensi'])?number_format($topPlayer['nilai_preferensi'],4):'–' ?></strong></li>
    <li><span>Metode:</span> <strong>Simple Additive Weighting (SAW)</strong></li>
    <li><span>Normalisasi:</span> <strong>Per posisi (Benefit: X/Max · Cost: Min/X)</strong></li>
  </ul>
</div>

<div class="table-container">
<h2>Tabel Hasil Perankingan</h2>
<table>
<thead><tr>
  <th>Ranking</th><th>Nama Pemain</th><th>Tim</th><th>Posisi</th><th>Nilai SAW</th><th>Keterangan</th>
</tr></thead>
<tbody>
<?php foreach($rows as $r):
  $medals=['🏆','🥈','🥉'];
  $rank=$r['ranking'];
  $medal= $rank<=3 ? $medals[$rank-1] : '';
  $ket= $rank===1?'Terbaik':($rank<=3?'Top 3':'');
?>
<tr>
  <td style="font-weight:700"><?= $medal ?> <?= $rank ?></td>
  <td><?= htmlspecialchars($r['nama_pemain']) ?></td>
  <td><?= htmlspecialchars($r['tim']) ?></td>
  <td><?= $r['posisi'] ?></td>
  <td style="font-weight:700;color:var(--accent)"><?= number_format($r['nilai_preferensi'],4) ?></td>
  <td><?= $ket ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<div style="text-align:right;margin-top:60px">
  <p>Mengetahui,</p>
  <br><br><br>
  <p>______________________</p>
  <p style="font-size:12px;color:var(--text-muted)">Penanggungjawab</p>
</div>

</div>
</div>
<script>document.getElementById('sidebarToggle')?.addEventListener('click',()=>document.querySelector('.sidebar').classList.toggle('open'));</script>
</body>
</html>
