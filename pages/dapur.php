<div class="min-h-screen dark-mode bg-slate-900">
    <header class="bg-slate-900 border-b border-slate-700 sticky top-0 z-10">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="?page=logout" class="text-slate-400 hover:text-white">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-white">Kitchen Display System</h1>
                        <p class="text-slate-400 text-sm">Monitor Antrean Pesanan</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-slate-400">
                        <i class="fas fa-clock mr-1"></i>
                        <span id="kitchen-time">--:--</span>
                    </div>
                    <a href="?page=logout" class="text-red-400 hover:text-red-300">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <div id="orders-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div class="col-span-full text-center py-12 text-slate-500">
                <i class="fas fa-concierge-bell text-5xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Tidak ada pesanan</h3>
                <p>Semua pesanan telah diproses</p>
            </div>
        </div>
    </main>
</div>