@extends('layouts.app')
@section('title', 'Menu Management — Admin')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
<style>
/* ===================================================================
   DESIGN TOKENS — mirror the kiosk's token set exactly
=================================================================== */
:root {
    --k-primary:             #a33800;
    --k-primary-container:   #ff7941;
    --k-secondary:           #954400;
    --k-secondary-container: #ffc5a5;
    --k-tertiary:            #f8a91f;
    --k-surface:             #f9f6f5;
    --k-surface-variant:     #efe0d8;
    --k-on-surface:          #2f2e2e;
    --k-on-surface-muted:    #7a6e6a;
    --k-on-surface-faint:    #c2b8b4;
    --k-border:              rgba(163,56,0,0.12);
    --k-border-strong:       rgba(163,56,0,0.22);
    --k-shadow:              rgba(47,46,46,0.10);
    --k-shadow-md:           rgba(47,46,46,0.16);
    --k-success:             #1a7f52;
    --k-success-bg:          rgba(26,127,82,0.10);
    --k-danger:              #dc2626;
    --k-danger-bg:           rgba(220,38,38,0.08);
    --k-info:                #2563eb;
    --k-info-bg:             rgba(37,99,235,0.10);
    --k-warning:             #d97706;
    --k-warning-bg:          rgba(245,166,35,0.12);
    --k-radius-sm:           10px;
    --k-radius-md:           16px;
    --k-radius-lg:           24px;
    --k-radius-full:         9999px;
    --k-spring:              cubic-bezier(0.34, 1.56, 0.64, 1);
    --k-ease-out:            cubic-bezier(0.16, 1, 0.3, 1);

    /* Legacy aliases used in JS innerHTML snippets */
    --bg-base:           #f9f6f5;
    --bg-surface:        #ffffff;
    --bg-surface-hover:  rgba(255,121,65,0.06);
    --bg-glass:          rgba(255,255,255,0.80);
    --bg-glass-strong:   #ffffff;
    --text-primary:      #2f2e2e;
    --text-secondary:    #5c5b5b;
    --text-muted:        #7a6e6a;
    --accent-primary:    #a33800;
    --accent-secondary:  #ff7941;
    --accent-gradient:   linear-gradient(135deg, #a33800 0%, #802c00 100%);
    --accent-glow:       rgba(163,56,0,0.22);
    --success:           #1a7f52;
    --success-glow:      rgba(26,127,82,0.25);
    --info:              #2563eb;
    --warning:           #d97706;
    --border:            rgba(163,56,0,0.12);
    --border-focus:      rgba(163,56,0,0.45);
    --radius-sm:         10px;
    --radius-md:         16px;
    --radius-lg:         24px;
    --radius-full:       9999px;
    --spring:            cubic-bezier(0.34, 1.56, 0.64, 1);
    --font-display:      'Plus Jakarta Sans', sans-serif;
}

/* ===================================================================
   RESET
=================================================================== */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { font-size: 16px; -webkit-tap-highlight-color: transparent; }
body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--k-surface);
    color: var(--k-on-surface);
    -webkit-font-smoothing: antialiased;
}
button { cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; }
input, textarea, select { font-family: 'Plus Jakarta Sans', sans-serif; }
.hidden { display: none !important; }
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: var(--k-on-surface-faint); border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: var(--k-on-surface-muted); }

/* ===================================================================
   MATERIAL ICON HELPER
=================================================================== */
.mat-icon {
    font-family: 'Material Symbols Outlined';
    font-weight: normal;
    font-style: normal;
    font-size: 24px;
    line-height: 1;
    letter-spacing: normal;
    text-transform: none;
    display: inline-block;
    white-space: nowrap;
    word-wrap: normal;
    direction: ltr;
    -webkit-font-smoothing: antialiased;
    user-select: none;
}
.mat-icon-filled { font-variation-settings: 'FILL' 1; }

/* ===================================================================
   ADMIN SHELL — mirrors .kiosk grid
=================================================================== */
.admin {
    display: grid;
    grid-template-columns: 300px 1fr;
    grid-template-rows: 72px 1fr;
    height: 100vh;
    overflow: hidden;
    background: var(--k-surface);
}

/* ===================================================================
   HEADER — mirrors .kiosk-header
=================================================================== */
.admin-topbar {
    grid-column: 1 / -1;
    grid-row: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 28px;
    background: #ffffff;
    border-bottom: 1px solid var(--k-border);
    box-shadow: 0 1px 8px var(--k-shadow);
    z-index: 50;
    gap: 16px;
}

.admin-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}
.header-brand-name {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--k-primary);
    letter-spacing: -0.3px;
    white-space: nowrap;
}
.header-brand-name span { color: var(--k-primary-container); }
.header-brand-sep {
    width: 1px;
    height: 22px;
    background: var(--k-border-strong);
    flex-shrink: 0;
}
.header-page-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--k-on-surface-muted);
    letter-spacing: 0.2px;
    white-space: nowrap;
}

.admin-topbar-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

/* Clear Cache button — same as kiosk cart-bar-checkout style */
.btn-clear-cache {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    background: #ffffff;
    color: var(--k-primary);
    border: 1.5px solid var(--k-border-strong);
    border-radius: var(--k-radius-full);
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.2px;
    box-shadow: 0 2px 8px var(--k-shadow);
    transition: transform 0.15s var(--k-spring), box-shadow 0.15s, border-color 0.15s, background 0.15s;
}
.btn-clear-cache:hover {
    background: var(--k-surface-variant);
    border-color: var(--k-primary-container);
    box-shadow: 0 4px 16px var(--k-shadow-md);
    transform: translateY(-1px);
}
.btn-clear-cache:active { transform: scale(0.96); }
.btn-clear-cache .mat-icon { font-size: 17px; }

/* ===================================================================
   SIDEBAR — mirrors .kiosk-sidebar exactly
=================================================================== */
.sidebar {
    grid-column: 1;
    grid-row: 2;
    background: #ffffff;
    border-right: 1px solid var(--k-border);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 2px 0 12px var(--k-shadow);
}

.sidebar-header {
    padding: 16px 20px 12px;
    border-bottom: 1px solid var(--k-border);
    flex-shrink: 0;
}
.sidebar-header-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}
.sidebar-section-label {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: var(--k-on-surface-faint);
}
.sidebar-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 22px;
    height: 22px;
    padding: 0 6px;
    background: var(--k-surface-variant);
    border-radius: var(--k-radius-full);
    font-size: 0.68rem;
    font-weight: 700;
    color: var(--k-on-surface-muted);
}

/* Sidebar list */
.sidebar-list {
    flex: 1;
    overflow-y: auto;
    padding: 10px 0;
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.sidebar-list::-webkit-scrollbar { width: 3px; }

/* Sidebar footer */
.sidebar-footer {
    padding: 12px 16px;
    border-top: 1px solid var(--k-border);
    flex-shrink: 0;
}

/* Add Category button — orange outline pill */
.btn-add-category {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    width: 100%;
    padding: 10px 16px;
    border: 1.5px solid var(--k-primary-container);
    border-radius: var(--k-radius-full);
    background: transparent;
    color: var(--k-primary);
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.2px;
    transition: background 0.18s var(--k-ease-out), box-shadow 0.18s, transform 0.15s var(--k-spring);
}
.btn-add-category:hover {
    background: rgba(255,121,65,0.08);
    box-shadow: 0 2px 12px rgba(255,121,65,0.20);
    transform: translateY(-1px);
}
.btn-add-category:active { transform: scale(0.97); }
.btn-add-category .mat-icon { font-size: 18px; }

/* ===================================================================
   CATEGORY NAV ITEMS — mirrors .cat-nav-item exactly
=================================================================== */
.cat-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px 12px 20px;
    margin: 0 12px 0 0;
    border-radius: 0 var(--k-radius-full) var(--k-radius-full) 0;
    cursor: pointer;
    transition: background 0.2s var(--k-ease-out), color 0.2s, box-shadow 0.2s;
    color: var(--k-on-surface-muted);
    font-size: 0.88rem;
    font-weight: 600;
    border: none;
    background: transparent;
    text-align: left;
    width: calc(100% - 12px);
    position: relative;
}
.cat-item:hover {
    background: rgba(255,121,65,0.08);
    color: var(--k-primary);
}
.cat-item.active {
    background: var(--k-primary-container);
    color: #ffffff;
    box-shadow: 4px 4px 16px rgba(255,121,65,0.30);
}
.cat-item .mat-icon {
    font-size: 20px;
    flex-shrink: 0;
    transition: font-variation-settings 0.2s;
}
.cat-item.active .mat-icon { font-variation-settings: 'FILL' 1; }
.cat-item.drag-over {
    background: rgba(255,121,65,0.12);
    outline: 1.5px dashed var(--k-primary-container);
    outline-offset: -2px;
}

.cat-info { flex: 1; min-width: 0; }
.cat-name {
    font-weight: 600;
    font-size: 0.88rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.cat-meta {
    font-size: 0.68rem;
    margin-top: 2px;
    display: flex;
    align-items: center;
    gap: 6px;
    opacity: 0.75;
}
.cat-item.active .cat-meta { opacity: 0.85; }

/* Product count pill — same as .cat-nav-item-count */
.cat-count {
    font-size: 0.68rem;
    font-weight: 700;
    background: rgba(255,255,255,0.25);
    padding: 2px 8px;
    border-radius: var(--k-radius-full);
    flex-shrink: 0;
}
.cat-item:not(.active) .cat-count {
    background: var(--k-surface-variant);
    color: var(--k-on-surface-muted);
}

/* Status badge */
.cat-badge {
    display: inline-flex;
    align-items: center;
    padding: 1px 6px;
    border-radius: var(--k-radius-full);
    font-size: 0.6rem;
    font-weight: 700;
}
.badge-active   { background: var(--k-success-bg); color: var(--k-success); }
.badge-inactive { background: rgba(0,0,0,0.06); color: var(--k-on-surface-faint); }

/* Cat actions (edit/delete) — revealed on hover */
.cat-actions {
    display: flex;
    align-items: center;
    gap: 2px;
    opacity: 0;
    transition: opacity 0.15s;
    flex-shrink: 0;
}
.cat-item:hover .cat-actions { opacity: 1; }
.cat-item.active .cat-actions { opacity: 0.8; }

.icon-btn {
    width: 28px;
    height: 28px;
    border: none;
    border-radius: 7px;
    background: transparent;
    display: grid;
    place-items: center;
    color: inherit;
    transition: background 0.15s, color 0.15s;
}
.cat-item:not(.active) .icon-btn:hover {
    background: var(--k-surface-variant);
    color: var(--k-on-surface);
}
.cat-item:not(.active) .icon-btn.del:hover {
    background: var(--k-danger-bg);
    color: var(--k-danger);
}
.cat-item.active .icon-btn:hover {
    background: rgba(255,255,255,0.22);
    color: #ffffff;
}
.icon-btn .mat-icon { font-size: 15px; }

.order-btns { display: flex; flex-direction: column; gap: 0; }
.order-btn .mat-icon { font-size: 13px; }

/* ===================================================================
   MAIN CONTENT AREA — mirrors .kiosk-main
=================================================================== */
.main-content {
    grid-column: 2;
    grid-row: 2;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

/* Content header — mirrors .main-category-header */
.content-header {
    padding: 22px 28px 16px;
    flex-shrink: 0;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    border-bottom: 1px solid var(--k-border);
    background: #ffffff;
}
.content-header-left { display: flex; flex-direction: column; gap: 4px; min-width: 0; }
.content-category-name {
    font-size: 1.55rem;
    font-weight: 700;
    color: var(--k-on-surface);
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.content-category-desc {
    font-size: 0.82rem;
    color: var(--k-on-surface-muted);
    line-height: 1.5;
}
.content-header-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

/* Search input */
.search-wrap { position: relative; }
.search-wrap .mat-icon {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 18px;
    color: var(--k-on-surface-faint);
    pointer-events: none;
}
.search-input {
    background: var(--k-surface-variant);
    border: 1.5px solid var(--k-border);
    border-radius: var(--k-radius-full);
    color: var(--k-on-surface);
    padding: 8px 14px 8px 36px;
    font-size: 0.82rem;
    font-weight: 500;
    width: 210px;
    outline: none;
    transition: border-color 0.15s, background 0.15s, box-shadow 0.15s;
}
.search-input:focus {
    border-color: var(--k-primary-container);
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(255,121,65,0.12);
}
.search-input::placeholder { color: var(--k-on-surface-faint); }

/* Add Product button — orange gradient */
.btn-add-product {
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 9px 20px;
    background: linear-gradient(135deg, var(--k-primary-container), var(--k-primary));
    color: #ffffff;
    border: none;
    border-radius: var(--k-radius-full);
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.2px;
    box-shadow: 0 4px 14px rgba(163,56,0,0.25);
    transition: transform 0.15s var(--k-spring), box-shadow 0.15s, opacity 0.15s;
}
.btn-add-product:hover {
    opacity: 0.92;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(163,56,0,0.35);
}
.btn-add-product:active { transform: scale(0.96); }
.btn-add-product .mat-icon { font-size: 18px; }

/* ===================================================================
   PRODUCTS SCROLL + GRID — mirrors .products-scroll and .bento-grid
=================================================================== */
.products-grid-wrap {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 20px 28px 40px;
}
.products-grid-wrap::-webkit-scrollbar { width: 4px; }

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 16px;
    padding-top: 4px;
    animation: gridFadeUp 0.38s var(--k-ease-out);
}
@keyframes gridFadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ===================================================================
   PRODUCT CARD — mirrors .product-card base + admin controls
=================================================================== */
.product-card {
    background: #ffffff;
    border-radius: var(--k-radius-lg);
    border: 1.5px solid var(--k-border);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    position: relative;
    transition: transform 0.22s var(--k-spring), box-shadow 0.22s var(--k-ease-out), border-color 0.2s;
    box-shadow: 0 2px 8px var(--k-shadow);
}
.product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 32px var(--k-shadow-md);
    border-color: var(--k-primary-container);
}
.product-card.unavailable { opacity: 0.6; }

/* ---- Image area ---- */
.product-img-wrap {
    position: relative;
    height: 160px;
    overflow: hidden;
    background: var(--k-surface-variant);
    cursor: pointer;
    flex-shrink: 0;
}
.product-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.38s var(--k-ease-out);
}
.product-card:hover .product-img { transform: scale(1.05); }

/* Gradient placeholder when no image — matching kiosk style */
.product-img-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: linear-gradient(135deg, var(--k-surface-variant) 0%, #e8d8d0 100%);
    position: absolute;
    inset: 0;
}
.product-img-placeholder .mat-icon {
    font-size: 40px;
    color: var(--k-on-surface-faint);
    font-variation-settings: 'FILL' 1;
    opacity: 0.45;
}
.product-img-placeholder span {
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--k-on-surface-faint);
    letter-spacing: 0.5px;
}

/* Image hover overlay — upload/replace/remove */
.product-img-overlay {
    position: absolute;
    inset: 0;
    background: rgba(47,46,46,0.52);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    opacity: 0;
    transition: opacity 0.22s var(--k-ease-out);
}
.product-img-wrap:hover .product-img-overlay { opacity: 1; }

.img-overlay-btn {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 7px 14px;
    border-radius: var(--k-radius-full);
    font-size: 0.75rem;
    font-weight: 700;
    border: none;
    background: rgba(255,255,255,0.90);
    color: var(--k-on-surface);
    backdrop-filter: blur(8px);
    transition: background 0.15s, transform 0.12s var(--k-spring);
    letter-spacing: 0.1px;
}
.img-overlay-btn:hover {
    background: #ffffff;
    transform: scale(1.04);
}
.img-overlay-btn.del-img {
    background: rgba(220,38,38,0.88);
    color: #ffffff;
}
.img-overlay-btn.del-img:hover {
    background: #dc2626;
}
.img-overlay-btn .mat-icon { font-size: 14px; }
.product-img-input { display: none; }


/* Pill badges overlaid on image */
.product-pills {
    position: absolute;
    bottom: 10px;
    left: 10px;
    right: 10px;
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    z-index: 3;
}
.avail-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: var(--k-radius-full);
    font-size: 0.64rem;
    font-weight: 700;
    letter-spacing: 0.4px;
    backdrop-filter: blur(8px);
    cursor: pointer;
    transition: transform 0.12s var(--k-spring), box-shadow 0.12s;
    border: none;
}
.avail-pill:hover { transform: scale(1.06); }
.avail-pill.on  {
    background: rgba(26,127,82,0.25);
    color: var(--k-success);
    border: 1px solid rgba(26,127,82,0.30);
}
.avail-pill.off {
    background: rgba(0,0,0,0.08);
    color: var(--k-on-surface-faint);
    border: 1px solid rgba(0,0,0,0.12);
}
.avail-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
.avail-pill.on  .avail-dot { background: var(--k-success); box-shadow: 0 0 5px rgba(26,127,82,0.6); animation: pulse-dot 2s infinite; }
.avail-pill.off .avail-dot { background: var(--k-on-surface-faint); }
@keyframes pulse-dot { 0%, 100% { opacity: 1; } 50% { opacity: 0.35; } }

.featured-pill {
    background: var(--k-warning-bg);
    color: var(--k-warning);
    border: 1px solid rgba(245,166,35,0.35);
    font-size: 0.62rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    padding: 3px 9px;
    border-radius: var(--k-radius-full);
    text-transform: uppercase;
    backdrop-filter: blur(8px);
}
.size-pill {
    background: var(--k-info-bg);
    color: var(--k-info);
    border: 1px solid rgba(37,99,235,0.25);
    font-size: 0.62rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    padding: 3px 9px;
    border-radius: var(--k-radius-full);
    text-transform: uppercase;
    backdrop-filter: blur(8px);
}

/* ---- Card body ---- */
.product-body {
    padding: 16px 16px 14px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.product-name {
    font-size: 1rem;
    font-weight: 700;
    color: var(--k-on-surface);
    line-height: 1.25;
    letter-spacing: -0.1px;
}
.product-desc {
    font-size: 0.78rem;
    color: var(--k-on-surface-muted);
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.product-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-top: 2px;
}
.product-price {
    font-size: 1.2rem;
    font-weight: 800;
    color: var(--k-primary);
    letter-spacing: -0.3px;
}
.product-prep {
    font-size: 0.68rem;
    color: var(--k-on-surface-faint);
    display: flex;
    align-items: center;
    gap: 3px;
}
.product-prep .mat-icon { font-size: 13px; }

/* ---- Customizations section ---- */
.customizations-section {
    border-top: 1px solid var(--k-border);
    padding-top: 10px;
    margin-top: 4px;
}
.customizations-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}
.customizations-label {
    font-size: 0.62rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--k-on-surface-faint);
}
.btn-add-custom {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border: 1.5px solid var(--k-border-strong);
    border-radius: var(--k-radius-full);
    background: transparent;
    color: var(--k-primary);
    font-size: 0.68rem;
    font-weight: 700;
    transition: background 0.15s, border-color 0.15s;
}
.btn-add-custom:hover {
    background: rgba(163,56,0,0.06);
    border-color: var(--k-primary-container);
}
.btn-add-custom .mat-icon { font-size: 13px; }

.customizations-list {
    display: flex;
    flex-direction: column;
    gap: 5px;
    max-height: 130px;
    overflow-y: auto;
}
.customizations-list::-webkit-scrollbar { width: 3px; }

/* Customization chip — pill with type-colored left border */
.custom-chip {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding: 6px 10px;
    background: var(--k-surface);
    border-radius: var(--k-radius-sm);
    border: 1px solid var(--k-border);
    border-left: 3px solid var(--k-primary-container);
    transition: border-color 0.15s, background 0.15s;
}
.custom-chip:hover {
    background: #ffffff;
    border-color: var(--k-border-strong);
    border-left-color: var(--k-primary-container);
}
.custom-chip-left {
    display: flex;
    align-items: center;
    gap: 7px;
    min-width: 0;
}
.custom-type-tag {
    padding: 2px 7px;
    background: rgba(163,56,0,0.08);
    color: var(--k-primary);
    border-radius: var(--k-radius-sm);
    font-size: 0.6rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    flex-shrink: 0;
}
.custom-name {
    font-size: 0.76rem;
    font-weight: 500;
    color: var(--k-on-surface);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.custom-price {
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--k-primary);
    flex-shrink: 0;
}
.custom-chip-actions {
    display: flex;
    gap: 2px;
    opacity: 0;
    transition: opacity 0.15s;
    flex-shrink: 0;
}
.custom-chip:hover .custom-chip-actions { opacity: 1; }
.chip-btn {
    width: 22px;
    height: 22px;
    border: none;
    border-radius: 6px;
    background: transparent;
    display: grid;
    place-items: center;
    color: var(--k-on-surface-muted);
    transition: background 0.12s, color 0.12s;
}
.chip-btn:hover     { background: var(--k-surface-variant); color: var(--k-on-surface); }
.chip-btn.del:hover { background: var(--k-danger-bg); color: var(--k-danger); }
.chip-btn .mat-icon { font-size: 13px; }

.no-customizations {
    font-size: 0.72rem;
    color: var(--k-on-surface-faint);
    font-style: italic;
    text-align: center;
    padding: 8px 0;
}

/* ---- Card footer ---- */
.product-footer {
    display: flex;
    gap: 6px;
    padding: 10px 14px 12px;
    border-top: 1px solid var(--k-border);
    background: #faf8f7;
    flex-shrink: 0;
}
.btn-card-edit,
.btn-card-delete {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px 12px;
    border-radius: var(--k-radius-sm);
    font-size: 0.78rem;
    font-weight: 700;
    transition: background 0.15s, color 0.15s, transform 0.12s var(--k-spring);
    border: 1.5px solid transparent;
}
.btn-card-edit {
    background: transparent;
    color: var(--k-on-surface-muted);
    border-color: var(--k-border-strong);
}
.btn-card-edit:hover {
    background: var(--k-surface-variant);
    color: var(--k-on-surface);
    border-color: var(--k-border-strong);
}
.btn-card-delete {
    background: transparent;
    color: var(--k-danger);
    border-color: rgba(220,38,38,0.20);
}
.btn-card-delete:hover {
    background: var(--k-danger-bg);
    border-color: rgba(220,38,38,0.35);
}
.btn-card-edit:active,
.btn-card-delete:active { transform: scale(0.96); }
.btn-card-edit .mat-icon,
.btn-card-delete .mat-icon { font-size: 15px; }

/* ===================================================================
   EMPTY STATES — warm gradient backgrounds with Material icons
=================================================================== */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 16px;
    padding: 80px 32px;
    text-align: center;
    color: var(--k-on-surface-muted);
}
.empty-state-icon-wrap {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--k-surface-variant) 0%, #e8d8d0 100%);
    border-radius: var(--k-radius-md);
    display: grid;
    place-items: center;
    border: 1.5px solid var(--k-border);
}
.empty-state-icon-wrap .mat-icon {
    font-size: 38px;
    color: var(--k-on-surface-faint);
    font-variation-settings: 'FILL' 1;
}
.empty-state-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--k-on-surface);
    letter-spacing: -0.1px;
}
.empty-state-text {
    font-size: 0.82rem;
    max-width: 320px;
    line-height: 1.6;
    color: var(--k-on-surface-muted);
}

/* No-category-selected placeholder */
.no-category-selected {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 18px;
    text-align: center;
    color: var(--k-on-surface-muted);
    padding: 40px;
}

/* ===================================================================
   MODALS — rounded-24px, white, drop shadow
=================================================================== */
.modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(47,46,46,0.45);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 200;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s var(--k-ease-out);
}
.modal-backdrop.open {
    opacity: 1;
    pointer-events: all;
}
.modal {
    background: #ffffff;
    border: 1.5px solid var(--k-border);
    border-radius: var(--k-radius-lg);
    width: 100%;
    max-width: 520px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 24px 64px rgba(47,46,46,0.22), 0 4px 18px rgba(47,46,46,0.08);
    transform: translateY(32px) scale(0.96);
    transition: transform 0.3s var(--k-spring);
}
.modal::-webkit-scrollbar { width: 3px; }
.modal-backdrop.open .modal {
    transform: translateY(0) scale(1);
}
.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 22px 24px 18px;
    border-bottom: 1px solid var(--k-border);
    background: var(--k-surface);
    border-radius: var(--k-radius-lg) var(--k-radius-lg) 0 0;
}
.modal-title {
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--k-on-surface);
    letter-spacing: -0.1px;
}
.modal-close {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: 1.5px solid var(--k-border-strong);
    background: #ffffff;
    color: var(--k-on-surface-muted);
    display: grid;
    place-items: center;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
    font-size: 1.1rem;
    line-height: 1;
}
.modal-close:hover {
    background: var(--k-primary-container);
    color: #ffffff;
    border-color: var(--k-primary-container);
}
.modal-body {
    padding: 22px 24px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 16px 24px 20px;
    border-top: 1px solid var(--k-border);
}

/* ===================================================================
   FORM ELEMENTS
=================================================================== */
.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-label {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: var(--k-on-surface-faint);
}
.form-label span { color: var(--k-danger); margin-left: 2px; }
.form-control {
    background: #faf8f7;
    border: 1.5px solid var(--k-border-strong);
    border-radius: var(--k-radius-sm);
    color: var(--k-on-surface);
    padding: 10px 13px;
    font-size: 0.85rem;
    font-weight: 500;
    outline: none;
    transition: border-color 0.15s, background 0.15s, box-shadow 0.15s;
    width: 100%;
}
.form-control:focus {
    border-color: var(--k-primary-container);
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(255,121,65,0.12);
}
.form-control::placeholder { color: var(--k-on-surface-faint); }
textarea.form-control { resize: vertical; min-height: 80px; line-height: 1.5; }
select.form-control { cursor: pointer; }
select.form-control option { background: #ffffff; color: var(--k-on-surface); }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

/* Toggle switch — matches kiosk style */
.toggle-wrap { display: flex; align-items: center; gap: 12px; }
.toggle-label-text { font-size: 0.85rem; font-weight: 500; color: var(--k-on-surface-muted); }
.toggle {
    position: relative;
    width: 44px;
    height: 26px;
    flex-shrink: 0;
}
.toggle input { opacity: 0; width: 0; height: 0; }
.toggle-slider {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.10);
    border-radius: 13px;
    border: 1.5px solid var(--k-border-strong);
    cursor: pointer;
    transition: background 0.22s, border-color 0.22s;
}
.toggle-slider::before {
    content: '';
    position: absolute;
    width: 18px;
    height: 18px;
    left: 3px;
    top: 2px;
    background: #ffffff;
    border-radius: 50%;
    box-shadow: 0 1px 4px rgba(0,0,0,0.22);
    transition: transform 0.22s var(--k-spring), background 0.22s;
}
.toggle input:checked + .toggle-slider {
    background: rgba(26,127,82,0.20);
    border-color: rgba(26,127,82,0.45);
}
.toggle input:checked + .toggle-slider::before {
    transform: translateX(18px);
    background: var(--k-success);
    box-shadow: 0 0 8px rgba(26,127,82,0.40);
}

/* ===================================================================
   MODAL BUTTONS
=================================================================== */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 20px;
    border-radius: var(--k-radius-sm);
    font-size: 0.85rem;
    font-weight: 700;
    border: none;
    transition: opacity 0.15s, transform 0.15s var(--k-spring), box-shadow 0.15s;
    white-space: nowrap;
    letter-spacing: 0.2px;
    cursor: pointer;
}
.btn:active { transform: scale(0.97); }

/* Orange gradient primary */
.btn-primary {
    background: linear-gradient(135deg, var(--k-primary-container), var(--k-primary));
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(163,56,0,0.25);
}
.btn-primary:hover {
    opacity: 0.92;
    box-shadow: 0 6px 20px rgba(163,56,0,0.35);
    transform: translateY(-1px);
}

/* Ghost secondary */
.btn-ghost {
    background: #ffffff;
    color: var(--k-on-surface-muted);
    border: 1.5px solid var(--k-border-strong);
}
.btn-ghost:hover {
    background: var(--k-surface-variant);
    color: var(--k-on-surface);
}

/* Danger */
.btn-danger {
    background: var(--k-danger-bg);
    color: var(--k-danger);
    border: 1.5px solid rgba(220,38,38,0.22);
}
.btn-danger:hover { background: rgba(220,38,38,0.14); }
.btn-sm { padding: 7px 14px; font-size: 0.78rem; }

/* ===================================================================
   DELETE CONFIRMATION MODAL
=================================================================== */
.delete-warning {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    background: rgba(220,38,38,0.05);
    border: 1.5px solid rgba(220,38,38,0.16);
    border-radius: var(--k-radius-md);
}
.delete-warning-icon {
    width: 44px;
    height: 44px;
    background: rgba(220,38,38,0.10);
    border-radius: var(--k-radius-sm);
    display: grid;
    place-items: center;
    color: var(--k-danger);
    flex-shrink: 0;
}
.delete-warning-icon .mat-icon {
    font-size: 22px;
    font-variation-settings: 'FILL' 1;
}
.delete-warning-text { font-size: 0.85rem; color: var(--k-on-surface-muted); line-height: 1.55; }
.delete-warning-subject {
    font-weight: 700;
    color: var(--k-on-surface);
    font-size: 0.92rem;
    margin-bottom: 4px;
}

/* ===================================================================
   TOAST — white card with colored left border
=================================================================== */
.toast-container {
    position: fixed;
    bottom: 28px;
    right: 28px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 10px;
    pointer-events: none;
}
.toast {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 13px 18px 13px 15px;
    background: #ffffff;
    border: 1px solid var(--k-border);
    border-radius: var(--k-radius-md);
    box-shadow: 0 8px 32px var(--k-shadow-md);
    min-width: 280px;
    max-width: 400px;
    pointer-events: all;
    animation: toastIn 0.35s var(--k-spring) forwards;
    position: relative;
    overflow: hidden;
}
.toast::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    border-radius: var(--k-radius-md) 0 0 var(--k-radius-md);
}
.toast.success::before { background: var(--k-success); }
.toast.error::before   { background: var(--k-danger); }
.toast.info::before    { background: var(--k-info); }
.toast.removing { animation: toastOut 0.25s ease-in forwards; }
@keyframes toastIn  { from { opacity: 0; transform: translateX(20px) scale(0.96); } to { opacity: 1; transform: none; } }
@keyframes toastOut { to   { opacity: 0; transform: translateX(8px) scale(0.95); } }

.toast-icon {
    width: 30px;
    height: 30px;
    border-radius: var(--k-radius-sm);
    display: grid;
    place-items: center;
    flex-shrink: 0;
}
.toast.success .toast-icon { background: var(--k-success-bg); color: var(--k-success); }
.toast.error   .toast-icon { background: var(--k-danger-bg);  color: var(--k-danger); }
.toast.info    .toast-icon { background: var(--k-info-bg);    color: var(--k-info); }
.toast-icon .mat-icon { font-size: 17px; font-variation-settings: 'FILL' 1; }

.toast-msg {
    font-size: 0.84rem;
    font-weight: 500;
    color: var(--k-on-surface);
    line-height: 1.4;
    flex: 1;
}

/* ===================================================================
   SPINNER
=================================================================== */
.spinner {
    width: 16px;
    height: 16px;
    border: 2px solid rgba(163,56,0,0.18);
    border-top-color: var(--k-primary);
    border-radius: 50%;
    animation: spin 0.65s linear infinite;
    flex-shrink: 0;
}
.btn-primary .spinner {
    border-color: rgba(255,255,255,0.28);
    border-top-color: #ffffff;
}
.btn-danger .spinner {
    border-color: rgba(220,38,38,0.20);
    border-top-color: var(--k-danger);
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ===================================================================
   SKELETON
=================================================================== */
.skeleton {
    background: linear-gradient(90deg, var(--k-surface-variant) 25%, rgba(0,0,0,0.03) 50%, var(--k-surface-variant) 75%);
    background-size: 800px 100%;
    animation: shimmer 1.5s infinite linear;
    border-radius: var(--k-radius-sm);
}
@keyframes shimmer { 0% { background-position: -400px 0; } 100% { background-position: 400px 0; } }
.skeleton-card { height: 280px; border-radius: var(--k-radius-lg); }
</style>
@endpush

@section('content')
<div class="admin" id="app">

    {{-- ================================================================
         HEADER
    ================================================================ --}}
    <header class="admin-topbar">
        <div class="admin-brand">
            <div class="header-brand-name">The Culinary <span>Concierge</span></div>
            <div class="header-brand-sep"></div>
            <div class="header-page-title">Menu Management</div>
        </div>

        <div></div>

        <div class="admin-topbar-right">
            <button class="btn-clear-cache" id="btnClearCache" title="Bust the 5-minute menu cache for the kiosk">
                <span class="mat-icon">sync</span>
                Clear Cache
            </button>
        </div>
    </header>

    {{-- ================================================================
         BODY
    ================================================================ --}}
    <div style="grid-column:1;grid-row:2;display:contents;">

        {{-- SIDEBAR --}}
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-header-top">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span class="sidebar-section-label">Categories</span>
                        <span class="sidebar-count" id="categoryCount">0</span>
                    </div>
                </div>
            </div>

            <div class="sidebar-list" id="categoryList">
                {{-- Skeleton --}}
                <div style="padding:10px 16px 10px 10px;display:flex;flex-direction:column;gap:6px;" id="categorySkeleton">
                    <div class="skeleton" style="height:52px;border-radius:0 var(--k-radius-full) var(--k-radius-full) 0;margin-right:12px;"></div>
                    <div class="skeleton" style="height:52px;border-radius:0 var(--k-radius-full) var(--k-radius-full) 0;margin-right:12px;opacity:.7;"></div>
                    <div class="skeleton" style="height:52px;border-radius:0 var(--k-radius-full) var(--k-radius-full) 0;margin-right:12px;opacity:.4;"></div>
                </div>
            </div>

            <div class="sidebar-footer">
                <button class="btn-add-category" id="btnAddCategory">
                    <span class="mat-icon">add_circle</span>
                    Add Category
                </button>
            </div>
        </aside>

        {{-- MAIN --}}
        <main class="main-content" id="mainContent">

            {{-- Placeholder when no category is selected --}}
            <div class="no-category-selected" id="noCategoryMsg">
                <div class="empty-state-icon-wrap" style="width:88px;height:88px;">
                    <span class="mat-icon" style="font-size:44px;color:var(--k-on-surface-faint);font-variation-settings:'FILL' 1;">grid_view</span>
                </div>
                <div class="empty-state-title">Select a category</div>
                <div class="empty-state-text">Choose a category from the sidebar to view and manage its products.</div>
            </div>

            {{-- Content when a category is selected --}}
            <div id="categoryContent" style="display:none;flex-direction:column;flex:1;overflow:hidden;">
                <div class="content-header">
                    <div class="content-header-left">
                        <div class="content-category-name" id="activeCategoryName">—</div>
                        <div class="content-category-desc" id="activeCategoryDesc"></div>
                    </div>
                    <div class="content-header-right">
                        <div class="search-wrap">
                            <span class="mat-icon">search</span>
                            <input type="text" class="search-input" id="productSearch" placeholder="Search products…">
                        </div>
                        <button class="btn-add-product" id="btnAddProduct">
                            <span class="mat-icon">add</span>
                            Add Product
                        </button>
                    </div>
                </div>

                <div class="products-grid-wrap">
                    <div class="products-grid" id="productsGrid">
                        {{-- JS rendered --}}
                    </div>
                    <div id="productsEmpty" class="empty-state" style="display:none;">
                        <div class="empty-state-icon-wrap">
                            <span class="mat-icon">lunch_dining</span>
                        </div>
                        <div class="empty-state-title">No products yet</div>
                        <div class="empty-state-text">Add the first product to this category using the button above.</div>
                    </div>
                    <div id="productsSearchEmpty" class="empty-state" style="display:none;">
                        <div class="empty-state-icon-wrap">
                            <span class="mat-icon">search_off</span>
                        </div>
                        <div class="empty-state-title">No results</div>
                        <div class="empty-state-text" id="searchEmptyText"></div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    {{-- ================================================================
         TOAST CONTAINER
    ================================================================ --}}
    <div class="toast-container" id="toastContainer"></div>

    {{-- ================================================================
         MODAL — Category Form
    ================================================================ --}}
    <div class="modal-backdrop" id="modalCategory">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalCategoryTitle">
            <div class="modal-header">
                <div class="modal-title" id="modalCategoryTitle">Add Category</div>
                <button class="modal-close" data-close="modalCategory">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Name <span>*</span></label>
                    <input type="text" class="form-control" id="catName" placeholder="e.g. Burgers" maxlength="120">
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" id="catDesc" placeholder="Short description shown in the kiosk…" maxlength="500"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Display Order</label>
                        <input type="number" class="form-control" id="catOrder" placeholder="0" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <div class="toggle-wrap" style="margin-top:8px;">
                            <label class="toggle">
                                <input type="checkbox" id="catActive" checked>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label-text">Active</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-ghost" data-close="modalCategory">Cancel</button>
                <button class="btn btn-primary" id="btnSaveCategory">
                    <span class="mat-icon" style="font-size:17px;">check</span>
                    Save Category
                </button>
            </div>
        </div>
    </div>

    {{-- ================================================================
         MODAL — Product Form
    ================================================================ --}}
    <div class="modal-backdrop" id="modalProduct">
        <div class="modal" style="max-width:560px;" role="dialog" aria-modal="true" aria-labelledby="modalProductTitle">
            <div class="modal-header">
                <div class="modal-title" id="modalProductTitle">Add Product</div>
                <button class="modal-close" data-close="modalProduct">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Name <span>*</span></label>
                    <input type="text" class="form-control" id="prodName" placeholder="e.g. Classic Cheeseburger" maxlength="160">
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" id="prodDesc" placeholder="Ingredients, highlights…" maxlength="1000"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Price <span>*</span></label>
                        <input type="number" class="form-control" id="prodPrice" placeholder="9.99" min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category <span>*</span></label>
                        <select class="form-control" id="prodCategory"></select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Prep Time (min)</label>
                        <input type="number" class="form-control" id="prodPrepTime" placeholder="10" min="0" max="999">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Display Order</label>
                        <input type="number" class="form-control" id="prodDisplayOrder" placeholder="0" min="0" max="999" value="0">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Card Size (Kiosk)</label>
                        <select class="form-control" id="prodCardSize">
                            <option value="standard">Standard</option>
                            <option value="wide">Wide (2 columns)</option>
                            <option value="tall">Tall (large image)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Display Options</label>
                        <div style="display:flex;flex-direction:column;gap:10px;margin-top:8px;">
                            <div class="toggle-wrap">
                                <label class="toggle">
                                    <input type="checkbox" id="prodAvailable" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="toggle-label-text">Available</span>
                            </div>
                            <div class="toggle-wrap">
                                <label class="toggle">
                                    <input type="checkbox" id="prodFeatured">
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="toggle-label-text">Featured</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-ghost" data-close="modalProduct">Cancel</button>
                <button class="btn btn-primary" id="btnSaveProduct">
                    <span class="mat-icon" style="font-size:17px;">check</span>
                    Save Product
                </button>
            </div>
        </div>
    </div>

    {{-- ================================================================
         MODAL — Customization Form
    ================================================================ --}}
    <div class="modal-backdrop" id="modalCustomization">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalCustomizationTitle">
            <div class="modal-header">
                <div class="modal-title" id="modalCustomizationTitle">Add Customization</div>
                <button class="modal-close" data-close="modalCustomization">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Name <span>*</span></label>
                    <input type="text" class="form-control" id="customName" placeholder="e.g. Extra Cheese" maxlength="120">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Type <span>*</span></label>
                        <input type="text" class="form-control" id="customType" placeholder="e.g. Add-on, Size, Sauce" maxlength="80" list="customTypeList">
                        <datalist id="customTypeList">
                            <option value="Add-on">
                            <option value="Size">
                            <option value="Sauce">
                            <option value="Topping">
                            <option value="Side">
                            <option value="Drink">
                            <option value="Cooking">
                        </datalist>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Price Modifier ($)</label>
                        <input type="number" class="form-control" id="customPrice" placeholder="0.00" step="0.01">
                    </div>
                </div>
                <div class="form-group">
                    <div class="toggle-wrap">
                        <label class="toggle">
                            <input type="checkbox" id="customAvailable" checked>
                            <span class="toggle-slider"></span>
                        </label>
                        <span class="toggle-label-text">Available</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-ghost" data-close="modalCustomization">Cancel</button>
                <button class="btn btn-primary" id="btnSaveCustomization">
                    <span class="mat-icon" style="font-size:17px;">check</span>
                    Save
                </button>
            </div>
        </div>
    </div>

    {{-- ================================================================
         MODAL — Delete Confirmation
    ================================================================ --}}
    <div class="modal-backdrop" id="modalDelete">
        <div class="modal" style="max-width:440px;" role="dialog" aria-modal="true">
            <div class="modal-header">
                <div class="modal-title">Confirm Deletion</div>
                <button class="modal-close" data-close="modalDelete">&times;</button>
            </div>
            <div class="modal-body">
                <div class="delete-warning">
                    <div class="delete-warning-icon">
                        <span class="mat-icon">warning</span>
                    </div>
                    <div class="delete-warning-text">
                        <div class="delete-warning-subject" id="deleteSubject">this item</div>
                        <div id="deleteDescription" style="margin-top:4px;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-ghost" data-close="modalDelete">Cancel</button>
                <button class="btn btn-danger" id="btnConfirmDelete">
                    <span class="mat-icon" style="font-size:17px;">delete</span>
                    Delete
                </button>
            </div>
        </div>
    </div>

</div><!-- /#app -->
@endsection

@push('scripts')
<script>
/* =====================================================================
   STATE
===================================================================== */
const state = {
    categories: [],       // [{id, name, description, display_order, is_active, products_count}]
    products: [],         // [{id, name, description, price, category_id, is_available, preparation_time_minutes, image_url, customizations:[]}]
    activeCategoryId: null,

    // Modal context
    editingCategoryId: null,
    editingProductId: null,
    editingCustomizationId: null,
    customizationProductId: null, // product we're adding a customization to

    // Delete context
    deleteCallback: null,
};

/* =====================================================================
   HELPERS — apiFetch is provided by layout, but we extend it here
   to handle non-JSON responses and strip the CSRF token for writes
===================================================================== */
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

async function api(endpoint, options = {}) {
    const isFormData = options.body instanceof FormData;
    const headers = {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': CSRF,
        ...(isFormData ? {} : { 'Content-Type': 'application/json' }),
        ...(options.headers ?? {}),
    };
    const res = await fetch(`/api${endpoint}`, { ...options, headers });
    const text = await res.text();
    let data;
    try { data = JSON.parse(text); } catch { data = { message: text }; }
    if (!res.ok) {
        const msg = data?.message ?? `HTTP ${res.status}`;
        const err = new Error(msg);
        err.errors = data?.errors ?? null;
        err.status = res.status;
        throw err;
    }
    return data;
}

/* =====================================================================
   TOAST
===================================================================== */
function toast(msg, type = 'success', duration = 3500) {
    const container = document.getElementById('toastContainer');
    const icons = {
        success: 'check_circle',
        error:   'error',
        info:    'info',
    };
    const el = document.createElement('div');
    el.className = `toast ${type}`;
    el.innerHTML = `<div class="toast-icon"><span class="mat-icon">${icons[type] ?? icons.info}</span></div><div class="toast-msg">${msg}</div>`;
    container.appendChild(el);
    setTimeout(() => {
        el.classList.add('removing');
        el.addEventListener('animationend', () => el.remove(), { once: true });
    }, duration);
}

/* =====================================================================
   MODAL HELPERS
===================================================================== */
function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

document.querySelectorAll('[data-close]').forEach(btn =>
    btn.addEventListener('click', () => closeModal(btn.dataset.close))
);
document.querySelectorAll('.modal-backdrop').forEach(backdrop =>
    backdrop.addEventListener('click', e => { if (e.target === backdrop) closeModal(backdrop.id); })
);
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.querySelectorAll('.modal-backdrop.open').forEach(m => closeModal(m.id));
});

/* =====================================================================
   RENDERING — CATEGORY LIST
===================================================================== */
function renderCategories() {
    const list = document.getElementById('categoryList');
    document.getElementById('categorySkeleton')?.remove();
    document.getElementById('categoryCount').textContent = state.categories.length;

    // Remove existing rendered items (not skeleton)
    list.querySelectorAll('.cat-item').forEach(n => n.remove());

    if (!state.categories.length) {
        const empty = document.createElement('div');
        empty.className = 'empty-state';
        empty.style.padding = '40px 16px';
        empty.innerHTML = `<div class="empty-state-title" style="font-size:.9rem;">No categories</div><div class="empty-state-text">Add your first category.</div>`;
        list.appendChild(empty);
        return;
    }

    list.querySelectorAll('.empty-state').forEach(n => n.remove());

    state.categories.forEach((cat, idx) => {
        const el = document.createElement('div');
        el.className = 'cat-item' + (cat.id === state.activeCategoryId ? ' active' : '');
        el.dataset.id = cat.id;
        el.draggable = true;
        el.innerHTML = `
            <span class="mat-icon" style="font-size:20px;flex-shrink:0;">category</span>
            <div class="cat-info">
                <div class="cat-name">${esc(cat.name)}</div>
                <div class="cat-meta">
                    <span class="cat-badge ${cat.is_active ? 'badge-active' : 'badge-inactive'}">${cat.is_active ? 'Active' : 'Inactive'}</span>
                </div>
            </div>
            <span class="cat-count">${cat.products_count ?? 0}</span>
            <div class="cat-actions">
                <div class="order-btns">
                    <button class="icon-btn order-btn" data-action="order-up" data-idx="${idx}" title="Move up"><span class="mat-icon" style="font-size:13px;">expand_less</span></button>
                    <button class="icon-btn order-btn" data-action="order-down" data-idx="${idx}" title="Move down"><span class="mat-icon" style="font-size:13px;">expand_more</span></button>
                </div>
                <button class="icon-btn" data-action="edit-cat" data-id="${cat.id}" title="Edit">
                    <span class="mat-icon" style="font-size:15px;">edit</span>
                </button>
                <button class="icon-btn del" data-action="delete-cat" data-id="${cat.id}" data-name="${esc(cat.name)}" title="Delete">
                    <span class="mat-icon" style="font-size:15px;">delete</span>
                </button>
            </div>`;

        el.addEventListener('click', e => {
            if (e.target.closest('[data-action]')) return;
            selectCategory(cat.id);
        });

        // Drag-and-drop reordering
        el.addEventListener('dragstart', e => {
            e.dataTransfer.setData('text/plain', idx);
            e.dataTransfer.effectAllowed = 'move';
        });
        el.addEventListener('dragover', e => {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            list.querySelectorAll('.cat-item').forEach(i => i.classList.remove('drag-over'));
            el.classList.add('drag-over');
        });
        el.addEventListener('dragleave', () => el.classList.remove('drag-over'));
        el.addEventListener('drop', e => {
            e.preventDefault();
            el.classList.remove('drag-over');
            const fromIdx = parseInt(e.dataTransfer.getData('text/plain'));
            const toIdx   = idx;
            if (fromIdx === toIdx) return;
            const moved = state.categories.splice(fromIdx, 1)[0];
            state.categories.splice(toIdx, 0, moved);
            reorderCategories();
        });

        list.appendChild(el);
    });

    // Category action buttons
    list.querySelectorAll('[data-action]').forEach(btn => {
        btn.addEventListener('click', e => {
            e.stopPropagation();
            const action = btn.dataset.action;
            if (action === 'edit-cat')   openEditCategory(parseInt(btn.dataset.id));
            if (action === 'delete-cat') confirmDeleteCategory(parseInt(btn.dataset.id), btn.dataset.name);
            if (action === 'order-up')   moveCategory(parseInt(btn.dataset.idx), -1);
            if (action === 'order-down') moveCategory(parseInt(btn.dataset.idx), +1);
        });
    });
}

function moveCategory(idx, dir) {
    const newIdx = idx + dir;
    if (newIdx < 0 || newIdx >= state.categories.length) return;
    const moved = state.categories.splice(idx, 1)[0];
    state.categories.splice(newIdx, 0, moved);
    reorderCategories();
}

async function reorderCategories() {
    // Assign sequential display_order values then persist each change
    state.categories.forEach((cat, i) => { cat.display_order = i; });
    renderCategories();
    // Fire-and-forget updates (best-effort)
    for (const cat of state.categories) {
        try {
            await api(`/admin/categories/${cat.id}`, {
                method: 'PUT',
                body: JSON.stringify({ display_order: cat.display_order }),
            });
        } catch {}
    }
    toast('Category order saved.', 'success', 2000);
}

/* =====================================================================
   RENDERING — PRODUCTS GRID
===================================================================== */
function getActiveCategory() {
    return state.categories.find(c => c.id === state.activeCategoryId) ?? null;
}

function selectCategory(id) {
    state.activeCategoryId = id;
    renderCategories();

    const cat = getActiveCategory();
    if (!cat) return;

    document.getElementById('noCategoryMsg').style.display     = 'none';
    const cc = document.getElementById('categoryContent');
    cc.style.display = 'flex';

    document.getElementById('activeCategoryName').textContent = cat.name;
    document.getElementById('activeCategoryDesc').textContent = cat.description ?? '';
    document.getElementById('productSearch').value = '';

    renderProducts();
}

function getFilteredProducts() {
    const q = (document.getElementById('productSearch')?.value ?? '').toLowerCase().trim();
    const products = state.products.filter(p => p.category_id === state.activeCategoryId);
    if (!q) return products;
    return products.filter(p =>
        p.name.toLowerCase().includes(q) ||
        (p.description ?? '').toLowerCase().includes(q)
    );
}

function renderProducts() {
    const grid    = document.getElementById('productsGrid');
    const empty   = document.getElementById('productsEmpty');
    const sEmpty  = document.getElementById('productsSearchEmpty');
    const q       = (document.getElementById('productSearch')?.value ?? '').trim();
    const all     = state.products.filter(p => p.category_id === state.activeCategoryId);
    const visible = getFilteredProducts();

    grid.innerHTML = '';
    empty.style.display  = 'none';
    sEmpty.style.display = 'none';

    if (!all.length) {
        empty.style.display = 'flex';
        return;
    }
    if (!visible.length) {
        document.getElementById('searchEmptyText').textContent = `No products matching "${q}".`;
        sEmpty.style.display = 'flex';
        return;
    }

    visible.forEach(product => {
        grid.appendChild(buildProductCard(product));
    });
}

function buildProductCard(product) {
    const card = document.createElement('div');
    card.className = 'product-card' + (product.is_available ? '' : ' unavailable');
    card.dataset.id = product.id;

    const hasImage = !!product.image_url;
    const imgSrc   = hasImage ? product.image_url : '';

    const customizationsHtml = buildCustomizationsHtml(product);

    card.innerHTML = `
        <div class="product-img-wrap" id="imgWrap-${product.id}">
            ${hasImage
                ? `<img class="product-img" src="${esc(imgSrc)}" alt="${esc(product.name)}" loading="lazy">`
                : `<div class="product-img-placeholder">
                       <span class="mat-icon">image</span>
                       <span>No image</span>
                   </div>`
            }
            <div class="product-img-overlay">
                <button class="img-overlay-btn" data-action="upload-img" data-id="${product.id}">
                    <span class="mat-icon" style="font-size:14px;">upload</span>
                    ${hasImage ? 'Replace' : 'Upload'}
                </button>
                ${hasImage ? `<button class="img-overlay-btn del-img" data-action="delete-img" data-id="${product.id}">
                    <span class="mat-icon" style="font-size:14px;">delete</span>
                    Remove
                </button>` : ''}
            </div>
            <input type="file" class="product-img-input" id="fileInput-${product.id}" accept="image/jpeg,image/png,image/webp,image/gif">
            <div class="product-pills">
                <button class="avail-pill ${product.is_available ? 'on' : 'off'}" data-action="toggle-avail" data-id="${product.id}" title="Click to toggle availability">
                    <span class="avail-dot"></span>
                    ${product.is_available ? 'Available' : 'Unavailable'}
                </button>
                ${product.is_featured ? `<span class="featured-pill" title="Featured in kiosk">&#9733; Featured</span>` : ''}
                ${product.card_size && product.card_size !== 'standard' ? `<span class="size-pill" title="Card size: ${product.card_size}">${product.card_size}</span>` : ''}
            </div>
        </div>
        <div class="product-body">
            <div class="product-name">${esc(product.name)}</div>
            ${product.description ? `<div class="product-desc">${esc(product.description)}</div>` : ''}
            <div class="product-meta">
                <div class="product-price">$${parseFloat(product.price).toFixed(2)}</div>
                <div class="product-prep">
                    <span class="mat-icon">schedule</span>
                    ${product.preparation_time_minutes ?? 10} min
                </div>
            </div>
            <div class="customizations-section">
                <div class="customizations-header">
                    <span class="customizations-label">Customizations</span>
                    <button class="btn-add-custom" data-action="add-custom" data-id="${product.id}">
                        <span class="mat-icon">add</span>
                        Add
                    </button>
                </div>
                <div class="customizations-list" id="customList-${product.id}">
                    ${customizationsHtml}
                </div>
            </div>
        </div>
        <div class="product-footer">
            <button class="btn-card-edit" data-action="edit-prod" data-id="${product.id}">
                <span class="mat-icon" style="font-size:15px;">edit</span>
                Edit
            </button>
            <button class="btn-card-delete" data-action="delete-prod" data-id="${product.id}" data-name="${esc(product.name)}">
                <span class="mat-icon" style="font-size:15px;">delete</span>
                Delete
            </button>
        </div>`;

    // Wire up events on this card
    card.querySelectorAll('[data-action]').forEach(btn => {
        btn.addEventListener('click', async e => {
            e.stopPropagation();
            const action = btn.dataset.action;
            const id     = parseInt(btn.dataset.id);

            if (action === 'edit-prod')    openEditProduct(id);
            if (action === 'delete-prod')  confirmDeleteProduct(id, btn.dataset.name);
            if (action === 'add-custom')   openAddCustomization(id);
            if (action === 'edit-custom')  openEditCustomization(parseInt(btn.dataset.cid), id);
            if (action === 'delete-custom') confirmDeleteCustomization(parseInt(btn.dataset.cid), btn.dataset.name, id);
            if (action === 'upload-img')   card.querySelector(`#fileInput-${id}`).click();
            if (action === 'toggle-avail') await toggleProductAvailability(id, btn);
            if (action === 'delete-img')   confirmDeleteProductImage(id, btn.dataset.name);
        });
    });

    // File input
    const fileInput = card.querySelector(`#fileInput-${product.id}`);
    fileInput.addEventListener('change', () => {
        if (fileInput.files[0]) uploadProductImage(product.id, fileInput.files[0]);
    });

    return card;
}

function buildCustomizationsHtml(product) {
    if (!product.customizations?.length) {
        return `<div class="no-customizations">None yet</div>`;
    }
    return product.customizations.map(c => `
        <div class="custom-chip">
            <div class="custom-chip-left">
                <span class="custom-type-tag">${esc(c.type)}</span>
                <span class="custom-name">${esc(c.name)}</span>
            </div>
            <div style="display:flex;align-items:center;gap:6px;">
                <span class="custom-price">${parseFloat(c.price_modifier ?? 0) >= 0 ? '+' : ''}$${parseFloat(c.price_modifier ?? 0).toFixed(2)}</span>
                <div class="custom-chip-actions">
                    <button class="chip-btn" data-action="edit-custom" data-id="${product.id}" data-cid="${c.id}" title="Edit">
                        <span class="mat-icon">edit</span>
                    </button>
                    <button class="chip-btn del" data-action="delete-custom" data-id="${product.id}" data-cid="${c.id}" data-name="${esc(c.name)}" title="Delete">
                        <span class="mat-icon">close</span>
                    </button>
                </div>
            </div>
        </div>`).join('');
}

function refreshProductCard(product) {
    const existing = document.querySelector(`.product-card[data-id="${product.id}"]`);
    if (!existing) return;
    const newCard = buildProductCard(product);
    existing.replaceWith(newCard);
}

/* =====================================================================
   DATA LOADING
===================================================================== */
async function loadAll() {
    try {
        const [catRes, prodRes] = await Promise.all([
            api('/admin/categories'),
            api('/admin/products'),
        ]);
        state.categories = catRes.data ?? [];
        state.products   = (prodRes.data ?? []).map(bustImageCache);
        renderCategories();

        // Re-select active category if still present
        if (state.activeCategoryId && state.categories.find(c => c.id === state.activeCategoryId)) {
            selectCategory(state.activeCategoryId);
        } else if (state.categories.length) {
            // Don't auto-select, let user choose (no flashing)
        }
    } catch (err) {
        toast('Failed to load menu data: ' + err.message, 'error');
    }
}

// Append cache-buster to image URLs so the browser doesn't serve stale images
function bustImageCache(product) {
    if (product.image_url && !product.image_url.includes('?t=')) {
        product.image_url = product.image_url + '?t=' + Date.now();
    }
    return product;
}

async function reloadProducts() {
    try {
        const res = await api('/admin/products');
        state.products = (res.data ?? []).map(bustImageCache);
        if (state.activeCategoryId) renderProducts();
        // Update product counts on categories
        state.categories.forEach(cat => {
            cat.products_count = state.products.filter(p => p.category_id === cat.id).length;
        });
        renderCategories();
    } catch {}
}

/* =====================================================================
   CATEGORY CRUD
===================================================================== */
document.getElementById('btnAddCategory').addEventListener('click', () => {
    state.editingCategoryId = null;
    document.getElementById('modalCategoryTitle').textContent = 'Add Category';
    document.getElementById('catName').value    = '';
    document.getElementById('catDesc').value    = '';
    document.getElementById('catOrder').value   = state.categories.length;
    document.getElementById('catActive').checked = true;
    openModal('modalCategory');
    setTimeout(() => document.getElementById('catName').focus(), 100);
});

function openEditCategory(id) {
    const cat = state.categories.find(c => c.id === id);
    if (!cat) return;
    state.editingCategoryId = id;
    document.getElementById('modalCategoryTitle').textContent = 'Edit Category';
    document.getElementById('catName').value    = cat.name;
    document.getElementById('catDesc').value    = cat.description ?? '';
    document.getElementById('catOrder').value   = cat.display_order ?? 0;
    document.getElementById('catActive').checked = cat.is_active;
    openModal('modalCategory');
    setTimeout(() => document.getElementById('catName').focus(), 100);
}

document.getElementById('btnSaveCategory').addEventListener('click', async () => {
    const name   = document.getElementById('catName').value.trim();
    const desc   = document.getElementById('catDesc').value.trim();
    const order  = parseInt(document.getElementById('catOrder').value) || 0;
    const active = document.getElementById('catActive').checked;

    if (!name) { toast('Category name is required.', 'error'); return; }

    const btn = document.getElementById('btnSaveCategory');
    btn.disabled = true;
    btn.innerHTML = '<div class="spinner"></div> Saving…';

    try {
        const payload = { name, description: desc || null, display_order: order, is_active: active };
        let res;
        if (state.editingCategoryId) {
            res = await api(`/admin/categories/${state.editingCategoryId}`, { method: 'PUT', body: JSON.stringify(payload) });
            const idx = state.categories.findIndex(c => c.id === state.editingCategoryId);
            if (idx !== -1) {
                state.categories[idx] = { ...state.categories[idx], ...res.data };
            }
            toast('Category updated.', 'success');
        } else {
            res = await api('/admin/categories', { method: 'POST', body: JSON.stringify(payload) });
            state.categories.push(res.data);
            toast('Category created.', 'success');
        }
        renderCategories();
        closeModal('modalCategory');
    } catch (err) {
        toast(err.message, 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<span class="mat-icon" style="font-size:17px;">check</span> Save Category';
    }
});

function confirmDeleteCategory(id, name) {
    const cat = state.categories.find(c => c.id === id);
    const productCount = cat?.products_count ?? 0;
    document.getElementById('deleteSubject').textContent = `"${name}"`;
    document.getElementById('deleteDescription').textContent =
        productCount > 0
            ? `This will permanently delete the category and all ${productCount} product(s) within it. This cannot be undone.`
            : 'This will permanently delete the category. This cannot be undone.';
    state.deleteCallback = async () => {
        await api(`/admin/categories/${id}`, { method: 'DELETE' });
        state.categories = state.categories.filter(c => c.id !== id);
        state.products   = state.products.filter(p => p.category_id !== id);
        if (state.activeCategoryId === id) {
            state.activeCategoryId = null;
            document.getElementById('noCategoryMsg').style.display     = '';
            document.getElementById('categoryContent').style.display   = 'none';
        }
        renderCategories();
        toast('Category deleted.', 'success');
    };
    openModal('modalDelete');
}

/* =====================================================================
   PRODUCT CRUD
===================================================================== */
function populateCategorySelect(selectedId) {
    const sel = document.getElementById('prodCategory');
    sel.innerHTML = state.categories.map(c =>
        `<option value="${c.id}" ${c.id === selectedId ? 'selected' : ''}>${esc(c.name)}</option>`
    ).join('');
}

document.getElementById('btnAddProduct').addEventListener('click', () => {
    state.editingProductId = null;
    document.getElementById('modalProductTitle').textContent = 'Add Product';
    document.getElementById('prodName').value    = '';
    document.getElementById('prodDesc').value    = '';
    document.getElementById('prodPrice').value   = '';
    document.getElementById('prodPrepTime').value = '10';
    document.getElementById('prodDisplayOrder').value = '0';
    document.getElementById('prodCardSize').value = 'standard';
    document.getElementById('prodAvailable').checked = true;
    document.getElementById('prodFeatured').checked = false;
    populateCategorySelect(state.activeCategoryId);
    openModal('modalProduct');
    setTimeout(() => document.getElementById('prodName').focus(), 100);
});

function openEditProduct(id) {
    const prod = state.products.find(p => p.id === id);
    if (!prod) return;
    state.editingProductId = id;
    document.getElementById('modalProductTitle').textContent = 'Edit Product';
    document.getElementById('prodName').value     = prod.name;
    document.getElementById('prodDesc').value     = prod.description ?? '';
    document.getElementById('prodPrice').value    = parseFloat(prod.price).toFixed(2);
    document.getElementById('prodPrepTime').value = prod.preparation_time_minutes ?? 10;
    document.getElementById('prodDisplayOrder').value = prod.display_order ?? 0;
    document.getElementById('prodCardSize').value = prod.card_size ?? 'standard';
    document.getElementById('prodAvailable').checked = prod.is_available;
    document.getElementById('prodFeatured').checked = !!prod.is_featured;
    populateCategorySelect(prod.category_id);
    openModal('modalProduct');
    setTimeout(() => document.getElementById('prodName').focus(), 100);
}

document.getElementById('btnSaveProduct').addEventListener('click', async () => {
    const name     = document.getElementById('prodName').value.trim();
    const desc     = document.getElementById('prodDesc').value.trim();
    const price    = parseFloat(document.getElementById('prodPrice').value);
    const catId    = parseInt(document.getElementById('prodCategory').value);
    const prepTime     = parseInt(document.getElementById('prodPrepTime').value) || 10;
    const displayOrder = parseInt(document.getElementById('prodDisplayOrder').value) || 0;
    const cardSize     = document.getElementById('prodCardSize').value;
    const avail        = document.getElementById('prodAvailable').checked;
    const featured     = document.getElementById('prodFeatured').checked;

    if (!name)     { toast('Product name is required.', 'error'); return; }
    if (isNaN(price) || price < 0) { toast('A valid price is required.', 'error'); return; }
    if (!catId)    { toast('Please select a category.', 'error'); return; }

    const btn = document.getElementById('btnSaveProduct');
    btn.disabled = true;
    btn.innerHTML = '<div class="spinner"></div> Saving…';

    try {
        const payload = {
            name,
            description: desc || null,
            price,
            category_id: catId,
            preparation_time_minutes: prepTime,
            display_order: displayOrder,
            card_size: cardSize,
            is_available: avail,
            is_featured: featured,
        };
        let res;
        if (state.editingProductId) {
            res = await api(`/admin/products/${state.editingProductId}`, { method: 'PUT', body: JSON.stringify(payload) });
            const idx = state.products.findIndex(p => p.id === state.editingProductId);
            if (idx !== -1) state.products[idx] = res.data;
            toast('Product updated.', 'success');
        } else {
            res = await api('/admin/products', { method: 'POST', body: JSON.stringify(payload) });
            state.products.push(res.data);
            toast('Product created.', 'success');
        }
        // Update product count on categories
        state.categories.forEach(cat => {
            cat.products_count = state.products.filter(p => p.category_id === cat.id).length;
        });
        renderCategories();
        renderProducts();
        closeModal('modalProduct');
    } catch (err) {
        toast(err.message, 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<span class="mat-icon" style="font-size:17px;">check</span> Save Product';
    }
});

function confirmDeleteProduct(id, name) {
    document.getElementById('deleteSubject').textContent = `"${name}"`;
    document.getElementById('deleteDescription').textContent = 'This will permanently delete the product and all its customizations. This cannot be undone.';
    state.deleteCallback = async () => {
        await api(`/admin/products/${id}`, { method: 'DELETE' });
        state.products = state.products.filter(p => p.id !== id);
        state.categories.forEach(cat => {
            cat.products_count = state.products.filter(p => p.category_id === cat.id).length;
        });
        renderCategories();
        renderProducts();
        toast('Product deleted.', 'success');
    };
    openModal('modalDelete');
}

/* =====================================================================
   AVAILABILITY TOGGLE (inline)
===================================================================== */
async function toggleProductAvailability(id, pillBtn) {
    const product = state.products.find(p => p.id === id);
    if (!product) return;
    const newValue = !product.is_available;
    try {
        const res = await api(`/admin/products/${id}`, {
            method: 'PUT',
            body: JSON.stringify({ is_available: newValue }),
        });
        const idx = state.products.findIndex(p => p.id === id);
        if (idx !== -1) state.products[idx] = res.data;
        refreshProductCard(res.data);
        toast(`${res.data.name} marked as ${newValue ? 'available' : 'unavailable'}.`, 'success', 2500);
    } catch (err) {
        toast('Failed to update availability: ' + err.message, 'error');
    }
}

/* =====================================================================
   PRODUCT IMAGES
===================================================================== */
async function uploadProductImage(productId, file) {
    if (file.size > 5 * 1024 * 1024) {
        toast('Image must be 5 MB or smaller.', 'error'); return;
    }
    const allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    if (!allowed.includes(file.type)) {
        toast('Only JPEG, PNG, WebP, and GIF are allowed.', 'error'); return;
    }

    // Show uploading indicator on the card's image overlay
    const imgWrap = document.getElementById(`imgWrap-${productId}`);
    if (imgWrap) {
        const overlay = imgWrap.querySelector('.product-img-overlay');
        if (overlay) overlay.innerHTML = `<div class="spinner" style="border-top-color:white;width:28px;height:28px;border-width:3px;"></div>`;
    }

    const formData = new FormData();
    formData.append('image', file);

    try {
        const res = await api(`/admin/products/${productId}/image`, { method: 'POST', body: formData });
        // Bust browser cache for this product's image by appending a timestamp
        const updatedUrl = res.image_url + '?t=' + Date.now();

        const idx = state.products.findIndex(p => p.id === productId);
        if (idx !== -1) state.products[idx].image_url = updatedUrl;

        refreshProductCard(state.products[idx]);
        toast('Image uploaded.', 'success');
    } catch (err) {
        toast('Upload failed: ' + err.message, 'error');
        // Restore card
        const product = state.products.find(p => p.id === productId);
        if (product) refreshProductCard(product);
    }
}

function confirmDeleteProductImage(productId) {
    document.getElementById('deleteSubject').textContent = 'the product image';
    document.getElementById('deleteDescription').textContent = 'The image will be removed from this product.';
    state.deleteCallback = async () => {
        await api(`/admin/products/${productId}/image`, { method: 'DELETE' });
        const idx = state.products.findIndex(p => p.id === productId);
        if (idx !== -1) {
            state.products[idx].image_url = null;
            refreshProductCard(state.products[idx]);
        }
        toast('Image deleted.', 'success');
    };
    openModal('modalDelete');
}

/* =====================================================================
   CUSTOMIZATIONS
===================================================================== */
function openAddCustomization(productId) {
    state.editingCustomizationId = null;
    state.customizationProductId = productId;
    document.getElementById('modalCustomizationTitle').textContent = 'Add Customization';
    document.getElementById('customName').value  = '';
    document.getElementById('customType').value  = '';
    document.getElementById('customPrice').value = '0.00';
    document.getElementById('customAvailable').checked = true;
    openModal('modalCustomization');
    setTimeout(() => document.getElementById('customName').focus(), 100);
}

function openEditCustomization(customizationId, productId) {
    const product = state.products.find(p => p.id === productId);
    const custom  = product?.customizations?.find(c => c.id === customizationId);
    if (!custom) return;

    state.editingCustomizationId = customizationId;
    state.customizationProductId = productId;
    document.getElementById('modalCustomizationTitle').textContent = 'Edit Customization';
    document.getElementById('customName').value  = custom.name;
    document.getElementById('customType').value  = custom.type;
    document.getElementById('customPrice').value = parseFloat(custom.price_modifier ?? 0).toFixed(2);
    document.getElementById('customAvailable').checked = custom.is_available;
    openModal('modalCustomization');
    setTimeout(() => document.getElementById('customName').focus(), 100);
}

document.getElementById('btnSaveCustomization').addEventListener('click', async () => {
    const name      = document.getElementById('customName').value.trim();
    const type      = document.getElementById('customType').value.trim();
    const price     = parseFloat(document.getElementById('customPrice').value) || 0;
    const available = document.getElementById('customAvailable').checked;

    if (!name) { toast('Customization name is required.', 'error'); return; }
    if (!type) { toast('Customization type is required.', 'error'); return; }

    const btn = document.getElementById('btnSaveCustomization');
    btn.disabled = true;
    btn.innerHTML = '<div class="spinner"></div> Saving…';

    try {
        const payload = { name, type, price_modifier: price, is_available: available };
        let res;
        if (state.editingCustomizationId) {
            res = await api(`/admin/customizations/${state.editingCustomizationId}`, { method: 'PUT', body: JSON.stringify(payload) });
            // Update in state
            const pidx = state.products.findIndex(p => p.id === state.customizationProductId);
            if (pidx !== -1) {
                const cidx = state.products[pidx].customizations.findIndex(c => c.id === state.editingCustomizationId);
                if (cidx !== -1) state.products[pidx].customizations[cidx] = res.data;
            }
            toast('Customization updated.', 'success');
        } else {
            res = await api(`/admin/products/${state.customizationProductId}/customizations`, { method: 'POST', body: JSON.stringify(payload) });
            const pidx = state.products.findIndex(p => p.id === state.customizationProductId);
            if (pidx !== -1) state.products[pidx].customizations.push(res.data);
            toast('Customization added.', 'success');
        }
        const product = state.products.find(p => p.id === state.customizationProductId);
        if (product) refreshProductCard(product);
        closeModal('modalCustomization');
    } catch (err) {
        toast(err.message, 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<span class="mat-icon" style="font-size:17px;">check</span> Save';
    }
});

function confirmDeleteCustomization(customizationId, name, productId) {
    document.getElementById('deleteSubject').textContent = `"${name}"`;
    document.getElementById('deleteDescription').textContent = 'This customization option will be permanently removed.';
    state.deleteCallback = async () => {
        await api(`/admin/customizations/${customizationId}`, { method: 'DELETE' });
        const pidx = state.products.findIndex(p => p.id === productId);
        if (pidx !== -1) {
            state.products[pidx].customizations = state.products[pidx].customizations.filter(c => c.id !== customizationId);
            refreshProductCard(state.products[pidx]);
        }
        toast('Customization deleted.', 'success');
    };
    openModal('modalDelete');
}

/* =====================================================================
   DELETE MODAL CONFIRM
===================================================================== */
document.getElementById('btnConfirmDelete').addEventListener('click', async () => {
    if (!state.deleteCallback) return;
    const btn = document.getElementById('btnConfirmDelete');
    btn.disabled = true;
    btn.innerHTML = '<div class="spinner"></div> Deleting…';
    try {
        await state.deleteCallback();
        closeModal('modalDelete');
    } catch (err) {
        toast('Delete failed: ' + err.message, 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<span class="mat-icon" style="font-size:17px;">delete</span> Delete';
        state.deleteCallback = null;
    }
});

/* =====================================================================
   CACHE CLEAR
===================================================================== */
document.getElementById('btnClearCache').addEventListener('click', async () => {
    const btn = document.getElementById('btnClearCache');
    btn.disabled = true;
    btn.innerHTML = '<div class="spinner" style="border-width:2px;width:14px;height:14px;"></div> Clearing…';
    try {
        await api('/admin/cache/clear', { method: 'POST' });
        toast('Menu cache cleared. Kiosk will load fresh data.', 'info');
    } catch (err) {
        toast('Cache clear failed: ' + err.message, 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<span class="mat-icon">sync</span> Clear Cache';
    }
});

/* =====================================================================
   SEARCH
===================================================================== */
document.getElementById('productSearch').addEventListener('input', () => renderProducts());

/* =====================================================================
   UTILITY
===================================================================== */
function esc(str) {
    if (!str && str !== 0) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

/* =====================================================================
   BOOT
===================================================================== */
loadAll();
</script>
@endpush
