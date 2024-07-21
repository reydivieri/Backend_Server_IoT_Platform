<?php

require_once(__DIR__ . '/../php/konek.php');

// add data

if (isset($_POST['addnewproduk'])) {
    // Ensure the file was uploaded correctly
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $namaproduk = mysqli_real_escape_string($conn, $_POST['namaproduk']);
        $price = mysqli_real_escape_string($conn, $_POST['harga']);
        $stok = mysqli_real_escape_string($conn, $_POST['stok']);
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = __DIR__ . "/../img/products/";

        // Ensure the uploads directory exists
        if (!is_dir($image_folder)) {
            mkdir($image_folder, 0777, true);
        }

        // Check if the image size is larger than 2MB
        if ($image_size > 2000000) {
            echo 'Image is too large';
        } else {
            // Generate a unique name for the image to avoid conflicts
            $image_new_name = uniqid() . '-' . basename($image);
            $target_file = $image_folder . $image_new_name;

            // Insert the product details into the database
            $addtotable = mysqli_query($conn, "INSERT INTO tb_produk (nama, harga, img) VALUES ('$namaproduk', '$price', '$image_new_name')");

            if ($addtotable) {
                // Move the uploaded image to the target directory
                if (move_uploaded_file($image_tmp_name, $target_file)) {
                    // Redirect to the product page
                    header('Location: produk.php');
                    exit;
                } else {
                    echo 'Failed to upload the image';
                }
            } else {
                echo 'Failed to add product to the database';
            }
        }
    } else {
        echo 'No image file uploaded or there was an error with the upload';
    }
}

/*
// edit data
if (isset($_POST['editproduk'])) {
    $idp = $_POST['idp'];
    $namaproduk = $_POST['namaproduk'];
    $price = $_POST['harga'];
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../img/products/' . $image;
    $old_image = $_POST['old_image'];



    $updateedit = mysqli_query($conn, "UPDATE tb_produk SET nama = '$namaproduk', harga = '$price' WHERE id ='$idp'");
    if ($updateedit) {
        if (!empty($image)) {
            if ($image_size > 2000000) {
                echo 'image too large';
            } else {
                $update_image = mysqli_query($conn, "UPDATE tb_produk SET img = '$image' WHERE id ='$idp'");
                if ($update_image) {
                    move_uploaded_file($image_tmp_name, $image_folder);
                    unlink('../img/products/' . $old_image);
                }
            }
        }
        header('location:produk.php');
    } else {
        echo 'gagal';
        header('location:produk.php');
    }
}
*/

if (isset($_POST['editproduk'])) {
    // Get the product ID
    $product_id = mysqli_real_escape_string($conn, $_POST['idp']);

    // Get the new product details from the form
    $namaproduk = mysqli_real_escape_string($conn, $_POST['namaproduk']);
    $price = mysqli_real_escape_string($conn, $_POST['harga']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);

    // Initialize the image variables
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = __DIR__ . "/../img/products/";

    // Ensure the uploads directory exists
    if (!is_dir($image_folder)) {
        mkdir($image_folder, 0777, true);
    }

    // Check if a new image was uploaded
    if (!empty($image)) {
        if ($image_size > 2000000) {
            echo 'Image is too large';
        } else {
            // Generate a unique name for the image to avoid conflicts
            $image_new_name = uniqid() . '-' . basename($image);
            $target_file = $image_folder . $image_new_name;

            // Move the uploaded image to the target directory
            if (!move_uploaded_file($image_tmp_name, $target_file)) {
                echo 'Failed to upload the image';
                exit;
            }

            // Get the existing image path to delete the old image if necessary
            $result = mysqli_query($conn, "SELECT img FROM tb_produk WHERE id = '$product_id'");
            $product = mysqli_fetch_assoc($result);
            if ($product && !empty($product['img'])) {
                $old_image_path = $image_folder . $product['img'];
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }

            // Update the product details with the new image
            $update_query = "UPDATE tb_produk SET nama = '$namaproduk', harga = '$price', stok = '$stok', img = '$image_new_name' WHERE id = '$product_id'";
        }
    } else {
        // Update the product details without changing the image
        $update_query = "UPDATE tb_produk SET nama = '$namaproduk', harga = '$price', stok = '$stok' WHERE id = '$product_id'";
    }

    // Execute the update query
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        // Redirect to the product page
        header('Location: produk.php');
        exit;
    } else {
        echo 'Failed to update product in the database';
    }
}



// delete data
if (isset($_POST['deleteproduk'])) {
    $idp = $_POST['idp'];
    $delete = mysqli_query($conn, "DELETE FROM tb_produk WHERE id ='$idp'");
    if ($delete) {
        header('location:produk.php');
    } else {
        echo 'gagal';
        header('location:produk.php');
    }
}
