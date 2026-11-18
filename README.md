# ⚽ SPK Pemain Terbaik – Metode SAW

Sistem Pendukung Keputusan (SPK) berbasis web untuk menentukan **pemain sepak bola terbaik** menggunakan metode **Simple Additive Weighting (SAW)**. Dibangun dengan PHP native dan MySQL.

Penilaian dilakukan **per posisi** (Penyerang, Gelandang, Bek, Penjaga Gawang), sehingga setiap posisi dapat memiliki kriteria dan bobot yang berbeda.

---

## ✨ Fitur

- 🔐 **Login admin** dengan password ter-hash (`password_hash` / `password_verify`)
- 🏠 **Dashboard** berisi ringkasan data dan grafik (Chart.js)
- 👤 **Data Pemain**: tambah, ubah, hapus (nama, posisi, tim, nomor punggung, umur, negara)
- 📋 **Data Kriteria**: kode, nama, posisi, bobot (1–5), atribut *Benefit/Cost*, status aktif/nonaktif
- 📝 **Penilaian**: input nilai (skala 1–5) tiap pemain berdasarkan kriteria sesuai posisinya
- ⚙️ **Hitung SAW**: menampilkan matriks keputusan, normalisasi, dan nilai preferensi
- 🏆 **Ranking** pemain berdasarkan nilai preferensi tertinggi
- 📊 **Laporan** dan **export ke Excel** (`.xls`)
- 🌐 Landing page publik

---

## 🧮 Metode SAW

1. **Normalisasi bobot**: `W_j = bobot_j / Σ bobot`
2. **Normalisasi matriks keputusan**
   - Kriteria **Benefit**: `r_ij = x_ij / max(x_j)`
   - Kriteria **Cost**: `r_ij = min(x_j) / x_ij`
3. **Nilai preferensi**: `V_i = Σ (W_j × r_ij)`
4. Pemain diurutkan dari `V_i` terbesar, lalu hasilnya disimpan ke tabel `hasil_saw`

Hanya kriteria dengan status **aktif** (`aktif = 'Y'`) yang ikut dihitung.

---

## 🛠️ Teknologi

| Komponen | Keterangan |
|---|---|
| Backend | PHP (native, `mysqli`) |
| Database | MySQL / MariaDB |
| Frontend | HTML, CSS, JavaScript |
| Grafik | [Chart.js](https://www.chartjs.org/) (CDN) |
| Font | Google Fonts (Inter, Poppins) |
| Server lokal | XAMPP / Laragon / WAMP |

---

## 📁 Struktur Folder

```
spksaw_fixed/
├── assets/
│   ├── css/            # Stylesheet tiap halaman
│   ├── images/         # Logo & gambar default
│   ├── js/
│   └── uploads/
├── config/
│   └── koneksi.php     # Konfigurasi koneksi database
├── pages/
│   ├── dashboard.php
│   ├── pemain.php / pemain_tambah.php / pemain_edit.php
│   ├── kriteria.php / kriteria_tambah.php / kriteria_edit.php
│   ├── penilaian.php / penilaian_kriteria.php
│   ├── saw.php         # Proses perhitungan SAW
│   ├── ranking.php
│   ├── laporan.php
│   └── export_excel.php
├── partials/           # header, sidebar, footer
├── index.php           # Redirect ke login / dashboard
├── landing-page.php
├── login.php
└── logout.php
```

---

## 🚀 Instalasi

### 1. Clone repository

```bash
git clone https://github.com/USERNAME/NAMA-REPO.git
```

Pindahkan folder ke direktori web server, misalnya `htdocs` (XAMPP) atau `www` (Laragon).

### 2. Buat database

Buat database bernama **`spk_pemain`**, lalu jalankan skema berikut (sesuaikan bila perlu):

```sql
CREATE DATABASE spk_pemain;
USE spk_pemain;

CREATE TABLE users (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama_lengkap VARCHAR(100) NOT NULL
);

CREATE TABLE pemain (
  id_pemain INT AUTO_INCREMENT PRIMARY KEY,
  nama_pemain VARCHAR(100) NOT NULL,
  posisi ENUM('Penyerang','Gelandang','Bek','Penjaga Gawang') NOT NULL,
  tim VARCHAR(100),
  nomor_punggung INT,
  umur INT,
  negara VARCHAR(100)
);

CREATE TABLE kriteria (
  id_kriteria INT AUTO_INCREMENT PRIMARY KEY,
  kode_kriteria VARCHAR(10) NOT NULL,
  nama_kriteria VARCHAR(100) NOT NULL,
  posisi VARCHAR(30) NOT NULL,
  bobot INT NOT NULL,
  atribut ENUM('Benefit','Cost') NOT NULL,
  aktif ENUM('Y','N') NOT NULL DEFAULT 'Y'
);

CREATE TABLE penilaian (
  id_penilaian INT AUTO_INCREMENT PRIMARY KEY,
  id_pemain INT NOT NULL,
  id_kriteria INT NOT NULL,
  nilai DOUBLE NOT NULL,
  FOREIGN KEY (id_pemain) REFERENCES pemain(id_pemain) ON DELETE CASCADE,
  FOREIGN KEY (id_kriteria) REFERENCES kriteria(id_kriteria) ON DELETE CASCADE
);

CREATE TABLE hasil_saw (
  id_hasil INT AUTO_INCREMENT PRIMARY KEY,
  id_pemain INT NOT NULL,
  nilai_preferensi DOUBLE NOT NULL,
  ranking INT NOT NULL,
  posisi VARCHAR(30),
  FOREIGN KEY (id_pemain) REFERENCES pemain(id_pemain) ON DELETE CASCADE
);
```

### 3. Buat akun admin

Buat hash password lewat PHP:

```bash
php -r "echo password_hash('admin123', PASSWORD_DEFAULT);"
```

Lalu masukkan ke tabel `users`:

```sql
INSERT INTO users (username, password, nama_lengkap)
VALUES ('admin', 'HASIL_HASH_DI_ATAS', 'Administrator');
```

### 4. Atur koneksi database

Edit `config/koneksi.php`:

```php
$conn = mysqli_connect("localhost", "root", "", "spk_pemain");
```

### 5. Jalankan

Aktifkan Apache dan MySQL, lalu buka:

```
http://localhost/spksaw_fixed/
```

---

## 📖 Cara Penggunaan

1. **Login** dengan akun admin.
2. Isi **Data Kriteria** untuk tiap posisi dan tentukan bobot serta atribut (Benefit/Cost).
3. Isi **Data Pemain**.
4. Buka menu **Penilaian**, lalu input nilai tiap pemain (skala 1–5).
5. Buka **Hitung SAW** untuk memproses perhitungan.
6. Lihat hasil di **Ranking** atau **Laporan**, lalu export ke Excel bila diperlukan.

---

## 🔒 Catatan Keamanan

Proyek ini dibuat untuk pembelajaran. Sebelum dipakai di lingkungan produksi, sebaiknya:

- Ganti user `root` tanpa password di `config/koneksi.php` dengan user database khusus.
- Gunakan *prepared statement* di seluruh query (saat ini sebagian masih memakai `mysqli_real_escape_string`).
- Tambahkan proteksi CSRF pada form.
- Jangan commit kredensial database asli ke GitHub.

---

## 📄 Lisensi

Silakan tambahkan lisensi sesuai kebutuhan (misalnya [MIT](https://choosealicense.com/licenses/mit/)).

---

## 👤 Author

Dibuat oleh **NAMA_KAMU**
GitHub: [@USERNAME](https://github.com/USERNAME)
