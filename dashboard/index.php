<?php

session_start();

$id = $_SESSION['id'];

if (!isset($id)) {
    header('location:authentication-login.php');
}

require_once(__DIR__ . '/../php/konek.php');




$sql = "SELECT COUNT(*) as total_transaksi FROM tb_transaksi";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Mengambil data
    $row = $result->fetch_assoc();
    $total_transaksi = $row['total_transaksi'];
} else {
    $total_transaksi = 0;
}

$sql2 = "SELECT COUNT(*) as total_ejected FROM tb_transaksi WHERE ejected = 1";
$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {
    // Mengambil data
    $row2 = $result2->fetch_assoc();
    $total_ejected = $row2['total_ejected'];
} else {
    $total_ejected = 0;
}

// $sql3 = "SELECT SUM(nominal) as total_nominal_ejected FROM tb_transaksi WHERE ejected = 1";
// $result3 = $conn->query($sql3);

// if ($result3->num_rows > 0) {
//     // Mengambil data
//     $row3 = $result3->fetch_assoc();
//     $total_nominal_ejected = $row3['total_nominal_ejected'];
// } else {
//     $total_nominal_ejected = 0;
// }

$sql3 = "SELECT SUM(t.nominal) as total_nominal_ejected FROM tb_transaksi t JOIN tb_transaksi_produk tp ON t.midtrans_order_id = tp.id_transaksi WHERE t.ejected = 1 AND tp.status = 0";
$result3 = $conn->query($sql3);

if ($result3->num_rows > 0) {
    // Mengambil data
    $row3 = $result3->fetch_assoc();
    $total_nominal_ejected = $row3['total_nominal_ejected'];
} else {
    $total_nominal_ejected = 0;
}

$sql4 = "SELECT COUNT(*) as total_non_ejected FROM tb_transaksi WHERE ejected = 0";
$result4 = $conn->query($sql4);

if ($result4->num_rows > 0) {
    // Mengambil data
    $row4 = $result4->fetch_assoc();
    $total_non_ejected = $row4['total_non_ejected'];
} else {
    $total_non_ejected = 0;
}

$conn->close();



?>

<?php include(__DIR__ .  '/partials/head.php') ?>
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Dashboard</h4>
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
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-md-flex align-items-center">
                        <div>
                            <h4 class="card-title">Site Analysis</h4>
                            <h5 class="card-subtitle">Overview</h5>
                        </div>
                    </div>
                    <div class="row">
                        <!-- column -->

                        <div class="col-lg-3">
                            <div class="row">
                                <div class="col-6">
                                    <div class="bg-dark p-10 text-white text-center">
                                        <i class="fa fa-exclamation-triangle m-b-5 font-16"></i>
                                        <h5 class="m-b-0 m-t-5"><?php echo $total_non_ejected; ?></h5>
                                        <small class="font-light">Not Ejected</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-dark p-10 text-white text-center">
                                        <i class="fa fa-dollar-sign m-b-5 font-16"></i>
                                        <h5 class="m-b-0 m-t-5"><?php echo $total_nominal_ejected; ?></h5>
                                        <small class="font-light">Money</small>
                                    </div>
                                </div>
                                <div class="col-6 m-t-15">
                                    <div class="bg-dark p-10 text-white text-center">
                                        <i class="fa fa-cart-plus m-b-5 font-16"></i>
                                        <h5 class="m-b-0 m-t-5"><?php echo $total_ejected; ?></h5>
                                        <small class="font-light">Total Finished Shop</small>
                                    </div>
                                </div>
                                <div class="col-6 m-t-15">
                                    <div class="bg-dark p-10 text-white text-center">
                                        <i class="fa fa-tag m-b-5 font-16"></i>
                                        <h5 class="m-b-0 m-t-5"><?php echo $total_transaksi; ?></h5>
                                        <small class="font-light">Total Orders</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- column -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
<?php include(__DIR__ .  '/partials/foot.php') ?>