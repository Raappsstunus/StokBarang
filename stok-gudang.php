<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Stok Gudang - Management Stok</title>
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

  $newIdStok = getNewId($conn, 'stok_gudang');
  $createdAt = getCurrentTimestamp();

  // Proses tambah
  if (isset($_POST["prosesTambah"])) {
    try {
      // Convert formatted price strings to numbers
      $harga = (int)str_replace('.', '', str_replace('Rp ', '', $_POST["hargaBarang"]));
      $total = (int)str_replace('.', '', str_replace('Rp ', '', $_POST["totalHarga"]));

      // Call the function to add data
      tambahDataStokGudang(
        $_POST["idStok"],
        $_POST["kodeTipe"],
        $_POST["idSatuan"],
        $_POST["pilihBarang"],
        $_POST["kuantitas"],
        $harga,
        $total,
        $_POST["createdAt"],
        $_POST["updatedAt"]
      );

      header("Location: " . $_SERVER['PHP_SELF']);
      exit;
    } catch (Exception $e) {
      // Handle the exception
      echo "Error: " . $e->getMessage();
    }
  }

  // Proses edit
  if (isset($_POST["prosesEdit"])) {
    try {
      // Convert formatted price strings to numbers
      $harga = (int)str_replace('.', '', str_replace('Rp ', '', $_POST["hargaBarangEdit"]));
      $total = (int)str_replace('.', '', str_replace('Rp ', '', $_POST["totalHargaEdit"]));

      updateDataStokGudang(
        $_POST["idStokEdit"],
        $_POST["kodeTipeEdit"],
        $_POST["idSatuanEdit"],
        $_POST["pilihBarangEdit"],
        $_POST["kuantitasEdit"],
        $harga,
        $total,
        $_POST["createdAtEdit"],
        $_POST["updatedAtEdit"]
      );
      header("Location: " . $_SERVER['PHP_SELF']);
      exit;
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
    }
  }

  // Proses delete
  if (isset($_POST["prosesDelete"])) {
    try {
      deleteDataStokGudang($_POST["idStokDeleteInput"]);
      header("Location: " . $_SERVER['PHP_SELF']);
      exit;
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
    }
  }

  $result = displayDataStokGudang();
  $resultBarang = displayDataBarang();

  // Filter hasil berdasarkan tanggal jika filterDate ada dalam POST
  // Cek jika method request adalah POST
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
        <a class="navbar-brand"><i class="bi bi-arrow-bar-left me-2"></i>Stok Gudang</a>
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

    <div class="card pt-2 px-3 bg-primary">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h4 class="text-white">Stok Gudang</h4>
        </div>
        <div class="d-flex align-items-center">
          <button type="button" class="btn btn-outline-light mb-2" data-bs-toggle="modal" data-bs-target="#modalCreateStok">
            Tambah Stok
          </button>
        </div>
      </div>
    </div>
    <table class="table table-striped table-hover mt-2 animated-card">
      <thead>
        <tr>
          <?php
          $headerGudang = ["No", "Kode Barang", "Nama Barang", "Satuan", "Tipe", "Total Kuantitas", "Harga Barang", "Total", "Tanggal", "Aksi"];
          for ($i = 0; $i < count($headerGudang); $i++) {
            echo "<th scope='col'>";
            echo $headerGudang[$i];
            echo "</th>";
          }
          ?>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows < 1) : ?>
          <tr>
            <td colspan="<?php echo count($headerGudang); ?>" class="text-center">Tidak ada data tersedia</td>
          </tr>
        <?php else : ?>
          <?php $no = 0; ?>
          <?php foreach ($result as $row) : ?>
            <?php $no++; ?>
            <tr>
              <td><?php echo $no; ?></td>
              <td><?php echo $row["kode_barang"]; ?></td>
              <td><?php echo $row["nama_barang"]; ?></td>
              <td><?php echo $row["nama_satuan"]; ?></td>
              <td><?php echo $row["nama_tipe"]; ?></td>
              <td><?php echo $row["total_kuantitas"]; ?></td>
              <td><?php echo formatRupiah($row["harga_barang"]); ?></td>
              <td><?php echo formatRupiah($row["total_harga"]); ?></td>
              <td><?php echo formatDate($row["updated_at"]); ?></td>
              <td>
                <a class='btn btn-primary btn-sm me-2 edit-button' data-bs-toggle='modal' data-bs-target='#modalEditStok' onclick='populateModalEditStok("<?php echo $row["id_stok"]; ?>", "<?php echo $row["kode_barang"]; ?>", "<?php echo $row["id_satuan"]; ?>", "<?php echo $row["kode_tipe"]; ?>", "<?php echo $row["total_kuantitas"]; ?>", "<?php echo $row["harga_barang"]; ?>", "<?php echo $row["total_harga"]; ?>", "<?php echo $row["updated_at"]; ?>")'><i class='bi bi-pencil'></i></a>
                <a class='btn btn-danger btn-sm me-2' data-bs-toggle='modal' data-bs-target='#deleteModalStok' onclick='populateDeleteModalStok("<?php echo $row["id_stok"]; ?>")'><i class='bi bi-trash'></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- modal create -->
  <form action="" method="post">
    <div class="modal fade" id="modalCreateStok" tabindex="-1" aria-labelledby="modalLabelCreateStok" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabelCreateStok">Tambah Barang</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="idStok" name="idStok" value="<?php echo $newIdStok; ?>">
            <input type="hidden" id="kodeTipe" name="kodeTipe">
            <input type="hidden" id="idSatuan" name="idSatuan">
            <input type="hidden" id="createdAt" name="createdAt" value="<?php echo $createdAt; ?>">
            <div class="mb-3">
              <label for="pilihBarang" class="form-label">Pilih Barang<span style="color: red;">*</span></label>
              <select class="form-select" aria-label="Default select example" id="pilihBarang" name="pilihBarang" required>
                <option selected disabled>Masukan Barang</option>
                <?php foreach ($resultBarang as $barang) : ?>
                  <option value="<?php echo htmlspecialchars($barang['kode_barang']); ?>" data-harga="<?php echo htmlspecialchars($barang['harga_barang']); ?>" data-kode-tipe="<?php echo htmlspecialchars($barang['kode_tipe']); ?>" data-id-satuan="<?php echo htmlspecialchars($barang['id_satuan']); ?>">
                    <?php echo htmlspecialchars($barang['nama_barang']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="kuantitas" class="form-label">Kuantitas<span style="color: red;">*</span></label>
              <input type="number" class="form-control" id="kuantitas" name="kuantitas" value="1" placeholder="Masukkan Kuantitas" required>
            </div>
            <div class="mb-3">
              <label for="updatedAt" class="form-label">Tanggal Stok<span style="color: red;">*</span></label>
              <input type="date" class="form-control" id="updatedAt" name="updatedAt" required>
            </div>
            <div class="mb-3">
              <label for="hargaBarang" class="form-label">Harga<span style="color: red;">*</span></label>
              <input type="text" class="form-control" id="hargaBarang" name="hargaBarang" placeholder="Pilih barang untuk menampilkan harga" readonly>
            </div>
            <div class="mb-3">
              <label for="totalHarga" class="form-label">Total Harga<span style="color: red;">*</span></label>
              <input type="text" class="form-control" id="totalHarga" name="totalHarga" placeholder="Isi kuantitas untuk menampilkan total" readonly>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keluar</button>
            <button type="submit" class="btn btn-primary" name="prosesTambah">Simpan</button>
          </div>
        </div>
      </div>
    </div>
  </form>



  <!-- modal edit -->
  <div class="modal fade" id="modalEditStok" tabindex="-1" aria-labelledby="modalLabelEditStok" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabelEditStok">Edit Barang</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editForm" method="post" action="">
            <input type="hidden" id="idStokEdit" name="idStokEdit">
            <input type="hidden" id="kodeTipeEdit" name="kodeTipeEdit">
            <input type="hidden" id="idSatuanEdit" name="idSatuanEdit">
            <input type="hidden" id="createdAtEdit" name="createdAtEdit" value="<?php echo $createdAt; ?>">
            <div class="mb-3">
              <label for="pilihBarangEdit" class="form-label">Pilih Barang<span style="color: red;">*</span></label>
              <select class="form-select" aria-label="Default select example" id="pilihBarangEdit" name="pilihBarangEdit" required>
                <option selected disabled>Masukan Barang</option>
                <?php foreach ($resultBarang as $barang) : ?>
                  <option value="<?php echo htmlspecialchars($barang['kode_barang']); ?>" data-harga-edit="<?php echo htmlspecialchars($barang['harga_barang']); ?>" data-kode-tipe-edit="<?php echo htmlspecialchars($barang['kode_tipe']); ?>" data-id-satuan-edit="<?php echo htmlspecialchars($barang['id_satuan']); ?>">
                    <?php echo htmlspecialchars($barang['nama_barang']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="kuantitasEdit" class="form-label">Kuantitas<span style="color: red;">*</span></label>
              <input type="number" class="form-control" id="kuantitasEdit" name="kuantitasEdit" value="1" placeholder="Masukkan Kuantitas" required>
            </div>
            <div class="mb-3">
              <label for="updatedAtEdit" class="form-label">Tanggal Stok<span style="color: red;">*</span></label>
              <input type="date" class="form-control" id="updatedAtEdit" name="updatedAtEdit" required>
            </div>
            <div class="mb-3">
              <label for="hargaBarangEdit" class="form-label">Harga<span style="color: red;">*</span></label>
              <input type="text" class="form-control" id="hargaBarangEdit" name="hargaBarangEdit" placeholder="Pilih barang untuk menampilkan harga" readonly>
            </div>
            <div class="mb-3">
              <label for="totalHargaEdit" class="form-label">Total Harga<span style="color: red;">*</span></label>
              <input type="text" class="form-control" id="totalHargaEdit" name="totalHargaEdit" placeholder="Isi kuantitas untuk menampilkan total" readonly>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keluar</button>
              <button type="submit" class="btn btn-primary" name="prosesEdit">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal delete-->
  <div class="modal fade" id="deleteModalStok" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="deleteForm" method="post" action="">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Apakah Anda yakin ingin menghapus data no <span id="idStokDelete"></span>?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
            <button type="submit" class="btn btn-danger" name="prosesDelete">Hapus</button>
            <input type="hidden" name="idStokDeleteInput" id="idStokDeleteInput">
          </div>
        </form>
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

    function formatRupiah(amount) {
      return 'Rp ' + amount.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    }

    function populateModalEditStok(idStok, kodeBarang, idSatuan, kodeTipe, kuantitas, hargaBarang, totalHarga, updatedAt) {
      document.getElementById('idStokEdit').value = idStok;
      document.getElementById('pilihBarangEdit').value = kodeBarang;
      document.getElementById('idSatuanEdit').value = idSatuan;
      document.getElementById('kodeTipeEdit').value = kodeTipe;
      document.getElementById('kuantitasEdit').value = kuantitas;
      document.getElementById('hargaBarangEdit').value = formatRupiah(parseFloat(hargaBarang));
      document.getElementById('totalHargaEdit').value = formatRupiah(parseFloat(totalHarga));
      document.getElementById('updatedAtEdit').value = updatedAt;
    }

    function populateDeleteModalStok(idStok) {
      document.getElementById('idStokDelete').innerText = idStok;
      document.getElementById('idStokDeleteInput').value = idStok;
    }

    document.addEventListener('DOMContentLoaded', function() {
      const pilihBarang = document.getElementById('pilihBarang');
      const kuantitas = document.getElementById('kuantitas');
      const hargaBarang = document.getElementById('hargaBarang');
      const totalHarga = document.getElementById('totalHarga');
      const kodeTipe = document.getElementById('kodeTipe');
      const idSatuan = document.getElementById('idSatuan');

      // Function to format number as Rupiah with dots as thousand separators
      function formatRupiah(amount) {
        return 'Rp ' + amount.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
      }

      // Update harga and hidden fields when a barang is selected
      pilihBarang.addEventListener('change', function() {
        const selectedOption = pilihBarang.options[pilihBarang.selectedIndex];
        const harga = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
        const kodeTipeValue = selectedOption.getAttribute('data-kode-tipe');
        const idSatuanValue = selectedOption.getAttribute('data-id-satuan');

        hargaBarang.value = formatRupiah(harga);
        kodeTipe.value = kodeTipeValue;
        idSatuan.value = idSatuanValue;
        kuantitas.value = 1; // Set default quantity to 1
        updateTotalHarga();
      });

      // Update total harga when kuantitas changes
      kuantitas.addEventListener('input', updateTotalHarga);

      function updateTotalHarga() {
        const hargaValue = hargaBarang.value.replace(/[^0-9]/g, '');
        const harga = parseFloat(hargaValue) || 0;
        const qty = parseInt(kuantitas.value) || 0;
        const total = harga * qty;

        totalHarga.value = formatRupiah(total);
      }

      // Initialize total harga on page load if there's a pre-selected barang
      if (pilihBarang.value) {
        const selectedOption = pilihBarang.options[pilihBarang.selectedIndex];
        const harga = parseFloat(selectedOption.getAttribute('data-harga')) || 0;

        hargaBarang.value = formatRupiah(harga);
        kuantitas.value = 1; // Set default quantity to 1
        updateTotalHarga();
      }
    });

    document.addEventListener('DOMContentLoaded', function() {
      const pilihBarang = document.getElementById('pilihBarangEdit');
      const kuantitas = document.getElementById('kuantitasEdit');
      const hargaBarang = document.getElementById('hargaBarangEdit');
      const totalHarga = document.getElementById('totalHargaEdit');
      const kodeTipe = document.getElementById('kodeTipeEdit');
      const idSatuan = document.getElementById('idSatuanEdit');

      // Function to format number as Rupiah with dots as thousand separators
      function formatRupiah(amount) {
        return 'Rp ' + amount.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
      }

      // Update harga and hidden fields when a barang is selected
      pilihBarang.addEventListener('change', function() {
        const selectedOption = pilihBarang.options[pilihBarang.selectedIndex];
        const harga = parseFloat(selectedOption.getAttribute('data-harga-edit')) || 0;
        const kodeTipeValue = selectedOption.getAttribute('data-kode-tipe-edit');
        const idSatuanValue = selectedOption.getAttribute('data-id-satuan-edit');

        hargaBarang.value = formatRupiah(harga);
        kodeTipe.value = kodeTipeValue;
        idSatuan.value = idSatuanValue;
        kuantitas.value = 1; // Set default quantity to 1
        updateTotalHarga();
      });

      // Update total harga when kuantitas changes
      kuantitas.addEventListener('input', updateTotalHarga);

      function updateTotalHarga() {
        const hargaValue = hargaBarang.value.replace(/[^0-9]/g, '');
        const harga = parseFloat(hargaValue) || 0;
        const qty = parseInt(kuantitas.value) || 0;
        const total = harga * qty;

        totalHarga.value = formatRupiah(total);
      }

      // Initialize total harga on page load if there's a pre-selected barang
      if (pilihBarang.value) {
        const selectedOption = pilihBarang.options[pilihBarang.selectedIndex];
        const harga = parseFloat(selectedOption.getAttribute('data-harga-edit')) || 0;

        hargaBarang.value = formatRupiah(harga);
        kuantitas.value = 1; // Set default quantity to 1
        updateTotalHarga();
      }
    });
  </script>
</body>

</html>