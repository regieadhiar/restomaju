<?php
$oldUsername = htmlspecialchars($_POST['username'] ?? '');
?>
<form action="index.php" method="POST" class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">Username</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-user text-slate-400"></i>
            </div>
            <input type="text" name="username" value="<?= $oldUsername ?>" class="pl-10 w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none" placeholder="Masukkan username" required>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">Password</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-lock text-slate-400"></i>
            </div>
            <input type="password" id="password" name="password" class="pl-10 w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none" placeholder="Masukkan password" required>
            <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <i class="fas fa-eye text-slate-400"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="w-full bg-orange-500 text-white py-3 px-4 rounded-lg font-semibold hover:bg-orange-600 transition duration-300 focus:ring-2 focus:ring-orange-500">
        <i class="fas fa-sign-in-alt mr-2"></i>Masuk Sistem
    </button>
</form>