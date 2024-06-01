<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "kampusku";

$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// cek koneksi, tampilkan error message jika invalid

if (!$link) {
    die ("Error while connecting to database: ".mysqli_connect_errno().
         " - ".mysqli_connect_error());
}