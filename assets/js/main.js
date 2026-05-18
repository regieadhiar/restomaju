let cart = [];

// Fungsi menerima data menu yang diklik dari PHP
function addToCart(id, name, price) {
    const cartItem = cart.find(item => item.id === id);
    
    if (cartItem) {
        cartItem.quantity++;
    } else {
        cart.push({ id: id, name: name, price: price, quantity: 1 });
    }
    updateCart();
}

function updateCart() {
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');
    const sendBtn = document.getElementById('send-order-btn');
    
    if (!cartItemsContainer) return;

    if (cart.length === 0) {
        cartItemsContainer.innerHTML = `
            <div class="text-center py-8 text-resto-gray">
                <i class="fas fa-shopping-cart text-4xl mb-3"></i>
                <p>Keranjang kosong</p>
            </div>`;
        cartTotal.innerText = 'Rp 0';
        sendBtn.disabled = true;
        return;
    }

    let total = 0;
    cartItemsContainer.innerHTML = cart.map(item => {
        const subtotal = item.price * item.quantity;
        total += subtotal;
        return `
            <div class="flex justify-between items-center border-b pb-3">
                <div class="max-w-[60%]">
                    <h4 class="font-semibold text-resto-dark text-sm truncate">${item.name}</h4>
                    <span class="text-resto-primary font-bold text-xs">Rp ${item.price.toLocaleString('id-ID')}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="changeQty(${item.id}, -1)" class="w-6 h-6 rounded bg-resto-light flex items-center justify-center hover:bg-resto-gray/20 font-bold">-</button>
                    <span class="font-bold text-sm">${item.quantity}</span>
                    <button onclick="changeQty(${item.id}, 1)" class="w-6 h-6 rounded bg-resto-light flex items-center justify-center hover:bg-resto-gray/20 font-bold">+</button>
                </div>
            </div>`;
    }).join('');

    cartTotal.innerText = `Rp ${total.toLocaleString('id-ID')}`;
    sendBtn.disabled = false;
}

function changeQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    
    item.quantity += delta;
    if (item.quantity <= 0) {
        cart = cart.filter(i => i.id !== id);
    }
    updateCart();
}