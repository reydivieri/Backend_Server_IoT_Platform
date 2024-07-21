<?php

session_start();

$id = $_SESSION['id'];

if (!isset($id)) {
    header('location:authentication-login.php');
}

require_once(__DIR__ . '/../php/konek.php');
require_once(__DIR__ . '/proses-produk.php');
?>

<?php include(__DIR__ .  '/partials/head.php') ?>
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Tables</h4>
            <div class="ml-auto text-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Library</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title">Basic Datatable</h5>
                    <div class="">
                        <!-- <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-success active">
                                <input type="radio" class="status-mesin-toggle" value="1" autocomplete="off" checked> Aktif
                            </label>
                            <label class="btn btn-danger">
                                <input type="radio" class="status-mesin-toggle" value="0" autocomplete="off"> Nonaktif
                            </label>
                        </div> -->
                        <button type="button" class="btn btn-primary" name="addnewproduk" data-toggle="modal" data-target="#modalbroken">Tambah Produk</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $ambilsemuadatastock = mysqli_query($conn, "SELECT * FROM `tb_produk`");
                                $i = 1;
                                while ($data = mysqli_fetch_array($ambilsemuadatastock)) {
                                    $namaproduk = $data['nama'];
                                    $jumlahproduk = $data['stok'];
                                    $hargaproduk = $data['harga'];
                                    $idp = $data['id'];
                                ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><?= $namaproduk; ?></td>
                                        <td><?= $hargaproduk; ?></td>
                                        <td><?= $jumlahproduk; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalrusak<?= $idp; ?>">Edit</button>
                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deletemodal<?= $idp; ?>">Delete</button>
                                        </td>
                                    </tr>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deletemodal<?= $idp; ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Delete Produk</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>

                                                <!-- Modal body -->
                                                <form method="post">
                                                    <div class="modal-body">
                                                        <h5>Apakah Anda yakin ingin menghapus <?= $namaproduk; ?> ? </h5>
                                                    </div>
                                                    <input type="hidden" name="idp" value="<?= $idp; ?>">
                                                    <!-- Modal Footer -->
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-danger" name="deleteproduk">Ok</button>
                                                        <div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php }; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Right sidebar -->
    <!-- ============================================================== -->
    <!-- .right-sidebar -->
    <!-- ============================================================== -->
    <!-- End Right sidebar -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->


<?php $asdt = mysqli_query($conn, "SELECT * FROM `tb_produk`");
while ($dt = mysqli_fetch_array($asdt)) {
?>
    <div class="modal fade" id="modalrusak<?= $dt['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true ">
        <div class="modal-dialog" role="document ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true ">&times;</span>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="fname" class="col-sm-3 text-right control-label col-form-label">Nama Produk</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="idp" value="<?= $dt['id']; ?>">
                                <input type="text" class="form-control" name="namaproduk" id="fname" value="<?= $dt['nama']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="fname" class="col-sm-3 text-right control-label col-form-label">Harga</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="harga" id="fname" value="<?= $dt['harga']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="fname" class="col-sm-3 text-right control-label col-form-label">Stok</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="stok" id="fname" value="<?= $dt['stok']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 text-right control-label col-form-label">File Upload</label>
                            <div class="col-md-9">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="image" accept="image/jpg, image/jpeg, image/png" id="validatedCustomFile">
                                    <label class="custom-file-label" for="validatedCustomFile"><?= $dt['img']; ?></label>
                                    <div class="invalid-feedback">Example invalid custom file feedback</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning" name="editproduk">Ok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php }; ?>

<div class="modal fade" id="modalbroken" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true ">
    <div class="modal-dialog" role="document ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true ">&times;</span>
                </button>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="fname" class="col-sm-3 text-right control-label col-form-label">Nama Produk</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="namaproduk" placeholder="Nama Produk" id="fname">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fname" class="col-sm-3 text-right control-label col-form-label">Harga</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="harga" placeholder="Harga Produk" id="fname">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fname" class="col-sm-3 text-right control-label col-form-label">Stok</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="stok" placeholder="Stok" id="fname">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 text-right control-label col-form-label">File Upload</label>
                        <div class="col-md-9">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image" accept="image/jpg, image/jpeg, image/png" id="validatedCustomFile" required>
                                <label class="custom-file-label" for="validatedCustomFile">Choose File</label>
                                <div class="invalid-feedback">Example invalid custom file feedback</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="addnewproduk">Ok</button>

                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('.status-mesin-toggle').on('change', function() {
        const data = new URLSearchParams();
        data.append('status', $(this).val());
        fetch('http://localhost/skripsivm/php/api-status-mesin.php', {
            'method': 'post',
            body: data,
        });
    });
</script>
<?php include(__DIR__ .  '/partials/foot.php') ?>