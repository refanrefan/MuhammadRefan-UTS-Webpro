<?php
require_once __DIR__ . "/koneksi.php";

$nim = $_POST['nim'];
$namalengkap = $_POST['namalengkap'];
$nilaiakhir = $_POST['nilaiakhir'];

if (0.00 <= $nilaiakhir && $nilaiakhir <= 34.50) {
    $nilaihuruf = 'E';
    $predikat = 'Tidak Lulus';
} elseif ($nilaiakhir > 34.50 && $nilaiakhir <= 50.50) {
    $nilaihuruf = 'D';
    $predikat = "Kurang Memuaskan";
} elseif ($nilaiakhir > 50.50 && $nilaiakhir <= 60.50) {
    $nilaihuruf = 'C';
    $predikat = 'Cukup Memuaskan';
} elseif ($nilaiakhir > 60.50 && $nilaiakhir <= 80.50) {
    $nilaihuruf = 'B';
    $predikat = 'Memuaskan';
} elseif ($nilaiakhir > 80.50 && $nilaiakhir <= 100.00) {
    $nilaihuruf = 'A';
    $predikat = 'Sangat Memuaskan';
} 

// upload foto
$target_dir = "img/";
$nama_foto = "";

if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0) {
    $file_name = basename($_FILES["foto"]["name"]);
    $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $new_filename = $nim . "_" . time() . "." . $imageFileType;
    $target_file = $target_dir . $new_filename;

    $uploadOk = 1;

    // autentikasi
    $check = getimagesize($_FILES["foto"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo"<script>alert('File Bukan Berupa Gambar.'); 
        window.history.back();
        </script>";
        exit;
    }
    
    //cek ukuran file
    if ($_FILES["foto"]["size"] > 2000000) {
        echo "<script>alert('Ukuran File Terlalu Besar. Maksimal 2MB.'); 
        window.history.back();
        </script>";
        exit;
    }

    //whitelist format
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "<script>alert('Harap Unggah File Dengan Ekstensi JPG, JPEG, dan PNG');
        window.history.back();
        </script>";
        exit;
    }

    //pindah file
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            $nama_foto = $new_filename;
        } else {
            echo "<script>alert('Terjadi Kesalahan saat Mengunggah Foto..');
            window.history.back();
            </script>";
            exit;
        }
    }

}

$sql = "INSERT INTO NilaiMahasiswa (nim, namalengkap, nilaiakhir, nilaihuruf, predikat, foto)
        VALUES ('$nim', '$namalengkap', $nilaiakhir, '$nilaihuruf', '$predikat', '$nama_foto')";

if (mysqli_query($conn, $sql)) {
    echo "<script>
            alert('New record created successfully');
            window.location.href = 'viewdata.php';
          </script>";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// mysqli_close($conn);
header ('Location: viewdata.php');
?>