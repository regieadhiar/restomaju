<?php
include __DIR__ . '/../config/db.php';
include __DIR__ . '/../actions/cashier_action.php';
checkRole('cashier');

$pageTitle = 'Halaman Kasir - RestoMaju';
$data = handleCashierRequest($conn);
$tables = $data['tables'];
?>
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../components/head.php'; ?>
<body class="bg-slate-50 min-h-screen">
    <header class="bg-white shadow-sm p-4 sticky top-0 z-10 flex justify-between items-center">
        <h1 class="text-xl font-bold text-slate-800"><i class="fas fa-cash-register text-orange-500 mr-2"></i>Halaman Kasir</h1>
        <div class="flex items-center space-x-4">
            <span class="text-sm text-slate-500"><i class="fas fa-user"></i> Kasir Resto (<?= $_SESSION['username'] ?>)</span>
            <a href="logout.php" class="text-red-500 text-sm font-semibold"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </header>

    <main class="container mx-auto px-4 py-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Daftar Meja</h2>
            <div class="grid grid-cols-3 gap-3">
                <?php foreach($tables as $t): 
                    $color = "bg-slate-100 text-slate-700 border-slate-200";
                    if($t['status'] === 'occupied') $color = "bg-red-50 text-red-600 border-red-200";
                    if($t['status'] === 'ready') $color = "bg-amber-50 text-amber-600 border-amber-200 blink";
                ?>
                    <button onclick="loadInvoice(<?= $t['id'] ?>)" class="p-3 border-2 rounded-xl text-center font-bold text-sm transition transform hover:scale-105 <?= $color ?>">
                        <div class="text-xl"><?= substr($t['table_number'], -2) ?></div>
                        <div class="text-xs font-medium mt-1"><?= $t['status'] === 'ready' ? 'Siap Bayar' : ($t['status']==='occupied'?'Memasak':'Kosong') ?></div>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div id="blank-panel" class="bg-white rounded-xl shadow p-12 text-center text-slate-400">
                <i class="fas fa-receipt text-5xl mb-4"></i>
                <p class="font-medium">Pilih meja yang berstatus 'Siap Bayar' untuk memuat struk belanjaan.</p>
            </div>

            <div id="invoice-panel" class="bg-white rounded-xl shadow p-6 hidden">
                <div class="flex justify-between items-center border-b pb-4 mb-4">
                    <div><h2 id="inv-title" class="text-xl font-bold text-slate-800">Meja --</h2><p id="inv-time" class="text-xs text-slate-400">Pesanan: --</p></div>
                    <i class="fas fa-utensils text-orange-500 text-xl"></i>
                </div>
                
                <div id="inv-items" class="space-y-3 text-sm text-slate-600 mb-6"></div>
                
                <div class="border-t pt-4 mb-6 space-y-4">
                    <!-- Discount Code Section -->
                    <div class="grid grid-cols-3 gap-2">
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kode Diskon (Opsional)</label>
                            <input type="text" id="discount-code" placeholder="Contoh: DISKON10" class="w-full border p-2.5 rounded-lg outline-none focus:ring-2 focus:ring-orange-500 uppercase">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">&nbsp;</label>
                            <button type="button" onclick="validateDiscount()" class="w-full bg-blue-500 text-white p-2.5 rounded-lg font-bold text-sm hover:bg-blue-600">Terapkan</button>
                        </div>
                    </div>
                    <div id="discount-info" class="text-sm text-slate-600"></div>

                    <!-- Price Breakdown -->
                    <div class="bg-slate-50 p-3 rounded-lg space-y-2">
                        <div class="flex justify-between text-sm">
                            <span>Subtotal:</span>
                            <span id="subtotal-text" class="font-semibold">Rp 0</span>
                        </div>
                        <div id="discount-line" class="flex justify-between text-sm text-red-600 hidden">
                            <span>Diskon:</span>
                            <span id="discount-amount">- Rp 0</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span>Pajak (10%):</span>
                            <span id="tax-text" class="font-semibold">Rp 0</span>
                        </div>
                    </div>

                    <!-- Tip Section -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tip untuk Pelayan (Opsional)</label>
                        <div class="grid grid-cols-4 gap-2">
                            <button type="button" onclick="setTip(0)" class="border p-2 rounded-lg text-sm font-bold hover:bg-slate-100">Tanpa Tip</button>
                            <button type="button" onclick="setTip(5000)" class="border p-2 rounded-lg text-sm font-bold hover:bg-slate-100">Rp 5K</button>
                            <button type="button" onclick="setTip(10000)" class="border p-2 rounded-lg text-sm font-bold hover:bg-slate-100">Rp 10K</button>
                            <input type="number" id="tip-custom" placeholder="Jumlah lain" class="border p-2 rounded-lg text-sm outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div class="flex justify-between mt-2 text-sm">
                            <span>Tip:</span>
                            <span id="tip-text" class="font-semibold text-green-600">Rp 0</span>
                        </div>
                    </div>
                </div>
                
                <form action="?action=pay" method="POST" class="border-t pt-4">
                    <input type="hidden" id="inv-table-id" name="table_id">
                    <input type="hidden" id="discount-percent" name="discount_percent" value="0">
                    <input type="hidden" id="tip-amount" name="tip" value="0">
                    
                    <div class="flex justify-between items-center mb-6 bg-orange-50 p-4 rounded-lg border-2 border-orange-200">
                        <span class="text-xl font-bold">TOTAL AKHIR:</span>
                        <span id="inv-total-text" class="text-3xl font-extrabold text-orange-500">Rp 0</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Uang Tunai Dibayarkan</label>
                            <input type="number" id="cash-paid" class="w-full border p-3 rounded-lg outline-none focus:ring-2 focus:ring-orange-500 font-bold text-lg" value="0">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kembalian</label>
                            <input type="text" id="cash-change" class="w-full border p-3 rounded-lg bg-slate-50 font-bold text-lg text-green-600" value="Rp 0" readonly>
                        </div>
                    </div>

                            <div class="flex space-x-3">
                        <button type="button" onclick="showReceiptPopup()" class="w-1/3 bg-slate-600 text-white p-3 rounded-lg font-bold hover:bg-slate-700 no-print"><i class="fas fa-eye mr-1"></i> Lihat Struk</button>
                        <button type="submit" id="btn-complete" class="w-2/3 bg-green-500 text-white p-3 rounded-lg font-bold hover:bg-green-600 transition no-print">Selesaikan Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <div id="receipt-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4">
        <div class="w-full max-w-xl bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b bg-slate-100">
                <div>
                    <h3 class="font-bold text-lg text-slate-800">Struk Pembayaran</h3>
                </div>
                <button type="button" onclick="closeReceiptPopup()" class="text-slate-500 hover:text-slate-800 font-bold">✕</button>
            </div>
            <div id="receipt-content" class="p-6 space-y-4 receipt-card"></div>
            <div class="flex justify-end gap-3 p-6 border-t no-print">
                <button type="button" onclick="closeReceiptPopup()" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100">Tutup</button>
                <button type="button" onclick="printReceiptPdf()" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100">Cetak PDF</button>
            </div>
        </div>  
    </div>

    <style>
        @media print {
            html, body {
                width: 100%;
                height: auto !important;
                margin: 0;
                padding: 0;
                background: #fff;
            }
            body * {
                display: none !important;
                visibility: hidden !important;
            }
            #receipt-modal,
            #receipt-modal * {
                display: block !important;
                visibility: visible !important;
            }
            #receipt-modal {
                position: static !important;
                inset: auto !important;
                width: 100% !important;
                min-height: auto !important;
                background: transparent !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 auto !important;
            }
            #receipt-modal > div {
                box-shadow: none !important;
                border: none !important;
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 auto !important;
            }
            .receipt-card {
                width: 320px;
                max-width: 100%;
                margin: 0 auto !important;
                padding: 0 !important;
            }
            .no-print,
            .receipt-card .no-print {
                display: none !important;
            }
            .receipt-row {
                page-break-inside: avoid !important;
            }
            @page {
                size: auto;
                margin: 10mm;
            }
        }
    </style>

    <script>
        let currentTotal = 0;
        let currentDiscount = 0;
        let currentTip = 0;
        let discountPercent = 0;
        
        function loadInvoice(tableId) {
            // Reset form
            currentDiscount = 0;
            currentTip = 0;
            discountPercent = 0;
            document.getElementById('discount-code').value = '';
            document.getElementById('discount-info').innerHTML = '';
            document.getElementById('discount-line').classList.add('hidden');
            document.getElementById('tip-custom').value = '';
            
            fetch('?action=get_bill&table_id=' + tableId)
            .then(res => res.json())
            .then(data => {
                if(!data || data.error) {
                    document.getElementById('invoice-panel').classList.add('hidden');
                    document.getElementById('blank-panel').classList.remove('hidden');
                    return;
                }
                document.getElementById('blank-panel').classList.add('hidden');
                document.getElementById('invoice-panel').classList.remove('hidden');
                
                document.getElementById('inv-table-id').value = tableId;
                document.getElementById('inv-title').textContent = data.table_number + " - " + data.customer_name;
                document.getElementById('inv-time').textContent = "Jam Pemesanan: " + data.order_time;
                
                currentTotal = data.total_amount;
                updateTotalDisplay();
                
                document.getElementById('inv-items').innerHTML = data.items.map(i => `
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <div><b>${i.name}</b> <span class="text-xs text-slate-400 ml-1">x${i.quantity}</span></div>
                        <div class="font-semibold text-slate-800">Rp ${(i.price * i.quantity).toLocaleString('id-ID')}</div>
                    </div>
                `).join('');
            })
            .catch(err => {
                console.error("Gagal memuat invoice:", err);
            });
        }

        function validateDiscount() {
            const code = document.getElementById('discount-code').value.trim();
            if (!code) {
                alert('Masukkan kode diskon');
                return;
            }

            fetch('?action=validate_discount&code=' + encodeURIComponent(code))
            .then(res => res.json())
            .then(data => {
                if (data.valid) {
                    discountPercent = data.discount_percent;
                    currentDiscount = (currentTotal * discountPercent) / 100;
                    document.getElementById('discount-info').innerHTML = `<div class="text-green-600 font-semibold">✓ Diskon ${discountPercent}% berhasil diterapkan</div>`;
                    document.getElementById('discount-percent').value = discountPercent;
                    updateTotalDisplay();
                } else {
                    alert(data.message || 'Kode diskon tidak valid');
                    document.getElementById('discount-info').innerHTML = `<div class="text-red-600 font-semibold">✗ Kode tidak valid</div>`;
                    discountPercent = 0;
                    currentDiscount = 0;
                    document.getElementById('discount-percent').value = 0;
                    updateTotalDisplay();
                }
            });
        }

        function setTip(amount) {
            currentTip = amount;
            document.getElementById('tip-custom').value = amount || '';
            document.getElementById('tip-amount').value = amount;
            updateTotalDisplay();
        }

        document.getElementById('tip-custom').addEventListener('input', function() {
            currentTip = parseFloat(this.value) || 0;
            document.getElementById('tip-amount').value = currentTip;
            updateTotalDisplay();
        });

        function updateTotalDisplay() {
            const subtotal = currentTotal;
            const tax = (subtotal * 0.1); // 10% tax
            const afterDiscount = subtotal - currentDiscount;
            const total = afterDiscount + tax + currentTip;

            document.getElementById('subtotal-text').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            
            if (currentDiscount > 0) {
                document.getElementById('discount-line').classList.remove('hidden');
                document.getElementById('discount-amount').textContent = '- Rp ' + currentDiscount.toLocaleString('id-ID');
            } else {
                document.getElementById('discount-line').classList.add('hidden');
            }

            document.getElementById('tax-text').textContent = 'Rp ' + tax.toLocaleString('id-ID');
            document.getElementById('tip-text').textContent = 'Rp ' + currentTip.toLocaleString('id-ID');
            document.getElementById('inv-total-text').textContent = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('cash-paid').value = total;
            document.getElementById('cash-change').value = 'Rp 0';
        }

        document.getElementById('cash-paid').addEventListener('input', function() {
            const subtotal = currentTotal;
            const tax = (subtotal * 0.1);
            const total = subtotal - currentDiscount + tax + currentTip;
            let paid = parseFloat(this.value) || 0;
            let change = paid - total;
            document.getElementById('cash-change').value = change >= 0 ? "Rp " + change.toLocaleString('id-ID') : "Uang Kurang";
            document.getElementById('btn-complete').disabled = change < 0;
        });

        function showReceiptPopup() {
            const subtotal = currentTotal;
            const tax = (subtotal * 0.1);
            const total = subtotal - currentDiscount + tax + currentTip;
            const title = document.getElementById('inv-title').textContent;
            const time = document.getElementById('inv-time').textContent;

            const items = Array.from(document.querySelectorAll('#inv-items > div')).map(item => item.innerHTML);
            const itemsHtml = items.map(line => `<div class="p-3 rounded-2xl bg-slate-50 border border-slate-200">${line}</div>`).join('');

            document.getElementById('receipt-content').innerHTML = `
                <div class="receipt-card bg-white rounded-3xl border border-slate-200 p-5 text-slate-800">
                    <div class="text-center space-y-1">
                        <p class="font-bold text-base">RESTOMAJU</p>
                        <p class="text-xs text-slate-500">Struk Pembayaran</p>
                        <div class="h-px bg-slate-200 my-3"></div>
                    </div>

                    <div class="text-xs text-slate-600 space-y-1">
                        <div class="flex justify-between"><span>Meja</span><span>${title}</span></div>
                        <div class="flex justify-between"><span>Waktu</span><span>${time}</span></div>
                    </div>

                    <div class="mt-4 text-sm text-slate-700 space-y-2">
                        ${itemsHtml}
                    </div>

                    <div class="mt-4 border-t border-slate-200 pt-3 text-sm space-y-2">
                        <div class="flex justify-between text-slate-600"><span>Subtotal</span><span>Rp ${subtotal.toLocaleString('id-ID')}</span></div>
                        ${currentDiscount > 0 ? `<div class="flex justify-between text-red-600"><span>Diskon (${discountPercent}%)</span><span>- Rp ${currentDiscount.toLocaleString('id-ID')}</span></div>` : ''}
                        <div class="flex justify-between text-slate-600"><span>Pajak 10%</span><span>Rp ${tax.toLocaleString('id-ID')}</span></div>
                        ${currentTip > 0 ? `<div class="flex justify-between text-slate-600"><span>Tip</span><span>Rp ${currentTip.toLocaleString('id-ID')}</span></div>` : ''}
                        <div class="border-t border-slate-200 pt-2 flex justify-between font-bold text-slate-800"><span>Total</span><span>Rp ${total.toLocaleString('id-ID')}</span></div>
                    </div>

                    <div class="mt-4 text-center text-xs text-slate-500">Terima kasih, selamat menikmati!</div>
                </div>
            `;

            document.getElementById('receipt-modal').classList.remove('hidden');
        }

        function closeReceiptPopup() {
            document.getElementById('receipt-modal').classList.add('hidden');
        }

        function printReceiptPdf() {
            const element = document.getElementById('receipt-content');

            const elementHeight = Math.ceil(element.offsetHeight * 0.264583) + 10;

            const opt = {
                margin:       0,
                filename:     'struk-pembayaran.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 3 },
                jsPDF:        { unit: 'mm', format: [110, elementHeight], orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).save();
        }

    </script>
</body>
</html>