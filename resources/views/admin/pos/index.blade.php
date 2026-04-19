@extends('layouts.admin')

@section('title', 'POS Terminal')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   POS TERMINAL
   ═══════════════════════════════════════════════════════════ */
.page-body { padding: 0 !important; }

.pos-shell {
    display: flex; flex-direction: column;
    height: calc(100vh - var(--topbar-h));
    overflow: hidden;
    padding: .85rem 1.25rem;
    gap: .85rem;
}

/* Cashier bar */
.pos-cashier-bar {
    background: linear-gradient(135deg, var(--pink-dark) 0%, var(--pink) 100%);
    border-radius: var(--radius); padding: .65rem 1.25rem;
    display: flex; align-items: center; justify-content: space-between;
    color: #fff; box-shadow: 0 4px 14px rgba(247,37,133,.3); flex-shrink: 0;
}
.pos-cashier-info { display: flex; align-items: center; gap: .75rem; }
.pos-cashier-avatar {
    width: 34px; height: 34px; background: rgba(255,255,255,.25);
    border-radius: 9px; display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .88rem; flex-shrink: 0;
}
.pos-cashier-name { font-weight: 600; font-size: .88rem; line-height: 1.2; }
.pos-cashier-role { font-size: .7rem; opacity: .8; }
.pos-clock { font-size: .78rem; opacity: .9; text-align: right; }
.pos-clock strong { display: block; font-size: 1rem; font-weight: 700; font-variant-numeric: tabular-nums; }

/* Main grid */
.pos-wrap {
    display: grid; grid-template-columns: 1fr 390px;
    gap: 1rem; flex: 1; min-height: 0;
}

/* LEFT */
.pos-left { display: flex; flex-direction: column; gap: .75rem; min-height: 0; }

.pos-search-bar { display: flex; gap: .65rem; align-items: center; flex-shrink: 0; }
.pos-search-wrap { flex: 1; position: relative; }
.pos-search-wrap i {
    position: absolute; left: .85rem; top: 50%; transform: translateY(-50%);
    color: var(--text-muted); font-size: .82rem; pointer-events: none;
}
.pos-search-wrap input {
    width: 100%; padding: .6rem .85rem .6rem 2.3rem;
    border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    font-size: .86rem; font-family: inherit; outline: none; background: #fff;
    transition: border-color .18s, box-shadow .18s;
}
.pos-search-wrap input:focus { border-color: var(--pink); box-shadow: 0 0 0 3px rgba(247,37,133,.1); }
.pos-cat-filter {
    padding: .6rem .85rem; border: 1.5px solid var(--border);
    border-radius: var(--radius-sm); font-size: .84rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text); cursor: pointer;
    min-width: 150px; transition: border-color .18s;
}
.pos-cat-filter:focus { border-color: var(--pink); }

.pos-products {
    flex: 1; overflow-y: auto;
    display: grid; grid-template-columns: repeat(auto-fill, minmax(145px, 1fr));
    gap: .7rem; align-content: start; padding-right: .2rem;
}
.pos-product-card {
    background: #fff; border: 1.5px solid var(--border); border-radius: var(--radius);
    padding: .8rem; cursor: pointer; transition: all .18s ease;
    display: flex; flex-direction: column; gap: .45rem;
    position: relative; overflow: hidden;
}
.pos-product-card:hover { border-color: var(--pink); box-shadow: var(--shadow-md); transform: translateY(-2px); }
.pos-product-card.out-of-stock { opacity: .5; cursor: not-allowed; pointer-events: none; }
.pos-product-img { width: 100%; aspect-ratio: 1; border-radius: 8px; object-fit: cover; background: var(--pink-soft); }
.pos-product-img-placeholder {
    width: 100%; aspect-ratio: 1; border-radius: 8px; background: var(--pink-soft);
    display: flex; align-items: center; justify-content: center; color: var(--pink); font-size: 1.4rem;
}
.pos-product-name {
    font-size: .78rem; font-weight: 600; color: var(--text); line-height: 1.3;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.pos-product-price { font-size: .88rem; font-weight: 700; color: var(--pink); }
.pos-product-price s { font-size: .72rem; color: var(--text-muted); font-weight: 400; margin-left: .25rem; }
.pos-product-stock { font-size: .67rem; color: var(--text-muted); }
.pos-product-badge {
    position: absolute; top: .45rem; right: .45rem; background: var(--tango); color: #fff;
    font-size: .58rem; font-weight: 700; padding: .12rem .38rem; border-radius: 20px;
}

/* RIGHT — never scrolls as a whole */
.pos-right {
    display: flex; flex-direction: column; background: #fff;
    border-radius: var(--radius); border: 1.5px solid var(--border);
    box-shadow: var(--shadow); overflow: hidden; height: 100%; min-height: 0;
}

.pos-cart-header {
    padding: .75rem 1rem;
    background: linear-gradient(120deg, #fff 55%, var(--pink-soft) 100%);
    border-bottom: 1.5px solid var(--border);
    display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;
}
.pos-cart-header h3 {
    font-family: 'Playfair Display', serif; font-size: .92rem; font-weight: 700;
    display: flex; align-items: center; gap: .45rem;
}
.pos-cart-count {
    background: var(--pink); color: #fff; font-size: .62rem; font-weight: 700;
    padding: .12rem .42rem; border-radius: 20px;
}

.pos-customer {
    padding: .75rem 1rem; border-bottom: 1.5px solid var(--border);
    background: var(--off-white); flex-shrink: 0;
}
.pos-customer-row { display: grid; grid-template-columns: 1fr 1fr; gap: .45rem; }
.pos-input {
    width: 100%; padding: .48rem .7rem; border: 1.5px solid var(--border);
    border-radius: var(--radius-sm); font-size: .81rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text); transition: border-color .18s;
}
.pos-input:focus { border-color: var(--pink); }
.pos-welcome-banner {
    display: none; padding: .45rem .7rem; background: var(--green-soft);
    border: 1px solid #bbf7d0; border-radius: var(--radius-sm);
    font-size: .76rem; font-weight: 600; color: #15803d;
    margin-top: .45rem; align-items: center; gap: .35rem;
}
.pos-welcome-banner.show { display: flex; }

/* ONLY this scrolls */
.pos-cart-items {
    flex: 1; overflow-y: auto; padding: .65rem 1rem;
    display: flex; flex-direction: column; gap: .55rem; min-height: 0;
}
.pos-cart-empty {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    height: 100%; color: var(--text-muted); gap: .4rem; text-align: center;
}
.pos-cart-empty i { font-size: 1.8rem; opacity: .18; }
.pos-cart-empty p { font-size: .8rem; }
.pos-cart-item {
    display: flex; align-items: center; gap: .6rem; padding: .55rem .7rem;
    background: var(--off-white); border-radius: var(--radius-sm);
    border: 1px solid var(--border); flex-shrink: 0;
}
.pos-cart-item-name { flex: 1; font-size: .78rem; font-weight: 600; color: var(--text); line-height: 1.3; }
.pos-cart-item-price { font-size: .78rem; color: var(--text-muted); white-space: nowrap; }
.pos-qty-control { display: flex; align-items: center; gap: .28rem; }
.pos-qty-btn {
    width: 22px; height: 22px; border-radius: 5px; border: 1.5px solid var(--border);
    background: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center;
    font-size: .72rem; font-weight: 700; color: var(--text); transition: all .15s; line-height: 1;
}
.pos-qty-btn:hover { border-color: var(--pink); color: var(--pink); background: var(--pink-soft); }
.pos-qty-val { width: 26px; text-align: center; font-size: .8rem; font-weight: 700; }
.pos-cart-item-remove {
    color: var(--text-muted); background: none; border: none; cursor: pointer;
    font-size: .78rem; padding: .18rem; transition: color .15s;
}
.pos-cart-item-remove:hover { color: var(--tango); }

/* Totals — fixed */
.pos-totals {
    padding: .7rem 1rem; border-top: 1.5px solid var(--border);
    background: var(--off-white); display: flex; flex-direction: column; gap: .35rem; flex-shrink: 0;
}
.pos-total-row {
    display: flex; justify-content: space-between; align-items: center;
    font-size: .8rem; color: var(--text-muted);
}
.pos-total-row.grand {
    font-size: .95rem; font-weight: 700; color: var(--text);
    padding-top: .35rem; border-top: 1.5px solid var(--border); margin-top: .15rem;
}
.pos-total-row.grand span:last-child { color: var(--pink); }
.pos-discount-input {
    width: 110px; padding: .32rem .55rem; border: 1.5px solid var(--border);
    border-radius: 7px; font-size: .8rem; font-family: inherit; outline: none;
    text-align: right; transition: border-color .18s;
}
.pos-discount-input:focus { border-color: var(--pink); }

/* Payment — ALWAYS visible */
.pos-payment {
    padding: .75rem 1rem; border-top: 1.5px solid var(--border);
    background: #fff; flex-shrink: 0;
}
.pos-pay-methods {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: .45rem; margin-bottom: .65rem;
}
.pos-pay-btn {
    padding: .5rem .35rem; border: 1.5px solid var(--border); border-radius: var(--radius-sm);
    background: #fff; cursor: pointer; font-size: .72rem; font-weight: 600; color: var(--text);
    text-align: center; transition: all .18s; font-family: inherit;
    display: flex; flex-direction: column; align-items: center; gap: .2rem;
}
.pos-pay-btn i { font-size: .95rem; }
.pos-pay-btn:hover { border-color: var(--pink); color: var(--pink); background: var(--pink-soft); }
.pos-pay-btn.active { border-color: var(--pink); background: var(--pink); color: #fff; box-shadow: 0 3px 10px rgba(247,37,133,.3); }
.pos-payment-inputs { display: flex; flex-direction: column; gap: .5rem; margin-bottom: .65rem; }
.pos-mpesa-code { display: none; }
.pos-change-display {
    display: none; padding: .5rem .8rem; background: var(--green-soft);
    border: 1px solid #bbf7d0; border-radius: var(--radius-sm);
    font-size: .82rem; font-weight: 600; color: #15803d;
    justify-content: space-between; align-items: center;
}
.pos-change-display.show { display: flex; }
.pos-charge-btn {
    width: 100%; padding: .78rem;
    background: linear-gradient(135deg, var(--pink) 0%, var(--pink-light) 100%);
    color: #fff; border: none; border-radius: var(--radius-sm);
    font-size: .95rem; font-weight: 700; cursor: pointer; font-family: inherit;
    box-shadow: 0 4px 14px rgba(247,37,133,.35); transition: all .18s;
    display: flex; align-items: center; justify-content: center; gap: .45rem;
}
.pos-charge-btn:hover { box-shadow: 0 6px 22px rgba(247,37,133,.45); transform: translateY(-1px); }
.pos-charge-btn:disabled { opacity: .5; cursor: not-allowed; transform: none; box-shadow: none; }

/* Modal */
.pos-modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(19,7,9,.55); backdrop-filter: blur(3px);
    z-index: 500; align-items: center; justify-content: center;
}
.pos-modal-overlay.show { display: flex; }
.pos-modal {
    background: #fff; border-radius: 20px; padding: 2rem;
    width: 360px; max-width: 95vw; text-align: center;
    box-shadow: var(--shadow-lg); animation: modalIn .22s ease;
}
@keyframes modalIn { from { opacity:0; transform:scale(.93); } to { opacity:1; transform:scale(1); } }
.pos-modal-icon {
    width: 64px; height: 64px; background: var(--green-soft); border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto .75rem; font-size: 1.75rem; color: var(--green);
}
.pos-modal h3 { font-family: 'Playfair Display', serif; font-size: 1.15rem; margin-bottom: .35rem; }
.pos-modal p  { font-size: .84rem; color: var(--text-muted); margin-bottom: .2rem; }
.modal-order-num { font-size: .76rem; font-weight: 700; color: var(--pink); letter-spacing: .05em; margin-bottom: .25rem; }
.modal-change    { font-size: 1.1rem; font-weight: 700; color: var(--green); margin: .5rem 0; }
.modal-cashier   { font-size: .76rem; color: var(--text-muted); margin-bottom: 1rem; }
.pos-modal-actions { display: flex; gap: .65rem; justify-content: center; }
</style>
@endpush

@section('content')
<div class="pos-shell">

    {{-- Cashier bar --}}
    <div class="pos-cashier-bar">
        <div class="pos-cashier-info">
            <div class="pos-cashier-avatar">{{ strtoupper(substr($cashier->name, 0, 1)) }}</div>
            <div>
                <div class="pos-cashier-name">{{ $cashier->name }}</div>
                <div class="pos-cashier-role">Cashier · POS Terminal</div>
            </div>
        </div>
        <div class="pos-clock">
            <strong id="posClock">--:--:--</strong>
            <span id="posDate"></span>
        </div>
    </div>

    <div class="pos-wrap">

        {{-- LEFT: Products --}}
        <div class="pos-left">
            <div class="pos-search-bar">
                <div class="pos-search-wrap">
                    <i class="fas fa-search"></i>
                    <input type="text" id="productSearch" placeholder="Search by name or SKU…" autocomplete="off">
                </div>
                <select class="pos-cat-filter" id="categoryFilter">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="pos-products" id="productsGrid">
                @forelse($products as $product)
                <div class="pos-product-card"
                     onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->getCurrentPrice() }}, {{ $product->stock_quantity }})"
                     data-id="{{ $product->id }}" data-category="{{ $product->category_id }}">
                    @if($product->thumbnail)
                        <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="pos-product-img">
                    @else
                        <div class="pos-product-img-placeholder"><i class="fas fa-spa"></i></div>
                    @endif
                    @if($product->getDiscountPercent() > 0)
                        <span class="pos-product-badge">-{{ $product->getDiscountPercent() }}%</span>
                    @endif
                    <div class="pos-product-name">{{ $product->name }}</div>
                    <div class="pos-product-price">
                        KSh {{ number_format($product->getCurrentPrice(), 2) }}
                        @if($product->sale_price)<s>{{ number_format($product->price, 2) }}</s>@endif
                    </div>
                    <div class="pos-product-stock"><i class="fas fa-box"></i> {{ $product->stock_quantity }} in stock</div>
                </div>
                @empty
                <div style="grid-column:1/-1">
                    <div class="empty-state"><i class="fas fa-box-open"></i><p>No products available</p></div>
                </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT: Cart --}}
        <div class="pos-right">

            <div class="pos-cart-header">
                <h3><i class="fas fa-shopping-basket" style="color:var(--pink)"></i> Cart <span class="pos-cart-count" id="cartCount">0</span></h3>
                <button class="btn btn-sm btn-outline" onclick="clearCart()"><i class="fas fa-trash"></i> Clear</button>
            </div>

            <div class="pos-customer">
                <div class="pos-customer-row">
                    <input type="text" class="pos-input" id="customerName" placeholder="Customer name">
                    <input type="text" class="pos-input" id="customerPhone" placeholder="Phone (07…)" oninput="lookupCustomer(this.value)">
                </div>
                <div class="pos-welcome-banner" id="welcomeBanner">
                    <i class="fas fa-hand-wave"></i>
                    <span id="welcomeMsg"></span>
                </div>
            </div>

            <div class="pos-cart-items" id="cartItems">
                <div class="pos-cart-empty" id="cartEmpty">
                    <i class="fas fa-shopping-basket"></i>
                    <p>Cart is empty<br><small>Click a product to add</small></p>
                </div>
            </div>

            <div class="pos-totals">
                <div class="pos-total-row">
                    <span>Subtotal</span><span id="subtotalDisplay">KSh 0.00</span>
                </div>
                <div class="pos-total-row">
                    <span>Discount</span>
                    <input type="number" class="pos-discount-input" id="discountInput" placeholder="0.00" min="0" step="0.01" oninput="recalculate()">
                </div>
                <div class="pos-total-row grand">
                    <span>TOTAL</span><span id="totalDisplay">KSh 0.00</span>
                </div>
            </div>

            <div class="pos-payment">
                <div class="pos-pay-methods">
                    <button class="pos-pay-btn active" data-method="cash" onclick="selectPayment('cash')">
                        <i class="fas fa-money-bill-wave"></i> Cash
                    </button>
                    <button class="pos-pay-btn" data-method="mpesa" onclick="selectPayment('mpesa')">
                        <i class="fas fa-mobile-screen"></i> M-Pesa
                    </button>
                    <button class="pos-pay-btn" data-method="card" onclick="selectPayment('card')">
                        <i class="fas fa-credit-card"></i> Card
                    </button>
                </div>
                <div class="pos-payment-inputs">
                    <input type="number" class="pos-input" id="amountPaid" placeholder="Amount paid" min="0" step="0.01" oninput="calcChange()">
                    <div class="pos-mpesa-code" id="mpesaCodeWrap">
                        <input type="text" class="pos-input" id="mpesaCode" placeholder="M-Pesa transaction code">
                    </div>
                    <div class="pos-change-display" id="changeDisplay">
                        <span>Change Due</span><strong id="changeAmt">KSh 0.00</strong>
                    </div>
                </div>
                <button class="pos-charge-btn" id="chargeBtn" onclick="processSale()" disabled>
                    <i class="fas fa-cash-register"></i>
                    <span>Charge — <span id="chargeBtnTotal">KSh 0.00</span></span>
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Success Modal --}}
<div class="pos-modal-overlay" id="successModal">
    <div class="pos-modal">
        <div class="pos-modal-icon"><i class="fas fa-check"></i></div>
        <h3>Sale Complete!</h3>
        <div class="modal-order-num" id="modalOrderNum"></div>
        <p id="modalCustomer"></p>
        <div class="modal-change" id="modalChange"></div>
        <div class="modal-cashier" id="modalCashier"></div>
        <div class="pos-modal-actions">
            <a href="#" id="modalReceiptBtn" class="btn btn-outline btn-sm" target="_blank">
                <i class="fas fa-print"></i> Receipt
            </a>
            <button class="btn btn-primary btn-sm" onclick="closeModal()">
                <i class="fas fa-plus"></i> New Sale
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const ROUTES = {
    sale:    '{{ route("admin.pos.sale") }}',
    search:  '{{ route("admin.pos.products.search") }}',
    lookup:  '{{ route("admin.pos.customer.lookup") }}',
    receipt: '{{ url("admin/pos/receipt") }}',
};
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

let cart = [], paymentMethod = 'cash', lookupTimer = null, searchTimer = null;

/* Clock */
function updateClock() {
    const now = new Date();
    document.getElementById('posClock').textContent =
        now.toLocaleTimeString('en-KE', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
    document.getElementById('posDate').textContent =
        now.toLocaleDateString('en-KE', { weekday:'short', day:'numeric', month:'short', year:'numeric' });
}
updateClock(); setInterval(updateClock, 1000);

/* Search */
document.getElementById('productSearch').addEventListener('input', function () {
    clearTimeout(searchTimer); searchTimer = setTimeout(fetchProducts, 350);
});
document.getElementById('categoryFilter').addEventListener('change', fetchProducts);

function fetchProducts() {
    const q = document.getElementById('productSearch').value;
    const cat = document.getElementById('categoryFilter').value;
    fetch(ROUTES.search + '?q=' + encodeURIComponent(q) + '&category_id=' + cat,
          { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(renderProducts).catch(() => {});
}

function renderProducts(products) {
    const grid = document.getElementById('productsGrid');
    if (!products.length) {
        grid.innerHTML = `<div style="grid-column:1/-1"><div class="empty-state"><i class="fas fa-search"></i><p>No products found</p></div></div>`;
        return;
    }
    grid.innerHTML = products.map(p => `
        <div class="pos-product-card ${p.stock_quantity < 1 ? 'out-of-stock' : ''}"
             onclick="addToCart(${p.id}, '${escHtml(p.name)}', ${p.current_price}, ${p.stock_quantity})">
            ${p.thumbnail ? `<img src="/storage/${p.thumbnail}" alt="${escHtml(p.name)}" class="pos-product-img">` : `<div class="pos-product-img-placeholder"><i class="fas fa-spa"></i></div>`}
            ${p.discount_percent > 0 ? `<span class="pos-product-badge">-${p.discount_percent}%</span>` : ''}
            <div class="pos-product-name">${escHtml(p.name)}</div>
            <div class="pos-product-price">KSh ${fmt(p.current_price)}${p.sale_price ? `<s>${fmt(p.price)}</s>` : ''}</div>
            <div class="pos-product-stock"><i class="fas fa-box"></i> ${p.stock_quantity} in stock</div>
        </div>`).join('');
}

/* Cart */
function addToCart(id, name, price, stock) {
    const ex = cart.find(i => i.id === id);
    if (ex) { if (ex.qty >= stock) { flashMsg('Max stock reached for ' + name, 'warning'); return; } ex.qty++; }
    else cart.push({ id, name, price, stock, qty: 1 });
    renderCart();
}
function removeFromCart(id) { cart = cart.filter(i => i.id !== id); renderCart(); }
function changeQty(id, delta) {
    const item = cart.find(i => i.id === id); if (!item) return;
    item.qty += delta;
    if (item.qty <= 0) { removeFromCart(id); return; }
    if (item.qty > item.stock) item.qty = item.stock;
    renderCart();
}
function clearCart() { cart = []; renderCart(); }

function renderCart() {
    const container = document.getElementById('cartItems');
    const empty = document.getElementById('cartEmpty');
    document.getElementById('cartCount').textContent = cart.reduce((s, i) => s + i.qty, 0);
    if (!cart.length) { container.innerHTML = ''; container.appendChild(empty); empty.style.display = 'flex'; recalculate(); return; }
    empty.style.display = 'none';
    container.innerHTML = cart.map(item => `
        <div class="pos-cart-item">
            <div class="pos-cart-item-name">${escHtml(item.name)}</div>
            <div class="pos-qty-control">
                <button class="pos-qty-btn" onclick="changeQty(${item.id}, -1)">−</button>
                <span class="pos-qty-val">${item.qty}</span>
                <button class="pos-qty-btn" onclick="changeQty(${item.id}, 1)">+</button>
            </div>
            <div class="pos-cart-item-price">KSh ${fmt(item.price * item.qty)}</div>
            <button class="pos-cart-item-remove" onclick="removeFromCart(${item.id})"><i class="fas fa-times"></i></button>
        </div>`).join('');
    recalculate();
}

/* Totals */
function getTotal() {
    const sub = cart.reduce((s, i) => s + i.price * i.qty, 0);
    return Math.max(0, sub - (parseFloat(document.getElementById('discountInput').value) || 0));
}
function recalculate() {
    const sub = cart.reduce((s, i) => s + i.price * i.qty, 0);
    const total = getTotal();
    document.getElementById('subtotalDisplay').textContent = 'KSh ' + fmt(sub);
    document.getElementById('totalDisplay').textContent = 'KSh ' + fmt(total);
    document.getElementById('chargeBtnTotal').textContent = 'KSh ' + fmt(total);
    document.getElementById('chargeBtn').disabled = cart.length === 0;
    calcChange();
}
function calcChange() {
    const total = getTotal(), paid = parseFloat(document.getElementById('amountPaid').value) || 0;
    const disp = document.getElementById('changeDisplay');
    if (paymentMethod === 'cash' && paid > 0 && paid >= total) {
        document.getElementById('changeAmt').textContent = 'KSh ' + fmt(paid - total);
        disp.classList.add('show');
    } else { disp.classList.remove('show'); }
}

/* Payment */
function selectPayment(method) {
    paymentMethod = method;
    document.querySelectorAll('.pos-pay-btn').forEach(b => b.classList.toggle('active', b.dataset.method === method));
    document.getElementById('mpesaCodeWrap').style.display = method === 'mpesa' ? 'block' : 'none';
    document.getElementById('amountPaid').placeholder =
        method === 'mpesa' ? 'Amount (M-Pesa)' : method === 'card' ? 'Amount (Card)' : 'Amount paid (cash)';
    calcChange();
}

/* Customer lookup */
function lookupCustomer(phone) {
    clearTimeout(lookupTimer);
    const banner = document.getElementById('welcomeBanner');
    if (phone.length < 10) { banner.classList.remove('show'); return; }
    lookupTimer = setTimeout(() => {
        fetch(ROUTES.lookup + '?phone=' + encodeURIComponent(phone), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                if (data.found) {
                    document.getElementById('welcomeMsg').textContent = data.message;
                    banner.classList.add('show');
                    if (!document.getElementById('customerName').value)
                        document.getElementById('customerName').value = data.name;
                } else { banner.classList.remove('show'); }
            }).catch(() => banner.classList.remove('show'));
    }, 600);
}

/* Process sale */
function processSale() {
    if (!cart.length) { flashMsg('Cart is empty', 'warning'); return; }
    const total = getTotal();
    const paid = parseFloat(document.getElementById('amountPaid').value) || 0;
    if (paymentMethod === 'cash' && paid < total) { flashMsg('Amount paid is less than total', 'danger'); return; }
    const btn = document.getElementById('chargeBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing…';
    fetch(ROUTES.sale, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({
            items: cart.map(i => ({ id: i.id, qty: i.qty })),
            payment_method: paymentMethod,
            customer_name: document.getElementById('customerName').value,
            customer_phone: document.getElementById('customerPhone').value,
            amount_paid: paid,
            discount: parseFloat(document.getElementById('discountInput').value) || 0,
            mpesa_code: document.getElementById('mpesaCode').value,
        }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) { showSuccessModal(data); }
        else { flashMsg(data.message || 'Sale failed', 'danger'); resetBtn(total); }
    })
    .catch(() => { flashMsg('Network error. Please try again.', 'danger'); resetBtn(total); });
}
function resetBtn(total) {
    const btn = document.getElementById('chargeBtn');
    btn.disabled = false;
    btn.innerHTML = `<i class="fas fa-cash-register"></i><span>Charge — <span id="chargeBtnTotal">KSh ${fmt(total)}</span></span>`;
}

/* Modal */
function showSuccessModal(data) {
    document.getElementById('modalOrderNum').textContent = data.order_number;
    document.getElementById('modalCustomer').textContent = data.customer || 'Walk-in Customer';
    document.getElementById('modalCashier').textContent  = 'Served by: ' + data.cashier + ' · ' + data.time;
    document.getElementById('modalReceiptBtn').href      = ROUTES.receipt + '/' + data.order_id;
    const ch = document.getElementById('modalChange');
    if (data.change > 0) { ch.textContent = 'Change: KSh ' + fmt(data.change); ch.style.display = 'block'; }
    else { ch.style.display = 'none'; }
    document.getElementById('successModal').classList.add('show');
}
function closeModal() {
    document.getElementById('successModal').classList.remove('show');
    clearCart();
    ['customerName','customerPhone','amountPaid','mpesaCode','discountInput'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('welcomeBanner').classList.remove('show');
    document.getElementById('chargeBtn').disabled = true;
    document.getElementById('chargeBtn').innerHTML = '<i class="fas fa-cash-register"></i><span>Charge — <span id="chargeBtnTotal">KSh 0.00</span></span>';
    selectPayment('cash');
}

/* Helpers */
function fmt(n) { return parseFloat(n).toLocaleString('en-KE', { minimumFractionDigits:2, maximumFractionDigits:2 }); }
function escHtml(str) { return String(str).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }
function flashMsg(msg, type = 'info') {
    const el = document.createElement('div');
    el.className = `alert alert-${type}`;
    el.style.cssText = 'position:fixed;top:80px;right:1.5rem;z-index:9999;min-width:280px;box-shadow:var(--shadow-lg)';
    el.innerHTML = `<i class="fas fa-circle-exclamation"></i><div>${msg}</div>`;
    document.body.appendChild(el);
    setTimeout(() => { el.style.transition = 'opacity .4s'; el.style.opacity = '0'; setTimeout(() => el.remove(), 400); }, 3500);
}
</script>
@endpush