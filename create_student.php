<?php
// periksa apakah user sudah login, cek kehadiran session name
// jika tidak ada, redirect ke login.php
session_start();
if (!isset($_SESSION["nama"])) {
  header("Location: login.php");
}

// buka koneksi dengan MySQL
include("connection.php");

// cek apakah form telah di submit
if (isset($_POST["submit"])) {
  // form telah disubmit, proses data

  // ambil semua nilai form
  $nim          = htmlentities(strip_tags(trim($_POST["nim"])));
  $nama         = htmlentities(strip_tags(trim($_POST["nama"])));
  $tempat_lahir = htmlentities(strip_tags(trim($_POST["tempat_lahir"])));
  $fakultas     = htmlentities(strip_tags(trim($_POST["fakultas"])));
  $jurusan      = htmlentities(strip_tags(trim($_POST["jurusan"])));
  $ipk          = htmlentities(strip_tags(trim($_POST["ipk"])));
  $tgl          = htmlentities(strip_tags(trim($_POST["tgl"])));
  $bln          = htmlentities(strip_tags(trim($_POST["bln"])));
  $thn          = htmlentities(strip_tags(trim($_POST["thn"])));


  // siapkan variabel untuk menampung pesan error
  $pesan_error = "";

  // cek apakah "nim" sudah diisi atau tidak
  if (empty($nim)) {
    $pesan_error .= "NIM belum diisi <br>";
  }
  // NIM harus angka dengan 8 digit
  elseif (!preg_match("/^[0-9]{8}$/", $nim)) {
    $pesan_error .= "NIM harus berupa 8 digit angka <br>";
  }

  // cek ke database, apakah sudah ada nomor NIM yang sama
  // filter data $nim
  $nim = mysqli_real_escape_string($link, $nim);
  $query = "SELECT * FROM mahasiswa WHERE nim='$nim'";
  $hasil_query = mysqli_query($link, $query);

  // cek jumlah record (baris), jika ada, $nim tidak bisa diproses
  $jumlah_data = mysqli_num_rows($hasil_query);
  if ($jumlah_data >= 1) {
    $pesan_error .= "NIM yang sama sudah digunakan <br>";
  }

  // cek apakah "nama" sudah diisi atau tidak
  if (empty($nama)) {
    $pesan_error .= "Nama belum diisi <br>";
  }

  // cek apakah "tempat lahir" sudah diisi atau tidak
  if (empty($tempat_lahir)) {
    $pesan_error .= "Tempat lahir belum diisi <br>";
  }

  // cek apakah "jurusan" sudah diisi atau tidak
  if (empty($jurusan)) {
    $pesan_error .= "Jurusan belum diisi <br>";
  }

  // siapkan variabel untuk menggenerate pilihan fakultas
  $select_kedokteran = "";
  $select_fmipa = "";
  $select_ekonomi = "";
  $select_teknik = "";
  $select_sastra = "";
  $select_fasilkom = "";

  switch ($fakultas) {
    case "Kedokteran":
      $select_kedokteran = "selected";
      break;
    case "FMIPA":
      $select_fmipa      = "selected";
      break;
    case "Ekonomi":
      $select_ekonomi    = "selected";
      break;
    case "Teknik":
      $select_teknik     = "selected";
      break;
    case "Sastra":
      $select_sastra     = "selected";
      break;
    case "FASILKOM":
      $select_mysql      = "selected";
      break;
  }


  // IPK harus berupa angka dan tidak boleh negatif
  if (!is_numeric($ipk) or ($ipk <= 0)) {
    $pesan_error .= "IPK harus diisi dengan angka";
  }

  // jika tidak ada error, input ke database
  if ($pesan_error === "") {

    // filter semua data
    $nim          = mysqli_real_escape_string($link, $nim);
    $nama         = mysqli_real_escape_string($link, $nama);
    $tempat_lahir = mysqli_real_escape_string($link, $tempat_lahir);
    $fakultas     = mysqli_real_escape_string($link, $fakultas);
    $jurusan      = mysqli_real_escape_string($link, $jurusan);
    $tgl          = mysqli_real_escape_string($link, $tgl);
    $bln          = mysqli_real_escape_string($link, $bln);
    $thn          = mysqli_real_escape_string($link, $thn);
    $ipk          = (float) $ipk;

    //gabungkan format tanggal agar sesuai dengan date MySQL
    $tgl_lhr = $thn . "-" . $bln . "-" . $tgl;

    //buat dan jalankan query INSERT
    $query = "INSERT INTO mahasiswa VALUES ";
    $query .= "('$nim', '$nama', '$tempat_lahir', ";
    $query .= "'$tgl_lhr','$fakultas','$jurusan',$ipk)";

    $result = mysqli_query($link, $query);

    //periksa hasil query
    if ($result) {
      // INSERT berhasil, redirect ke tampil_mahasiswa.php + pesan
      $pesan = "Mahasiswa dengan nama = \"<b>$nama</b>\" sudah berhasil di tambah";
      $pesan = urlencode($pesan);
      header("Location: read_student.php?pesan={$pesan}");
    } else {
      die("Query gagal dijalankan: " . mysqli_errno($link) .
        " - " . mysqli_error($link));
    }
  }
} else {
  // form belum disubmit atau halaman ini tampil untuk pertama kali
  // berikan nilai awal untuk semua isian form
  $pesan_error       = "";
  $nim               = "";
  $nama              = "";
  $tempat_lahir      = "";
  $select_kedokteran = "selected";
  $select_fmipa = "";
  $select_ekonomi = "";
  $select_teknik = "";
  $select_sastra = "";
  $select_fasilkom = "";
  $jurusan = "";
  $ipk = "";
  $tgl = 1;
  $bln = "1";
  $thn = 1996;
}

// siapkan array untuk nama bulan
$arr_bln = array(
  "1" => "Januari",
  "2" => "Februari",
  "3" => "Maret",
  "4" => "April",
  "5" => "Mei",
  "6" => "Juni",
  "7" => "Juli",
  "8" => "Agustus",
  "9" => "September",
  "10" => "Oktober",
  "11" => "Nopember",
  "12" => "Desember"
);
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Sistem Informasi Mahasiswa</title>
  <link href="css/bootstrap.css" rel="stylesheet">
  <link rel="icon" href="favicon.png" type="image/png">
</head>

<body>
  <div class="container">
    <div id="header" class="mt-4 d-flex justify-content-between align-items-center">
      <h1 id="logo">Sistem Informasi <span>Kampusku</span></h1>
      <p id="tanggal"><?php echo date("d M Y"); ?></p>
    </div>
    <hr>
    <nav class="d-flex justify-content-center">
      <ul class="nav">
        <li class="nav-item px-2"><a class="btn btn-primary nav-link text-white" href="read_student.php">Tampil</a></li>
        <li class="nav-item px-2"><a class="btn btn-primary nav-link text-white" href="create_student.php">Tambah</a></li>
        <li class="nav-item px-2"><a class="btn btn-primary nav-link text-white" href="update_student.php">Edit</a></li>
        <li class="nav-item px-2"><a class="btn btn-primary nav-link text-white" href="delete_student.php">Hapus</a></li>
        <li class="nav-item px-2"><a class="btn btn-primary nav-link text-white" href="logout.php">Logout</a></li>
      </ul>
    </nav>
    <hr>
    <h2 class="text-center">Tambah Data Mahasiswa</h2>
    <?php
    // tampilkan error jika ada
    if ($pesan_error !== "") {
      echo "<div class=\"alert alert-danger\">$pesan_error</div>";
    }
    ?>
    <form id="form_mahasiswa" action="create_student.php" method="post">
      <p class="mb-1">
        <label for="nim" class="form-label">NIM : </label>
      <div class="d-flex align-items-center">
        <input type="text" name="nim" id="nim" value="<?php echo $nim ?>" placeholder="Contoh: 12345678" class="form-control me-2">
        <span class="col-auto text-end text-muted">( 8 digit angka )</span>
      </div>
      </p>
      <p>
        <label for="nama" class="form-label">Nama : </label>
        <input type="text" name="nama" id="nama" value="<?php echo $nama ?>" class="form-control">
      </p>
      <p>
        <label for="tempat_lahir" class="form-label">Tempat Lahir : </label>
        <input type="text" name="tempat_lahir" id="tempat_lahir" value="<?php echo $tempat_lahir ?>" class="form-control">
      </p>
      <p class="mb-1">
        <label for="tgl" class="form-label">Tanggal Lahir :</label>
      <div class="d-flex">
        <select name="tgl" id="tgl" class="form-select me-2">
          <?php
          for ($i = 1; $i <= 31; $i++) {
            if ($i == $tgl) {
              echo "<option value = $i selected>";
            } else {
              echo "<option value = $i >";
            }
            echo str_pad($i, 2, "0", STR_PAD_LEFT);
            echo "</option>";
          }
          ?>
        </select>
        <select name="bln" class="form-select me-2">
          <?php
          foreach ($arr_bln as $key => $value) {
            if ($key == $bln) {
              echo "<option value=\"{$key}\" selected>{$value}</option>";
            } else {
              echo "<option value=\"{$key}\">{$value}</option>";
            }
          }
          ?>
        </select>
        <select name="thn" class="form-select">
          <?php
          for ($i = 1990; $i <= 2005; $i++) {
            if ($i == $thn) {
              echo "<option value = $i selected>";
            } else {
              echo "<option value = $i >";
            }
            echo "$i </option>";
          }
          ?>
        </select>
      </div>
      </p>
      <p>
        <label for="fakultas" class="form-label">Fakultas : </label>
        <select name="fakultas" id="fakultas" class="form-select">
          <option value="Kedokteran" <?php echo $select_kedokteran ?>>
            Kedokteran </option>
          <option value="FMIPA" <?php echo $select_fmipa ?>>
            FMIPA</option>
          <option value="Ekonomi" <?php echo $select_ekonomi ?>>
            Ekonomi</option>
          <option value="Teknik" <?php echo $select_teknik ?>>
            Teknik</option>
          <option value="Sastra" <?php echo $select_sastra ?>>
            Sastra</option>
          <option value="FASILKOM" <?php echo $select_fasilkom ?>>
            FASILKOM</option>
        </select>
      </p>
      <p>
        <label for="jurusan" class="form-label">Jurusan : </label>
        <input type="text" name="jurusan" id="jurusan" value="<?php echo $jurusan ?>" class="form-control">
      </p>
      <p class="mb-1">
        <label for="ipk" class="form-label">IPK : </label>
      <div class="d-flex align-items-center">
        <input type="text" name="ipk" id="ipk" value="<?php echo $ipk ?>" placeholder="Contoh: 2.75" class="form-control me-2">
        <span class="col-auto text-end text-muted">(angka desimal dipisah dengan karakter titik ".")</span>
      </div>
      </p>
      <p class="text-center">
        <input type="submit" name="submit" value="Tambah Data" class="btn btn-outline-primary w-100 my-2">
      </p>
    </form>
    <hr>
    <div id="footer">
      <p class="text-center">
        KampusQ &copy; <?php echo date("Y"); ?>
      </p>
    </div>

  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
// tutup koneksi dengan database mysql
mysqli_close($link);
?>