<?php
// Ganti angka 123456 dengan password apapun yang Anda inginkan
$password_asli = "123456";
$password_hash = password_hash($password_asli, PASSWORD_DEFAULT);

echo "Password Asli: " . $password_asli . "<br><br>";
echo "Copy teks di bawah ini dan paste ke kolom password di database Anda:<br><br>";
echo "<strong style='font-family:monospace; background:#eee; padding:10px;'>" . $password_hash . "</strong>";
?>