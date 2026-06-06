<?php
header('Location: index.php');
exit();
?>
<!DOCTYPE html>
<html lang="id">
<?php include 'components/head.php'; ?>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="blur-background"></div>
    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <?php include 'components/page_brand.php'; ?>
        <?php include 'components/alert.php'; ?>
        <?php include 'components/login_form.php'; ?>
    </div>
    <script>
        document.getElementById('toggle-password').addEventListener('click', function() {
            const pwd = document.getElementById('password');
            const icon = this.querySelector('i');
            if (pwd.type === 'password') { pwd.type = 'text'; icon.className = 'fas fa-eye-slash text-slate-400'; }
            else { pwd.type = 'password'; icon.className = 'fas fa-eye text-slate-400'; }
        });
    </script>
</body>
</html>