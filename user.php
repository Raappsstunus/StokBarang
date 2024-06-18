<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Pengguna - Management Stok</title>
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

    .card-img-top {
        aspect-ratio: 16/9;
        object-fit: cover;
        width: 100%;
        height: auto;
    }

    .alert-info {
        background-color: #e9f7fe;
        border-color: #b8e1f5;
        color: #31708f;
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

    // Get new ID for user
    $newId = getNewId($conn, 'user');
    $createdAt = getCurrentTimestamp();

    // Proses Tambah
    if (isset($_POST["prosesTambah"])) {
        try {
            $avatarDir = 'uploads/avatars/';
            $avatarFile = $avatarDir . basename($_FILES['avatar']['name']);
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $avatarFile)) {
                $avatarPath = $avatarFile;
                $idRole = $_POST["jabatan"];

                // Ambil roleName berdasarkan idRole dari database
                $queryRole = "SELECT role_name FROM role WHERE id_role = ?";
                $stmtRole = mysqli_prepare($conn, $queryRole);
                mysqli_stmt_bind_param($stmtRole, 'i', $idRole);
                mysqli_stmt_execute($stmtRole);
                mysqli_stmt_bind_result($stmtRole, $roleName);
                mysqli_stmt_fetch($stmtRole);
                mysqli_stmt_close($stmtRole);

                // Hash the password
                $hashedPassword = password_hash($_POST["kataSandi"], PASSWORD_BCRYPT);

                tambahDataPengguna($_POST["idUser"], $avatarPath, $_POST["namaPengguna"], $_POST["namaLengkap"], $_POST["nomorTelepon"], $_POST["alamat"], $_POST["email"], $hashedPassword, $idRole, $roleName, $_POST["createdAt"]);
            } else {
                echo "Gagal mengunggah file avatar.";
            }
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (Exception $e) {
            // Handle the exception
            echo "Error: " . $e->getMessage();
        }
    }

    // Proses Edit
    if (isset($_POST["prosesEdit"])) {
        try {
            $avatarDir = 'uploads/avatars/';
            $avatarPath = $_POST['currentAvatarPath'];

            if (!empty($_FILES['avatarEdit']['name'])) {
                $avatarFile = $avatarDir . basename($_FILES['avatarEdit']['name']);
                if (move_uploaded_file($_FILES['avatarEdit']['tmp_name'], $avatarFile)) {
                    $avatarPath = $avatarFile;
                } else {
                    echo "Gagal mengunggah file avatar.";
                }
            }

            $idRole = $_POST["jabatanEdit"];
            $password = $_POST["kataSandiEdit"];
            $createdAtEdit = getCurrentTimestamp();

            // Enkripsi password jika ada perubahan password
            $hashedPassword = '';
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            }

            $queryRole = "SELECT role_name FROM role WHERE id_role = ?";
            $stmtRole = mysqli_prepare($conn, $queryRole);
            mysqli_stmt_bind_param($stmtRole, 'i', $idRole);
            mysqli_stmt_execute($stmtRole);
            mysqli_stmt_bind_result($stmtRole, $roleNameEdit);
            mysqli_stmt_fetch($stmtRole);
            mysqli_stmt_close($stmtRole);

            // Update user data
            updateDataPengguna($_POST["idUser"], $avatarPath, $_POST["namaPenggunaEdit"], $_POST["namaLengkapEdit"], $_POST["nomorTeleponEdit"], $_POST["alamatEdit"], $_POST["emailEdit"], $idRole, $roleNameEdit, $createdAtEdit, $hashedPassword);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (Exception $e) {
            // Handle the exception
            echo "Error: " . $e->getMessage();
        }
    }

    // Proses Delete
    if (isset($_POST["prosesDelete"])) {
        try {
            deleteDataPengguna($_POST["idUserDelete"]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    $result = displayDataManajemenPengguna();
    $resultRole = displayDataRole();
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
                <a class="navbar-brand"><i class="bi bi-arrow-bar-left me-2"></i>Manajemen Pengguna</a>
                <form class="d-flex">
                    <button class="btn btn-outline-primary" type="button" onclick="toLogin()">
                        <i class="bi bi-box-arrow-left me-2"></i>
                        Logout
                    </button>
                </form>
            </div>
        </nav>
        <div class="mt-4 d-flex justify-content-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateUser">
                <i class="bi bi-plus"></i>
                Tambah Pengguna
            </button>
        </div>
        <div class="row mt-4">
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    echo '<div class="col-12 col-md-6 col-lg-4 mb-4">';
                    echo '<div class="card animated-card" style="width: 100%;">';
                    echo '<img src="' . htmlspecialchars($row["avatar"]) . '" class="card-img-top" alt="img-avatar">';
                    echo '<div class="card-body">';
                    echo '<table class="table table-bordered">';
                    echo '<tbody>';
                    echo '<tr style="font-size: 13px;"><th>Nama Lengkap</th><td>' . htmlspecialchars($row["fullname"]) . '</td></tr>';
                    echo '<tr style="font-size: 13px;"><th>Email</th><td>' . htmlspecialchars($row["email"]) . '</td></tr>';
                    echo '<tr style="font-size: 13px;"><th>Nomor Telepon</th><td>' . htmlspecialchars($row["no_telp"]) . '</td></tr>';
                    echo '<tr style="font-size: 13px;"><th>Jabatan</th><td>' . htmlspecialchars($row["role_name"]) . '</td></tr>';
                    echo '<tr style="font-size: 13px;"><th>Alamat</th><td>' . htmlspecialchars($row["address_user"]) . '</td></tr>';
                    echo '<tr style="font-size: 13px;"><th>Aksi</th><td>';
                    echo "<a class='btn btn-primary btn-sm me-2 edit-button' data-bs-toggle='modal' data-bs-target='#modalEditUser' onclick='populateModal(\"{$row["id_user"]}\", \"{$row["avatar"]}\", \"{$row["username"]}\", \"{$row["fullname"]}\", \"{$row["no_telp"]}\", \"{$row["address_user"]}\", \"{$row["email"]}\", \"{$row["password_user"]}\", \"{$row["id_role"]}\", \"{$row["role_name"]}\")'><i class='bi bi-pencil'></i></a>";
                    echo "<a class='btn btn-danger btn-sm me-2' data-bs-toggle='modal' data-bs-target='#deleteModal' onclick='populateDeleteModal(\"{$row["id_user"]}\", \"{$row["fullname"]}\")'><i class='bi bi-trash'></i></a>";
                    echo '</td></tr>';
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="col-12 text-center">';
                echo '<div class="alert alert-info" role="alert" style="padding: 40px; margin: 20px; border-radius: 10px;">';
                echo '<i class="bi bi-info-circle" style="font-size: 2rem;"></i>';
                echo '<h4 class="alert-heading mt-3">Data Tidak Ditemukan</h4>';
                echo '<p>Maaf, tidak ada data yang tersedia saat ini. Silakan tambahkan data baru atau periksa kembali nanti.</p>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <!-- modal create -->
    <div class="modal fade" id="modalCreateUser" tabindex="-1" aria-labelledby="modalCreateUser" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabelCreateUser">Tambah Pengguna</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="idUser" name="idUser" value="<?php echo $newId; ?>">
                        <input type="hidden" id="createdAt" name="createdAt" value="<?php echo $createdAt; ?>">
                        <div class="mb-3">
                            <label for="namaPengguna" class="form-label">Nama Pengguna</label>
                            <input type="text" class="form-control" name="namaPengguna" id="namaPengguna" placeholder="Masukan Nama Pengguna">
                        </div>
                        <div class="mb-3">
                            <label for="namaLengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="namaLengkap" id="namaLengkap" placeholder="Masukan Nama Lengkap">
                        </div>
                        <div class="mb-3">
                            <label for="nomorTelepon" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" name="nomorTelepon" id="nomorTelepon" placeholder="Masukan Nomor Telepon">
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Masukan Alamat">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email">
                        </div>
                        <div class="mb-3">
                            <label for="kataSandi" class="form-label">Kata Sandi</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="kataSandi" name="kataSandi" placeholder="Masukkan Kata Sandi">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword1">
                                    <i class="bi bi-eye-slash-fill" id="passwordToggle1"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <select class="form-select" aria-label="Jabatan" id="jabatan" name="jabatan">
                                <option selected disabled>Masukkan Jabatan</option>
                                <?php foreach ($resultRole as $role) : ?>
                                    <option value="<?php echo htmlspecialchars($role['id_role']); ?>">
                                        <?php echo htmlspecialchars($role['role_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="avatar" class="form-label">Avatar</label>
                            <input class="form-control" type="file" id="formFile" name="avatar">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">keluar</button>
                        <button type="submit" class="btn btn-primary" name="prosesTambah">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEditUser" tabindex="-1" aria-labelledby="modalLabelEditUser" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm" method="post" action="" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabelEditUser">Edit Pengguna</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="idUserEdit" name="idUser">
                        <input type="hidden" id="createdAtEdit" name="createdAt" value="<?php echo htmlspecialchars($createdAt); ?>">
                        <input type="hidden" id="currentAvatarPath" name="currentAvatarPath">
                        <div class="mb-3">
                            <label for="namaPenggunaEdit" class="form-label">Nama Pengguna</label>
                            <input type="text" class="form-control" name="namaPenggunaEdit" id="namaPenggunaEdit" placeholder="Masukan Nama Pengguna">
                        </div>
                        <div class="mb-3">
                            <label for="namaLengkapEdit" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="namaLengkapEdit" id="namaLengkapEdit" placeholder="Masukan Nama Lengkap">
                        </div>
                        <div class="mb-3">
                            <label for="nomorTeleponEdit" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" name="nomorTeleponEdit" id="nomorTeleponEdit" placeholder="Masukan Nomor Telepon">
                        </div>
                        <div class="mb-3">
                            <label for="alamatEdit" class="form-label">Alamat</label>
                            <input type="text" class="form-control" name="alamatEdit" id="alamatEdit" placeholder="Masukan Alamat">
                        </div>
                        <div class="mb-3">
                            <label for="emailEdit" class="form-label">Email</label>
                            <input type="email" class="form-control" id="emailEdit" name="emailEdit" placeholder="Masukkan Email">
                        </div>
                        <div class="mb-3" style="display: none;">
                            <label for="kataSandiEdit" class="form-label">Kata Sandi</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="passwordEdit" name="kataSandiEdit" placeholder="Masukkan Kata Sandi">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword2">
                                    <i class="bi bi-eye-slash-fill" id="passwordToggle2"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="jabatanEdit" class="form-label">Jabatan</label>
                            <select class="form-select" aria-label="jabatan" id="jabatanEdit" name="jabatanEdit">
                                <option selected disabled>Masukkan Jabatan</option>
                                <?php foreach ($resultRole as $role) : ?>
                                    <option value="<?php echo htmlspecialchars($role['id_role']); ?>">
                                        <?php echo htmlspecialchars($role['role_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="avatarEdit" class="form-label">Avatar</label>
                            <input class="form-control" type="file" id="avatarEdit" name="avatarEdit">
                            <img id="avatarPreview" src="" alt="Avatar Preview" style="max-width: 120px; max-height: 120px; margin-top: 10px; border: 1px solid #c7c7c7; border-radius: 5px;">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keluar</button>
                            <button type="submit" class="btn btn-primary" name="prosesEdit">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal delete-->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="deleteForm" method="post" action="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus pengguna dengan nama <span id="namaLengkapDelete"></span>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-danger" name="prosesDelete">Hapus</button>
                        <input type="hidden" id="idUserDelete" name="idUserDelete" id="prosesDelete">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <script>
        // JavaScript code to handle button click
        function toLogin() {
            window.location.href = "/login.php";
        }

        function populateModal(idUser, avatar, namaPengguna, namaLengkap, noTelp, alamat, email, password, jabatan, namaJabatan) {
            document.getElementById('idUserEdit').value = idUser;
            document.getElementById('namaPenggunaEdit').value = namaPengguna;
            document.getElementById('namaLengkapEdit').value = namaLengkap;
            document.getElementById('emailEdit').value = email;
            document.getElementById('passwordEdit').value = password;
            document.getElementById('nomorTeleponEdit').value = noTelp;
            document.getElementById('alamatEdit').value = alamat;
            document.getElementById('jabatanEdit').value = jabatan;
            document.getElementById('currentAvatarPath').value = avatar;
            console.log(namaJabatan);

            var avatarPreview = document.getElementById('avatarPreview');
            avatarPreview.src = avatar;
        }

        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('avatarEdit').addEventListener('change', function() {
            previewAvatar(this);
        });

        // Function to toggle password visibility
        document.getElementById('togglePassword1').addEventListener('click', function() {
            const passwordInput = document.getElementById('kataSandi');
            const passwordToggle = document.getElementById('passwordToggle1');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggle.classList.remove('bi-eye-slash-fill');
                passwordToggle.classList.add('bi-eye-fill');
            } else {
                passwordInput.type = 'password';
                passwordToggle.classList.remove('bi-eye-fill');
                passwordToggle.classList.add('bi-eye-slash-fill');
            }
        });

        // Function to toggle password visibility
        document.getElementById('togglePassword2').addEventListener('click', function() {
            const passwordInput = document.getElementById('passwordEdit');
            const passwordToggle = document.getElementById('passwordToggle2');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggle.classList.remove('bi-eye-slash-fill');
                passwordToggle.classList.add('bi-eye-fill');
            } else {
                passwordInput.type = 'password';
                passwordToggle.classList.remove('bi-eye-fill');
                passwordToggle.classList.add('bi-eye-slash-fill');
            }
        });

        function populateDeleteModal(idUser, namaLengkap) {
            document.getElementById('idUserDelete').value = idUser;
            document.getElementById('namaLengkapDelete').innerText = namaLengkap;
        }
    </script>
</body>

</html>