<?php

// membuat koneksi dengan database mysql

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$link   = mysqli_connect($dbhost, $dbuser, $dbpass);

// cek koneksi, tampilkan error message jika invalid

if (!$link) {
    die ("Error while connecting to database: ".mysqli_connect_errno().
         " - ".mysqli_connect_error());
}

// buat database kampusku jika belum ada

$query  = "CREATE DATABASE IF NOT EXISTS kampusku";
$result = mysqli_query($link, $query);

if (!$result) {
    die ("Query error: ".mysqli_errno($link)." - ".mysqli_error($link));
} else {
    echo "Database <b>'kampusku'</b> berhasil dibuat ... <br>";
}

// memilih database kampusku

$result = mysqli_select_db($link, 'kampusku');

if (!$result) {
    die ("Query error: ".mysqli_errno($link)." - ".mysqli_error($link));
} else {
    echo "Database <b>'kampusku'</b> berhasil dipilih ... <br>";
}

// cek apakah tabel mahasiswa sudah ada, jika ada, hapus tabel

$query       = "DROP TABLE IF EXISTS mahasiswa";
$hasil_query = mysqli_query($link, $query);

if (!$hasil_query) {
    die ("Query error: ".mysqli_errno($link)." - ".mysqli_error($link));
} else {
    echo "Tabel <b>'mahasiswa'</b> berhasil dihapus ... <br>";
}

// membuat query untuk CREATE TABLE mahasiswa

$query  = "CREATE TABLE mahasiswa (";
$query .= "nim CHAR(8), nama VARCHAR(100), ";
$query .= "tempat_lahir VARCHAR(50), tanggal_lahir DATE, ";
$query .= "fakultas VARCHAR(50), jurusan VARCHAR(50), ";
$query .= "ipk DECIMAL(3,2), PRIMARY KEY (nim))";

$hasil_query = mysqli_query($link, $query);

if (!$hasil_query) {
    die ("Query error: ".mysqli_errno($link)." - ".mysqli_error($link));
} else {
    echo "Tabel <b>'mahasiswa'</b> berhasil dibuat ... <br>";
}

// membuat query untuk INSERT data ke tabel mahasiswa
$query  = "INSERT INTO mahasiswa VALUES ";
$query .= "('14005011', 'Riana Putri', 'Padang', '1996-11-23', 'FMIPA', 'Kimia', 3.1), ";
$query .= "('15021044', 'Rudi Permana', 'Bandung', '1994-08-22', 'FASILKOM', 'Ilmu Komputer', 2.9), ";
$query .= "('15003036', 'Sari Citra Lestari', 'Jakarta', '1997-12-31', 'Ekonomi', 'Manajemen', 3.5), ";
$query .= "('15002032', 'Rina Kumala Sari', 'Jakarta', '1997-06-28', 'Ekonomi', 'Akutansi', 3.4), ";
$query .= "('13012021', 'James Situmorang', 'Medan', '1995-04-02', 'Kedokteran', 'Kedokteran Gigi', 2.7)";

$hasil_query = mysqli_query($link, $query);

if (!$hasil_query) {
    die ("Querry error: ".mysqli_errno($link)." - ".mysqli_error($link));
} else {
    echo "Tabel <b>'mahasiswa'</b> berhasil diisi ... <br>";
}

// cek apakah tabel admin sudah ada, jika ada, hapus tabel

$query       = "DROP TABLE IF EXISTS admin";
$hasil_query = mysqli_query($link, $query);

if (!$hasil_query) {
    die ("Query error: ".mysqli_errno($link)." - ".mysqli_error($link));
} else {
    echo "Tabel <b>'admin'</b> berhasil dihapus ... <br>";
}

// buat query untuk CREATE TABLE admin

$query       = "CREATE TABLE admin (username VARCHAR(50), password CHAR(40))";
$hasil_query = mysqli_query($link, $query);

if (!$hasil_query) {
    die ("Query error: ".mysqli_errno($link)." - ".mysqli_error($link));
} else {
    echo "Tabel <b>'admin'</b> berhasil dibuat ... <br>";
}

// buat data username dan password untuk admin

$username = "admin123";
$password = sha1("rahasia");

// buat query untuk INSERT data ke tabel admin

$query       = "INSERT INTO admin VALUES ('$username', '$password')";
$hasil_query = mysqli_query($link, $query);

if (!$hasil_query) {
    die ("Query error: ".mysqli_errno($link)." - ".mysqli_error($link));
} else {
    echo "Tabel <b>'admin'</b> berhasil diisi ... <br>";
}