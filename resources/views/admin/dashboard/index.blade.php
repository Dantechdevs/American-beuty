@extends('layouts.admin')
@section('title', 'POS Terminal')

@push('styles')
<style>
/* ── POS Layout ─────────────────────────────────────────────── */
.pos-wrap {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 1.25rem;
    height: calc(100vh - 80px);
    overflow: hidden;
}

/* LEFT – Product Browser */
.pos-left {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    overflow: hidden;
}

.pos-search-bar {
    display: flex;
    gap: .75rem;
    align-items: center;
}

.pos-search-bar input {
    flex: 1;
    padding: .65rem 1rem;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-size: .95rem;
    outline: none;
    transition: border-color .2s;
}
.pos-search-bar input:focus { border-color: var(--primary); }

.pos-search-bar select {
    padding: .65rem .9rem;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-size: .88rem;
    background: #fff;
    outline: none;
    cursor: pointer;
}

/* Category pills */
.cat-pills {
    display: flex;
    gap: .5rem;
    flex-wrap: wrap;
}
.cat-pill {
    padding: .35rem .9rem;
    border-radius: 20px;
    font-size: .8rem;
    font-weight: 600;
    border: 1.5px solid var(--border);
    cursor: pointer;
    transition: all .15s;
    background: #fff;
    color: #555;
}
.cat-pill:hover, .cat-pill.active {
    background: var(--primary);
    border-color: var(--primary);
    color: #fff;
}

/* Product Grid */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(155px, 1fr));
    gap: .9rem;
    overflow-y: auto;
    padding-right: .25rem;
}

.product-grid::-webkit-scrollbar { width: 5px; }
.product-grid::-webkit-scrollbar-thumb { background: #ddd; border-radius: 4px; }

.prod-card {
    background: #fff;
    border-radius: 14px;
    border: 1.5px solid var(--border);
    padding: .9rem .75rem;
    cursor: pointer;
    transition: all .18s;
    text-align: center;
    position: relative;
}
.prod-card:hover {
    border-color: var(--primary);
    box-shadow: 0 4px 16px rgba(99,102,241,.12);
    transform: translateY(-2px);
}
.prod-card.out-of-stock {
    opacity: .45;
    cursor: not-allowed;
    pointer-events: none;
}

.prod-card img {
    width: 72px; height: 72px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: .6rem;
    background: #f5f5f5;
}
.prod-img-placeholder {
    width: 72px; height: 72px;
    border-radius: 10px;
    background: linear-gradient(135deg,#f0f0f0,#e0e0e0);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto .6rem;
    font-size: 1.6rem; color: #bbb;
}
.prod-name { font-size: .82rem; font-weight: 600; color: #222; margin-bottom: .3rem; line-height: 1.3; }
.prod-price { font-size: .92rem; font-weight: 700; color: var(--primary); }
.prod-stock { font-size: .72rem; color: #999; margin-top: .2rem; }
.stock-badge {
    position: absolute; top: .5rem; right: .5rem;
    background: #fee2e2; color: #dc2626;
    font-size: .65rem; font-weight: 700;
    padding: .15rem .4rem; border-radius: 6px;
}

/* RIGHT – Cart */
.pos-cart {
    background: #fff;
    border-radius: 16px;
    border: 1.5px solid var(--border);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.cart-header {
    padding: 1rem 1.2rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.cart-header h3 { font-size: 1rem; font-weight: 700; }
.cart-count {
    background: var(--primary);
    color: #fff;
    font-size: .75rem;
    font-weight: 700;
    padding: .2rem .55rem;
    border-radius: 20px;
}

.cart-items {
    flex: 1;
    overflow-y: auto;
    padding: .75rem 1rem;
}
.cart-items::-webkit-scrollbar { width: 4px; }
.cart-items::-webkit-scrollbar-thumb { background: #eee; border-radius: 4px; }

.cart-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #bbb;
    font-size: .9rem;
    gap: .5rem;
}
.cart-empty i { font-size: 2.5rem; }

.cart-item {
    display: flex;
    align-items: center;
    gap: .7rem;
    padding: .65rem 0;
    border-bottom: 1px solid #f5f5f5;
}
.cart-item:last-child { border-bottom: none; }

.ci-img {
    width: 42px; height: 42px;
    border-radius: 8px;
    object-fit: cover;
    background: #f5f5f5;
    flex-shrink: 0;
}
.ci-img-ph {
    width: 42px; height: 42px;
    border-radius: 8px;
    background: #f0f0f0;
    display: flex; align-items: center; justify-content: center;
    color: #ccc; font-size: 1.1rem;
    flex-shrink: 0;
}

.ci-info { flex: 1; min-width: 0; }
.ci-name { font-size: .82rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ci-price { font-size: .78rem; color: #888; }

.ci-qty {
    display: flex;
    align-items: center;
    gap: .4rem;
}
.ci-qty button {
    width: 26px; height: 26px;
    border-radius: 7px;
    border: 1.5px solid var(--border);
    background: #fff;
    font-size: .9rem;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all .15s;
}
.ci-qty button:hover { background: var(--primary); border-color: var(--primary); color: #fff; }
.ci-qty span { font-size: .88rem; font-weight: 700; min-width: 20px; text-align: center; }

.ci-total { font-size: .88rem; font-weight: 700; color: #222; min-width: 65px; text-align: right; }
.ci-remove { color: #dc2626; cursor: pointer; font-size: .85rem; padding: .2rem .3rem; }

/* Cart Totals */
.cart-totals {
    padding: .9rem 1.2rem;
    border-top: 1px solid var(--border);
    background: #fafafa;
}
.total-row {
    display: flex;
    justify-content: space-between;
    font-size: .85rem;
    margin-bottom: .4rem;
    color: #555;
}
.total-row.grand {
    font-size: 1.05rem;
    font-weight: 700;
    color: #111;
    margin-top: .5rem;
    padding-top: .5rem;
    border-top: 1.5px solid var(--border);
}

.discount-row {
    display: flex;
    gap: .5rem;
    margin: .5rem 0;
}
.discount-row input {
    flex: 1;
    padding: .45rem .7rem;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    font-size: .82rem;
    outline: none;
}
.discount-row input:focus { border-color: var(--primary); }

/* Customer & Payment */
.cart-customer {
    padding: .9rem 1.2rem;
    border-top: 1px solid var(--border);
}
.cart-customer label { font-size: .78rem; font-weight: 600; color: #666; display: block; margin-bottom: .3rem; }
.cart-customer input {
    width: 100%;
    padding: .55rem .8rem;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    font-size: .85rem;
    margin-bottom: .6rem;
    outline: none;
    box-sizing: border-box;
}
.cart-customer input:focus { border-color: var(--primary); }

.pay-methods {
    display: grid;
    grid-template-columns: repeat(3,1fr);
    gap: .5rem;
    margin-bottom: .7rem;
}
.pay-btn {
    padding: .55rem .3rem;
    border: 1.5px solid var(--border);
    border-radius: 9px;
    background: #fff;
    font-size: .78rem;
    font-weight: 600;
    cursor: pointer;
    text-align: center;
    transition: all .15s;
    color: #444;
}
.pay-btn:hover { border-color: var(--primary); color: var(--primary); }
.pay-btn.active { background: var(--primary); border-color: var(--primary); color: #fff; }
.pay-btn i { display: block; font-size: 1.1rem; margin-bottom: .2rem; }

.amount-paid-row {
    display: flex;
    gap: .5rem;
    align-items: center;
    margin-bottom: .7rem;
}
.amount-paid-row label { font-size: .78rem; font-weight: 600; color: #666; white-space: nowrap; }
.amount-paid-row input {
    flex: 1;
    padding: .5rem .7rem;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    font-size: .88rem;
    font-weight: 700;
    outline: none;
}
.amount-paid-row input:focus { border-color: var(--primary); }

#mpesa-code-wrap { display: none; margin-bottom: .7rem; }
#mpesa-code-wrap input {
    width: 100%;
    padding: .5rem .8rem;
    border: 1.5px solid #10b981;
    border-radius: 8px;
    font-size: .85rem;
    outline: none;
    box-sizing: border-box;
}

.charge-btn {
    width: 100%;
    padding: .85rem;
    background: var(--primary);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: background .2s, transform .1s;
    letter-spacing: .02em;
}
.charge-btn:hover { background: var(--primary-dark, #4f46e5); }
.charge-btn:active { transform: scale(.98); }
.charge-btn:disabled { background: #bbb; cursor: not-allowed; }

/* Modal */
.pos-modal-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.45);
    display: flex; align-items: center; justify-content: center;
    z-index: 9999;
    display: none;
}
.pos-modal-overlay.open { display: flex; }
.pos-modal {
    background: #fff;
    border-radius: 18px;
    padding: 2rem;
    width: 380px;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0,0,0,.2);
    animation: modalIn .25s ease;
}
@keyframes modalIn {
    from { transform: scale(.9); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}
.modal-success-icon { font-size: 3rem; color: #10b981; margin-bottom: 1rem; }
.modal-order-num { font-size: 1.4rem; font-weight: 700; margin-bottom: .5rem; }
.modal-total { font-size: 1.8rem; font-weight: 800; color: var(--primary); margin-bottom: .3rem; }
.modal-change { font-size: 1rem; color: #10b981; font-weight: 600; margin-bottom: 1.5rem; }
.modal-actions { display: flex; gap: .75rem; justify-content: center; }
.modal-actions a, .modal-actions button {
    padding: .65rem 1.4rem;
    border-radius: 10px;
    font-size: .9rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    border: none;
    transition: all .15s;
}
.btn-print { background: #111; color: #fff; }
.btn-new   { background: var(--primary); color: #fff; }
.btn-print:hover { background: #333; }
.btn-new:hover   { background: var(--primary-dark, #4f46e5); }
</style>
@endpush

@section('content')
<div class="pos-wrap">

    {{-- ── LEFT: Products ─────────────────────────────── --}}
    <div class="pos-left">

        {{-- Search & Filter --}}
        <div class="pos-search-bar">
            <input type="text" id="productSearch" placeholder="🔍  Search by name, SKU or barcode…" autocomplete="off">
            <select id="categoryFilter">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Category Pills --}}
        <div class="cat-pills">
            <span class="cat-pill active" data-id="">All</span>
            @foreach($categories as $cat)
                <span class="cat-pill" data-id="{{ $cat->id }}">{{ $cat->name }}</span>
            @endforeach
        </div>

        {{-- Product Grid --}}
        <div class="product-grid" id="productGrid">
            @foreach($products as $product)
            <div class="prod-card {{ $product->stock === 0 ? 'out-of-stock' : '' }}"
                 data-id="{{ $product->id }}"
                 data-name="{{ $product->name }}"
                 data-price="{{ $product->price }}"
                 data-stock="{{ $product->stock }}"
                 data-image="{{ $product->image ? asset('storage/'.$product->image) : '' }}"
                 data-cat="{{ $product->category_id }}"
                 onclick="addToCart(this)">
                @if($product->stock <= 5 && $product->stock > 0)
                    <span class="stock-badge">Low</span>
                @endif
                @if($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" loading="lazy">
                @else
                    <div class="prod-img-placeholder"><i class="fas fa-box"></i></div>
                @endif
                <div class="prod-name">{{ $product->name }}</div>
                <div class="prod-price">KSh {{ number_format($product->price, 0) }}</div>
                <div class="prod-stock">{{ $product->stock }} in stock</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── RIGHT: Cart ─────────────────────────────────── --}}
    <div class="pos-cart">

        <div class="cart-header">
            <h3><i class="fas fa-shopping-cart" style="color:var(--primary);margin-right:.4rem"></i> Cart</h3>
            <div style="display:flex;align-items:center;gap:.7rem">
                <span class="cart-count" id="cartCount">0</span>
                <button onclick="clearCart()" class="btn btn-outline btn-sm" title="Clear cart">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>

        <div class="cart-items" id="cartItems">
            <div class="cart-empty" id="cartEmpty">
                <i class="fas fa-shopping-basket"></i>
                <span>Cart is empty</span>
                <small style="color:#ddd">Click a product to add</small>
            </div>
        </div>

        {{-- Totals --}}
        <div class="cart-totals">
            <div class="total-row">
                <span>Subtotal</span>
                <span id="subtotalDisplay">KSh 0</span>
            </div>
            <div class="discount-row">
                <input type="number" id="discountInput" placeholder="Discount (KSh)" min="0" oninput="updateTotals()">
            </div>
            <div class="total-row grand">
                <span>Total</span>
                <span id="totalDisplay">KSh 0</span>
            </div>
        </div>

        {{-- Customer & Payment --}}
        <div class="cart-customer" style="overflow-y:auto;flex-shrink:0">

            <label>Customer Name (optional)</label>
            <input type="text" id="customerName" placeholder="Walk-in Customer">

            <label>Customer Phone (optional)</label>
            <input type="text" id="customerPhone" placeholder="+254…">

            <label style="margin-bottom:.5rem">Payment Method</label>
            <div class="pay-methods">
                <div class="pay-btn active" data-method="cash" onclick="setPayment(this,'cash')">
                    <i class="fas fa-money-bill-wave"></i> Cash
                </div>
                <div class="pay-btn" data-method="mpesa" onclick="setPayment(this,'mpesa')">
                    <i class="fas fa-mobile-alt"></i> M-PESA
                </div>
                <div class="pay-btn" data-method="card" onclick="setPayment(this,'card')">
                    <i class="fas fa-credit-card"></i> Card
                </div>
            </div>

            <div id="mpesa-code-wrap">
                <label>M-PESA Transaction Code</label>
                <input type="text" id="mpesaCode" placeholder="e.g. QGH4XZ1ABC" style="text-transform:uppercase">
            </div>

            <div class="amount-paid-row">
                <label>Amount Paid</label>
                <input type="number" id="amountPaid" placeholder="0" min="0" oninput="updateChange()">
                <span style="font-size:.8rem;color:#888;white-space:nowrap" id="changeDisplay"></span>
            </div>

            <button class="charge-btn" id="chargeBtn" onclick="processSale()" disabled>
                <i class="fas fa-check-circle"></i> &nbsp;Charge — <span id="chargeBtnTotal">KSh 0</span>
            </button>
        </div>
    </div>
</div>

{{-- Success Modal --}}
<div class="pos-modal-overlay" id="successModal">
    <div class="pos-modal">
        <div class="modal-success-icon"><i class="fas fa-check-circle"></i></div>
        <div class="modal-order-num" id="modalOrderNum"></div>
        <div class="modal-total" id="modalTotal"></div>
        <div class="modal-change" id="modalChange"></div>
        <div class="modal-actions">
            <a href="#" id="printReceiptBtn" target="_blank" class="btn-print">
                <i class="fas fa-print"></i> Print
            </a>
            <button onclick="newSale()" class="btn-new">
                <i class="fas fa-plus"></i> New Sale
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ── State ──────────────────────────────────────────────────────
let cart = {};           // { productId: { id, name, price, stock, qty, image } }
let paymentMethod = 'cash';

// ── Add to Cart ────────────────────────────────────────────────
function addToCart(el) {
    const id    = el.dataset.id;
    const name  = el.dataset.name;
    const price = parseFloat(el.dataset.price);
    const stock = parseInt(el.dataset.stock);
    const image = el.dataset.image;

    if (cart[id]) {
        if (cart[id].qty >= stock) {
            showToast('Max stock reached for ' + name, 'error');
            return;
        }
        cart[id].qty++;
    } else {
        cart[id] = { id, name, price, stock, qty: 1, image };
    }
    renderCart();
}

// ── Render Cart ────────────────────────────────────────────────
function renderCart() {
    const wrap  = document.getElementById('cartItems');
    const empty = document.getElementById('cartEmpty');
    const keys  = Object.keys(cart);

    if (!keys.length) {
        wrap.innerHTML = '';
        wrap.appendChild(empty);
        empty.style.display = 'flex';
        document.getElementById('chargeBtn').disabled = true;
        updateTotals();
        return;
    }

    empty.style.display = 'none';
    wrap.innerHTML = '';

    keys.forEach(id => {
        const item = cart[id];
        const div  = document.createElement('div');
        div.className = 'cart-item';
        div.innerHTML = `
            ${item.image
                ? `<img class="ci-img" src="${item.image}" alt="${item.name}">`
                : `<div class="ci-img-ph"><i class="fas fa-box"></i></div>`}
            <div class="ci-info">
                <div class="ci-name">${item.name}</div>
                <div class="ci-price">KSh ${fmtNum(item.price)}</div>
            </div>
            <div class="ci-qty">
                <button onclick="changeQty('${id}',-1)">−</button>
                <span>${item.qty}</span>
                <button onclick="changeQty('${id}',1)">+</button>
            </div>
            <div class="ci-total">KSh ${fmtNum(item.price * item.qty)}</div>
            <span class="ci-remove" onclick="removeItem('${id}')"><i class="fas fa-times"></i></span>
        `;
        wrap.appendChild(div);
    });

    updateTotals();
    document.getElementById('chargeBtn').disabled = false;
}

function changeQty(id, delta) {
    if (!cart[id]) return;
    const newQty = cart[id].qty + delta;
    if (newQty <= 0) { removeItem(id); return; }
    if (newQty > cart[id].stock) { showToast('Max stock reached', 'error'); return; }
    cart[id].qty = newQty;
    renderCart();
}

function removeItem(id) {
    delete cart[id];
    renderCart();
}

function clearCart() {
    if (!Object.keys(cart).length) return;
    if (!confirm('Clear all items from cart?')) return;
    cart = {};
    renderCart();
}

// ── Totals ─────────────────────────────────────────────────────
function updateTotals() {
    const subtotal = Object.values(cart).reduce((s, i) => s + i.price * i.qty, 0);
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    const total    = Math.max(0, subtotal - discount);

    document.getElementById('subtotalDisplay').textContent = 'KSh ' + fmtNum(subtotal);
    document.getElementById('totalDisplay').textContent    = 'KSh ' + fmtNum(total);
    document.getElementById('chargeBtnTotal').textContent  = 'KSh ' + fmtNum(total);
    document.getElementById('cartCount').textContent       = Object.keys(cart).length;

    updateChange();
}

function updateChange() {
    const total   = getTotal();
    const paid    = parseFloat(document.getElementById('amountPaid').value) || 0;
    const change  = paid - total;
    const el      = document.getElementById('changeDisplay');

    if (paid > 0 && change >= 0) {
        el.textContent = 'Change: KSh ' + fmtNum(change);
        el.style.color = '#10b981';
    } else if (paid > 0 && change < 0) {
        el.textContent = 'Short: KSh ' + fmtNum(Math.abs(change));
        el.style.color = '#ef4444';
    } else {
        el.textContent = '';
    }
}

function getTotal() {
    const subtotal = Object.values(cart).reduce((s, i) => s + i.price * i.qty, 0);
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    return Math.max(0, subtotal - discount);
}

// ── Payment ────────────────────────────────────────────────────
function setPayment(el, method) {
    paymentMethod = method;
    document.querySelectorAll('.pay-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('mpesa-code-wrap').style.display = method === 'mpesa' ? 'block' : 'none';
}

// ── Process Sale ───────────────────────────────────────────────
function processSale() {
    const items = Object.values(cart).map(i => ({ id: i.id, qty: i.qty }));
    if (!items.length) return;

    const total   = getTotal();
    const paid    = parseFloat(document.getElementById('amountPaid').value) || 0;

    if (paymentMethod === 'cash' && paid < total) {
        showToast('Amount paid is less than total!', 'error'); return;
    }

    const btn = document.getElementById('chargeBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing…';

    const payload = {
        items,
        payment_method: paymentMethod,
        customer_name:  document.getElementById('customerName').value,
        customer_phone: document.getElementById('customerPhone').value,
        amount_paid:    paid || total,
        discount:       parseFloat(document.getElementById('discountInput').value) || 0,
        mpesa_code:     document.getElementById('mpesaCode').value,
        _token:         '{{ csrf_token() }}',
    };

    fetch('{{ route("admin.pos.sale") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(payload),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('modalOrderNum').textContent = data.order_number;
            document.getElementById('modalTotal').textContent    = 'KSh ' + fmtNum(data.total);
            document.getElementById('modalChange').textContent   = data.change > 0 ? 'Change: KSh ' + fmtNum(data.change) : '✓ Exact payment';
            document.getElementById('printReceiptBtn').href      = `/admin/pos/receipt/${data.order_id}`;
            document.getElementById('successModal').classList.add('open');
        } else {
            showToast(data.message || 'Sale failed', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check-circle"></i> &nbsp;Charge — <span id="chargeBtnTotal">KSh ' + fmtNum(total) + '</span>';
        }
    })
    .catch(() => {
        showToast('Network error, please try again', 'error');
        btn.disabled = false;
    });
}

function newSale() {
    document.getElementById('successModal').classList.remove('open');
    cart = {};
    document.getElementById('discountInput').value  = '';
    document.getElementById('customerName').value   = '';
    document.getElementById('customerPhone').value  = '';
    document.getElementById('amountPaid').value     = '';
    document.getElementById('mpesaCode').value      = '';
    renderCart();
}

// ── Search / Filter ────────────────────────────────────────────
function filterProducts() {
    const q      = document.getElementById('productSearch').value.toLowerCase();
    const catId  = document.getElementById('categoryFilter').value;

    document.querySelectorAll('.prod-card').forEach(card => {
        const matchName = card.dataset.name.toLowerCase().includes(q);
        const matchCat  = !catId || card.dataset.cat === catId;
        card.style.display = (matchName && matchCat) ? '' : 'none';
    });
}

document.getElementById('productSearch').addEventListener('input', filterProducts);
document.getElementById('categoryFilter').addEventListener('change', filterProducts);

document.querySelectorAll('.cat-pill').forEach(pill => {
    pill.addEventListener('click', function () {
        document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('categoryFilter').value = this.dataset.id;
        filterProducts();
    });
});

// ── Helpers ────────────────────────────────────────────────────
function fmtNum(n) {
    return Number(n).toLocaleString('en-KE', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
}

function showToast(msg, type) {
    const t = document.createElement('div');
    t.style.cssText = `position:fixed;bottom:1.5rem;right:1.5rem;padding:.75rem 1.2rem;border-radius:10px;
        background:${type === 'error' ? '#ef4444' : '#10b981'};color:#fff;font-size:.88rem;
        font-weight:600;z-index:99999;box-shadow:0 4px 16px rgba(0,0,0,.2);
        animation:slideIn .3s ease`;
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 3000);
}
</script>
@endpush