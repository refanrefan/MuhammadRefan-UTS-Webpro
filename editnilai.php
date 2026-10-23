<?php
require_once __DIR__ . "/koneksi.php";

if (isset($_POST['nim'])) {
    $nim = $_POST['nim'];
    $namalengkap = $_POST['namalengkap'];
    $nilaiakhir = $_POST['nilaiakhir'];

    if ($nilaiakhir >= 80) {
        $nilaihuruf = 'A';
        $predikat = 'Sangat Memuaskan';
    } elseif ($nilaiakhir >= 70) {
        $nilaihuruf = 'B';
        $predikat = 'Memuaskan';
    } elseif ($nilaiakhir >= 60) {
        $nilaihuruf = 'C';
        $predikat = 'Cukup Memuaskan';
    } elseif ($nilaiakhir >= 50) {
        $nilaihuruf = 'D';
        $predikat = 'Kurang Memuaskan';
    } else {
        $nilaihuruf = 'E';
        $predikat = 'Tidak Lulus';
    }

    // unggah foto (opsional)
    $update_foto = "";

    // cek foto - DIPERBAIKI MENJADI ["name"]
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0 && !empty($_FILES["foto"]["name"])) {
        $target_dir = "img/";
        $file_name = basename($_FILES["foto"]["name"]);
        $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // buat nama file baru
        $new_filename = $nim . "_" . time(). "." . $imageFileType;
        $target_file = $target_dir . $new_filename;

        // autentikasi
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if($check === false) {
            echo "<script>alert('File Bukan Gambar.');
            window.history.back();
            </script>";
            exit;
        }

        //ukuran file
        if ($_FILES["foto"]["size"] > 2000000) {
            echo "<script>alert('Ukuran File Terlalu Besar. Maksimal 2MB.');
            window.history.back();
            </script>";
            exit;
        }

        //whitelist format
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "<script>alert('Harap Unggah Foto Dengan Ekstensi JPG, JPEG, dan PNG.');
            window.history.back();
            </script>";
            exit;
        }
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            $update_foto = ", foto = '$new_filename'";
        } else {
            echo "<script>alert('Gagal mengunggah foto.'); window.history.back();</script>";
            exit;
        }
    }

    $sql = "UPDATE nilaimahasiswa 
            SET namalengkap = '$namalengkap', 
                nilaiakhir = '$nilaiakhir', 
                nilaihuruf = '$nilaihuruf', 
                predikat = '$predikat'
                $update_foto
            WHERE nim = '$nim'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Data Berhasil Diperbarui!');
                window.location.href = 'viewdata.php'; 
              </script>";
    } else {
        echo "Error mengupdate record: " . mysqli_error($conn);
    }
} else {
    echo "Akses ditolak!";
}
?>