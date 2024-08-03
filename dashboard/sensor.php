<?php

session_start();

$id = $_SESSION['id'];

if (!isset($id)) {
    header('location:authentication-login.php');
}

require_once(__DIR__ . '/../php/konek.php');
require_once(__DIR__ . '/proses-sensor.php');
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
    <!-- Chart-2 -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Sensor Produk</h5>
                    <div id="placeholder" style="height: 400px; width:100%"></div>
                    <p id="choices" class="m-t-20"></p>
                </div>
            </div>
        </div>
    </div>
    <!-- End Chart-2 -->
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
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Sensor</th>
                                    <th>Id Produk</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Waktu Produk Keluar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $ambilsemuadata = mysqli_query($conn, "SELECT * FROM `tb_sensor`");
                                $i = 1;
                                while ($data = mysqli_fetch_array($ambilsemuadata)) {
                                    $nama = $data['nama_sensor'];
                                    $idp = $data['id_produk'];
                                    $jumlah = $data['jumlah'];
                                    $status = $data['status'];
                                    $waktu = $data['waktu_produk_keluar'];

                                ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><?= $nama; ?></td>
                                        <td><?= $idp; ?></td>
                                        <td><?= $jumlah; ?></td>
                                        <td><?= $status == 0 ? 'Berhasil Keluar' : $status; ?></td>
                                        <td><?= $waktu; ?></td>
                                    </tr>
                                <?php }; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Sensor</th>
                                    <th>Id Produk</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Waktu Produk Keluar</th>
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






<!-- <script>
    $('.status-mesin-toggle').on('change', function() {
        const data = new URLSearchParams();
        data.append('status', $(this).val());
        fetch('http://localhost/skripsivm/php/api-status-mesin.php', {
            'method': 'post',
            body: data,
        });
    });
</script> -->
<script src="assets/libs/chart/turning-series.js"></script>
<?php include(__DIR__ .  '/partials/foot.php') ?>