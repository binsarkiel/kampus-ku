<?php
session_start();
if (!isset($_SESSION["nama"])) {
  header("Location: login.php");
  exit;
}

include("connection.php");

$pesan = "";

if (isset($_GET["pesan"])) {
  $pesan = $_GET["pesan"];
}

if (isset($_GET["submit"])) {
  $nama = trim($_GET["nama"]);
  $nama = mysqli_real_escape_string($link, $nama);

  $query  = "SELECT * FROM mahasiswa WHERE nama LIKE '%$nama%' ";
  $query .= "ORDER BY nama ASC";

  $pesan = "Hasil pencarian untuk nama <b>\"$nama\"</b>: ";
} else {
  $query = "SELECT * FROM mahasiswa ORDER BY nama ASC";
}
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
    <div class="container d-flex justify-content-between">
      <h2>Data Mahasiswa</h2>
      <form id="search" action="read_student.php" method="get" role="search">
        <p class="mb-0 d-flex">
          <input type="text" name="nama" id="nama" placeholder="Cari nama di sini ..." class="form-control me-2">
          <input type="submit" name="submit" value="Temukan" class="btn btn-outline-primary">
        </p>
      </form>
    </div>
    <?php
    if (!empty($pesan)) {
      echo '<div class="alert alert-primary text-center" role="alert">' . $pesan . '</div>';
    }
    ?>
    <table class="table mt-3">
      <tr>
        <th>NIM</th>
        <th>Nama</th>
        <th>Tempat Lahir</th>
        <th>Tanggal Lahir</th>
        <th>Fakultas</th>
        <th>Jurusan</th>
        <th>IPK</th>
      </tr>
      <tbody class="table-group-divider">
        <?php
        // jalankan query
        $result = mysqli_query($link, $query);

        if (!$result) {
          die("Query Error: " . mysqli_errno($link) .
            " - " . mysqli_error($link));
        }

        //buat perulangan untuk element tabel dari data mahasiswa
        while ($data = mysqli_fetch_assoc($result)) {
          // konversi date MySQL (yyyy-mm-dd) menjadi dd-mm-yyyy
          $tanggal_php = strtotime($data["tanggal_lahir"]);
          $tanggal = date("d - m - Y", $tanggal_php);

          echo "<tr>";
          echo "<td>$data[nim]</td>";
          echo "<td>$data[nama]</td>";
          echo "<td>$data[tempat_lahir]</td>";
          echo "<td>$tanggal</td>";
          echo "<td>$data[fakultas]</td>";
          echo "<td>$data[jurusan]</td>";
          echo "<td>$data[ipk]</td>";
          echo "</tr>";
        }

        // bebaskan memory 
        mysqli_free_result($result);

        // tutup koneksi dengan database mysql
        mysqli_close($link);
        ?>
      </tbody>
    </table>
    <div id="footer">
      <p class="text-center mt-4">
        KampusQ &copy; <?php echo date("Y"); ?>
      </p>
    </div>
  </div>
</body>

</html>