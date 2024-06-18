<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pengelolaan Data - Management Stok</title>
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

    /* Custom scrollbar for WebKit browsers (Chrome, Safari) */
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: var(--bs-primary);
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: darken(var(--bs-primary), 10%);
    }

    /* Custom scrollbar for Firefox */
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: var(--bs-primary) #f1f1f1;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: var(--bs-primary);
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: darken(var(--bs-primary), 10%);
    }

    .btn-xs {
        padding: 0.20rem 0.4rem;
        font-size: 0.75rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }

    .btn-xs i {
        font-size: 0.75rem;
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

    $newIdSatuan = getNewId($conn, 'satuan');
    $createdAt = getCurrentTimestamp();

    // Proses Tambah
    if (isset($_POST["prosesTambahBarang"])) {
        try {
            // Call the function to add data
            tambahDataBarang($_POST["kodeBarang"], $_POST["namaBarang"], $_POST["pilihTipe"], $_POST["pilihSatuan"], $_POST["hargaBarang"]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (Exception $e) {
            // Handle the exception
            echo "Error: " . $e->getMessage();
        }
    }

    if (isset($_POST["prosesTambahTipeBarang"])) {
        try {
            // Call the function to add data
            tambahDataTipeBarang($_POST["kodeTipe"], $_POST["namaTipe"]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (Exception $e) {
            // Handle the exception
            echo "Error: " . $e->getMessage();
        }
    }

    if (isset($_POST["prosesTambahSatuanBarang"])) {
        try {
            // Call the function to add data
            tambahDataSatuan($_POST["idSatuan"], $_POST["namaSatuan"], $_POST["inisialSatuan"]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (Exception $e) {
            // Handle the exception
            echo "Error: " . $e->getMessage();
        }
    }

    // Proses Edit
    if (isset($_POST["prosesEditBarang"])) {
        try {
            updateDataBarang($_POST["originalKodeBarangEdit"], $_POST["kodeBarangEdit"], $_POST["namaBarangEdit"], $_POST["pilihTipeEdit"], $_POST["pilihSatuanEdit"], $_POST["hargaBarangEdit"]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    if (isset($_POST["prosesEditTipeBarang"])) {
        try {
            updateDataTipeBarang($_POST["originalKodeTipeEdit"], $_POST["kodeTipeEdit"], $_POST["namaTipeEdit"]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    if (isset($_POST["prosesEditSatuanBarang"])) {
        try {
            updateDataSatuan($_POST["idSatuanEdit"], $_POST["namaSatuanEdit"], $_POST["inisialSatuanEdit"]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Proses Delete
    if (isset($_POST["prosesDeleteBarang"])) {
        try {
            deleteDataBarang($_POST["kodeBarangDelete"]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    if (isset($_POST["prosesDeleteTipeBarang"])) {
        try {
            deleteDataTipeBarang($_POST["deleteKodeTipe"]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    if (isset($_POST["prosesDeleteSatuanBarang"])) {
        try {
            deleteDataSatuan($_POST["idSatuanDelete"]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    $resultBarang = displayDataBarang();
    $resultTipeBarang = displayDataTipeBarang();
    $resultSatuanBarang = displayDataSatuan();

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
                <a class="navbar-brand"><i class="bi bi-arrow-bar-left me-2"></i>Pengelolaan Data</a>
                <form class="d-flex">
                    <button class="btn btn-outline-primary" type="button" onclick="toLogin()">
                        <i class="bi bi-box-arrow-left me-2"></i>
                        Logout
                    </button>
                </form>
            </div>
        </nav>
        <div class="row mt-4">
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card animated-card" style="width: 100%;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Daftar Barang</h5>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCreateBarang" data-bs-toggle="tooltip" title="Tambah Barang">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                        <div class="table-responsive custom-scrollbar mt-3" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-striped table-hover mt-4">
                                <thead>
                                    <tr>
                                        <?php
                                        $headerBarang = ["No", "Kode", "Nama", "Harga", "Aksi"];
                                        for ($i = 0; $i < count($headerBarang); $i++) {
                                            echo "<th scope='col' style='font-size: 13px;'>";
                                            echo $headerBarang[$i];
                                            echo "</th>";
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 0;
                                    if ($resultBarang && $resultBarang->num_rows > 0) {
                                        while ($row = mysqli_fetch_array($resultBarang)) {
                                            $no++;
                                            echo "<tr style='font-size: 13px;'>";
                                            echo "<td>" . $no . "</td>";
                                            echo "<td>" . $row["kode_barang"] . "</td>";
                                            echo "<td>" . $row["nama_barang"] . "</td>";
                                            echo "<td>" . formatRupiah($row["harga_barang"]) . "</td>";
                                            echo "<td>";
                                            echo "<a class='btn btn-primary btn-xs mt-1' data-bs-toggle='modal' data-bs-target='#modalEditBarang' onclick='populateModalEditBarang(\"{$row["kode_barang"]}\", \"{$row["nama_barang"]}\", \"{$row["kode_tipe"]}\", \"{$row["id_satuan"]}\", \"{$row["harga_barang"]}\")'><i class='bi bi-pencil'></i></a>";
                                            echo "<a class='btn btn-danger btn-xs mt-1' data-bs-toggle='modal' data-bs-target='#modalDeleteBarang' onclick='populateDeleteModalBarang(\"{$row["kode_barang"]}\", \"{$row["nama_barang"]}\")'><i class='bi bi-trash'></i></a>";
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr class='text-center'><td colspan='6'>No data found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card animated-card" style="width: 100%;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Daftar Tipe</h5>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCreateTipe" data-bs-toggle="tooltip" title="Tambah Tipe Barang">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                        <div class="table-responsive custom-scrollbar mt-3" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-striped table-hover mt-4">
                                <thead>
                                    <tr>
                                        <?php
                                        $headerTipe = ["No", "Kode", "Nama", "Aksi"];
                                        for ($i = 0; $i < count($headerTipe); $i++) {
                                            echo "<th scope='col' style='font-size: 13px;'>";
                                            echo $headerTipe[$i];
                                            echo "</th>";
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 0;
                                    if ($resultTipeBarang && $resultTipeBarang->num_rows > 0) {
                                        while ($row = mysqli_fetch_array($resultTipeBarang)) {
                                            $no++;
                                            echo "<tr style='font-size: 13px;'>";
                                            echo "<td>" . $no . "</td>";
                                            echo "<td>" . $row["kode_tipe"] . "</td>";
                                            echo "<td>" . $row["nama_tipe"] . "</td>";
                                            echo "<td>";
                                            echo "<a class='btn btn-primary btn-xs mt-1 me-1' data-bs-toggle='modal' data-bs-target='#modalEditTipeBarang' onclick='populateModalEditTipeBarang(\"{$row["kode_tipe"]}\", \"{$row["nama_tipe"]}\")'><i class='bi bi-pencil'></i></a>";
                                            echo "<a class='btn btn-danger btn-xs mt-1' data-bs-toggle='modal' data-bs-target='#deleteModalTipeBarang' onclick='populateDeleteModalTipeBarang(\"{$row["kode_tipe"]}\", \"{$row["nama_tipe"]}\")'><i class='bi bi-trash'></i></a>";
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr class='text-center'><td colspan='6'>No data found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card animated-card" style="width: 100%;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Daftar Satuan</h5>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCreateSatuan" data-bs-toggle="tooltip" title="Tambah Satuan Barang">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                        <div class="table-responsive custom-scrollbar mt-3" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-striped table-hover mt-4">
                                <thead>
                                    <tr>
                                        <?php
                                        $headerSatuan = ["No", "Satuan", "Inisial", "Aksi"];
                                        for ($i = 0; $i < count($headerSatuan); $i++) {
                                            echo "<th scope='col' style='font-size: 13px;'>";
                                            echo $headerSatuan[$i];
                                            echo "</th>";
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 0;
                                    if ($resultSatuanBarang && $resultSatuanBarang->num_rows > 0) {
                                        while ($row = mysqli_fetch_array($resultSatuanBarang)) {
                                            $no++;
                                            echo "<tr style='font-size: 13px;'>";
                                            echo "<td>" . $no . "</td>";
                                            echo "<td>" . $row["nama_satuan"] . "</td>";
                                            echo "<td>" . $row["inisial_satuan"] . "</td>";
                                            // Action buttons column
                                            echo "<td>";
                                            echo "<a class='btn btn-primary btn-xs mt-1 me-1' data-bs-toggle='modal' data-bs-target='#editModalSatuanBarang' onclick='populateModalEditSatuan(\"{$row["id_satuan"]}\", \"{$row["nama_satuan"]}\", \"{$row["inisial_satuan"]}\")'><i class='bi bi-pencil'></i></a>";
                                            echo "<a class='btn btn-danger btn-xs mt-1' data-bs-toggle='modal' data-bs-target='#deleteModalSatuanBarang' onclick='populateDeleteModalSatuan(\"{$row["id_satuan"]}\", \"{$row["nama_satuan"]}\")'><i class='bi bi-trash'></i></a>";
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr class='text-center'><td colspan='6'>No data found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal create Barang-->
    <form action="" method="post">
        <div class="modal fade" id="modalCreateBarang" tabindex="-1" aria-labelledby="modalLabelCreateBarang" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabelCreateBarang">Tambah Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kodeBarang" class="form-label">Kode Barang<span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="kodeBarang" id="kodeBarang" placeholder="Masukan Kode Barang" required>
                        </div>
                        <div class="mb-3">
                            <label for="namaBarang" class="form-label">Nama Barang<span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="namaBarang" id="namaBarang" placeholder="Masukan Nama Barang" required>
                        </div>
                        <div class="mb-3">
                            <label for="pilihTipe" class="form-label">Pilih Tipe<span style="color: red;">*</span></label>
                            <select class="form-select" aria-label="Default select example" id="pilihTipe" name="pilihTipe" required>
                                <option selected disabled>Masukan Tipe</option>
                                <?php foreach ($resultTipeBarang as $tipe) : ?>
                                    <option value="<?php echo htmlspecialchars($tipe['kode_tipe']); ?>">
                                        <?php echo htmlspecialchars($tipe['nama_tipe']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pilihSatuan" class="form-label">Pilih Satuan<span style="color: red;">*</span></label>
                            <select class="form-select" aria-label="satuan" id="pilihSatuan" name="pilihSatuan" required>
                                <option selected disabled>Masukan Satuan</option>
                                <?php foreach ($resultSatuanBarang as $satuan) : ?>
                                    <option value="<?php echo htmlspecialchars($satuan['id_satuan']); ?>">
                                        <?php echo htmlspecialchars($satuan['nama_satuan']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="hargaBarang" class="form-label">Harga<span style="color: red;">*</span></label>
                            <input type="number" class="form-control" name="hargaBarang" id="hargaBarang" placeholder="Masukan Harga Barang" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">keluar</button>
                        <button type="submit" class="btn btn-primary" name="prosesTambahBarang">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal Edit Barang -->
    <div class="modal fade" id="modalEditBarang" tabindex="-1" aria-labelledby="modalLabelEditBarang" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabelEditBarang">Edit Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editFormBarang" method="post" action="">
                        <input type="hidden" name="originalKodeBarangEdit" id="originalKodeBarangEdit">
                        <div class="mb-3">
                            <label for="kodeBarangEdit" class="form-label">Kode Barang<span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="kodeBarangEdit" id="kodeBarangEdit" placeholder="Masukan Kode Barang" required>
                        </div>
                        <div class="mb-3">
                            <label for="namaBarangEdit" class="form-label">Nama Barang<span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="namaBarangEdit" id="namaBarangEdit" placeholder="Masukan Nama Barang" required>
                        </div>
                        <div class="mb-3">
                            <label for="pilihTipeEdit" class="form-label">Pilih Tipe<span style="color: red;">*</span></label>
                            <select class="form-select" aria-label="Default select example" id="pilihTipeEdit" name="pilihTipeEdit" required>
                                <option selected disabled>Masukan Tipe</option>
                                <?php foreach ($resultTipeBarang as $tipe) : ?>
                                    <option value="<?php echo htmlspecialchars($tipe['kode_tipe']); ?>">
                                        <?php echo htmlspecialchars($tipe['nama_tipe']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pilihSatuanEdit" class="form-label">Pilih Satuan<span style="color: red;">*</span></label>
                            <select class="form-select" aria-label="satuan" id="pilihSatuanEdit" name="pilihSatuanEdit" required>
                                <option selected disabled>Masukan Satuan</option>
                                <?php foreach ($resultSatuanBarang as $satuan) : ?>
                                    <option value="<?php echo htmlspecialchars($satuan['id_satuan']); ?>">
                                        <?php echo htmlspecialchars($satuan['nama_satuan']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="hargaBarangEdit" class="form-label">Harga<span style="color: red;">*</span></label>
                            <input type="number" class="form-control" name="hargaBarangEdit" id="hargaBarangEdit" placeholder="Masukan Harga Barang" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keluar</button>
                            <button type="submit" class="btn btn-primary" name="prosesEditBarang">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- modal create Tipe-->
    <form action="" method="post">
        <div class="modal fade" id="modalCreateTipe" tabindex="-1" aria-labelledby="modalLabelCreateTipe" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabelCreateTipe">Tambah Tipe</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kodeTipe" class="form-label">Kode Tipe<span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="kodeTipe" id="kodeTipe" placeholder="Masukan Kode Tipe" required>
                        </div>
                        <div class="mb-3">
                            <label for="namaTipe" class="form-label">Nama Tipe<span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="namaTipe" id="namaTipe" placeholder="Masukan Nama Tipe" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">keluar</button>
                        <button type="submit" class="btn btn-primary" name="prosesTambahTipeBarang">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal Edit Tipe -->
    <div class="modal fade" id="modalEditTipeBarang" tabindex="-1" aria-labelledby="modalLabelEditTipeBarang" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabelEditTipeBarang">Edit Tipe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editFormTipeBarang" method="post" action="">
                        <input type="hidden" name="originalKodeTipeEdit" id="originalKodeTipeEdit">
                        <div class="mb-3">
                            <label for="kodeTipeEdit" class="form-label">Kode Tipe<span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="kodeTipeEdit" id="kodeTipeEdit" placeholder="Masukan Kode Tipe" required>
                        </div>
                        <div class="mb-3">
                            <label for="namaTipeEdit" class="form-label">Nama Tipe<span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="namaTipeEdit" id="namaTipeEdit" placeholder="Masukan Nama Tipe" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keluar</button>
                            <button type="submit" class="btn btn-primary" name="prosesEditTipeBarang">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- modal create Satuan-->
    <form action="" method="post">
        <div class="modal fade" id="modalCreateSatuan" tabindex="-1" aria-labelledby="modalLabelCreateSatuan" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabelCreateSatuan">Tambah Satuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="idSatuan" name="idSatuan" value="<?php echo $newIdSatuan; ?>">
                        <div class="mb-3">
                            <label for="namaSatuan" class="form-label">Nama Satuan<span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="namaSatuan" id="namaSatuan" placeholder="Masukan Nama Satuan" required>
                        </div>
                        <div class="mb-3">
                            <label for="inisialSatuan" class="form-label">Inisial Satuan<span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="inisialSatuan" id="inisialSatuan" placeholder="Masukan Inisial Satuan" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">keluar</button>
                        <button type="submit" class="btn btn-primary" name="prosesTambahSatuanBarang">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Edit Modal Satuan -->
    <div class="modal fade" id="editModalSatuanBarang" tabindex="-1" aria-labelledby="editModalLabelSatuanBarang" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabelSatuanBarang">Edit Satuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editFormSatuan" method="post" action="">
                        <input type="hidden" id="idSatuanEdit" name="idSatuanEdit">
                        <div class="mb-3">
                            <label for="namaSatuanEdit" class="form-label">Nama Satuan<span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="namaSatuanEdit" id="namaSatuanEdit" placeholder="Masukan Nama Satuan" required>
                        </div>
                        <div class="mb-3">
                            <label for="inisialSatuanEdit" class="form-label">Inisial Satuan<span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="inisialSatuanEdit" id="inisialSatuanEdit" placeholder="Masukan Inisial Satuan" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keluar</button>
                            <button type="submit" class="btn btn-primary" name="prosesEditSatuanBarang">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal delete Barang -->
    <div class="modal fade" id="modalDeleteBarang" tabindex="-1" aria-labelledby="modalLabelDeleteBarang" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="deleteForm" method="post" action="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabelDeleteBarang">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus barang <span id="namaBarangDelete"></span>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-danger" name="prosesDeleteBarang">Hapus</button>
                        <input type="hidden" name="kodeBarangDelete" id="kodeBarangDelete">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal delete Tipe -->
    <div class="modal fade" id="deleteModalTipeBarang" tabindex="-1" aria-labelledby="deleteModalLabelTipeBarang" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="deleteForm" method="post" action="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabelTipeBarang">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus tipe <span id="deleteNamaTipe"></span>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-danger" name="prosesDeleteTipeBarang">Hapus</button>
                        <input type="hidden" name="deleteKodeTipe" id="deleteKodeTipe">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal delete Satuan -->
    <div class="modal fade" id="deleteModalSatuanBarang" tabindex="-1" aria-labelledby="deleteLabelModalSatuanBarang" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="deleteFormSatuanBarang" method="post" action="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteLabelModalSatuanBarang">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus satuan <span id="deleteNamaSatuan"></span>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-danger" name="prosesDeleteSatuanBarang">Hapus</button>
                        <input type="hidden" name="idSatuanDelete" id="idSatuanDelete">
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

        // edit

        function populateModalEditBarang(kodeBarang, namaBarang, kodeTipe, idSatuan, hargaBarang) {
            document.getElementById('originalKodeBarangEdit').value = kodeBarang;
            document.getElementById('kodeBarangEdit').value = kodeBarang;
            document.getElementById('namaBarangEdit').value = namaBarang;
            document.getElementById('pilihTipeEdit').value = kodeTipe;
            document.getElementById('pilihSatuanEdit').value = idSatuan;
            document.getElementById('hargaBarangEdit').value = hargaBarang;
        }

        function populateModalEditTipeBarang(kodeTipe, namaTipe) {
            document.getElementById('originalKodeTipeEdit').value = kodeTipe;
            document.getElementById('kodeTipeEdit').value = kodeTipe;
            document.getElementById('namaTipeEdit').value = namaTipe;
        }

        function populateModalEditSatuan(idSatuan, namaSatuan, inisialSatuan) {
            document.getElementById('idSatuanEdit').value = idSatuan;
            document.getElementById('namaSatuanEdit').value = namaSatuan;
            document.getElementById('inisialSatuanEdit').value = inisialSatuan;
        }

        // delete

        function populateDeleteModalBarang(kodeBarang, namaBarang) {
            document.getElementById('namaBarangDelete').innerText = namaBarang;
            document.getElementById('kodeBarangDelete').value = kodeBarang;
        }

        function populateDeleteModalTipeBarang(kodeTipe, namaTipe) {
            document.getElementById('deleteNamaTipe').innerText = namaTipe;
            document.getElementById('deleteKodeTipe').value = kodeTipe;
        }

        function populateDeleteModalSatuan(idSatuan, namaSatuan) {
            document.getElementById('deleteNamaSatuan').innerText = namaSatuan;
            document.getElementById('idSatuanDelete').value = idSatuan;
        }
    </script>
</body>

</html>