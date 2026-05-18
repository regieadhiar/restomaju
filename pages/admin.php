<div class="min-h-screen">
    <div class="flex">
        <div class="w-64 bg-resto-dark text-white min-h-screen fixed">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-10 h-10 bg-resto-primary rounded-full flex items-center justify-center">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div>
                        <h2 class="font-bold">Admin Panel</h2>
                        <p class="text-xs text-resto-gray">Pemilik Restoran</p>
                    </div>
                </div>
                <nav class="space-y-2">
                    <a href="#" class="admin-nav-item active bg-resto-primary text-white block px-4 py-2 rounded">
                        <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                    </a>
                    </nav>
            </div>
            <div class="absolute bottom-0 w-full p-6 border-t border-slate-700">
                <a href="?page=logout" class="w-full text-left text-resto-gray hover:text-white block">
                    <i class="fas fa-sign-out-alt mr-3"></i>Keluar
                </a>
            </div>
        </div>

        <div class="ml-64 flex-1">
            <header class="bg-white shadow-sm sticky top-0 z-10">
                <div class="px-6 py-4 flex justify-between items-center">
                    <h1 class="text-xl font-bold text-resto-dark">Dashboard Pemilik</h1>
                    <div class="text-sm text-resto-gray">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        <span id="admin-date">--/--/----</span>
                    </div>
                </div>
            </header>

            <main class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow p-6">
                        <h2 class="text-lg font-bold text-resto-dark mb-6">Manajemen Menu</h2>
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-3 text-resto-gray">Nama Menu</th>
                                    <th class="text-left py-3 text-resto-gray">Kategori</th>
                                    <th class="text-left py-3 text-resto-gray">Harga</th>
                                </tr>
                            </thead>
                            <tbody id="menu-table-body">
                                </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>