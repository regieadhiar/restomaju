<div class="min-h-screen">
    <header class="bg-white shadow-sm sticky top-0 z-10">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="?page=logout" class="text-resto-gray hover:text-resto-primary">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <h1 class="text-xl font-bold text-resto-dark">Halaman Kasir</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-resto-gray">
                        <i class="fas fa-user mr-1"></i>
                        <span>Kasir 01</span>
                    </div>
                    <a href="?page=logout" class="text-resto-danger hover:text-red-700">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-xl font-bold text-resto-dark mb-6">Daftar Meja</h2>
                    <div id="tables-grid" class="grid grid-cols-4 gap-4">
                        </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div id="transaction-panel" class="bg-white rounded-xl shadow p-6 hidden">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-resto-dark" id="selected-table-name">Meja 01 - Andi</h2>
                            <p class="text-resto-gray" id="order-time">Pesanan: 15:30</p>
                        </div>
                        <button id="close-transaction" class="text-resto-gray hover:text-resto-dark">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <div class="mb-6">
                        <h3 class="font-semibold text-resto-dark mb-4">Detail Pesanan</h3>
                        <div id="transaction-items" class="space-y-3">
                            </div>
                    </div>

                    <div class="border-t pt-6">
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-2xl font-bold text-resto-dark">TOTAL:</span>
                            <span id="transaction-total" class="text-3xl font-bold text-resto-primary">Rp 0</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-resto-dark mb-2">Uang Dibayar</label>
                                <input type="number" id="cash-paid" class="w-full px-4 py-3 border border-resto-gray/30 rounded-lg" placeholder="0">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-resto-dark mb-2">Kembalian</label>
                                <input type="text" id="cash-change" class="w-full px-4 py-3 border border-resto-gray/30 rounded-lg bg-resto-light" placeholder="0" readonly>
                            </div>
                        </div>

                        <div class="flex space-x-4">
                            <button id="complete-payment-btn" class="flex-1 bg-resto-success text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-600 transition">
                                <i class="fas fa-check-circle mr-2"></i>Selesaikan Pembayaran
                            </button>
                        </div>
                    </div>
                </div>

                <div id="empty-transaction" class="bg-white rounded-xl shadow p-12 text-center">
                    <i class="fas fa-cash-register text-5xl text-resto-gray mb-4"></i>
                    <h3 class="text-xl font-semibold text-resto-dark mb-2">Pilih Meja</h3>
                    <p class="text-resto-gray">Klik salah satu meja di sebelah kiri untuk melihat detail transaksi</p>
                </div>
            </div>
        </div>
    </main>
</div>