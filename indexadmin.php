<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Skripsi</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap" rel="stylesheet">

  <!-- Feather Icons -->
  <script src="https://unpkg.com/feather-icons"></script>

  <!-- Alpine JS -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- My Style -->
  <link rel="stylesheet" href="css/style.css">

  <!-- My App -->
  <script src="src/app.js" async></script>

  <!-- SNAP Midtrans -->
  <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-lSzYcslYZZE7a-4C"></script>

</head>

<body>

  <!-- Navbar start -->
  <nav class="navbar" x-data>
    <a href="#" class="navbar-logo">skripsi<span>vm</span>.</a>

    <div class="navbar-nav">
      <a href="#home">Home</a>
      <a href="#products">Produk</a>
    </div>

    <div class="navbar-extra">
      <a href="#" id="search-button"><i data-feather="search"></i></a>
      <a href="#" id="shopping-cart-button">
        <i data-feather="shopping-cart"></i>
        <span class="quantity-badge" x-show="$store.cart.quantity" x-text="$store.cart.quantity"></span>
      </a>
      <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
    </div>
  </nav>
  <!-- Navbar end -->

  <!-- Hero Section start -->
  <section class="hero" id="home">
    <div class="mask-container">
    </div>
  </section>
  <!-- Hero Section end -->

  <div class="card-header">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
      Tambah Produk Baru
    </button>
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Info boxes -->
      <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">CPU Traffic</span>
              <span class="info-box-number">
                10
                <small>%</small>
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Likes</span>
              <span class="info-box-number">41,410</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Sales</span>
              <span class="info-box-number">760</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">New Members</span>
              <span class="info-box-number">2,000</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
    </div><!--/. container-fluid -->
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Snack</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $ambilsemuadatastock = mysqli_query($conn, "SELECT * FROM `tb_produk`");
          $i = 1;
          while ($data = mysqli_fetch_array($ambilsemuadatastock)) {
            $namaproduk = $data['nama'];

            $jumlahproduk = $data['stok'];
            $hargaproduk = $data['harga'];

            $idp = $data['id']

          ?>
            <tr>
              <td><?= $i++; ?></td>
              <td><?= $namaproduk; ?></td>


              <td><?= $hargaproduk; ?></td>
              <td><?= $jumlahproduk; ?></td>

              <td>
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editmodal<?= $idp; ?>">
                  Edit
                </button>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deletemodal<?= $idp; ?>">
                  Delete
                </button>
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

                  <!-- Modal Body -->
                  <div class="modal-body">
                    <form action="" method="POST">
                      <h5>Apakah Anda yakin ingin menghapus <?= $namaproduk; ?> ? </h5>
                      <input type="hidden" name="idp" value="<?= $idp; ?>">
                  </div>


                  <!-- Modal Footer -->
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-warning" name="deleteproduk">Ok</button>
                  </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editmodal<?= $idp; ?>">
              <div class="modal-dialog">
                <div class="modal-content">

                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Edit Produk</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>

                  <!-- Modal body -->
                  <form method="post">
                    <div class="modal-body">
                      <input type="text" name="namaproduk" value="<?= $namaproduk; ?>" class="form-control" required>
                      <br>
                      <input type="number" name="stok" value="<?= $jumlahproduk; ?>" class="form-control" required>
                      <br>
                      <input type="number" name="harga" value="<?= $hargaproduk; ?>" class="form-control" required>
                    </div>
                    <input type="hidden" name="idp" value="<?= $idp; ?>">
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-warning" name="editproduk">Ok</button>
                      <div>
                  </form>
                </div>
              </div>
            </div>
          <?php
          };
          ?>

        </tbody>
      </table>
    </div>
  </section>
  <!-- /.content -->





  <!-- Footer start -->
  <footer>
    <div class="credit">
      <p>Created by <a href="">Eri</a>. | &copy; 2023.</p>
    </div>
  </footer>
  <!-- Footer end -->


  <!-- Feather Icons -->
  <script>
    feather.replace()
  </script>

  <!-- My Javascript -->
  <script src="js/script.js"></script>
</body>

<!-- The Modal -->
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Tambah Produk</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <form method="post">
        <div class="modal-body">
          <input type="text" name="namaproduk" placeholder="Nama Produk" class="form-control" required>
          <br>
          <input type="number" name="quantity" placeholder="Quantity" class="form-control" required>
          <br>
          <input type="number" name="price" placeholder="Price" class="form-control" required>
        </div>


        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" name="addnewproduk">Submit</button>
        </div>
      </form>
      <!--Modal Diatas Jangan Dioprek-->
    </div>
  </div>
</div>

</html>