@extends('layouts.admin')

@section('title', 'POS Terminal')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   POS TERMINAL
   ═══════════════════════════════════════════════════════════ */
.pos-wrap {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 1.25rem;
    height: calc(100vh - var(--topbar-h) - 3rem);
    min-height: 600px;
}

/* ── Left panel ─────────────────────────────────────────── */
.pos-left {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    min-height: 0;
}

/* Cashier bar */
.pos-cashier-bar {
    background: linear-gradient(135deg, var(--pink-dark) 0%, var(--pink) 100%);
    border-radius: var(--radius);
    padding: .75rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #fff;
    box-shadow: 0 4px 14px rgba(247,37,133,.3);
}
.pos-cashier-info { display: flex; align-items: center; gap: .75rem; }
.pos-cashier-avatar {
    width: 36px; height: 36px;
    background: rgba(255,255,255,.25);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .9rem;
}
.pos-cashier-name { font-weight: 600; font-size: .9rem; line-height: 1.2; }
.pos-cashier-role { font-size: .72rem; opacity: .8; }
.pos-clock { font-size: .82rem; opacity: .9; text-align: right; }
.pos-clock strong { display: block; font-size: 1.05rem; font-weight: 700; font-variant-numeric: tabular-nums; }

/* Search & filter bar */
.pos-search-bar {
    display: flex;
    gap: .65rem;
    align-items: center;
}
.pos-search-wrap {
    flex: 1; position: relative;
}
.pos-search-wrap i {
    position: absolute; left: .9rem; top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted); font-size: .85rem;
    pointer-events: none;
}
.pos-search-wrap input {
    width: 100%;
    padding: .65rem .9rem .65rem 2.4rem;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    font-size: .87rem; font-family: inherit;
    outline: none; background: #fff;
    transition: border-color .18s, box-shadow .18s;
}
.pos-search-wrap input:focus {
    border-color: var(--pink);
    box-shadow: 0 0 0 3px rgba(247,37,133,.1);
}
.pos-cat-filter {
    padding: .65rem .9rem;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    font-size: .85rem; font-family: inherit;
    outline: none; background: #fff;
    color: var(--text); cursor: pointer;
    min-width: 140px;
    transition: border-color .18s;
}
.pos-cat-filter:focus { border-color: var(--pink); }

/* Products grid */
.pos-products {
    flex: 1;
    overflow-y: auto;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(148px, 1fr));
    gap: .75rem;
    align-content: start;
    padding-right: .25rem;
}
.pos-product-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    padding: .85rem;
    cursor: pointer;
    transition: all .18s ease;
    display: flex; flex-direction: column;
    gap: .5rem;
    position: relative;
    overflow: hidden;
}
.pos-product-card:hover {
    border-color: var(--pink);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}
.pos-product-card.out-of-stock {
    opacity: .5; cursor: not-allowed;
    pointer-events: none;
}
.pos-product-img {
    width: 100%; aspect-ratio: 1;
    border-radius: 9px;
    object-fit: cover;
    background: var(--pink-soft);
}
.pos-product-img-placeholder {
    width: 100%; aspect-ratio: 1;
    border-radius: 9px;
    background: var(--pink-soft);
    display: flex; align-items: center; justify-content: center;
    color: var(--pink); font-size: 1.5rem;
}
.pos-product-name {
    font-size: .8rem; font-weight: 600;
    color: var(--text); line-height: 1.3;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
}
.pos-product-price {
    font-size: .9rem; font-weight: 700;
    color: var(--pink);
}
.pos-product-price s {
    font-size: .75rem; color: var(--text-muted);
    font-weight: 400; margin-left: .3rem;
}
.pos-product-stock {
    font-size: .68rem; color: var(--text-muted);
}
.pos-product-badge {
    position: absolute; top: .5rem; right: .5rem;
    background: var(--tango);
    color: #fff; font-size: .6rem; font-weight: 700;
    padding: .15rem .4rem; border-radius: 20px;
}

/* ── Right panel ────────────────────────────────────────── */
.pos-right {
    display: flex;
    flex-direction: column;
    gap: 0;
    background: #fff;
    border-radius: var(--radius);
    border: 1.5px solid var(--border);
    box-shadow: var(--shadow);
    overflow: hidden;
}

/* Cart header */
.pos-cart-header {
    padding: .9rem 1.1rem;
    background: linear-gradient(120deg, #fff 55%, var(--pink-soft) 100%);
    border-bottom: 1.5px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
}
.pos-cart-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: .95rem; font-weight: 700;
    display: flex; align-items: center; gap: .5rem;
}
.pos-cart-count {
    background: var(--pink); color: #fff;
    font-size: .65rem; font-weight: 700;
    padding: .15rem .45rem; border-radius: 20px;
}

/* Customer section */
.pos-customer {
    padding: .85rem 1.1rem;
    border-bottom: 1.5px solid var(--border);
    background: var(--off-white);
}
.pos-customer-row {
    display: grid; grid-template-columns: 1fr 1fr; gap: .5rem;
    margin-bottom: .5rem;
}
.pos-customer-row:last-child { margin-bottom: 0; }
.pos-input {
    width: 100%; padding: .5rem .75rem;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    font-size: .82rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    transition: border-color .18s;
}
.pos-input:focus { border-color: var(--pink); }
.pos-welcome-banner {
    display: none;
    padding: .5rem .75rem;
    background: var(--green-soft);
    border: 1px solid #bbf7d0;
    border-radius: var(--radius-sm);
    font-size: .78rem; font-weight: 600;
    color: #15803d;
    margin-top: .5rem;
    align-items: center; gap: .4rem;
}
.pos-welcome-banner.show { display: flex; }

/* Cart items */
.pos-cart-items {
    flex: 1; overflow-y: auto;
    padding: .75rem 1.1rem;
    display: flex; flex-direction: column; gap: .6rem;
}
.pos-cart-empty {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    height: 100%; color: var(--text-muted);
    gap: .5rem; text-align: center;
}
.pos-cart-empty i { font-size: 2rem; opacity: .2; }
.pos-cart-empty p { font-size: .82rem; }

.pos-cart-item {
    display: flex; align-items: center; gap: .65rem;
    padding: .6rem .75rem;
    background: var(--off-white);
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
}
.pos-cart-item-name {
    flex: 1; font-size: .8rem; font-weight: 600;
    color: var(--text); line-height: 1.3;
}
.pos-cart-item-price {
    font-size: .8rem; color: var(--text-muted);
    white-space: nowrap;
}
.pos-qty-control {
    display: flex; align-items: center; gap: .3rem;
}
.pos-qty-btn {
    width: 24px; height: 24px;
    border-radius: 6px;
    border: 1.5px solid var(--border);
    background: #fff; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem; font-weight: 700; color: var(--text);
    transition: all .15s;
    line-height: 1;
}
.pos-qty-btn:hover { border-color: var(--pink); color: var(--pink); background: var(--pink-soft); }
.pos-qty-val {
    width: 28px; text-align: center;
    font-size: .82rem; font-weight: 700;
}
.pos-cart-item-remove {
    color: var(--text-muted); background: none; border: none;
    cursor: pointer; font-size: .8rem; padding: .2rem;
    transition: color .15s;
}
.pos-cart-item-remove:hover { color: var(--tango); }

/* Totals */
.pos-totals {
    padding: .85rem 1.1rem;
    border-top: 1.5px solid var(--border);
    background: var(--off-white);
    display: flex; flex-direction: column; gap: .4rem;
}
.pos-total-row {
    display: flex; justify-content: space-between;
    font-size: .82rem; color: var(--text-muted);
}
.pos-total-row.grand {
    font-size: 1rem; font-weight: 700;
    color: var(--text); padding-top: .4rem;
    border-top: 1.5px solid var(--border);
    margin-top: .2rem;
}
.pos-total-row.grand span:last-child { color: var(--pink); }
.pos-discount-row {
    display: flex; gap: .5rem; align-items: center;
}
.pos-discount-row input {
    flex: 1; padding: .4rem .65rem;
    border: 1.5px solid var(--border);
    border-radius: 7px; font-size: .82rem;
    font-family: inherit; outline: none;
    transition: border-color .18s;
}
.pos-discount-row input:focus { border-color: var(--pink); }

/* Payment section */
.pos-payment {
    padding: .85rem 1.1rem;
    border-top: 1.5px solid var(--border);
}
.pos-pay-methods {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: .5rem; margin-bottom: .75rem;
}
.pos-pay-btn {
    padding: .55rem .4rem;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    background: #fff; cursor: pointer;
    font-size: .75rem; font-weight: 600;
    color: var(--text); text-align: center;
    transition: all .18s; font-family: inherit;
    display: flex; flex-direction: column;
    align-items: center; gap: .25rem;
}
.pos-pay-btn i { font-size: 1rem; }
.pos-pay-btn:hover { border-color: var(--pink); color: var(--pink); background: var(--pink-soft); }
.pos-pay-btn.active {
    border-color: var(--pink); background: var(--pink);
    color: #fff; box-shadow: 0 3px 10px rgba(247,37,133,.3);
}
.pos-amount-paid {
    margin-bottom: .75rem;
}
.pos-mpesa-code {
    margin-bottom: .75rem; display: none;
}
.pos-change-display {
    display: none;
    padding: .6rem .85rem;
    background: var(--green-soft);
    border: 1px solid #bbf7d0;
    border-radius: var(--radius-sm);
    font-size: .85rem; font-weight: 600;
    color: #15803d; margin-bottom: .75rem;
    justify-content: space-between;
    align-items: center;
}
.pos-change-display.show { display: flex; }

.pos-charge-btn {
    width: 100%;
    padding: .85rem;
    background: linear-gradient(135deg, var(--pink) 0%, var(--pink-light) 100%);
    color: #fff; border: none; border-radius: var(--radius-sm);
    font-size: 1rem; font-weight: 700; cursor: pointer;
    font-family: inherit;
    box-shadow: 0 4px 14px rgba(247,37,133,.35);
    transition: all .18s;
    display: flex; align-items: center; justify-content: center; gap: .5rem;
}
.pos-charge-btn:hover { box-shadow: 0 6px 22px rgba(247,37,133,.45); transform: translateY(-1px); }
.pos-charge-btn:disabled { opacity: .55; cursor: not-allowed; transform: none; }

/* ── Success modal ──────────────────────────────────────── */
.pos-modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(19,7,9,.55); backdrop-filter: blur(3px);
    z-index: 500; align-items: center; justify-content: center;
}
.pos-modal-overlay.show { display: flex; }
.pos-modal {
    background: #fff; border-radius: 20px;
    padding: 2rem; width: 360px; max-width: 95vw;
    text-align: center; box-shadow: var(--shadow-lg);
    animation: modalIn .22s ease;
}
@keyframes modalIn {
    from { opacity: 0; transform: scale(.93); }
    to   { opacity: 1; transform: scale(1); }
}
.pos-modal-icon {
    width: 64px; height: 64px;
    background: var(--green-soft);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto .75rem;
    font-size: 1.75rem; color: var(--green);
}
.pos-modal h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem; margin-bottom: .35rem;
}
.pos-modal p { font-size: .85rem; color: var(--text-muted); margin-bottom: .2rem; }
.pos-modal .order-num {
    font-size: .78rem; font-weight: 700;
    color: var(--pink); letter-spacing: .05em;
    margin-bottom: .25rem;
}
.pos-modal .change-due {
    font-size: 1.1rem; font-weight: 700;
    color: var(--green); margin: .5rem 0;
}
.pos-modal .cashier-line {
    font-size: .78rem; color: var(--text-muted);
    margin-bottom: 1rem;
}
.pos-modal-actions {
    display: flex; gap: .65rem; justify-content: center;
}
</style>
@endpush

@section('content')

{{-- Cashier bar --}}
<div class="pos-cashier-bar" style="margin-bottom:1rem">
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

    {{-- ══ LEFT: Products ══ --}}
    <div class="pos-left">

        {{-- Search & filter --}}
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

        {{-- Products grid --}}
        <div class="pos-products" id="productsGrid">
            @forelse($products as $product)
            <div class="pos-product-card"
                 onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->getCurrentPrice() }}, {{ $product->stock_quantity }})"
                 data-id="{{ $product->id }}"
                 data-name="{{ $product->name }}"
                 data-price="{{ $product->getCurrentPrice() }}"
                 data-stock="{{ $product->stock_quantity }}"
                 data-category="{{ $product->category_id }}">

                @if($product->thumbnail)
                    <img src="{{ asset('storage/' . $product->thumbnail) }}"
                         alt="{{ $product->name }}" class="pos-product-img">
                @else
                    <div class="pos-product-img-placeholder">
                        <i class="fas fa-spa"></i>
                    </div>
                @endif

                @if($product->getDiscountPercent() > 0)
                    <span class="pos-product-badge">-{{ $product->getDiscountPercent() }}%</span>
                @endif

                <div class="pos-product-name">{{ $product->name }}</div>
                <div class="pos-product-price">
                    KSh {{ number_format($product->getCurrentPrice(), 2) }}
                    @if($product->sale_price)
                        <s>{{ number_format($product->price, 2) }}</s>
                    @endif
                </div>
                <div class="pos-product-stock">
                    <i class="fas fa-box"></i> {{ $product->stock_quantity }} in stock
                </div>
            </div>
            @empty
            <div style="grid-column:1/-1">
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <p>No products available</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ══ RIGHT: Cart ══ --}}
    <div class="pos-right">

        {{-- Cart header --}}
        <div class="pos-cart-header">
            <h3><i class="fas fa-shopping-basket" style="color:var(--pink)"></i> Cart <span class="pos-cart-count" id="cartCount">0</span></h3>
            <button class="btn btn-sm btn-outline" onclick="clearCart()">
                <i class="fas fa-trash"></i> Clear
            </button>
        </div>

        {{-- Customer --}}
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

        {{-- Cart items --}}
        <div class="pos-cart-items" id="cartItems">
            <div class="pos-cart-empty" id="cartEmpty">
                <i class="fas fa-shopping-basket"></i>
                <p>Cart is empty<br><small>Click a product to add</small></p>
            </div>
        </div>

        {{-- Totals --}}
        <div class="pos-totals">
            <div class="pos-total-row">
                <span>Subtotal</span>
                <span id="subtotalDisplay">KSh 0.00</span>
            </div>
            <div class="pos-total-row">
                <span>Discount</span>
                <div class="pos-discount-row">
                    <input type="number" id="discountInput" placeholder="0.00" min="0" step="0.01" oninput="recalculate()">
                </div>
            </div>
            <div class="pos-total-row grand">
                <span>TOTAL</span>
                <span id="totalDisplay">KSh 0.00</span>
            </div>
        </div>

        {{-- Payment --}}
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

            <div class="pos-amount-paid">
                <input type="number" class="pos-input" id="amountPaid"
                       placeholder="Amount paid" min="0" step="0.01"
                       oninput="calcChange()" style="width:100%">
            </div>

            <div class="pos-mpesa-code" id="mpesaCodeWrap">
                <input type="text" class="pos-input" id="mpesaCode"
                       placeholder="M-Pesa transaction code" style="width:100%">
            </div>

            <div class="pos-change-display" id="changeDisplay">
                <span>Change Due</span>
                <strong id="changeAmt">KSh 0.00</strong>
            </div>

            <button class="pos-charge-btn" id="chargeBtn" onclick="processSale()" disabled>
                <i class="fas fa-cash-register"></i>
                <span>Charge — <span id="chargeBtnTotal">KSh 0.00</span></span>
            </button>
        </div>
    </div>
</div>

{{-- ══ Success Modal ══ --}}
<div class="pos-modal-overlay" id="successModal">
    <div class="pos-modal">
        <div class="pos-modal-icon"><i class="fas fa-check"></i></div>
        <h3>Sale Complete!</h3>
        <div class="order-num" id="modalOrderNum"></div>
        <p id="modalCustomer"></p>
        <div class="change-due" id="modalChange"></div>
        <div class="cashier-line" id="modalCashier"></div>
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
/* ═══════════════════════════════════════════════════════════
   POS TERMINAL JS
   ═══════════════════════════════════════════════════════════ */

const ROUTES = {
    sale:    '{{ route("admin.pos.sale") }}',
    search:  '{{ route("admin.pos.products.search") }}',
    lookup:  '{{ route("admin.pos.customer.lookup") }}',
    receipt: '{{ url("admin/pos/receipt") }}',
};
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

/* ── State ──────────────────────────────────────────────── */
let cart          = [];
let paymentMethod = 'cash';
let lookupTimer   = null;

/* ── Clock ──────────────────────────────────────────────── */
function updateClock() {
    const now  = new Date();
    document.getElementById('posClock').textContent =
        now.toLocaleTimeString('en-KE', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    document.getElementById('posDate').textContent =
        now.toLocaleDateString('en-KE', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
}
updateClock();
setInterval(updateClock, 1000);

/* ── Product search / filter ────────────────────────────── */
let searchTimer = null;
document.getElementById('productSearch').addEventListener('input', function () {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(fetchProducts, 350);
});
document.getElementById('categoryFilter').addEventListener('change', fetchProducts);

function fetchProducts() {
    const q   = document.getElementById('productSearch').value;
    const cat = document.getElementById('categoryFilter').value;
    const url = ROUTES.search + '?q=' + encodeURIComponent(q) + '&category_id=' + cat;

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(products => renderProducts(products))
        .catch(() => {});
}

function renderProducts(products) {
    const grid = document.getElementById('productsGrid');
    if (!products.length) {
        grid.innerHTML = `<div style="grid-column:1/-1"><div class="empty-state"><i class="fas fa-search"></i><p>No products found</p></div></div>`;
        return;
    }
    grid.innerHTML = products.map(p => `
        <div class="pos-product-card ${p.stock_quantity < 1 ? 'out-of-stock' : ''}"
             onclick="addToCart(${p.id}, '${escHtml(p.name)}', ${p.current_price}, ${p.stock_quantity})"
             data-id="${p.id}">
            ${p.thumbnail
                ? `<img src="/storage/${p.thumbnail}" alt="${escHtml(p.name)}" class="pos-product-img">`
                : `<div class="pos-product-img-placeholder"><i class="fas fa-spa"></i></div>`}
            ${p.discount_percent > 0 ? `<span class="pos-product-badge">-${p.discount_percent}%</span>` : ''}
            <div class="pos-product-name">${escHtml(p.name)}</div>
            <div class="pos-product-price">KSh ${fmt(p.current_price)}
                ${p.sale_price ? `<s>${fmt(p.price)}</s>` : ''}
            </div>
            <div class="pos-product-stock"><i class="fas fa-box"></i> ${p.stock_quantity} in stock</div>
        </div>
    `).join('');
}

/* ── Cart ───────────────────────────────────────────────── */
function addToCart(id, name, price, stock) {
    const existing = cart.find(i => i.id === id);
    if (existing) {
        if (existing.qty >= stock) {
            flashMsg('Max stock reached for ' + name, 'warning');
            return;
        }
        existing.qty++;
    } else {
        cart.push({ id, name, price, stock, qty: 1 });
    }
    renderCart();
}

function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    renderCart();
}

function changeQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    item.qty += delta;
    if (item.qty <= 0) { removeFromCart(id); return; }
    if (item.qty > item.stock) { item.qty = item.stock; }
    renderCart();
}

function clearCart() {
    cart = [];
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cartItems');
    const empty     = document.getElementById('cartEmpty');
    const count     = document.getElementById('cartCount');

    count.textContent = cart.reduce((s, i) => s + i.qty, 0);

    if (!cart.length) {
        empty.style.display = 'flex';
        container.innerHTML = '';
        container.appendChild(empty);
        recalculate();
        return;
    }

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
            <button class="pos-cart-item-remove" onclick="removeFromCart(${item.id})">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `).join('');

    recalculate();
}

/* ── Totals ─────────────────────────────────────────────── */
function recalculate() {
    const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    const total    = Math.max(0, subtotal - discount);

    document.getElementById('subtotalDisplay').textContent  = 'KSh ' + fmt(subtotal);
    document.getElementById('totalDisplay').textContent     = 'KSh ' + fmt(total);
    document.getElementById('chargeBtnTotal').textContent   = 'KSh ' + fmt(total);

    const chargeBtn = document.getElementById('chargeBtn');
    chargeBtn.disabled = cart.length === 0;

    calcChange();
}

function calcChange() {
    const total    = getTotal();
    const paid     = parseFloat(document.getElementById('amountPaid').value) || 0;
    const change   = paid - total;
    const display  = document.getElementById('changeDisplay');
    const changeEl = document.getElementById('changeAmt');

    if (paymentMethod === 'cash' && paid > 0 && paid >= total) {
        display.classList.add('show');
        changeEl.textContent = 'KSh ' + fmt(Math.max(0, change));
    } else {
        display.classList.remove('show');
    }
}

function getTotal() {
    const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    return Math.max(0, subtotal - discount);
}

/* ── Payment method ─────────────────────────────────────── */
function selectPayment(method) {
    paymentMethod = method;
    document.querySelectorAll('.pos-pay-btn').forEach(b => {
        b.classList.toggle('active', b.dataset.method === method);
    });
    document.getElementById('mpesaCodeWrap').style.display = method === 'mpesa' ? 'block' : 'none';
    document.getElementById('amountPaid').placeholder =
        method === 'mpesa' ? 'Amount (M-Pesa)' :
        method === 'card'  ? 'Amount (Card)'   : 'Amount paid (cash)';
    calcChange();
}

/* ── Customer lookup ────────────────────────────────────── */
function lookupCustomer(phone) {
    clearTimeout(lookupTimer);
    const banner = document.getElementById('welcomeBanner');
    const msg    = document.getElementById('welcomeMsg');

    if (phone.length < 10) {
        banner.classList.remove('show');
        return;
    }

    lookupTimer = setTimeout(() => {
        fetch(ROUTES.lookup + '?phone=' + encodeURIComponent(phone), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.found) {
                msg.textContent = data.message;
                banner.classList.add('show');
                if (!document.getElementById('customerName').value) {
                    document.getElementById('customerName').value = data.name;
                }
            } else {
                banner.classList.remove('show');
            }
        })
        .catch(() => banner.classList.remove('show'));
    }, 600);
}

/* ── Process sale ───────────────────────────────────────── */
function processSale() {
    if (!cart.length) { flashMsg('Cart is empty', 'warning'); return; }

    const total = getTotal();
    const paid  = parseFloat(document.getElementById('amountPaid').value) || 0;

    if (paymentMethod === 'cash' && paid < total) {
        flashMsg('Amount paid is less than total', 'danger'); return;
    }

    const btn = document.getElementById('chargeBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing…';

    const payload = {
        items:           cart.map(i => ({ id: i.id, qty: i.qty })),
        payment_method:  paymentMethod,
        customer_name:   document.getElementById('customerName').value,
        customer_phone:  document.getElementById('customerPhone').value,
        amount_paid:     paid,
        discount:        parseFloat(document.getElementById('discountInput').value) || 0,
        mpesa_code:      document.getElementById('mpesaCode').value,
        _token:          CSRF,
    };

    fetch(ROUTES.sale, {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body:    JSON.stringify(payload),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showSuccessModal(data);
        } else {
            flashMsg(data.message || 'Sale failed', 'danger');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-cash-register"></i> <span>Charge — <span id="chargeBtnTotal">KSh ' + fmt(total) + '</span></span>';
        }
    })
    .catch(() => {
        flashMsg('Network error. Please try again.', 'danger');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-cash-register"></i> <span>Charge — <span id="chargeBtnTotal">KSh ' + fmt(total) + '</span></span>';
    });
}

/* ── Success modal ──────────────────────────────────────── */
function showSuccessModal(data) {
    document.getElementById('modalOrderNum').textContent  = data.order_number;
    document.getElementById('modalCustomer').textContent  = data.customer || 'Walk-in Customer';
    document.getElementById('modalCashier').textContent   = 'Served by: ' + data.cashier + ' · ' + data.time;
    document.getElementById('modalReceiptBtn').href       = ROUTES.receipt + '/' + data.order_id;

    const changeEl = document.getElementById('modalChange');
    if (data.change > 0) {
        changeEl.textContent = 'Change: KSh ' + fmt(data.change);
        changeEl.style.display = 'block';
    } else {
        changeEl.style.display = 'none';
    }

    document.getElementById('successModal').classList.add('show');
}

function closeModal() {
    document.getElementById('successModal').classList.remove('show');
    clearCart();
    document.getElementById('customerName').value  = '';
    document.getElementById('customerPhone').value = '';
    document.getElementById('amountPaid').value    = '';
    document.getElementById('mpesaCode').value     = '';
    document.getElementById('discountInput').value = '';
    document.getElementById('welcomeBanner').classList.remove('show');
    document.getElementById('chargeBtn').disabled  = true;
    document.getElementById('chargeBtn').innerHTML =
        '<i class="fas fa-cash-register"></i> <span>Charge — <span id="chargeBtnTotal">KSh 0.00</span></span>';
    selectPayment('cash');
}

/* ── Helpers ────────────────────────────────────────────── */
function fmt(n) {
    return parseFloat(n).toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function escHtml(str) {
    return String(str).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
}

function flashMsg(msg, type = 'info') {
    const wrap = document.createElement('div');
    wrap.className = `alert alert-${type}`;
    wrap.style.cssText = 'position:fixed;top:80px;right:1.5rem;z-index:9999;min-width:280px;box-shadow:var(--shadow-lg)';
    wrap.innerHTML = `<i class="fas fa-circle-exclamation"></i><div>${msg}</div>`;
    document.body.appendChild(wrap);
    setTimeout(() => { wrap.style.opacity = '0'; wrap.style.transition = 'opacity .4s'; setTimeout(() => wrap.remove(), 400); }, 3500);
}
</script>
@endpush