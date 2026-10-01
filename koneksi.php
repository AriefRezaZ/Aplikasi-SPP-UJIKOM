<?php
$host = 'localhost';
$username = 'root';
$password = '';
$db = 'aplikasispp';

$conn = mysqli_connect($host,$username,$password,$db);
if (!$conn)
    {
        die('Koneksi gagal ya');
    }
?>