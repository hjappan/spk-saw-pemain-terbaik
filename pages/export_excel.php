<?php
session_start();
if(!isset($_SESSION['login'])){ header("Location: ../login.php"); exit; }
include '../config/koneksi.php';

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Laporan_Ranking_SAW_".date('Ymd').".xls");
header("Cache-Control: max-age=0");

$data=mysqli_query($conn,"SELECT h.ranking,h.nilai_preferensi,p.nama_pemain,p.tim,p.posisi FROM hasil_saw h JOIN pemain p ON h.id_pemain=p.id_pemain ORDER BY h.ranking ASC");
?>
<html><head><meta charset="UTF-8"><style>
th { background:#166534;color:white;padding:6px 10px;text-align:left; }
td { padding:5px 10px;border:1px solid #ccc; }
</style></head><body>
<h2>Laporan Hasil SPK Pemain Terbaik – Metode SAW</h2>
<p>Tanggal Export: <?= date('d-m-Y H:i:s') ?></p><br>
<table border="1">
<thead><tr><th>Ranking</th><th>Nama Pemain</th><th>Tim</th><th>Posisi</th><th>Nilai SAW</th></tr></thead>
<tbody>
<?php while($row=mysqli_fetch_assoc($data)): ?>
<tr>
  <td><?= $row['ranking'] ?></td>
  <td><?= htmlspecialchars($row['nama_pemain']) ?></td>
  <td><?= htmlspecialchars($row['tim']) ?></td>
  <td><?= $row['posisi'] ?></td>
  <td><?= number_format($row['nilai_preferensi'],4) ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</body></html>
