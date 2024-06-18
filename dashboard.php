<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard - Management Stok</title>
  <!-- Bootstrap CSS -->
  <!-- Include Chart.js library -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
  @keyframes fadeIn {
    from {
      opacity: 0;
    }

    to {
      opacity: 1;
    }
  }

  .animated-card {
    animation: fadeIn 1s ease-in-out;
  }

  /* Global styles */
  body {
    font-family: Arial, sans-serif;
    margin: 0;
  }

  /* Sidebar styles */
  .sidebar {
    height: 100%;
    width: 250px;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #2e3539;
    padding-top: 20px;
    transition: width 0.3s ease;
    /* Smooth transition for sidebar width */
  }

  .sidebar h2 {
    color: #fff;
    text-align: center;
    margin-bottom: 30px;
  }

  .sidebar ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
  }

  .sidebar li {
    padding: 10px;
    transition: background-color 0.3s ease;
    /* Smooth transition for background color change */
  }

  .sidebar a {
    color: #fff;
    text-decoration: none;
    display: block;
    /* Ensures the entire li area is clickable */
    padding: 10px;
  }

  .sidebar li.active a {
    background-color: #1a73e8;
    border-radius: 10px;
    /* Active background color */
  }

  .sidebar li.active a {
    font-weight: bold;
    /* Example: Highlight active link with bold text */
  }

  .sidebar a:hover {
    background-color: #1a73e8;
    border-radius: 10px;
    /* Hover background color */
  }

  /* Content styles */
  .content {
    margin-left: 250px;
    padding: 20px;
    transition: margin-left 0.3s ease;
    /* Smooth transition for content margin adjustment */
  }

  .icon-large {
    font-size: 3em;
    /* Atur ukuran sesuai kebutuhan Anda */
  }

  .card {
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
  }

  .card-hover:hover {
    transform: scale(1.05);
  }

  .card-body {
    padding: 20px;
  }

  .icon-large {
    font-size: 3em;
    color: #007bff;
    /* Ubah warna sesuai kebutuhan Anda */
  }

  .card-title {
    margin: 0;
    font-size: 1.5em;
    font-weight: bold;
  }

  .card-text {
    font-size: 1.2em;
    color: #555;
  }

  .text-green {
    color: green;
    font-weight: bold;
  }
</style>

<body>
  <?php
  require_once('config/connection.php');
  require_once('config/helper.php');
  require_once('config/services.php');

  $current_page = basename($_SERVER['REQUEST_URI']);

  $resultUser = displayDataManajemenPengguna();
  if ($resultUser instanceof mysqli_result) {
    $userCount = $resultUser->num_rows;
  } else {
    // Jika $resultUser bukan objek mysqli_result, set nilai default
    $userCount = 0;
  }

  $resultBarang = displayDataBarang();
  if ($resultBarang instanceof mysqli_result) {
    $userCountBarang = $resultBarang->num_rows;
  } else {
    // Jika $resultUser bukan objek mysqli_result, set nilai default
    $userCountBarang = 0;
  }

  $resultTipeBarang = displayDataTipeBarang();
  if ($resultTipeBarang instanceof mysqli_result) {
    $userCountTipeBarang = $resultTipeBarang->num_rows;
  } else {
    // Jika $resultUser bukan objek mysqli_result, set nilai default
    $userCountTipeBarang = 0;
  }

  $resultKuantitas = displayTotalKuantitas();
  $resultOmset = displayTotalOmset();
  $resultBarangDiJual = displayTotalBarangTerjual();

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Clear filter jika parameter clear=true diberikan
    if (isset($_GET["clear"]) && $_GET["clear"] == "true") {
      unset($_POST["filterDate"]); // Hapus filterDate dari POST data
      unset($_GET["clear"]); // Hapus parameter clear dari URL
      // Redirect kembali ke halaman ini (opsional, tergantung kebutuhan)
      header("Location: " . $_SERVER['PHP_SELF']);
      exit; // Pastikan keluar dari skrip setelah melakukan redirect
    }

    // Proses filter jika filterDate ada dalam POST dan tidak kosong
    if (isset($_POST["filterDate"]) && !empty($_POST["filterDate"])) {
      $filterDate = $_POST["filterDate"];

      $filteredResults = []; // Array untuk menyimpan hasil yang difilter

      // Loop melalui hasil dari fungsi displayDataStokGudang()
      while ($row = mysqli_fetch_assoc($result)) {
        // Konversi format tanggal dari database
        $tanggalStok = date("Y-m-d", strtotime($row['updated_at']));

        // Filter data berdasarkan tanggal yang sesuai
        if ($tanggalStok == $filterDate) {
          $filteredResults[] = $row;
        }
      }

      // Ganti $result dengan hasil yang telah difilter
      $result = $filteredResults;
    }
  }
  ?>
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="d-flex justify-content-center mb-4">
      <img src="./assets/Logo-Bengkel.png" width="200" alt="logo-bengkel" />
    </div>
    <ul>
      <li class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>"><a href="dashboard.php">Dashboard</a></li>
      <li class="<?php echo $current_page == 'stok-gudang.php' ? 'active' : ''; ?>"><a href="stok-gudang.php">Stok Gudang</a></li>
      <li class="<?php echo $current_page == 'pengelolaan-data.php' ? 'active' : ''; ?>"><a href="pengelolaan-data.php">Pengelolaan Data</a></li>
      <li class="<?php echo $current_page == 'transaksi.php' ? 'active' : ''; ?>"><a href="transaksi.php">Catatan Transaksi</a></li>
      <li class="<?php echo $current_page == 'user.php' ? 'active' : ''; ?>"><a href="user.php">Manajemen Pengguna</a></li>
      <li class="<?php echo $current_page == 'notification.php' ? 'active' : ''; ?>"><a href="notification.php">Aktivitas Pengguna</a></li>
    </ul>
  </div>

  <div class="content">
    <nav class="navbar navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand"><i class="bi bi-arrow-bar-left me-2"></i>Dashboard</a>
        <form class="d-flex">
          <a href="login.php"><button class="btn btn-outline-primary" type="button">
              <i class="bi bi-box-arrow-left me-2"></i>
              Logout
            </button></a>
        </form>
      </div>
    </nav>
    <!-- content -->
    <div class="d-flex justify-content-end mb-3" style="margin-top: 2%;">
      <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="d-flex">
        <div class="input-group">
          <input type="date" class="form-control me-2 mb-2" id="filterDate" name="filterDate" style="width: 30%;" value="<?php echo isset($_POST['filterDate']) ? htmlspecialchars($_POST['filterDate']) : ''; ?>">
          <button type="submit" class="btn btn-success mb-2">Filte Data</button>
          <?php if (isset($_POST['filterDate']) && !empty($_POST['filterDate'])) : ?>
            <a href="?clear=true" class="btn btn-secondary mb-2 ms-2">Clear Filter</a>
          <?php endif; ?>
        </div>
      </form>
    </div>

    <div class="row mt-4 animated-card">
      <div class="col-12 col-sm-6 col-md-4">
        <div class="card card-hover text-center">
          <div class="card-body">
            <h5 class="card-title"><i class="fas fa-chart-line icon-large"></i></h5>
            <h5 class="card-title mt-3">Omset</h5>
            <p class="card-text mt-2 text-green"><?php echo formatRupiah($resultOmset) ?></p>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4">
        <div class="card card-hover text-center">
          <div class="card-body">
            <h5 class="card-title"><i class="fas fa-box icon-large"></i></h5>
            <h5 class="card-title mt-3">Total</h5>
            <p class="card-text mt-2"><?php echo $resultBarangDiJual ?> Barang Terjual</p>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4">
        <div class="card card-hover text-center">
          <div class="card-body">
            <h5 class="card-title"><i class="fas fa-cubes icon-large"></i></h5>
            <h5 class="card-title mt-3">Total</h5>
            <p class="card-text mt-2"><?php echo $userCountBarang ?> Barang</p>
          </div>
        </div>
      </div>

    </div>
    <div class="row mt-4 animated-card">
      <div class="col-12 col-sm-6 col-md-4">
        <div class="card card-hover text-center">
          <div class="card-body">
            <h5 class="card-title"><i class="fas fa-tags icon-large"></i></h5>
            <h5 class="card-title mt-3">Total</h5>
            <p class="card-text mt-2"><?php echo $userCountTipeBarang ?> Tipe Barang</p>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4">
        <div class="card card-hover text-center">
          <div class="card-body">
            <h5 class="card-title"><i class="fas fa-boxes icon-large"></i></h5>
            <h5 class="card-title mt-3">Total</h5>
            <p class="card-text mt-2"><?php echo $resultKuantitas ?> Kuantitas Stok Barang</p>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-4">
        <div class="card card-hover text-center">
          <div class="card-body">
            <h5 class="card-title"><i class="fas fa-user icon-large"></i></h5>
            <h5 class="card-title mt-3">Total</h5>
            <p class="card-text mt-2"><?php echo $userCount ?> Pengguna</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous" />
  <script>
    // JavaScript code to handle button click
    function toLogin() {
      window.location.href = "/login.php";
    }
  </script>
</body>

</html>