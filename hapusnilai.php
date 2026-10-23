<?php
require_once __DIR__ . "/koneksi.php";

if (isset($_POST['nim'])) {
    $nim = $_POST['nim'];
    $sql = "DELETE FROM nilaimahasiswa WHERE nim = '$nim'";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Data Berhasil Dihapus');
                window.location.href = 'viewdata.php';
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
} else {
    echo "NIM tidak ditemukan!";
}
?>