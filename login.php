<?php
session_start();

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    // Process the form data

    // Retrieve form values
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Initialize error message
    $pesan_error = "";

    // Check if "username" is empty
    if (empty($username)) {
        $pesan_error .= "Username belum di-isi! <br>";
    }

    // Check if "password" is empty
    if (empty($password)) {
        $pesan_error .= "Password belum di-isi! <br>";
    }

    // Include the connection file
    include("connection.php");

    // Escape special characters to prevent SQL injection
    $username = mysqli_real_escape_string($link, $username);
    $password = mysqli_real_escape_string($link, $password);

    // Generate password hash
    $password_sha1 = sha1($password);

    // Check if username and password exist in the admin table
    if (!empty($username) && !empty($password)) {
        $query  = "SELECT * FROM admin WHERE username='$username' AND password='$password_sha1'";
        $result = mysqli_query($link, $query);

        if (mysqli_num_rows($result) == 0) {
            // Data not found, create an error message
            $pesan_error .= "Username dan/atau Password tidak sesuai!";
        }

        // Free memory
        mysqli_free_result($result);
    }

    // Close the MySQL database connection
    mysqli_close($link);

    // If validation passes, set session
    if ($pesan_error === "") {
        $_SESSION["nama"] = $username;
        header("Location: read_student.php");
        exit();
    }
} else {
    // Form has not been submitted or this is the first time the page is displayed
    // Set initial values for all form fields
    $username = "";
    $password = "";
    $pesan_error = "";
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sistem Informasi Mahasiswa</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="style.css">

</head>

<body class="text-center">
    <main class="form-signin w-100 m-auto">
        <form action="login.php" method="POST">
            <img class="mb-4" src="favicon.png" alt="" width="72" height="72">
            <h1 class="h3 mb-3 fw-normal">Sistem Informasi Mahasiswa</h1>
            <?php
            // Display error message if any
            if ($pesan_error !== "") {
                echo '<div class="alert alert-danger" role="alert">' . $pesan_error . '</div>';
            }
            ?>
            <div class="form-floating">
                <input type="text" class="form-control" name="username" id="floatingInput" placeholder="Username" value="<?php echo $username ?>">
                <label for="floatingInput">Username</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password" value="<?php echo $password ?>">
                <label for="floatingPassword">Password</label>
            </div>

            <input class="w-100 btn btn-lg btn-primary" type="submit" name="submit" value="Log in">
            <p class="mt-3 mb-3 text-muted">KampusQ &copy; 2024</p>
        </form>
    </main>


    <script src="js/bootstrap.bundle.js"></script>
</body>

</html>