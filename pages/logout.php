<?php
include __DIR__ . '/../config/db.php';

$_SESSION = array();
session_destroy();
header('Location: ../index.php?msg=Logout Berhasil!');
exit;
?>