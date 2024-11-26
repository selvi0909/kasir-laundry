<?php 
// panggil dile koneksi.php disini
include 'koneksi/koneksi.php';
// Menangani pengiriman form untuk "Create"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // pastikan semua field ada
    if (isset($_POST['status'], $_POST['kode_invoice'], $_POST['pelanggan'],
              $_POST['tanggal'], $_POST['batas_waktu'], $_POST['tanggal_dibayar'],
              $_POST['dibayar'], $_POST['total'])) {
        // Ambil data dari post
        $status = $_POST['status'] ;
        $kode_invoice = $_POST['kode_invoice'];
        $pelanggan = $_POST['pelanggan'];
        $tanggal = $_POST['tanggal'];
        $batas_waktu = $_POST['batas_waktu'];
        $tanggal_dibayar = $_POST['tanggal_dibayar'];
        $dibayar = $_POST['dibayar'];
        $total = $_POST['total'];
        // query untuk mmenambah data 
        $query = "INSERT INTO tb_transaksi (status, kode_invoice, pelanggan, tanggal, batas waktu, tanggal_dibayar, dibayar, tota l)
                  VALUES ('$status', '$kode_invoice', '$pelanggan', $tanggal', '$batas_waktu', '$tanggal_dibayar', '$dibayar', '$total')";
        if (mysqli_query($koneksi, $query)) {
                    // redirect setelah berhasil menambah data 
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    }else {
        echo "Semua field harus diisi";
    }
}
// buat perintah untuk mengirimkan inputan disini!

// Menangani pengiriman form untuk "Edit"
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $tanggal_dibayar = $_POST['tanggal_dibayar'];
    $dibayar = $_POST['dibayar'];

    $query = "UPDATE tb_transaksi SET status='$status', tanggal_dibayar='$tanggal_dibayar', dibayar='$dibayar' WHERE id='$id'";

    if (mysqli_query($koneksi, $query)) {
        // redirect setelah berhasil mengedit data
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
// buat perintah untuk mengedit disini!

// Menangani pengiriman form untuk "Delete"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    // Ambil ID dari input hidden
    $id = intval($_POST['id']); 

    // Prepared statement untuk menghapus data berdasarkan ID
    $stmt = mysqli_prepare($koneksi, "DELETE FROM tb_transaksi WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        // Redirect ke halaman yang sama setelah penghapusan
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }

    mysqli_stmt_close($stmt);
}

// buat perintah untuk mengirimkan menghapus data disini!

// Mengambil data untuk "Read"
$result = mysqli_query($koneksi, "SELECT * FROM tb_transaksi");
// buat perintah untuk mengambil data disini 
?>
 
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bening - Tables</title>
    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        .center{
        text-align: center;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Selvi <sup>Laundry</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Nav Item - Transaksi -->
            <li class="nav-item active">
                <a class="nav-link" href="transaksi.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Transaksi</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Selvi Yanti</span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading --> 
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h4 class="m-0 font-weight-bold text-primary">Data Laundry</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Tambah</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr class="center">
                                            <th>No</th> 
                                            <th >Nama Pelanggan</th>
                                            <th>Status</th>
                                            <th>Kode Invoice</th>
                                            <th>Tanggal</th>
                                            <th>Batas Waktu</th>
                                            <th>Tanggal Dibayar</th>
                                            <th>Dibayar</th>
                                            <th>Total</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead> 
                                    <tbody> 
                                    <!-- buat pemanggilan data disini untuk mengisi tabel  -->
                                        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $row['Pelanggan']; ?></td>
                                                <td><?= $row['status']; ?></td>
                                                <td><?= $row['kode_invoice']; ?></td>
                                                <td><?= $row['tanggal']; ?></td>
                                                <td><?= $row['batas_waktu']; ?></td>
                                                <td><?= $row['tanggal_dibayar']; ?></td>
                                                <td><?= $row['dibayar']; ?></td>
                                                <td><?= $row['total']; ?></td>

                                            <td class="d-blox">
                                                <button class='btn btn-info btn-sm' 
                                                onclick="openDetailModal(<?php echo $row['id']; ?>, '<?php echo addslashes($row['name']); ?>', '<?php echo addslashes($row['description']); ?>')"
                                                data-bs-toggle='modal' 
                                                data-bs-target='#detailModal'>x<i class="fas fa-info-circle"></i> Detail
                                                </button>

                                                <button class="btn btn-warning btn-sm" onclick="openEditModal(<?php echo $row['id']; ?>, '<?php echo $row['status']; ?>', '<?php echo $row['tanggal_dibayar']; ?>', '<?php echo $row['dibayar']; ?>')">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                                </button>

                                                <button class="btn btn-danger btn-sm" onclick="setDeleteId(<?= $row['id']; ?>)" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr> 
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Modal Tambah Data -->
            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="addModalLabel">Tambah Data Laundry</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- pada from gunakan metod post -->
                         <!-- Setiap inputan tambahkan name yang sesuai dengan database
                          contoh:
                          name="nama" //sesuaikan dengan nama kolom pada tabel database -->
                    <form id="formTambah" method="POST" action=""> 
                        <div class="mb-3">
                          <label for="pelanggan" class="form-label">Pelanggan</label> 
                          <input type="text" class="form-control" id="pelanggan" required>  <!-- tambahkan name disini -->
                        </div>
                        <div class="mb-3">
                          <label for="status" class="form-label">Status</label>
                          <select class="form-control" id="status" required><!-- tambahkan name disini -->                       
                            <option>Baru</option>
                            <option>Proses</option>
                            <option>Selesai</option>
                            <option>Diambil</option>
                          </select>
                        </div>
                        <div class="mb-3">
                          <label for="kodeInvoice" class="form-label">Kode Invoice</label>
                          <input type="datetime-local" class="form-control" id="kodeInvoice" required><!-- tambahkan name disini -->
                        </div>
                        <div class="mb-3">
                          <label for="tanggal" class="form-label">Tanggal</label>
                          <input type="datetime-local" class="form-control" id="tanggal" required> <!-- tambahkan name disini -->
                        </div>
                        <div class="mb-3">
                          <label for="batasWaktu" class="form-label">Batas Waktu</label>
                          <input type="datetime-local" class="form-control" id="batasWaktu" required> <!-- tambahkan name disini -->
                        </div>
                        <div class="mb-3">
                            <label for="dibayar" class="form-label">Dibayar</label>
                            <select id="dibayar" class="form-control" > <!-- tambahkan name disini -->
                                <option value="Sudah">Sudah</option>
                                <option value="Belum">Belum</option>
                            </select>
                        </div>
                        
                        <div class="mb-3" id="divTanggalDibayar" style="display: none;">
                            <label for="tanggalDibayar" class="form-label">Tanggal Dibayar</label>
                            <input type="datetime-local" class="form-control" id="tanggalDibayar" > <!-- tambahkan name disini -->
                        </div>
                        <div class="mb-3">
                          <label for="total" class="form-label">Total</label>
                          <input type="number" class="form-control"  id="total" required> <!-- tambahkan name disini -->
                        </div>
                      </form>
                    </div> 
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button> 
                        <!-- pastikan type dari button tambah adalah submit-->
                        <button type="submit" class="btn btn-primary" form="formTambah">Tambah</button>
                    </div>
                  </div>
                </div>
              </div> 
            <!-- Modal Tambah Data end -->

            <!-- Modal detail -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content to display details -->
                <p>This is where the details of the item will be displayed.</p>
                <!-- Example: dynamically load content or fill with data -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
            <!-- Modal detail end -->

            <!-- Modal Edit --> 
                <!-- buat modal edit disini -->
                <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="editmodallabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editmodallabel">Edit Data Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEdit" method="POST" action="">
                    <!-- Hidden fields to pass data -->
                    <input type="hidden" id="edit_id" name="id">
                    <input type="hidden" id="action" name="edit">

                    <!-- Status field -->
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="Baru">Baru</option>
                            <option value="Proses">Proses</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Diambil">Diambil</option>
                        </select>
                    </div>

                    <!-- Tanggal Dibayar field -->
                    <div class="mb-3">
                        <label for="edit_tanggal_dibayar" class="form-label">Tanggal Dibayar</label>
                        <input type="datetime-local" class="form-control" id="edit_tanggal_dibayar" name="tanggal_dibayar">
                    </div>

                    <!-- Dibayar field -->
                    <div class="mb-3">
                        <label for="edit_dibayar" class="form-label">Dibayar</label>
                        <select class="form-select" id="edit_dibayar" name="dibayar" required>
                            <option value="Sudah">Sudah</option>
                            <option value="Belum">Belum</option>
                        </select>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Edit End -->


            <!-- Modal Konfirmasi Hapus -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data ini?
            </div>
            <div class="modal-footer">
                <form method="POST">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="deleteId"> <!-- ID akan diisi oleh JS -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
            <!-- Modal Konfirmasi Hapus End -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Selvi Laundry </span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
    
    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>

    <script>
        // Event listener untuk menampilkan atau menyembunyikan input "Tanggal Dibayar"
        document.getElementById('dibayar').addEventListener('change', function() {
            const dibayar = this.value;
            const divTanggalDibayar = document.getElementById('divTanggalDibayar');

            // Jika dibayar "Sudah", tampilkan input Tanggal Dibayar
            if (dibayar === 'Sudah') {
                divTanggalDibayar.style.display = 'block';
            } else {
                // Jika dibayar "Belum", sembunyikan input Tanggal Dibayar dan kosongkan nilai
                divTanggalDibayar.style.display = 'none';
                document.getElementById('tanggalDibayar').value = '';
            }
        });

        let nomor = 1;

        function tambahData() {
            const status = document.getElementById('status').value;
            const kodeInvoice = document.getElementById('kodeInvoice').value;
            const pelanggan = document.getElementById('pelanggan').value;
            const tanggal = document.getElementById('tanggal').value;
            const batasWaktu = document.getElementById('batasWaktu').value;
            const dibayar = document.getElementById('dibayar').value;
            let tanggalDibayar = document.getElementById('tanggalDibayar').value || '-'; // Default '-' jika kosong
            const total = document.getElementById('total').value;

            // Jika dibayar "Belum", kosongkan tanggal dibayar
            if (dibayar === 'Belum') {
                tanggalDibayar = '-';
            }

            const tableBody = document.getElementById('tableBody');
            const newRow = `
            <tr>
                <td>${nomor}</td>
                <td>${pelanggan}</td>
                <td>${status}</td>
                <td>${kodeInvoice}</td>
                <td>${tanggal}</td>
                <td>${batasWaktu}</td>
                <td>${tanggalDibayar}</td>
                <td>${dibayar}</td>
                <td>Rp ${total}</td>
                <td>
                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal">Detail</button>
                <button class="btn btn-warning btn-sm">Edit</button>
                <button class="btn btn-danger btn-sm">Hapus</button>
                </td>
            </tr>
            `;

            tableBody.insertAdjacentHTML('beforeend', newRow);
            nomor++;

            // Reset form
            document.getElementById('formTambah').reset();
            // Sembunyikan input tanggal dibayar setelah reset form
            document.getElementById('divTanggalDibayar').style.display = 'none';

            // Tutup modal
            const addModal = new bootstrap.Modal(document.getElementById('addModal'));
            addModal.hide();
        }
    </script>

    <!-- script untuk menambah data -->
    <!-- tambahkan script untuk menambah data disini -->

<script>
    document.genElementById("dibayar").addEventListener("change", function () {
        const tanggalDibayarDiv = document.getElementById("divTanggalDibayar");
        tanggalDibayarDiv.style.display = this.value === "Sudah" ? "block" : "none";

    });
</script>
    <!-- script edit -->
    <!-- tambahkan script untuk mengedit disini -->
    <script>
       function openEditModal(id, status, tanggalDibayar, dibayar) {
    // Populate form fields with the data passed from the button click
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_status").value = status;
    document.getElementById("edit_tanggal_dibayar").value = tanggalDibayar;
    document.getElementById("edit_dibayar").value = dibayar;
    
    // Show the modal (Bootstrap 5 syntax)
    $('#editmodal').modal('show');

            //menampilkan modal 
            const editModal = new bootstrap.Modal(document.getElementBYid('editModal'));
            editModal.show();
        }

        // fungsi untuk mengupdate data
        function updateData() {
            const form = document.getElementById('formEdit');
            const formData = new FormData(form);

            // kirim data menggunakan fetch API
            fetch('', { // menggunakan URL yang sama (file ini) untuk mengirim data
                method: 'POST',
                body: formData,
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Tampilkan respon server
                location.reload(); // refresh halaman setelah data diperbarui
            }) 
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
    <!-- script untuk hapus data -->
    <!-- tambahkab script untuk menghapus data disini -->
    <script>
    function setDeleteId(id) {
        document.getElementById('deleteId').value = id; // Masukkan ID ke input hidden
    }
    </script>
    <script>
        // js detail modal
        // Function to populate modal with dynamic content
    function openDetailModal(id, name, description) {
    // Assuming you have a modal with id 'detailModal'
    document.getElementById('detailModalLabel').textContent = name;  // Set the modal title
    document.getElementById('detailModalBody').innerHTML = description;  // Set the modal body content
    
    // Show the modal
    $('#detailModal').modal('show');
    }

    </script>


</body>

</html>