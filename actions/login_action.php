<?php

function handleLogin(PDO $conn, array $post): string {
    $user = trim($post['username'] ?? '');
    $pass = trim($post['password'] ?? '');

    if ($user === '' || $pass === '') {
        return 'Username dan password wajib diisi.';
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$user]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userData && password_verify($pass, $userData['password'])) {
        $_SESSION['user_id']  = $userData['id'];
        $_SESSION['username'] = $userData['username'];
        $_SESSION['role']     = $userData['role'];

        header("Location: pages/" . $userData['role'] . ".php");
        exit();
    }

    return 'Username atau password salah!';
}
