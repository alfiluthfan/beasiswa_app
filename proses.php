<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Merapikan Nama (Kapital di awal kata)
    $nama = ucwords(strtolower($_POST['nama']));
    
    $email = $_POST['email'];
    
    // Merapikan Nomor HP otomatis jadi +62
    $no_hp = preg_replace('/[^0-9]/', '', $_POST['no_hp']); 
    if (substr($no_hp, 0, 1) == '0') {
        $no_hp = '+62' . substr($no_hp, 1);
    } elseif (substr($no_hp, 0, 2) == '62') {
        $no_hp = '+' . $no_hp;
    } else {
        if (substr($_POST['no_hp'], 0, 1) == '+') { $no_hp = '+' . $no_hp; }
    }

    $semester = $_POST['semester'];
    $ipk = $_POST['ipk'];
    $pilihan = $_POST['pilihan_beasiswa'];
    $status_ajuan = "belum di verifikasi";

    // Validasi Duplikasi Email
    $cek_email = mysqli_query($conn, "SELECT email FROM pendaftaran WHERE email = '$email'");
    if (mysqli_num_rows($cek_email) > 0) {
        $_SESSION['flash_error'] = "Alamat email ini sudah terdaftar. Silakan gunakan email lain.";
        header("Location: index.php");
        exit;
    }

    $nama_file = $_FILES['berkas']['name'];
    $ukuran_file = $_FILES['berkas']['size'];
    $tmp_file = $_FILES['berkas']['tmp_name'];
    
    // Validasi Ukuran File Backend
    if ($ukuran_file > 2097152) {
        $_SESSION['flash_error'] = "Ukuran file berkas melebihi batasan server (2 MB).";
        header("Location: index.php");
        exit;
    }
    
    $path = "uploads/" . $nama_file;
    if(move_uploaded_file($tmp_file, $path)) {
        $query = "INSERT INTO pendaftaran (nama, email, no_hp, semester, ipk, pilihan_beasiswa, berkas, status_ajuan) 
                  VALUES ('$nama', '$email', '$no_hp', '$semester', '$ipk', '$pilihan', '$nama_file', '$status_ajuan')";
                  
        if (mysqli_query($conn, $query)) {
            $_SESSION['flash_success'] = "Selamat! Pendaftaran beasiswa Anda berhasil dikirim dan tersimpan di database.";
            header("Location: hasil.php");
            exit;
        } else {
            $_SESSION['flash_error'] = "Terjadi kesalahan sistem: " . mysqli_error($conn);
            header("Location: index.php");
            exit;
        }
    } else {
        $_SESSION['flash_error'] = "Gagal mengunggah berkas ke server.";
        header("Location: index.php");
        exit;
    }
}
?>