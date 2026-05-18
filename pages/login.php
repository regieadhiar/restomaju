<div class="min-h-screen flex items-center justify-center p-4">
    <div class="blur-bg"></div>
    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-utensils text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-dark">Resto<span class="text-primary">Ku</span></h1>
            <p class="text-gray mt-2">Sistem Pemesanan Terintegrasi</p>
        </div>

        <form action="actions/proses-login.php" method="POST" class="space-y-6">
    <div>
        <label class="block text-sm font-medium text-dark mb-2">Username</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray">
                <i class="fas fa-user"></i>
            </span>
            <input type="text" name="username" required class="w-full pl-10 pr-4 py-3 border border-gray/30 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition" placeholder="Masukkan username Anda">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-dark mb-2">Password</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray">
                <i class="fas fa-lock"></i>
            </span>
            <input type="password" name="password" required class="w-full pl-10 pr-4 py-3 border border-gray/30 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition" placeholder="••••••••">
        </div>
    </div>

    <button type="submit" class="w-full bg-primary text-white py-3 px-4 rounded-lg font-semibold hover:bg-orange-600 transition duration-300 transform active:scale-[0.98]">
        Masuk Ke Sistem
    </button>
</form>
    </div>
</div>