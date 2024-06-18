<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Aktivitas Pengguna - Management Stok</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
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
</style>

<body>
  <?php
  require_once('config/connection.php');
  require_once('config/helper.php');
  require_once('config/services.php');

  $current_page = basename($_SERVER['REQUEST_URI']);

  $result = displayDataAktivitasPengguna();
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
        <a class="navbar-brand"><i class="bi bi-arrow-bar-left me-2"></i>Aktivitas Pengguna</a>
        <form class="d-flex">
          <a href="login.php"><button class="btn btn-outline-primary" type="button">
              <i class="bi bi-box-arrow-left me-2"></i>
              Logout
            </button></a>
        </form>
      </div>
    </nav>
    <!-- content -->
    <div class="card pt-2 px-3 bg-primary" style="margin-top: 3%">
      <div class="d-flex justify-content-between align-items-center">
        <h4 class="text-white">Aktivitas Pengguna</h4>
      </div>
    </div>
    <table class="table table-striped table-hover animated-card mt-4">
      <thead>
        <tr>
          <?php
          $headerAktivitas = ["No", "Nama Pengguna", "Tanggal", "Deskripsi"];
          for ($i = 0; $i < count($headerAktivitas); $i++) {
            echo "<th scope='col'>";
            echo $headerAktivitas[$i];
            echo "</th>";
          }
          ?>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 0;
        if ($result && $result->num_rows > 0) {
          while ($row = mysqli_fetch_array($result)) {
            $no++;
            echo "<tr>";
            echo "<td>" . $no . "</td>";
            echo "<td>" . $row["fullname"] . "</td>";
            echo "<td>" . $row["created_at"] . "</td>";
            echo "<td>" . $row["description"] . "</td>";
            echo "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr class='text-center'><td colspan='6'>Tidak ada data tersedia</td></tr>";
        }
        ?>
      </tbody>
    </table>

  </div>
  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous" />
  <script>
    // JavaScript code to handle button click
    // JavaScript code to handle button click
    function toLogin() {
      window.location.href = "/login.php";
    }
  </script>
</body>

</html>