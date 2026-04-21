@extends('layouts.admin')

@section('title', 'Adjust Stock — ' . $product->name)

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   STOCK — ADJUST
   ═══════════════════════════════════════════════════════════ */
.adjust-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 1.25rem;
    align-items: start;
}
@media (max-width: 960px) { .adjust-grid { grid-template-columns: 1fr; } }

/* ── Cards ── */
.sa-card {
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 1.25rem;
}
.sa-card:last-child { margin-bottom: 0; }
.sa-card-header {
    padding: .9rem 1.25rem;
    border-bottom: 1.5px solid var(--border);
    background: linear-gradient(120deg, #fff 55%, var(--purple-soft) 100%);
    display: flex; align-items: center; justify-content: space-between;
}
.sa-card-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: .95rem; font-weight: 700; margin: 0;
    display: flex; align-items: center; gap: .5rem;
}
.sa-card-header h3 i { color: var(--purple); }
.sa-card-body { padding: 1.25rem; }

/* ── Product hero ── */
.product-hero {
    background: linear-gradient(135deg, #5B21B6 0%, #7C3AED 55%, #F72585 100%);
    border-radius: var(--r);
    padding: 1.25rem 1.5rem;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
    box-shadow: 0 6px 24px rgba(124,58,237,.35);
    flex-wrap: wrap;
}
.product-hero-thumb {
    width: 64px; height: 64px; border-radius: 14px;
    object-fit: cover; flex-shrink: 0;
    border: 2px solid rgba(255,255,255,.3);
}
.product-hero-placeholder {
    width: 64px; height: 64px; border-radius: 14px;
    background: rgba(255,255,255,.18);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; flex-shrink: 0;
    border: 2px solid rgba(255,255,255,.3);
}
.product-hero-info { flex: 1; }
.product-hero-name {
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem; font-weight: 700; margin-bottom: .2rem;
}
.product-hero-meta { font-size: .78rem; opacity: .8; }
.product-hero-right { text-align: right; }
.product-hero-stock-label { font-size: .72rem; opacity: .8; margin-bottom: .2rem; }
.product-hero-stock-qty {
    font-size: 2rem; font-weight: 800;
    font-variant-numeric: tabular-nums;
    line-height: 1;
}
.product-hero-stock-unit { font-size: .78rem; opacity: .7; }

/* ── Adjustment type selector ── */
.adj-type-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: .65rem;
    margin-bottom: 1.25rem;
}
.adj-type-btn {
    padding: .85rem .75rem;
    border: 2px solid var(--border);
    border-radius: var(--r-sm);
    background: #fff;
    cursor: pointer;
    font-size: .82rem;
    font-weight: 600;
    font-family: inherit;
    color: var(--muted);
    text-align: center;
    transition: all .18s;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .35rem;
    position: relative;
}
.adj-type-btn i { font-size: 1.1rem; }
.adj-type-btn:hover { border-color: var(--purple); color: var(--purple); background: var(--purple-soft); }

.adj-type-btn.active-manual_add    { border-color: var(--green); background: var(--green-soft); color: #15803d; }
.adj-type-btn.active-manual_deduct { border-color: var(--gold);  background: #fffbeb;            color: #a16207; }
.adj-type-btn.active-damaged       { border-color: var(--tango); background: var(--pink-soft);   color: var(--tango); }
.adj-type-btn.active-expired       { border-color: var(--purple);background: var(--purple-soft); color: var(--purple); }

.adj-type-btn .adj-direction {
    font-size: .65rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    padding: .1rem .4rem; border-radius: 20px;
    margin-top: .1rem;
}
.adj-type-btn.active-manual_add    .adj-direction { background: #dcfce7; color: #15803d; }
.adj-type-btn.active-manual_deduct .adj-direction { background: #fef9c3; color: #a16207; }
.adj-type-btn.active-damaged       .adj-direction { background: #fecdd3; color: var(--tango); }
.adj-type-btn.active-expired       .adj-direction { background: var(--purple-soft); color: var(--purple); }

/* ── Form fields ── */
.pf-field { display: flex; flex-direction: column; gap: .35rem; margin-bottom: 1rem; }
.pf-field:last-child { margin-bottom: 0; }
.pf-label {
    font-size: .72rem; font-weight: 700;
    color: var(--muted); text-transform: uppercase; letter-spacing: .05em;
}
.pf-label span { color: var(--pink); margin-left: .15rem; }
.pf-input, .pf-textarea {
    padding: .62rem .9rem;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    font-size: .87rem; font-family: inherit;
    outline: none; background: #fff; color: var(--text);
    transition: border-color .18s, box-shadow .18s; width: 100%;
}
.pf-input:focus, .pf-textarea:focus {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(124,58,237,.08);
}
.pf-textarea { resize: vertical; min-height: 80px; }

/* ── Preview card ── */
.preview-card {
    background: #faf7ff;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    padding: 1rem;
    margin-bottom: 1rem;
}
.preview-row {
    display: flex; justify-content: space-between;
    align-items: center; padding: .4rem 0;
    border-bottom: 1px dashed var(--border);
    font-size: .84rem; color: var(--muted);
}
.preview-row:last-child { border-bottom: none; }
.preview-row.result {
    font-size: .95rem; font-weight: 700;
    color: var(--text); padding-top: .6rem;
    border-top: 2px solid var(--border);
    margin-top: .25rem; border-bottom: none;
}
.preview-row.result span:last-child { color: var(--purple); font-size: 1.05rem; }

/* Submit button */
.adj-submit-btn {
    width: 100%; padding: .9rem 1rem;
    border: none; border-radius: 12px;
    font-size: .95rem; font-weight: 700;
    cursor: pointer; font-family: inherit;
    transition: all .2s; display: flex;
    align-items: center; justify-content: center;
    gap: .55rem; letter-spacing: .02em;
}
.adj-submit-btn.add    { background: linear-gradient(135deg, var(--green), var(--green-lt)); color: #fff; box-shadow: 0 6px 20px rgba(45,198,83,.3); }
.adj-submit-btn.deduct { background: linear-gradient(135deg, var(--gold), #fbbf24); color: #fff; box-shadow: 0 6px 20px rgba(245,158,11,.3); }
.adj-submit-btn.damage { background: linear-gradient(135deg, var(--tango), var(--pink)); color: #fff; box-shadow: 0 6px 20px rgba(247,37,133,.3); }
.adj-submit-btn:hover  { transform: translateY(-2px); filter: brightness(1.05); }

/* ── History timeline ── */
.hist-timeline { display: flex; flex-direction: column; gap: 0; }
.hist-item {
    display: flex; gap: .85rem;
    padding-bottom: 1rem; position: relative;
}
.hist-item:not(:last-child)::before {
    content: '';
    position: absolute; left: 13px; top: 28px; bottom: 0;
    width: 2px; background: var(--border);
}
.hist-dot {
    width: 28px; height: 28px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .7rem; flex-shrink: 0; position: relative; z-index: 1;
    border: 2px solid transparent;
}
.hist-dot.in  { background: var(--green-soft); color: var(--green); border-color: var(--green); }
.hist-dot.out { background: var(--pink-soft);  color: var(--tango); border-color: var(--tango); }
.hist-content { flex: 1; padding-top: .2rem; }
.hist-title { font-size: .82rem; font-weight: 600; color: var(--text); }
.hist-meta  { font-size: .71rem; color: var(--muted); margin-top: .1rem; }
.hist-qty   {
    font-size: .78rem; font-weight: 700;
    padding: .12rem .45rem; border-radius: 20px;
    white-space: nowrap;
}
.hist-qty.in  { background: var(--green-soft); color: var(--green); }
.hist-qty.out { background: var(--pink-soft);  color: var(--tango); }
</style>
@endpush

@section('content')

{{-- Page header --}}
<div class="page-header" style="margin-bottom:1.25rem">
    <div>
        <h1 class="page-title">
            <i class="fas fa-sliders" style="color:var(--purple)"></i> Adjust Stock
        </h1>
        <p class="page-sub">Manually update stock for {{ $product->name }}</p>
    </div>
    <a href="{{ route('admin.stock.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

{{-- Product hero --}}
<div class="product-hero">
    <div>
        @if($product->thumbnail)
            <img src="{{ asset('storage/'.$product->thumbnail) }}"
                 alt="{{ $product->name }}" class="product-hero-thumb">
        @else
            <div class="product-hero-placeholder"><i class="fas fa-spa"></i></div>
        @endif
    </div>
    <div class="product-hero-info">
        <div class="product-hero-name">{{ $product->name }}</div>
        <div class="product-hero-meta">
            @if($product->sku)SKU: {{ $product->sku }} &nbsp;·&nbsp;@endif
            {{ $product->category?->name ?? 'Uncategorised' }}
        </div>
    </div>
    <div class="product-hero-right">
        <div class="product-hero-stock-label">Current Stock</div>
        <div class="product-hero-stock-qty" id="heroQty">
            {{ number_format($product->stock_quantity) }}
        </div>
        <div class="product-hero-stock-unit">units</div>
    </div>
</div>

<form method="POST"
      action="{{ route('admin.stock.store', $product->id) }}"
      id="adjustForm">
@csrf

<div class="adjust-grid">

    {{-- ════ LEFT ════ --}}
    <div>

        {{-- Adjustment type --}}
        <div class="sa-card">
            <div class="sa-card-header">
                <h3><i class="fas fa-arrows-up-down"></i> Adjustment Type</h3>
            </div>
            <div class="sa-card-body">

                <input type="hidden" name="type" id="typeInput" value="manual_add">

                <div class="adj-type-grid">

                    <button type="button" class="adj-type-btn active-manual_add"
                            data-type="manual_add" data-dir="in"
                            onclick="setType('manual_add','in')">
                        <i class="fas fa-plus-circle"></i>
                        Add Stock
                        <span class="adj-direction">+ IN</span>
                    </button>

                    <button type="button" class="adj-type-btn"
                            data-type="manual_deduct" data-dir="out"
                            onclick="setType('manual_deduct','out')">
                        <i class="fas fa-minus-circle"></i>
                        Deduct Stock
                        <span class="adj-direction">− OUT</span>
                    </button>

                    <button type="button" class="adj-type-btn"
                            data-type="damaged" data-dir="out"
                            onclick="setType('damaged','out')">
                        <i class="fas fa-box-archive"></i>
                        Mark Damaged
                        <span class="adj-direction">− OUT</span>
                    </button>

                    <button type="button" class="adj-type-btn"
                            data-type="expired" data-dir="out"
                            onclick="setType('expired','out')">
                        <i class="fas fa-clock"></i>
                        Mark Expired
                        <span class="adj-direction">− OUT</span>
                    </button>

                </div>

            </div>
        </div>

        {{-- Quantity & note --}}
        <div class="sa-card">
            <div class="sa-card-header">
                <h3><i class="fas fa-hashtag"></i> Details</h3>
            </div>
            <div class="sa-card-body">

                <div class="pf-field">
                    <label class="pf-label">Quantity <span>*</span></label>
                    <input type="number" name="quantity" id="qtyInput"
                           class="pf-input" value="1" min="1"
                           oninput="updatePreview()" required>
                </div>

                <div class="pf-field">
                    <label class="pf-label">Note / Reason</label>
                    <textarea name="note" class="pf-textarea"
                              placeholder="Optional reason for this adjustment…"></textarea>
                </div>

            </div>
        </div>

    </div>{{-- end left --}}

    {{-- ════ RIGHT ════ --}}
    <div>

        {{-- Preview --}}
        <div class="sa-card">
            <div class="sa-card-header">
                <h3><i class="fas fa-calculator"></i> Preview</h3>
            </div>
            <div class="sa-card-body">

                <div class="preview-card">
                    <div class="preview-row">
                        <span>Current Stock</span>
                        <span style="font-weight:600">{{ $product->stock_quantity }} units</span>
                    </div>
                    <div class="preview-row">
                        <span id="previewTypeLabel">Adding</span>
                        <span id="previewQty" style="font-weight:600;color:var(--green)">+ 1 unit</span>
                    </div>
                    <div class="preview-row result">
                        <span>New Stock</span>
                        <span id="previewResult">{{ $product->stock_quantity + 1 }} units</span>
                    </div>
                </div>

                <button type="submit" class="adj-submit-btn add" id="submitBtn">
                    <i class="fas fa-plus-circle" id="submitIcon"></i>
                    <span id="submitLabel">Add Stock</span>
                </button>

                <a href="{{ route('admin.stock.index') }}"
                   class="btn btn-outline"
                   style="width:100%;margin-top:.65rem;text-align:center;display:block">
                    Cancel
                </a>

            </div>
        </div>

        {{-- Recent history --}}
        <div class="sa-card">
            <div class="sa-card-header">
                <h3><i class="fas fa-clock-rotate-left"></i> Recent History</h3>
                <a href="{{ route('admin.stock.history', ['product_id' => $product->id]) }}"
                   style="font-size:.75rem;color:var(--purple);font-weight:600">
                    View all
                </a>
            </div>
            <div class="sa-card-body">

                @if($history->isEmpty())
                    <div style="text-align:center;color:var(--muted);font-size:.83rem;padding:1rem 0">
                        <i class="fas fa-clock" style="opacity:.2;font-size:1.5rem;display:block;margin-bottom:.5rem"></i>
                        No history yet
                    </div>
                @else
                    <div class="hist-timeline">
                        @foreach($history as $log)
                        <div class="hist-item">
                            <div class="hist-dot {{ $log->direction }}">
                                <i class="fas {{ $log->getTypeIconClass() }}"></i>
                            </div>
                            <div class="hist-content">
                                <div style="display:flex;align-items:center;justify-content:space-between;gap:.4rem">
                                    <div class="hist-title">{{ $log->getTypeLabel() }}</div>
                                    <span class="hist-qty {{ $log->direction }}">
                                        {{ $log->direction === 'in' ? '+' : '-' }}{{ $log->quantity }}
                                    </span>
                                </div>
                                <div class="hist-meta">
                                    {{ $log->created_at->format('d M Y, H:i') }}
                                    · {{ $log->createdBy->name ?? 'System' }}
                                    @if($log->note)
                                        · <em>{{ Str::limit($log->note, 40) }}</em>
                                    @endif
                                </div>
                                <div style="font-size:.7rem;color:var(--muted);margin-top:.1rem">
                                    {{ $log->stock_before }} → {{ $log->stock_after }} units
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>

    </div>{{-- end right --}}

</div>
</form>

@endsection

@push('scripts')
<script>
const CURRENT_STOCK = {{ $product->stock_quantity }};

const TYPE_CONFIG = {
    manual_add: {
        label:       'Add Stock',
        previewLabel:'Adding',
        icon:        'fa-plus-circle',
        btnClass:    'add',
        sign:        '+',
        color:       'var(--green)',
    },
    manual_deduct: {
        label:       'Deduct Stock',
        previewLabel:'Deducting',
        icon:        'fa-minus-circle',
        btnClass:    'deduct',
        sign:        '-',
        color:       'var(--gold)',
    },
    damaged: {
        label:       'Mark as Damaged',
        previewLabel:'Removing (Damaged)',
        icon:        'fa-box-archive',
        btnClass:    'damage',
        sign:        '-',
        color:       'var(--tango)',
    },
    expired: {
        label:       'Mark as Expired',
        previewLabel:'Removing (Expired)',
        icon:        'fa-clock',
        btnClass:    'damage',
        sign:        '-',
        color:       'var(--purple)',
    },
};

let currentType = 'manual_add';
let currentDir  = 'in';

function setType(type, dir) {
    currentType = type;
    currentDir  = dir;

    // Update hidden input
    document.getElementById('typeInput').value = type;

    // Toggle active class on buttons
    document.querySelectorAll('.adj-type-btn').forEach(btn => {
        btn.classList.remove(
            'active-manual_add','active-manual_deduct',
            'active-damaged','active-expired'
        );
        if (btn.dataset.type === type) {
            btn.classList.add('active-' + type);
        }
    });

    updatePreview();
}

function updatePreview() {
    const cfg      = TYPE_CONFIG[currentType];
    const qty      = parseInt(document.getElementById('qtyInput').value) || 0;
    const newStock = currentDir === 'in'
        ? CURRENT_STOCK + qty
        : Math.max(0, CURRENT_STOCK - qty);

    // Preview labels
    document.getElementById('previewTypeLabel').textContent = cfg.previewLabel;
    document.getElementById('previewQty').textContent       = cfg.sign + qty + ' unit' + (qty !== 1 ? 's' : '');
    document.getElementById('previewQty').style.color       = cfg.color;
    document.getElementById('previewResult').textContent    = newStock + ' units';
    document.getElementById('previewResult').style.color    = newStock <= 0
        ? 'var(--tango)' : newStock <= 10
        ? 'var(--gold)'  : 'var(--green)';

    // Update hero qty
    document.getElementById('heroQty').textContent = newStock.toLocaleString();

    // Submit button
    const btn = document.getElementById('submitBtn');
    btn.className = 'adj-submit-btn ' + cfg.btnClass;
    document.getElementById('submitIcon').className = 'fas ' + cfg.icon;
    document.getElementById('submitLabel').textContent = cfg.label;
}

// Init
setType('manual_add', 'in');
</script>
@endpush