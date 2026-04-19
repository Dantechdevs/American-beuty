@extends('layouts.admin')

@section('title', 'POS Terminal')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   POS TERMINAL
   ═══════════════════════════════════════════════════════════ */
.pos-wrap {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 1.25rem;
    height: calc(100vh - var(--bar-h) - 3rem);
    min-height: 600px;
}

/* ── Left panel ── */
.pos-left {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    min-height: 0;
    overflow: hidden;
}

/* Cashier bar */
.pos-cashier-bar {
    background: linear-gradient(135deg, #5B21B6 0%, #7C3AED 50%, #F72585 100%);
    border-radius: var(--r);
    padding: .75rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #fff;
    box-shadow: 0 4px 18px rgba(124,58,237,.35);
    flex-shrink: 0;
}
.pos-cashier-info { display: flex; align-items: center; gap: .75rem; }
.pos-cashier-avatar {
    width: 38px; height: 38px;
    background: rgba(255,255,255,.22);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .95rem;
}
.pos-cashier-name { font-weight: 700; font-size: .9rem; line-height: 1.2; }
.pos-cashier-role { font-size: .7rem; opacity: .8; }
.pos-clock { font-size: .8rem; opacity: .9; text-align: right; }
.pos-clock strong { display: block; font-size: 1.1rem; font-weight: 700; font-variant-numeric: tabular-nums; }

/* Search bar */
.pos-search-bar {
    display: flex;
    gap: .65rem;
    align-items: center;
    flex-shrink: 0;
}
.pos-search-wrap { flex: 1; position: relative; }
.pos-search-wrap i {
    position: absolute; left: .9rem; top: 50%;
    transform: translateY(-50%);
    color: var(--muted); font-size: .85rem; pointer-events: none;
}
.pos-search-wrap input {
    width: 100%;
    padding: .65rem .9rem .65rem 2.4rem;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    font-size: .87rem; font-family: inherit;
    outline: none; background: #fff;
    transition: border-color .18s, box-shadow .18s;
}
.pos-search-wrap input:focus {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(124,58,237,.1);
}
.pos-cat-filter {
    padding: .65rem .9rem;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    font-size: .85rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    cursor: pointer; min-width: 140px;
    transition: border-color .18s;
}
.pos-cat-filter:focus { border-color: var(--purple); }

/* Products grid */
.pos-products {
    flex: 1;
    overflow-y: auto;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(145px, 1fr));
    gap: .75rem;
    align-content: start;
    padding-right: .25rem;
    min-height: 0;
}
.pos-product-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    padding: .85rem;
    cursor: pointer;
    transition: all .18s ease;
    display: flex; flex-direction: column;
    gap: .45rem;
    position: relative;
    overflow: hidden;
}
.pos-product-card:hover {
    border-color: var(--purple);
    box-shadow: 0 8px 24px rgba(124,58,237,.15);
    transform: translateY(-2px);
}
.pos-product-card.out-of-stock { opacity: .5; cursor: not-allowed; pointer-events: none; }
.pos-product-img {
    width: 100%; aspect-ratio: 1;
    border-radius: 9px; object-fit: cover;
    background: var(--purple-soft);
}
.pos-product-img-placeholder {
    width: 100%; aspect-ratio: 1;
    border-radius: 9px;
    background: linear-gradient(135deg, var(--purple-soft), var(--pink-soft));
    display: flex; align-items: center; justify-content: center;
    color: var(--purple); font-size: 1.5rem;
}
.pos-product-name {
    font-size: .79rem; font-weight: 600; color: var(--text);
    line-height: 1.3;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
}
.pos-product-price { font-size: .9rem; font-weight: 700; color: var(--purple); }
.pos-product-price s { font-size: .72rem; color: var(--muted); font-weight: 400; margin-left: .25rem; }
.pos-product-stock { font-size: .67rem; color: var(--muted); }
.pos-product-badge {
    position: absolute; top: .5rem; right: .5rem;
    background: var(--pink); color: #fff;
    font-size: .6rem; font-weight: 700;
    padding: .15rem .4rem; border-radius: 20px;
}

/* ── Right panel — FIXED HEIGHT + SCROLLABLE ── */
.pos-right {
    display: flex;
    flex-direction: column;
    background: #fff;
    border-radius: var(--r);
    border: 1.5px solid var(--border);
    box-shadow: var(--shadow);
    overflow: hidden;         /* clip rounded corners */
    min-height: 0;
    height: 100%;
}

/* Cart header */
.pos-cart-header {
    padding: .9rem 1.1rem;
    background: linear-gradient(120deg, #fff 55%, var(--purple-soft) 100%);
    border-bottom: 1.5px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    flex-shrink: 0;
}
.pos-cart-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: .95rem; font-weight: 700;
    display: flex; align-items: center; gap: .5rem;
}
.pos-cart-count {
    background: var(--purple); color: #fff;
    font-size: .63rem; font-weight: 700;
    padding: .15rem .45rem; border-radius: 20px;
}

/* Customer */
.pos-customer {
    padding: .75rem 1.1rem;
    border-bottom: 1.5px solid var(--border);
    background: #faf7ff;
    flex-shrink: 0;
}
.pos-customer-row {
    display: grid; grid-template-columns: 1fr 1fr; gap: .5rem;
    margin-bottom: .5rem;
}
.pos-customer-row:last-child { margin-bottom: 0; }
.pos-input {
    width: 100%; padding: .5rem .75rem;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    font-size: .82rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    transition: border-color .18s;
}
.pos-input:focus { border-color: var(--purple); box-shadow: 0 0 0 3px rgba(124,58,237,.08); }
.pos-welcome-banner {
    display: none;
    padding: .45rem .7rem;
    background: var(--green-soft);
    border: 1px solid #bbf7d0;
    border-radius: var(--r-sm);
    font-size: .77rem; font-weight: 600;
    color: #15803d; margin-top: .45rem;
    align-items: center; gap: .4rem;
}
.pos-welcome-banner.show { display: flex; }

/* Cart items — scrollable middle section */
.pos-cart-items {
    flex: 1;
    overflow-y: auto;
    padding: .75rem 1.1rem;
    display: flex; flex-direction: column; gap: .55rem;
    min-height: 0;
}
.pos-cart-empty {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    height: 100%; color: var(--muted);
    gap: .5rem; text-align: center;
}
.pos-cart-empty i { font-size: 2.2rem; opacity: .15; color: var(--purple); }
.pos-cart-empty p { font-size: .82rem; }

.pos-cart-item {
    display: flex; align-items: center; gap: .6rem;
    padding: .6rem .75rem;
    background: #faf7ff;
    border-radius: var(--r-sm);
    border: 1px solid var(--border);
}
.pos-cart-item-name {
    flex: 1; font-size: .79rem; font-weight: 600;
    color: var(--text); line-height: 1.3;
}
.pos-cart-item-price { font-size: .79rem; color: var(--muted); white-space: nowrap; }
.pos-qty-control { display: flex; align-items: center; gap: .28rem; }
.pos-qty-btn {
    width: 24px; height: 24px;
    border-radius: 6px;
    border: 1.5px solid var(--border);
    background: #fff; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem; font-weight: 700; color: var(--text);
    transition: all .15s; line-height: 1;
}
.pos-qty-btn:hover { border-color: var(--purple); color: var(--purple); background: var(--purple-soft); }
.pos-qty-val { width: 26px; text-align: center; font-size: .82rem; font-weight: 700; }
.pos-cart-item-remove {
    color: var(--muted); background: none; border: none;
    cursor: pointer; font-size: .8rem; padding: .2rem;
    transition: color .15s;
}
.pos-cart-item-remove:hover { color: var(--tango); }

/* Totals */
.pos-totals {
    padding: .8rem 1.1rem;
    border-top: 1.5px solid var(--border);
    background: #faf7ff;
    display: flex; flex-direction: column; gap: .38rem;
    flex-shrink: 0;
}
.pos-total-row {
    display: flex; justify-content: space-between;
    font-size: .82rem; color: var(--muted);
    align-items: center;
}
.pos-total-row.grand {
    font-size: 1rem; font-weight: 700;
    color: var(--text); padding-top: .38rem;
    border-top: 1.5px solid var(--border); margin-top: .15rem;
}
.pos-total-row.grand span:last-child { color: var(--pink); }
.pos-discount-row { display: flex; gap: .5rem; align-items: center; }
.pos-discount-row input {
    flex: 1; padding: .38rem .6rem;
    border: 1.5px solid var(--border);
    border-radius: 7px; font-size: .82rem;
    font-family: inherit; outline: none;
    transition: border-color .18s; width: 110px;
}
.pos-discount-row input:focus { border-color: var(--purple); }

/* Payment */
.pos-payment {
    padding: .85rem 1.1rem 1rem;
    border-top: 1.5px solid var(--border);
    background: #fff;
    flex-shrink: 0;
    display: flex; flex-direction: column; gap: .65rem;
}

.pos-pay-methods {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: .5rem;
}
.pos-pay-btn {
    padding: .6rem .4rem;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    background: #fff; cursor: pointer;
    font-size: .75rem; font-weight: 600;
    color: var(--text); text-align: center;
    transition: all .18s; font-family: inherit;
    display: flex; flex-direction: column;
    align-items: center; gap: .22rem;
}
.pos-pay-btn i { font-size: 1.05rem; }
.pos-pay-btn:hover { border-color: var(--purple); color: var(--purple); background: var(--purple-soft); }
.pos-pay-btn.active {
    border-color: var(--pink);
    background: linear-gradient(135deg, var(--pink), var(--pink-lt));
    color: #fff; box-shadow: 0 3px 12px rgba(247,37,133,.3);
}

.pos-amount-paid input { width: 100%; }

.pos-mpesa-code { display: none; }
.pos-mpesa-code input { width: 100%; }

.pos-change-display {
    display: none;
    padding: .6rem .85rem;
    background: var(--green-soft);
    border: 1px solid #bbf7d0;
    border-radius: var(--r-sm);
    font-size: .85rem; font-weight: 600;
    color: #15803d;
    justify-content: space-between;
    align-items: center;
}
.pos-change-display.show { display: flex; }
.pos-change-display strong { color: #15803d; font-size: .95rem; }

/* ── THE CHARGE BUTTON — visible, prominent ── */
.pos-charge-btn {
    width: 100%;
    padding: .95rem 1rem;
    background: linear-gradient(135deg, #7C3AED 0%, #F72585 100%);
    color: #fff; border: none; border-radius: 12px;
    font-size: 1rem; font-weight: 700; cursor: pointer;
    font-family: inherit;
    box-shadow: 0 6px 20px rgba(124,58,237,.38);
    transition: all .2s;
    display: flex; align-items: center; justify-content: center; gap: .55rem;
    letter-spacing: .02em;
    flex-shrink: 0;
}
.pos-charge-btn:hover {
    box-shadow: 0 8px 28px rgba(124,58,237,.5);
    transform: translateY(-2px);
}
.pos-charge-btn:disabled {
    opacity: .45; cursor: not-allowed; transform: none;
    box-shadow: none;
    background: #c4b5e8;
}
.pos-charge-btn i { font-size: 1.05rem; }

/* Modal */
.pos-modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(10,1,20,.6); backdrop-filter: blur(4px);
    z-index: 500; align-items: center; justify-content: center;
}
.pos-modal-overlay.show { display: flex; }
.pos-modal {
    background: #fff; border-radius: 20px;
    padding: 2rem; width: 370px; max-width: 95vw;
    text-align: center; box-shadow: 0 20px 60px rgba(124,58,237,.2);
    animation: modalIn .22s ease;
}
@keyframes modalIn { from { opacity: 0; transform: scale(.93); } to { opacity: 1; transform: scale(1); } }
.pos-modal-icon {
    width: 68px; height: 68px;
    background: linear-gradient(135deg, var(--green-soft), #dcfce7);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto .8rem;
    font-size: 1.85rem; color: var(--green);
    box-shadow: 0 4px 14px rgba(45,198,83,.2);
}
.pos-modal h3 { font-family: 'Playfair Display', serif; font-size: 1.2rem; margin-bottom: .35rem; }
.pos-modal p  { font-size: .85rem; color: var(--muted); margin-bottom: .2rem; }
.pos-modal .order-num { font-size: .78rem; font-weight: 700; color: var(--purple); letter-spacing: .05em; margin-bottom: .25rem; }
.pos-modal .change-due { font-size: 1.15rem; font-weight: 700; color: var(--green); margin: .5rem 0; }
.pos-modal .cashier-line { font-size: .78rem; color: var(--muted); margin-bottom: 1rem; }
.pos-modal-actions { display: flex; gap: .65rem; justify-content: center; }
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
            <div class="pos-product-card {{ $product->stock_quantity < 1 ? 'out-of-stock':'' }}"
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
                    <div class="pos-product-img-placeholder"><i class="fas fa-spa"></i></div>
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

        {{-- Header --}}
        <div class="pos-cart-header">
            <h3>
                <i class="fas fa-shopping-basket" style="color:var(--purple)"></i>
                Cart <span class="pos-cart-count" id="cartCount">0</span>
            </h3>
            <button class="btn btn-sm btn-danger" onclick="clearCart()">
                <i class="fas fa-trash"></i> Clear
            </button>
        </div>

        {{-- Customer --}}
        <div class="pos-customer">
            <div class="pos-customer-row">
                <input type="text" class="pos-input" id="customerName" placeholder="Customer name">
                <input type="text" class="pos-input" id="customerPhone"
                       placeholder="Phone (07…)" oninput="lookupCustomer(this.value)">
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
                <p>Cart is empty<br><small>Tap a product to add</small></p>
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
                    <input type="number" id="discountInput"
                           placeholder="0.00" min="0" step="0.01"
                           oninput="recalculate()">
                </div>
            </div>
            <div class="pos-total-row grand">
                <span>TOTAL</span>
                <span id="totalDisplay">KSh 0.00</span>
            </div>
        </div>

        {{-- Payment --}}
        <div class="pos-payment">

            {{-- Method tabs --}}
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

            {{-- Amount paid --}}
            <div class="pos-amount-paid">
                <input type="number" class="pos-input" id="amountPaid"
                       placeholder="Amount paid (cash)" min="0" step="0.01"
                       oninput="calcChange()">
            </div>

            {{-- M-Pesa code --}}
            <div class="pos-mpesa-code" id="mpesaCodeWrap">
                <input type="text" class="pos-input" id="mpesaCode"
                       placeholder="M-Pesa transaction code">
            </div>

            {{-- Change due --}}
            <div class="pos-change-display" id="changeDisplay">
                <span>Change Due</span>
                <strong id="changeAmt">KSh 0.00</strong>
            </div>

            {{-- ✅ CHARGE BUTTON — always visible at bottom --}}
            <button class="pos-charge-btn" id="chargeBtn" onclick="processSale()" disabled>
                <i class="fas fa-cash-register"></i>
                <span>Charge &nbsp;—&nbsp; <span id="chargeBtnTotal">KSh 0.00</span></span>
            </button>

        </div>
    </div>

</div>

{{-- Success Modal --}}
<div class="pos-modal-overlay" id="successModal">
    <div class="pos-modal">
        <div class="pos-modal-icon"><i class="fas fa-check"></i></div>
        <h3>Sale Complete! 🎉</h3>
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
const ROUTES = {
    sale:    '{{ route("admin.pos.sale") }}',
    search:  '{{ route("admin.pos.products.search") }}',
    lookup:  '{{ route("admin.pos.customer.lookup") }}',
    receipt: '{{ url("admin/pos/receipt") }}',
};
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

let cart          = [];
let paymentMethod = 'cash';
let lookupTimer   = null;
let searchTimer   = null;

/* Clock */
function updateClock() {
    const now = new Date();
    document.getElementById('posClock').textContent =
        now.toLocaleTimeString('en-KE', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
    document.getElementById('posDate').textContent =
        now.toLocaleDateString('en-KE', { weekday:'short', day:'numeric', month:'short', year:'numeric' });
}
updateClock();
setInterval(updateClock, 1000);

/* Search */
document.getElementById('productSearch').addEventListener('input', function () {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(fetchProducts, 350);
});
document.getElementById('categoryFilter').addEventListener('change', fetchProducts);

function fetchProducts() {
    const q   = document.getElementById('productSearch').value;
    const cat = document.getElementById('categoryFilter').value;
    fetch(ROUTES.search + '?q=' + encodeURIComponent(q) + '&category_id=' + cat,
          { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(renderProducts)
        .catch(() => {});
}

function renderProducts(products) {
    const grid = document.getElementById('productsGrid');
    if (!products.length) {
        grid.innerHTML = `<div style="grid-column:1/-1"><div class="empty-state"><i class="fas fa-search"></i><p>No products found</p></div></div>`;
        return;
    }
    grid.innerHTML = products.map(p => `
        <div class="pos-product-card ${p.stock_quantity < 1 ? 'out-of-stock':''}"
             onclick="addToCart(${p.id},'${escHtml(p.name)}',${p.current_price},${p.stock_quantity})"
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

/* Cart */
function addToCart(id, name, price, stock) {
    const existing = cart.find(i => i.id === id);
    if (existing) {
        if (existing.qty >= stock) { flashMsg('Max stock reached for ' + name, 'warning'); return; }
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
    if (item.qty > item.stock) item.qty = item.stock;
    renderCart();
}

function clearCart() {
    cart = [];
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cartItems');
    const empty     = document.getElementById('cartEmpty');
    document.getElementById('cartCount').textContent = cart.reduce((s, i) => s + i.qty, 0);

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
                <button class="pos-qty-btn" onclick="changeQty(${item.id},-1)">−</button>
                <span class="pos-qty-val">${item.qty}</span>
                <button class="pos-qty-btn" onclick="changeQty(${item.id},1)">+</button>
            </div>
            <div class="pos-cart-item-price">KSh ${fmt(item.price * item.qty)}</div>
            <button class="pos-cart-item-remove" onclick="removeFromCart(${item.id})">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `).join('');
    recalculate();
}

/* Totals */
function recalculate() {
    const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    const total    = Math.max(0, subtotal - discount);
    document.getElementById('subtotalDisplay').textContent = 'KSh ' + fmt(subtotal);
    document.getElementById('totalDisplay').textContent    = 'KSh ' + fmt(total);
    document.getElementById('chargeBtnTotal').textContent  = 'KSh ' + fmt(total);
    document.getElementById('chargeBtn').disabled = cart.length === 0;
    calcChange();
}

function calcChange() {
    const total  = getTotal();
    const paid   = parseFloat(document.getElementById('amountPaid').value) || 0;
    const change = paid - total;
    const disp   = document.getElementById('changeDisplay');
    if (paymentMethod === 'cash' && paid > 0 && paid >= total) {
        disp.classList.add('show');
        document.getElementById('changeAmt').textContent = 'KSh ' + fmt(Math.max(0, change));
    } else {
        disp.classList.remove('show');
    }
}

function getTotal() {
    const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    return Math.max(0, subtotal - discount);
}

/* Payment */
function selectPayment(method) {
    paymentMethod = method;
    document.querySelectorAll('.pos-pay-btn').forEach(b =>
        b.classList.toggle('active', b.dataset.method === method));
    document.getElementById('mpesaCodeWrap').style.display = method === 'mpesa' ? 'block' : 'none';
    document.getElementById('amountPaid').placeholder =
        method === 'mpesa' ? 'Amount (M-Pesa)' :
        method === 'card'  ? 'Amount (Card)'   : 'Amount paid (cash)';
    calcChange();
}

/* Customer lookup */
function lookupCustomer(phone) {
    clearTimeout(lookupTimer);
    const banner = document.getElementById('welcomeBanner');
    if (phone.length < 10) { banner.classList.remove('show'); return; }
    lookupTimer = setTimeout(() => {
        fetch(ROUTES.lookup + '?phone=' + encodeURIComponent(phone),
              { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                if (data.found) {
                    document.getElementById('welcomeMsg').textContent = data.message;
                    banner.classList.add('show');
                    if (!document.getElementById('customerName').value)
                        document.getElementById('customerName').value = data.name;
                } else {
                    banner.classList.remove('show');
                }
            })
            .catch(() => banner.classList.remove('show'));
    }, 600);
}

/* Process sale */
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

    fetch(ROUTES.sale, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({
            items:          cart.map(i => ({ id: i.id, qty: i.qty })),
            payment_method: paymentMethod,
            customer_name:  document.getElementById('customerName').value,
            customer_phone: document.getElementById('customerPhone').value,
            amount_paid:    paid,
            discount:       parseFloat(document.getElementById('discountInput').value) || 0,
            mpesa_code:     document.getElementById('mpesaCode').value,
            _token:         CSRF,
        }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showSuccessModal(data);
        } else {
            flashMsg(data.message || 'Sale failed', 'danger');
            btn.disabled = false;
            btn.innerHTML = `<i class="fas fa-cash-register"></i> <span>Charge &nbsp;—&nbsp; <span id="chargeBtnTotal">KSh ${fmt(total)}</span></span>`;
        }
    })
    .catch(() => {
        flashMsg('Network error. Please try again.', 'danger');
        btn.disabled = false;
        btn.innerHTML = `<i class="fas fa-cash-register"></i> <span>Charge &nbsp;—&nbsp; <span id="chargeBtnTotal">KSh ${fmt(total)}</span></span>`;
    });
}

function showSuccessModal(data) {
    document.getElementById('modalOrderNum').textContent = data.order_number;
    document.getElementById('modalCustomer').textContent = data.customer || 'Walk-in Customer';
    document.getElementById('modalCashier').textContent  = 'Served by: ' + data.cashier + ' · ' + data.time;
    document.getElementById('modalReceiptBtn').href      = ROUTES.receipt + '/' + data.order_id;
    const ch = document.getElementById('modalChange');
    if (data.change > 0) {
        ch.textContent = 'Change: KSh ' + fmt(data.change);
        ch.style.display = 'block';
    } else {
        ch.style.display = 'none';
    }
    document.getElementById('successModal').classList.add('show');
}

function closeModal() {
    document.getElementById('successModal').classList.remove('show');
    clearCart();
    ['customerName','customerPhone','amountPaid','mpesaCode','discountInput']
        .forEach(id => document.getElementById(id).value = '');
    document.getElementById('welcomeBanner').classList.remove('show');
    document.getElementById('chargeBtn').disabled = true;
    document.getElementById('chargeBtn').innerHTML =
        '<i class="fas fa-cash-register"></i> <span>Charge &nbsp;—&nbsp; <span id="chargeBtnTotal">KSh 0.00</span></span>';
    selectPayment('cash');
}

function fmt(n) {
    return parseFloat(n).toLocaleString('en-KE', { minimumFractionDigits:2, maximumFractionDigits:2 });
}
function escHtml(str) {
    return String(str).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
}
function flashMsg(msg, type = 'info') {
    const wrap = document.createElement('div');
    wrap.className = `flash flash-${type === 'warning' ? 'warning' : type === 'danger' ? 'error' : 'success'}`;
    wrap.style.cssText = 'position:fixed;top:80px;right:1.5rem;z-index:9999;min-width:280px;box-shadow:0 8px 24px rgba(0,0,0,.12)';
    wrap.innerHTML = `<i class="fas fa-circle-exclamation"></i><div>${msg}</div>`;
    document.body.appendChild(wrap);
    setTimeout(() => {
        wrap.style.transition = 'opacity .4s';
        wrap.style.opacity = '0';
        setTimeout(() => wrap.remove(), 400);
    }, 3500);
}
</script>
@endpush