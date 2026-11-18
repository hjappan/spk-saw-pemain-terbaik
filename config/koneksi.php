<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "spk_pemain"
);

if(!$conn){
    die("Koneksi gagal");
}