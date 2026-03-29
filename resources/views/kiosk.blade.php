@extends('layouts.app')
@section('title', 'Order Here')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
<style>
    /* ===== LIGHT THEME OVERRIDES ===== */
    :root {
        --k-primary:           #a33800;
        --k-primary-container: #ff7941;
        --k-secondary:         #954400;
        --k-secondary-container: #ffc5a5;
        --k-tertiary:          #f8a91f;
        --k-surface:           #f9f6f5;
        --k-surface-variant:   #efe0d8;
        --k-on-surface:        #2f2e2e;
        --k-on-surface-muted:  #7a6e6a;
        --k-on-surface-faint:  #c2b8b4;
        --k-border:            rgba(163,56,0,0.12);
        --k-border-strong:     rgba(163,56,0,0.22);
        --k-shadow:            rgba(47,46,46,0.10);
        --k-shadow-md:         rgba(47,46,46,0.16);
        --k-success:           #1a7f52;
        --k-success-bg:        rgba(26,127,82,0.1);
        --k-warning:           #f8a91f;
        --k-warning-bg:        rgba(248,169,31,0.12);
        --k-info:              #2563eb;
        --k-info-bg:           rgba(37,99,235,0.1);
        --k-radius-sm:         10px;
        --k-radius-md:         16px;
        --k-radius-lg:         24px;
        --k-radius-full:       9999px;
        --k-spring:            cubic-bezier(0.34, 1.56, 0.64, 1);
        --k-ease-out:          cubic-bezier(0.16, 1, 0.3, 1);
        /* Override layout dark vars */
        --bg-base:             #f9f6f5;
        --text-primary:        #2f2e2e;
        --text-secondary:      #7a6e6a;
        --text-muted:          #c2b8b4;
        --border:              rgba(163,56,0,0.12);
        --accent-primary:      #ff7941;
    }

    /* ===== RESET FOR LIGHT THEME ===== */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { font-size: 16px; -webkit-tap-highlight-color: transparent; }
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--k-surface);
        color: var(--k-on-surface);
        overflow: hidden;
        -webkit-font-smoothing: antialiased;
    }
    button { cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; }
    input, textarea { font-family: 'Plus Jakarta Sans', sans-serif; }
    .hidden { display: none !important; }
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--k-on-surface-faint); border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--k-on-surface-muted); }

    /* ===== MATERIAL ICONS HELPER ===== */
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
    .mat-icon-filled {
        font-variation-settings: 'FILL' 1;
    }

    /* ===== KIOSK SHELL ===== */
    .kiosk {
        display: grid;
        grid-template-columns: var(--sidebar-width, 320px) 6px 1fr;
        grid-template-rows: 72px 1fr;
        height: 100vh;
        overflow: hidden;
        background: var(--k-surface);
    }

    /* ===== HEADER ===== */
    .kiosk-header {
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
    .header-brand {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--k-primary);
        letter-spacing: -0.3px;
        white-space: nowrap;
    }
    .header-brand span {
        color: var(--k-primary-container);
    }
    .header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .header-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border: 1.5px solid var(--k-border-strong);
        border-radius: var(--k-radius-full);
        background: transparent;
        color: var(--k-on-surface-muted);
        font-size: 0.82rem;
        font-weight: 600;
        transition: background 0.2s, color 0.2s, border-color 0.2s;
    }
    .header-btn:hover {
        background: var(--k-surface-variant);
        color: var(--k-on-surface);
        border-color: var(--k-primary-container);
    }
    .header-btn .mat-icon { font-size: 18px; }

    /* ===== SIDEBAR ===== */
    .kiosk-sidebar {
        grid-column: 1;
        grid-row: 2;
        background: #ffffff;
        border-right: 1px solid var(--k-border);
        overflow-y: auto;
        padding: 16px 0;
        display: flex;
        flex-direction: column;
        gap: 4px;
        box-shadow: 2px 0 12px var(--k-shadow);
    }
    .kiosk-sidebar::-webkit-scrollbar { width: 3px; }

    /* Resize handle */
    .sidebar-resize-handle {
        grid-row: 2;
        width: 6px;
        cursor: col-resize;
        background: transparent;
        position: relative;
        z-index: 5;
        margin-left: -3px;
        transition: background 0.15s;
    }
    .sidebar-resize-handle:hover,
    .sidebar-resize-handle.active {
        background: var(--k-primary-container);
    }
    .sidebar-resize-handle::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 4px;
        height: 32px;
        border-radius: 2px;
        background: var(--k-on-surface-faint);
        opacity: 0;
        transition: opacity 0.15s;
    }
    .sidebar-resize-handle:hover::after,
    .sidebar-resize-handle.active::after {
        opacity: 0.5;
    }

    .sidebar-section-title {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: var(--k-on-surface-faint);
        padding: 4px 20px 8px;
        margin-top: 4px;
    }

    .cat-nav-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 13px 20px 13px 20px;
        margin: 0 12px 0 0;
        border-radius: 0 var(--k-radius-full) var(--k-radius-full) 0;
        cursor: pointer;
        transition: background 0.2s var(--k-ease-out), color 0.2s, box-shadow 0.2s;
        color: var(--k-on-surface-muted);
        font-size: 0.92rem;
        font-weight: 600;
        border: none;
        background: transparent;
        text-align: left;
        width: calc(100% - 12px);
        position: relative;
    }
    .cat-nav-item:hover {
        background: rgba(255,121,65,0.08);
        color: var(--k-primary);
    }
    .cat-nav-item.active {
        background: var(--k-primary-container);
        color: #ffffff;
        box-shadow: 4px 4px 16px rgba(255,121,65,0.35);
    }
    .cat-nav-item .mat-icon {
        font-size: 22px;
        flex-shrink: 0;
        transition: font-variation-settings 0.2s;
    }
    .cat-nav-item.active .mat-icon {
        font-variation-settings: 'FILL' 1;
    }
    .cat-nav-item-name {
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .cat-nav-item-count {
        font-size: 0.72rem;
        font-weight: 700;
        background: rgba(255,255,255,0.25);
        padding: 2px 8px;
        border-radius: var(--k-radius-full);
        flex-shrink: 0;
    }
    .cat-nav-item:not(.active) .cat-nav-item-count {
        background: var(--k-surface-variant);
        color: var(--k-on-surface-muted);
    }

    /* ===== MAIN CONTENT AREA ===== */
    .kiosk-main {
        grid-column: 3;
        grid-row: 2;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .main-category-header {
        padding: 24px 28px 16px;
        flex-shrink: 0;
    }
    .main-category-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--k-on-surface);
        line-height: 1.2;
        margin-bottom: 4px;
    }
    .main-category-desc {
        font-size: 0.85rem;
        color: var(--k-on-surface-muted);
        line-height: 1.5;
    }

    /* ===== PRODUCTS SCROLL ===== */
    .products-scroll {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 4px 28px 120px;
    }
    .products-scroll::-webkit-scrollbar { width: 4px; }

    /* ===== BENTO GRID ===== */
    .bento-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        padding-top: 6px;
        animation: gridFadeUp 0.38s var(--k-ease-out);
    }
    @keyframes gridFadeUp {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ===== BASE PRODUCT CARD ===== */
    .product-card {
        background: #ffffff;
        border-radius: var(--k-radius-lg);
        border: 1.5px solid var(--k-border);
        cursor: pointer;
        overflow: hidden;
        transition: transform 0.22s var(--k-spring), box-shadow 0.22s var(--k-ease-out), border-color 0.2s;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .product-card:hover {
        transform: translateY(-3px) scale(1.012);
        box-shadow: 0 12px 36px var(--k-shadow-md);
        border-color: var(--k-primary-container);
    }
    .product-card:active {
        transform: scale(0.97) translateY(0);
        box-shadow: 0 2px 10px var(--k-shadow);
    }

    /* ===== FEATURED CARD (first item, 2-col span) ===== */
    .product-card-featured {
        grid-column: span 2;
        flex-direction: row;
        min-height: 200px;
    }
    .product-card-featured .card-image-wrap {
        width: 42%;
        flex-shrink: 0;
        min-height: 200px;
        border-radius: 0;
    }
    .product-card-featured .card-body {
        padding: 24px 22px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1;
    }
    .product-card-featured .card-add-btn {
        margin-top: 16px;
        padding: 12px 24px;
        border-radius: var(--k-radius-full);
        background: linear-gradient(135deg, var(--k-primary-container), var(--k-primary));
        color: #ffffff;
        border: none;
        font-size: 0.9rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        align-self: flex-start;
        box-shadow: 0 4px 16px rgba(163,56,0,0.25);
        transition: transform 0.15s var(--k-spring), box-shadow 0.15s;
    }
    .product-card-featured .card-add-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 22px rgba(163,56,0,0.35);
    }
    .product-card-featured .card-add-btn:active {
        transform: scale(0.94);
    }
    .product-card-featured .p-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: var(--k-secondary-container);
        color: var(--k-primary);
        font-size: 0.7rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: var(--k-radius-full);
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        width: fit-content;
    }

    /* ===== STANDARD CARD ===== */
    .product-card-standard .card-image-wrap {
        width: 100%;
        height: 150px;
    }
    .product-card-standard .card-body {
        padding: 14px 16px 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .product-card-standard .card-add-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--k-secondary-container);
        color: var(--k-primary);
        border: none;
        font-size: 1.3rem;
        font-weight: 300;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        transition: background 0.18s var(--k-spring), color 0.18s, transform 0.15s var(--k-spring);
        line-height: 1;
    }
    .product-card:hover .product-card-standard .card-add-btn,
    .product-card-standard:hover .card-add-btn {
        background: var(--k-primary-container);
        color: #ffffff;
        transform: scale(1.1);
    }
    .product-card-standard:active .card-add-btn {
        transform: scale(0.88);
    }

    /* ===== TALL CARD ===== */
    .product-card-tall .card-image-wrap {
        width: 100%;
        height: 240px;
    }
    .product-card-tall .card-body {
        padding: 14px 16px 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .product-card-tall .card-add-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--k-secondary-container);
        color: var(--k-primary);
        border: none;
        font-size: 1.3rem;
        font-weight: 300;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        transition: background 0.18s var(--k-spring), color 0.18s, transform 0.15s var(--k-spring);
    }
    .product-card-tall:hover .card-add-btn {
        background: var(--k-primary);
        color: #ffffff;
        transform: scale(1.1);
    }
    .product-card-tall:active .card-add-btn {
        transform: scale(0.88);
    }

    /* ===== CARD IMAGE ===== */
    .card-image-wrap {
        overflow: hidden;
        position: relative;
        background: var(--k-surface-variant);
    }
    .card-image-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.45s var(--k-ease-out);
        display: block;
    }
    .product-card:hover .card-image-wrap img {
        transform: scale(1.06);
    }
    .card-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        inset: 0;
    }
    .card-image-placeholder .mat-icon {
        font-size: 42px;
        opacity: 0.35;
        font-variation-settings: 'FILL' 1;
    }

    /* ===== CARD BODY ELEMENTS ===== */
    .p-name {
        font-size: 1rem;
        font-weight: 700;
        color: var(--k-on-surface);
        line-height: 1.25;
        margin-bottom: 6px;
        letter-spacing: -0.1px;
    }
    .product-card-featured .p-name {
        font-size: 1.3rem;
        margin-bottom: 8px;
    }
    .p-desc {
        font-size: 0.78rem;
        color: var(--k-on-surface-muted);
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
        margin-bottom: 12px;
    }
    .product-card-featured .p-desc {
        font-size: 0.85rem;
        -webkit-line-clamp: 3;
        margin-bottom: 0;
    }
    .p-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
    }
    .p-price {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--k-primary);
        letter-spacing: -0.3px;
    }
    .product-card-featured .p-price {
        font-size: 1.6rem;
        font-weight: 800;
    }
    .p-prep-time {
        font-size: 0.68rem;
        color: var(--k-on-surface-faint);
        display: flex;
        align-items: center;
        gap: 3px;
        margin-top: 6px;
    }
    .p-prep-time .mat-icon { font-size: 13px; }

    /* ===== BOTTOM CART BAR ===== */
    .cart-bar {
        position: fixed;
        bottom: 0;
        left: var(--sidebar-width, 320px);
        right: 0;
        z-index: 200;
        background: linear-gradient(135deg, #a33800 0%, #802c00 100%);
        color: #ffffff;
        padding: 0 28px;
        height: 70px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 -4px 24px rgba(163,56,0,0.25);
        transform: translateY(100%);
        transition: transform 0.4s var(--k-spring);
        cursor: pointer;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
    .cart-bar.visible {
        transform: translateY(0);
    }
    .cart-bar-icon-wrap {
        position: relative;
        flex-shrink: 0;
    }
    .cart-bar-icon-wrap .mat-icon {
        font-size: 28px;
        font-variation-settings: 'FILL' 1;
    }
    .cart-bar-badge {
        position: absolute;
        top: -8px;
        right: -10px;
        background: var(--k-tertiary);
        color: #2f2e2e;
        font-size: 0.7rem;
        font-weight: 800;
        min-width: 20px;
        height: 20px;
        padding: 0 5px;
        border-radius: var(--k-radius-full);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #a33800;
        line-height: 1;
    }
    .cart-bar-badge.pop {
        animation: badgePop 0.4s var(--k-spring);
    }
    @keyframes badgePop {
        0%   { transform: scale(1); }
        45%  { transform: scale(1.5); }
        70%  { transform: scale(0.88); }
        100% { transform: scale(1); }
    }
    .cart-bar-label {
        font-size: 1rem;
        font-weight: 700;
        flex: 1;
        letter-spacing: 0.2px;
    }
    .cart-bar-count {
        font-size: 0.8rem;
        opacity: 0.8;
        font-weight: 500;
    }
    .cart-bar-sep {
        width: 1px;
        height: 32px;
        background: rgba(255,255,255,0.25);
        flex-shrink: 0;
    }
    .cart-bar-subtotal {
        font-size: 1.1rem;
        font-weight: 700;
        flex-shrink: 0;
    }
    .cart-bar-checkout {
        background: #ffffff;
        color: var(--k-primary);
        border: none;
        border-radius: var(--k-radius-full);
        padding: 10px 22px;
        font-size: 0.9rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        flex-shrink: 0;
        transition: transform 0.15s var(--k-spring), box-shadow 0.15s;
        box-shadow: 0 2px 10px rgba(0,0,0,0.12);
    }
    .cart-bar-checkout:hover {
        transform: scale(1.04);
        box-shadow: 0 4px 18px rgba(0,0,0,0.18);
    }
    .cart-bar-checkout:active {
        transform: scale(0.95);
    }

    /* ===== CART DRAWER ===== */
    .cart-drawer-overlay {
        position: fixed;
        inset: 0;
        background: rgba(47,46,46,0.35);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 300;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s var(--k-ease-out);
    }
    .cart-drawer-overlay.open {
        opacity: 1;
        pointer-events: all;
    }

    .cart-drawer {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        width: 400px;
        background: #ffffff;
        z-index: 310;
        display: flex;
        flex-direction: column;
        box-shadow: -8px 0 40px rgba(47,46,46,0.18);
        transform: translateX(100%);
        transition: transform 0.38s var(--k-spring);
    }
    .cart-drawer.open {
        transform: translateX(0);
    }

    .drawer-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--k-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
        background: var(--k-surface-variant);
    }
    .drawer-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--k-on-surface);
        letter-spacing: -0.2px;
    }
    .drawer-close {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 1.5px solid var(--k-border-strong);
        background: #ffffff;
        color: var(--k-on-surface-muted);
        display: grid;
        place-items: center;
        transition: background 0.18s, color 0.18s, border-color 0.18s;
    }
    .drawer-close:hover {
        background: var(--k-primary-container);
        color: #ffffff;
        border-color: var(--k-primary-container);
    }
    .drawer-close .mat-icon { font-size: 20px; }

    .drawer-items {
        flex: 1;
        overflow-y: auto;
        padding: 8px 20px;
    }
    .drawer-items::-webkit-scrollbar { width: 3px; }

    .drawer-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: var(--k-on-surface-muted);
        text-align: center;
        gap: 14px;
        padding: 40px 20px;
    }
    .drawer-empty .mat-icon {
        font-size: 56px;
        color: var(--k-on-surface-faint);
        font-variation-settings: 'FILL' 1;
    }
    .drawer-empty-text {
        font-size: 0.9rem;
        line-height: 1.6;
        color: var(--k-on-surface-muted);
    }

    .drawer-item {
        display: flex;
        gap: 12px;
        padding: 14px 0;
        border-bottom: 1px solid var(--k-border);
        animation: drawerItemIn 0.28s var(--k-ease-out);
    }
    .drawer-item.removing {
        animation: drawerItemOut 0.22s var(--k-ease-out) forwards;
    }
    @keyframes drawerItemIn {
        from { opacity: 0; transform: translateX(14px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    @keyframes drawerItemOut {
        to { opacity: 0; transform: scale(0.9); max-height: 0; padding: 0; margin: 0; overflow: hidden; }
    }
    .drawer-item-info { flex: 1; min-width: 0; }
    .drawer-item-name {
        font-weight: 600;
        font-size: 0.88rem;
        color: var(--k-on-surface);
        margin-bottom: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .drawer-item-mods {
        font-size: 0.72rem;
        color: var(--k-primary);
        font-weight: 500;
        line-height: 1.4;
    }
    .drawer-item-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 6px;
        flex-shrink: 0;
    }
    .drawer-item-price {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--k-on-surface);
    }
    /* Quantity stepper in cart */
    .drawer-qty-stepper {
        display: flex;
        align-items: center;
        gap: 0;
        border: 1.5px solid var(--k-border);
        border-radius: var(--k-radius-full);
        overflow: hidden;
        background: var(--k-surface);
    }
    .drawer-qty-btn {
        width: 32px;
        height: 32px;
        display: grid;
        place-items: center;
        background: none;
        border: none;
        color: var(--k-on-surface);
        font-size: 0.85rem;
        transition: background 0.12s, color 0.12s;
    }
    .drawer-qty-btn:hover { background: var(--k-surface-variant); }
    .drawer-qty-btn:active { background: var(--k-primary-container); color: #fff; }
    .drawer-qty-btn.remove-last { color: #c0392b; }
    .drawer-qty-btn.remove-last:hover { background: rgba(192,57,43,0.08); }
    .drawer-qty-val {
        min-width: 26px;
        text-align: center;
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--k-on-surface);
        user-select: none;
    }

    /* ===== SKELETON LOADING ===== */
    @keyframes shimmer {
        0%   { background-position: -400px 0; }
        100% { background-position: 400px 0; }
    }
    .skeleton {
        background: linear-gradient(90deg, var(--k-surface-variant) 25%, rgba(0,0,0,0.04) 50%, var(--k-surface-variant) 75%);
        background-size: 800px 100%;
        animation: shimmer 1.5s infinite linear;
        border-radius: var(--k-radius-sm);
    }
    .skeleton-card {
        background: var(--k-surface-container);
        border-radius: var(--k-radius-md);
        overflow: hidden;
    }
    .skeleton-image { height: 200px; }
    .skeleton-body { padding: 16px; }
    .skeleton-line {
        height: 14px;
        margin-bottom: 10px;
        border-radius: 6px;
    }
    .skeleton-line.w60 { width: 60%; }
    .skeleton-line.w80 { width: 80%; }
    .skeleton-line.w40 { width: 40%; }
    .skeleton-line.h20 { height: 20px; }
    .skeleton-cat {
        padding: 18px 24px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .skeleton-cat-icon { width: 32px; height: 32px; border-radius: 8px; }
    .skeleton-cat-text { height: 16px; width: 80px; border-radius: 6px; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .kiosk { --sidebar-width: 220px; }
        .cat-nav-item { padding: 14px 18px; font-size: 0.9rem; }
        .cat-nav-item .mat-icon { font-size: 22px; }
        .bento-grid { grid-template-columns: repeat(2, 1fr); }
        .product-card-featured { grid-column: span 2; }
    }
    /* Mobile hamburger button (hidden on desktop) */
    .mobile-menu-btn {
        display: none;
        background: none;
        border: none;
        color: var(--k-on-surface);
        padding: 8px;
        border-radius: var(--k-radius-sm);
        transition: background 0.12s;
    }
    .mobile-menu-btn:active { background: var(--k-surface-variant); }
    .mobile-sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        z-index: 99;
        opacity: 0;
        transition: opacity 0.25s;
    }
    .mobile-sidebar-overlay.visible { display: block; opacity: 1; }

    @media (max-width: 768px) {
        .mobile-menu-btn { display: block; }
        .kiosk {
            grid-template-columns: 1fr;
            grid-template-rows: 60px 1fr;
        }
        .sidebar-resize-handle { display: none; }
        .kiosk-header {
            padding: 0 12px;
            grid-column: 1;
            grid-row: 1;
        }
        .header-brand { font-size: 1.1rem; }
        .kiosk-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 280px !important;
            z-index: 100;
            grid-column: unset;
            grid-row: unset;
            transform: translateX(-100%);
            transition: transform 0.3s var(--k-ease-out);
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 16px 0;
            border-right: 1px solid var(--k-border);
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
        }
        .kiosk-sidebar.mobile-open {
            transform: translateX(0);
        }
        .sidebar-header { display: flex !important; }
        .cat-nav-list { flex-direction: column !important; }
        .cat-nav-item {
            border-radius: 0 !important;
            white-space: nowrap;
        }
        .cat-nav-item.active { border-radius: 0 var(--k-radius-full) var(--k-radius-full) 0 !important; }
        .kiosk-main {
            grid-column: 1;
            grid-row: 2;
        }
        .main-category-title { font-size: 1.6rem !important; }
        .main-category-desc { font-size: 0.85rem; }
        .products-scroll { padding: 0 14px 120px; }
        .bento-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .product-card-featured {
            grid-column: span 2;
            flex-direction: column;
            min-height: auto;
        }
        .product-card-featured .card-image-wrap {
            width: 100%;
            min-height: 180px;
            height: 180px;
        }
        .product-card-featured .card-body { padding: 14px; }
        .product-card-featured .p-name { font-size: 1.2rem; }
        .cart-bar {
            left: 0 !important;
            right: 0;
            padding: 10px 14px;
            height: auto;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .cart-bar-label { flex: none; }
        .cart-bar-checkout { width: 100%; text-align: center; margin-top: 4px; }
        .cart-drawer { width: 100%; max-width: 100%; }
        .skeleton-cat { padding: 10px 16px; }
    }
    @media (max-width: 480px) {
        .bento-grid { grid-template-columns: 1fr; }
        .product-card-featured {
            grid-column: span 1;
            flex-direction: column;
            min-height: auto;
        }
        .product-card-featured .card-image-wrap {
            width: 100%;
            height: 180px;
            min-height: 180px;
        }
        .product-card-featured .card-add-btn {
            align-self: stretch;
            justify-content: center;
        }
        .product-card-tall .card-image-wrap { height: 180px; }
        .payment-methods { grid-template-columns: 1fr 1fr; gap: 10px; }
        .pay-method { padding: 18px 10px; }
    }

    .drawer-footer {
        padding: 16px 20px 22px;
        border-top: 1px solid var(--k-border);
        background: var(--k-surface-variant);
        flex-shrink: 0;
    }
    .drawer-summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.82rem;
        color: var(--k-on-surface-muted);
        margin-bottom: 6px;
    }
    .drawer-total-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        padding-top: 12px;
        margin-top: 6px;
        border-top: 1px solid var(--k-border-strong);
        margin-bottom: 14px;
    }
    .drawer-total-label {
        font-size: 1rem;
        font-weight: 700;
        color: var(--k-on-surface);
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    .drawer-total-amount {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--k-primary);
    }
    .drawer-footer-actions {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .btn-proceed {
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: var(--k-radius-md);
        background: linear-gradient(135deg, var(--k-primary-container), var(--k-primary));
        color: #ffffff;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        box-shadow: 0 4px 18px rgba(163,56,0,0.25);
        transition: transform 0.15s var(--k-spring), box-shadow 0.15s;
    }
    .btn-proceed:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 24px rgba(163,56,0,0.35);
    }
    .btn-proceed:active {
        transform: scale(0.97);
        box-shadow: none;
    }
    .btn-clear-order {
        background: none;
        border: none;
        color: var(--k-on-surface-muted);
        font-size: 0.82rem;
        font-weight: 600;
        text-align: center;
        padding: 6px;
        transition: color 0.15s;
        text-decoration: underline;
        text-underline-offset: 2px;
        text-decoration-color: transparent;
    }
    .btn-clear-order:hover {
        color: #c0392b;
        text-decoration-color: #c0392b;
    }

    /* ===== CUSTOMIZATION MODAL ===== */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(47,46,46,0.45);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        display: grid;
        place-items: center;
        z-index: 500;
        animation: overlayIn 0.2s var(--k-ease-out);
        padding: 20px;
    }
    @keyframes overlayIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    .modal-card {
        background: #ffffff;
        border-radius: var(--k-radius-lg);
        width: 92%;
        max-width: 500px;
        max-height: 88vh;
        overflow-y: auto;
        border: 1.5px solid var(--k-border);
        box-shadow: 0 24px 60px rgba(47,46,46,0.22);
        animation: modalRise 0.32s var(--k-spring);
    }
    .modal-card::-webkit-scrollbar { width: 3px; }
    @keyframes modalRise {
        from { opacity: 0; transform: translateY(40px) scale(0.94); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .modal-header {
        padding: 24px 24px 20px;
        border-bottom: 1px solid var(--k-border);
        background: var(--k-surface);
    }
    .modal-product-image-wrap {
        width: 100%;
        height: 160px;
        border-radius: var(--k-radius-md);
        overflow: hidden;
        background: var(--k-surface-variant);
        margin-bottom: 16px;
        position: relative;
    }
    .modal-product-image-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .modal-product-image-wrap .card-image-placeholder .mat-icon {
        font-size: 52px;
    }
    .modal-product-name {
        font-size: 1.55rem;
        font-weight: 800;
        color: var(--k-on-surface);
        line-height: 1.15;
        margin-bottom: 8px;
        letter-spacing: -0.3px;
    }
    .modal-product-desc {
        font-size: 0.85rem;
        color: var(--k-on-surface-muted);
        line-height: 1.55;
    }
    .modal-price-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 16px;
    }
    .modal-price {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--k-primary);
    }

    /* Quantity control */
    .qty-control {
        display: flex;
        align-items: center;
        gap: 4px;
        background: var(--k-surface-variant);
        border-radius: var(--k-radius-full);
        border: 1.5px solid var(--k-border);
        padding: 4px;
    }
    .qty-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: transparent;
        color: var(--k-on-surface);
        font-size: 1.2rem;
        font-weight: 700;
        display: grid;
        place-items: center;
        transition: background 0.18s var(--k-spring), color 0.15s, transform 0.15s var(--k-spring);
    }
    .qty-btn:hover {
        background: var(--k-primary-container);
        color: #ffffff;
    }
    .qty-btn:active { transform: scale(0.86); }
    .qty-btn span { position: relative; z-index: 1; }
    .qty-display {
        font-size: 1.3rem;
        font-weight: 700;
        min-width: 34px;
        text-align: center;
        color: var(--k-on-surface);
    }

    /* Custom options */
    .modal-body { padding: 18px 24px; }
    .custom-section-title {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 2.5px;
        color: var(--k-on-surface-muted);
        margin-bottom: 8px;
        margin-top: 16px;
        text-transform: uppercase;
    }
    .custom-section-title:first-child { margin-top: 0; }

    .custom-option {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 12px;
        border-radius: var(--k-radius-sm);
        cursor: pointer;
        transition: background 0.15s;
        margin-bottom: 4px;
        border: 1px solid transparent;
    }
    .custom-option:hover { background: var(--k-surface-variant); }
    .custom-option:active { background: var(--k-secondary-container); }

    .custom-toggle {
        width: 44px;
        height: 24px;
        border-radius: var(--k-radius-full);
        background: var(--k-on-surface-faint);
        position: relative;
        flex-shrink: 0;
        transition: background 0.25s var(--k-spring);
        cursor: pointer;
    }
    .custom-toggle::after {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #ffffff;
        transition: transform 0.26s var(--k-spring);
        box-shadow: 0 1px 4px rgba(0,0,0,0.25);
    }
    .custom-toggle.checked {
        background: var(--k-primary-container);
    }
    .custom-toggle.checked::after {
        transform: translateX(20px);
    }

    .custom-option-label {
        flex: 1;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--k-on-surface);
    }
    .custom-option-price {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--k-primary);
    }
    .custom-option-free {
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--k-success);
        letter-spacing: 0.5px;
    }

    .modal-footer {
        padding: 14px 24px 22px;
        display: flex;
        gap: 10px;
        border-top: 1px solid var(--k-border);
        background: var(--k-surface);
    }
    .btn-modal-cancel {
        padding: 13px 20px;
        border: 1.5px solid var(--k-border-strong);
        border-radius: var(--k-radius-md);
        background: transparent;
        color: var(--k-on-surface-muted);
        font-size: 0.9rem;
        font-weight: 600;
        flex: 0 0 auto;
        transition: background 0.18s, color 0.18s;
    }
    .btn-modal-cancel:hover {
        background: var(--k-surface-variant);
        color: var(--k-on-surface);
    }
    .btn-add-to-order {
        flex: 1;
        padding: 13px 20px;
        border: none;
        border-radius: var(--k-radius-md);
        background: linear-gradient(135deg, var(--k-primary-container), var(--k-primary));
        color: #ffffff;
        font-size: 0.95rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        box-shadow: 0 4px 16px rgba(163,56,0,0.22);
        transition: transform 0.15s var(--k-spring), box-shadow 0.15s, opacity 0.15s;
    }
    .btn-add-to-order:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 22px rgba(163,56,0,0.3);
    }
    .btn-add-to-order:active {
        transform: scale(0.96);
        box-shadow: none;
    }

    /* ===== PAYMENT SCREEN ===== */
    .screen-overlay {
        position: fixed;
        inset: 0;
        background: rgba(249,246,245,0.92);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        z-index: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: overlayIn 0.32s var(--k-ease-out);
        overflow-y: auto;
        padding: 40px 20px;
    }
    .payment-container {
        width: 100%;
        max-width: 580px;
        text-align: center;
        animation: gridFadeUp 0.35s var(--k-ease-out);
    }
    .payment-header { margin-bottom: 28px; }
    .payment-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--k-on-surface);
        margin-bottom: 6px;
        letter-spacing: -0.5px;
    }
    .payment-amount {
        font-size: 3rem;
        font-weight: 800;
        color: var(--k-primary);
        letter-spacing: -1px;
    }

    .payment-summary {
        background: #ffffff;
        border: 1.5px solid var(--k-border);
        border-radius: var(--k-radius-md);
        padding: 16px 20px;
        margin-bottom: 22px;
        text-align: left;
        max-height: 180px;
        overflow-y: auto;
        box-shadow: 0 2px 12px var(--k-shadow);
    }
    .payment-summary::-webkit-scrollbar { width: 3px; }
    .payment-summary-title {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 2px;
        color: var(--k-on-surface-muted);
        text-transform: uppercase;
        margin-bottom: 10px;
    }
    .payment-summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.88rem;
        color: var(--k-on-surface);
        padding: 5px 0;
        border-bottom: 1px solid var(--k-border);
    }
    .payment-summary-item:last-child { border-bottom: none; }
    .payment-summary-item .qty-tag {
        font-size: 0.72rem;
        background: var(--k-secondary-container);
        color: var(--k-primary);
        padding: 1px 7px;
        border-radius: var(--k-radius-full);
        margin-right: 8px;
        font-weight: 700;
    }

    .payment-methods {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 22px;
    }
    .pay-method {
        background: #ffffff;
        border: 1.5px solid var(--k-border);
        border-radius: var(--k-radius-md);
        padding: 20px 16px 18px;
        cursor: pointer;
        transition: all 0.22s var(--k-ease-out), transform 0.15s var(--k-spring);
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 8px var(--k-shadow);
    }
    .pay-method:hover {
        border-color: var(--k-primary-container);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px var(--k-shadow-md);
    }
    .pay-method:active { transform: scale(0.96); }
    .pay-method.selected {
        border-color: var(--k-primary-container);
        background: rgba(255,121,65,0.06);
        box-shadow: 0 0 0 3px rgba(255,121,65,0.2);
    }
    .pay-method-checkmark {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--k-primary-container), var(--k-primary));
        display: none;
        place-items: center;
        animation: checkPop 0.32s var(--k-spring);
    }
    .pay-method.selected .pay-method-checkmark { display: grid; }
    @keyframes checkPop {
        from { transform: scale(0); opacity: 0; }
        60%  { transform: scale(1.3); }
        to   { transform: scale(1); opacity: 1; }
    }
    .pay-method-icon {
        display: block;
        margin: 0 auto 10px;
        width: 40px;
        height: 40px;
        color: var(--k-on-surface-muted);
    }
    .pay-method.selected .pay-method-icon { color: var(--k-primary); }
    .pay-method-label {
        font-size: 0.9rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        color: var(--k-on-surface-muted);
        transition: color 0.2s;
    }
    .pay-method.selected .pay-method-label { color: var(--k-primary); }

    .payment-inputs { margin-bottom: 4px; }
    .payment-input {
        width: 100%;
        padding: 14px 16px;
        background: #ffffff;
        border: 1.5px solid var(--k-border);
        border-radius: var(--k-radius-md);
        color: var(--k-on-surface);
        font-size: 0.95rem;
        margin-bottom: 10px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-shadow: 0 1px 4px var(--k-shadow);
    }
    .payment-input:focus {
        border-color: var(--k-primary-container);
        box-shadow: 0 0 0 3px rgba(255,121,65,0.15);
    }
    .payment-input::placeholder { color: var(--k-on-surface-faint); }

    .payment-actions {
        display: flex;
        gap: 12px;
        margin-top: 16px;
    }
    .btn-payment-back {
        padding: 15px 20px;
        flex: 0 0 110px;
        border: 1.5px solid var(--k-border-strong);
        border-radius: var(--k-radius-md);
        background: #ffffff;
        color: var(--k-on-surface-muted);
        font-size: 0.9rem;
        font-weight: 600;
        transition: background 0.18s, color 0.18s;
    }
    .btn-payment-back:hover {
        background: var(--k-surface-variant);
        color: var(--k-on-surface);
    }
    .btn-place-order {
        flex: 1;
        padding: 15px 20px;
        border: none;
        border-radius: var(--k-radius-md);
        background: linear-gradient(135deg, var(--k-primary-container), var(--k-primary));
        color: #ffffff;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 18px rgba(163,56,0,0.25);
        transition: transform 0.15s var(--k-spring), box-shadow 0.15s, opacity 0.15s;
    }
    .btn-place-order:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 24px rgba(163,56,0,0.32);
    }
    .btn-place-order:active { transform: scale(0.97); box-shadow: none; }
    .btn-place-order:disabled { opacity: 0.35; pointer-events: none; }

    /* ===== CONFIRMATION SCREEN ===== */
    .confetti-wrap {
        position: fixed;
        inset: 0;
        pointer-events: none;
        z-index: 1;
        overflow: hidden;
    }
    .confetti-dot {
        position: absolute;
        top: -10px;
        border-radius: 50%;
        animation: confettiFall linear forwards;
    }
    @keyframes confettiFall {
        0%   { transform: translateY(-10px) rotate(0deg); opacity: 1; }
        80%  { opacity: 1; }
        100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
    }

    .confirm-container {
        width: 100%;
        max-width: 440px;
        text-align: center;
        position: relative;
        animation: gridFadeUp 0.35s var(--k-ease-out);
    }
    .confirm-checkmark-wrap {
        width: 108px;
        height: 108px;
        margin: 0 auto 24px;
        animation: checkmarkPopIn 0.45s var(--k-spring);
    }
    @keyframes checkmarkPopIn {
        from { transform: scale(0); opacity: 0; }
        60%  { transform: scale(1.18); }
        to   { transform: scale(1); opacity: 1; }
    }
    .confirm-checkmark-bg {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: rgba(26,127,82,0.1);
        border: 2.5px solid rgba(26,127,82,0.28);
        display: grid;
        place-items: center;
        box-shadow: 0 0 50px rgba(26,127,82,0.15);
    }
    .confirm-check-svg { width: 50px; height: 50px; }
    .confirm-check-path {
        stroke-dasharray: 60;
        stroke-dashoffset: 60;
        animation: drawCheck 0.55s var(--k-ease-out) 0.28s forwards;
    }
    @keyframes drawCheck { to { stroke-dashoffset: 0; } }

    .confirm-title {
        font-size: 1.9rem;
        font-weight: 800;
        color: var(--k-on-surface);
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }
    .confirm-order-num {
        font-size: 3.6rem;
        font-weight: 800;
        color: var(--k-primary);
        letter-spacing: -1px;
        line-height: 1;
        margin: 18px 0;
        animation: orderNumPulse 2.8s var(--k-ease-out) infinite;
    }
    @keyframes orderNumPulse {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.75; }
    }
    .confirm-msg {
        font-size: 0.95rem;
        color: var(--k-on-surface-muted);
        line-height: 1.65;
        margin-bottom: 26px;
    }
    .confirm-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 20px;
        border-radius: var(--k-radius-full);
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 34px;
    }
    .confirm-status::before {
        content: '';
        display: inline-block;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
        animation: statusBlink 1.2s ease-in-out infinite;
    }
    @keyframes statusBlink {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.25; }
    }
    .confirm-status.pending  { background: var(--k-warning-bg); color: #b87800; }
    .confirm-status.preparing{ background: var(--k-info-bg); color: var(--k-info); }
    .confirm-status.ready    { background: var(--k-success-bg); color: var(--k-success); }
    .confirm-status.delivered{ background: var(--k-success-bg); color: var(--k-success); }
    .confirm-status.cancelled{ background: rgba(192,57,43,0.1); color: #c0392b; }
    .btn-new-order {
        padding: 16px 56px;
        border: none;
        border-radius: var(--k-radius-full);
        background: linear-gradient(135deg, var(--k-primary-container), var(--k-primary));
        color: #ffffff;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 18px rgba(163,56,0,0.25);
        transition: transform 0.15s var(--k-spring), box-shadow 0.15s;
    }
    .btn-new-order:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 24px rgba(163,56,0,0.35);
    }
    .btn-new-order:active { transform: scale(0.96); box-shadow: none; }

    /* ===== TOAST ===== */
    .toast-container {
        position: fixed;
        top: 90px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 9px;
        pointer-events: none;
    }
    .toast {
        background: #ffffff;
        border: 1.5px solid var(--k-border);
        border-left: 4px solid var(--k-primary-container);
        border-radius: var(--k-radius-md);
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 6px 24px var(--k-shadow-md);
        animation: toastSlideIn 0.32s var(--k-spring);
        min-width: 220px;
        max-width: 300px;
    }
    .toast.removing {
        animation: toastSlideOut 0.25s var(--k-ease-out) forwards;
    }
    @keyframes toastSlideIn {
        from { opacity: 0; transform: translateX(50px) scale(0.92); }
        to   { opacity: 1; transform: translateX(0) scale(1); }
    }
    @keyframes toastSlideOut {
        to { opacity: 0; transform: translateX(40px) scale(0.92); }
    }
    .toast-icon { font-size: 1.1rem; flex-shrink: 0; color: var(--k-success); }
    .toast-text {
        font-size: 0.84rem;
        font-weight: 600;
        color: var(--k-on-surface);
        line-height: 1.3;
    }
    .toast-sub {
        font-size: 0.71rem;
        color: var(--k-on-surface-muted);
        font-weight: 400;
    }

    /* ===== LOADING ANIMATION ===== */
    .loading-dots::after {
        content: '';
        animation: dots 1.5s steps(4, end) infinite;
    }
    @keyframes dots {
        0%   { content: ''; }
        25%  { content: '.'; }
        50%  { content: '..'; }
        75%  { content: '...'; }
    }

    .screen-transition { animation: gridFadeUp 0.35s var(--k-ease-out); }
</style>
@endpush

@section('content')

<!-- ====== TOAST CONTAINER ====== -->
<div id="toast-container" class="toast-container" aria-live="polite"></div>

<!-- ====== MENU SCREEN ====== -->
<div id="menu-screen" class="kiosk">

    <!-- HEADER -->
    <header class="kiosk-header">
        <button class="mobile-menu-btn" onclick="toggleMobileSidebar()" aria-label="Open menu">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
        </button>
        <div class="header-brand">The Culinary <span>Concierge</span></div>
        <div class="header-actions"></div>
    </header>

    <!-- MOBILE SIDEBAR OVERLAY -->
    <div id="mobile-sidebar-overlay" class="mobile-sidebar-overlay" onclick="toggleMobileSidebar()"></div>

    <!-- SIDEBAR (categories) -->
    <nav class="kiosk-sidebar" aria-label="Menu categories">
        <div class="sidebar-header">
            <div class="sidebar-section-title">Menu</div>
        </div>
        <div class="cat-nav-list" id="categories">
            <!-- Skeleton categories -->
            <div class="skeleton-cat"><div class="skeleton skeleton-cat-icon"></div><div class="skeleton skeleton-cat-text"></div></div>
            <div class="skeleton-cat"><div class="skeleton skeleton-cat-icon"></div><div class="skeleton skeleton-cat-text"></div></div>
            <div class="skeleton-cat"><div class="skeleton skeleton-cat-icon"></div><div class="skeleton skeleton-cat-text"></div></div>
            <div class="skeleton-cat"><div class="skeleton skeleton-cat-icon"></div><div class="skeleton skeleton-cat-text"></div></div>
            <div class="skeleton-cat"><div class="skeleton skeleton-cat-icon"></div><div class="skeleton skeleton-cat-text"></div></div>
        </div>
    </nav>

    <!-- RESIZE HANDLE -->
    <div class="sidebar-resize-handle" id="sidebar-resize-handle" title="Drag to resize sidebar"></div>

    <!-- MAIN CONTENT -->
    <main class="kiosk-main">
        <div class="main-category-header">
            <div class="main-category-title" id="category-title"><div class="skeleton skeleton-line h20 w60"></div></div>
            <div class="main-category-desc" id="category-desc"><div class="skeleton skeleton-line w80" style="margin-top:8px;"></div></div>
        </div>
        <div class="products-scroll">
            <div class="bento-grid" id="products">
                <!-- Skeleton product cards -->
                <div class="skeleton-card" style="grid-column: span 2;"><div class="skeleton skeleton-image"></div><div class="skeleton-body"><div class="skeleton skeleton-line h20 w60"></div><div class="skeleton skeleton-line w80"></div><div class="skeleton skeleton-line w40"></div></div></div>
                <div class="skeleton-card"><div class="skeleton skeleton-image"></div><div class="skeleton-body"><div class="skeleton skeleton-line h20 w60"></div><div class="skeleton skeleton-line w80"></div><div class="skeleton skeleton-line w40"></div></div></div>
                <div class="skeleton-card"><div class="skeleton skeleton-image"></div><div class="skeleton-body"><div class="skeleton skeleton-line h20 w60"></div><div class="skeleton skeleton-line w80"></div><div class="skeleton skeleton-line w40"></div></div></div>
                <div class="skeleton-card"><div class="skeleton skeleton-image"></div><div class="skeleton-body"><div class="skeleton skeleton-line h20 w60"></div><div class="skeleton skeleton-line w80"></div><div class="skeleton skeleton-line w40"></div></div></div>
                <div class="skeleton-card"><div class="skeleton skeleton-image"></div><div class="skeleton-body"><div class="skeleton skeleton-line h20 w60"></div><div class="skeleton skeleton-line w80"></div><div class="skeleton skeleton-line w40"></div></div></div>
            </div>
        </div>
    </main>

</div>

<!-- ====== BOTTOM CART BAR ====== -->
<div id="cart-bar" class="cart-bar" onclick="toggleCartDrawer()" role="button" aria-label="View cart" tabindex="0">
    <div class="cart-bar-icon-wrap">
        <span class="mat-icon" style="font-variation-settings:'FILL' 1;">shopping_basket</span>
        <span class="cart-bar-badge hidden" id="cart-bar-badge">0</span>
    </div>
    <div class="cart-bar-label">View Cart</div>
    <div class="cart-bar-count" id="cart-bar-count"></div>
    <div class="cart-bar-sep"></div>
    <div class="cart-bar-subtotal" id="cart-bar-subtotal">$0.00</div>
    <button class="cart-bar-checkout" onclick="event.stopPropagation(); goToPayment()">Checkout Now</button>
</div>

<!-- ====== CART DRAWER ====== -->
<div id="cart-drawer-overlay" class="cart-drawer-overlay" onclick="toggleCartDrawer()" aria-hidden="true"></div>
<div id="cart-drawer" class="cart-drawer" role="dialog" aria-label="Your order" aria-modal="true">
    <div class="drawer-header">
        <div class="drawer-title">Your Order</div>
        <button class="drawer-close" onclick="toggleCartDrawer()" aria-label="Close cart">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
    </div>
    <div class="drawer-items" id="drawer-items">
        <div class="drawer-empty">
            <span class="mat-icon">shopping_basket</span>
            <div class="drawer-empty-text">Tap an item to start<br>building your meal</div>
        </div>
    </div>
    <div class="drawer-footer hidden" id="drawer-footer">
        <div class="drawer-summary-row">
            <span>Subtotal</span>
            <span id="drawer-subtotal">$0.00</span>
        </div>
        <div class="drawer-summary-row">
            <span>Tax (8%)</span>
            <span id="drawer-tax">$0.00</span>
        </div>
        <div class="drawer-total-row">
            <span class="drawer-total-label">Total</span>
            <span class="drawer-total-amount" id="drawer-total">$0.00</span>
        </div>
        <div class="drawer-footer-actions">
            <button class="btn-proceed" onclick="toggleCartDrawer(); goToPayment()">Proceed to Payment</button>
            <button class="btn-clear-order" onclick="clearCart()">Clear Order</button>
        </div>
    </div>
</div>

<!-- ====== PAYMENT SCREEN ====== -->
<div id="payment-screen" class="screen-overlay hidden">
    <div class="payment-container">
        <div class="payment-header">
            <div class="payment-title">Choose Payment</div>
            <div class="payment-amount" id="payment-total">$0.00</div>
        </div>

        <div class="payment-summary" id="payment-summary">
            <div class="payment-summary-title">Order Summary</div>
            <div id="payment-summary-items"></div>
        </div>

        <div class="payment-methods">
            <!-- Cash -->
            <div class="pay-method" onclick="selectPayment('cash', this)">
                <div class="pay-method-checkmark">
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><polyline points="1.5 5 4 7.5 8.5 2" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <svg class="pay-method-icon" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4" y="11" width="32" height="20" rx="4" stroke="currentColor" stroke-width="2" fill="none"/>
                    <circle cx="20" cy="21" r="5" stroke="currentColor" stroke-width="2"/>
                    <line x1="4" y1="16" x2="10" y2="16" stroke="currentColor" stroke-width="2"/>
                    <line x1="30" y1="26" x2="36" y2="26" stroke="currentColor" stroke-width="2"/>
                </svg>
                <div class="pay-method-label">CASH</div>
            </div>
            <!-- Credit -->
            <div class="pay-method" onclick="selectPayment('credit_card', this)">
                <div class="pay-method-checkmark">
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><polyline points="1.5 5 4 7.5 8.5 2" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <svg class="pay-method-icon" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4" y="10" width="32" height="22" rx="4" stroke="currentColor" stroke-width="2" fill="none"/>
                    <line x1="4" y1="16" x2="36" y2="16" stroke="currentColor" stroke-width="2.5"/>
                    <rect x="8" y="22" width="10" height="5" rx="1.5" fill="currentColor" opacity="0.7"/>
                </svg>
                <div class="pay-method-label">CREDIT</div>
            </div>
            <!-- Debit -->
            <div class="pay-method" onclick="selectPayment('debit_card', this)">
                <div class="pay-method-checkmark">
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><polyline points="1.5 5 4 7.5 8.5 2" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <svg class="pay-method-icon" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4" y="10" width="32" height="22" rx="4" stroke="currentColor" stroke-width="2" fill="none"/>
                    <line x1="4" y1="16" x2="36" y2="16" stroke="currentColor" stroke-width="2.5"/>
                    <circle cx="28" cy="24" r="3" stroke="currentColor" stroke-width="1.8"/>
                    <circle cx="22" cy="24" r="3" stroke="currentColor" stroke-width="1.8"/>
                </svg>
                <div class="pay-method-label">DEBIT</div>
            </div>
            <!-- Mobile -->
            <div class="pay-method" onclick="selectPayment('mobile_pay', this)">
                <div class="pay-method-checkmark">
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><polyline points="1.5 5 4 7.5 8.5 2" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <svg class="pay-method-icon" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="11" y="4" width="18" height="32" rx="4" stroke="currentColor" stroke-width="2" fill="none"/>
                    <line x1="17" y1="31" x2="23" y2="31" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M17 16 L20 13 L23 16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17 21 L20 24 L23 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="pay-method-label">MOBILE</div>
            </div>
        </div>

        <div class="payment-inputs">
            <input type="text" class="payment-input" id="customer-name" placeholder="Your name (optional)" autocomplete="off">
            <textarea class="payment-input" id="order-notes" placeholder="Special instructions (optional)" rows="2" style="resize:none;"></textarea>
        </div>

        <div class="payment-actions">
            <button class="btn-payment-back" onclick="goToMenu()">&larr; Back</button>
            <button class="btn-place-order" id="place-order-btn" disabled onclick="placeOrder()">Place Order</button>
        </div>
    </div>
</div>

<!-- ====== CONFIRMATION SCREEN ====== -->
<div id="confirmation-screen" class="screen-overlay hidden">
    <div class="confetti-wrap" id="confetti-wrap"></div>
    <div class="confirm-container">
        <div class="confirm-checkmark-wrap">
            <div class="confirm-checkmark-bg">
                <svg class="confirm-check-svg" viewBox="0 0 52 52" fill="none">
                    <polyline class="confirm-check-path" points="10 26 21 37 42 16"
                        stroke="#1a7f52" stroke-width="4"
                        stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
        <div class="confirm-title">Order Placed!</div>
        <div class="confirm-order-num" id="confirm-order-number">ORD-0000</div>
        <div class="confirm-msg">Your order is being prepared.<br>Please wait for your number to be called.</div>
        <div class="confirm-status hidden" id="confirm-status"></div>
        <button class="btn-new-order" onclick="newOrder()">New Order</button>
    </div>
</div>

<!-- ====== CUSTOMIZATION MODAL ====== -->
<div id="customization-modal" class="modal-overlay hidden" onclick="event.target===this && closeModal()">
    <div class="modal-card">
        <div class="modal-header">
            <div class="modal-product-image-wrap" id="modal-product-image-wrap">
                <div class="card-image-placeholder">
                    <span class="mat-icon mat-icon-filled" id="modal-placeholder-icon">restaurant</span>
                </div>
            </div>
            <div class="modal-product-name" id="modal-product-name"></div>
            <div class="modal-product-desc" id="modal-product-desc"></div>
            <div class="modal-price-row">
                <div class="modal-price" id="modal-product-price"></div>
                <div class="qty-control">
                    <button class="qty-btn" onclick="changeQty(-1)" aria-label="Decrease quantity"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg></button>
                    <span class="qty-display" id="modal-qty">1</span>
                    <button class="qty-btn" onclick="changeQty(1)" aria-label="Increase quantity"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg></button>
                </div>
            </div>
        </div>
        <div class="modal-body" id="modal-customizations"></div>
        <div class="modal-footer">
            <button class="btn-modal-cancel" onclick="closeModal()">Cancel</button>
            <button class="btn-add-to-order" onclick="addToCart()">Add to Order</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let menuData = [];
    let cart = [];
    let selectedProduct = null;
    let modalQty = 1;
    let selectedPayment = null;
    let activeCategory = null;
    let statusPollInterval = null;
    let cartDrawerOpen = false;

    document.addEventListener('DOMContentLoaded', loadMenu);

    // ===== MENU LOADING =====

    async function loadMenu() {
        try {
            const data = await apiFetch('/menu');
            menuData = data.data || data;
            renderCategories();
            if (menuData.length > 0) {
                activeCategory = menuData[0].id;
                renderProducts(activeCategory);
            }
        } catch (e) {
            console.error('Failed to load menu:', e);
        }
    }

    // ===== CATEGORY ICON HELPER =====

    function getCategoryIcon(categoryName) {
        if (!categoryName) return 'restaurant';
        const n = categoryName.toLowerCase();
        if (n.includes('burger') || n.includes('sandwich')) return 'lunch_dining';
        if (n.includes('side') || n.includes('fry') || n.includes('fries')) return 'fastfood';
        if (n.includes('drink') || n.includes('beverage') || n.includes('shake')) return 'local_drink';
        if (n.includes('dessert') || n.includes('sweet') || n.includes('ice cream')) return 'icecream';
        if (n.includes('combo') || n.includes('meal')) return 'restaurant_menu';
        return 'restaurant';
    }

    function getCategorySlug(catName) {
        if (!catName) return 'default';
        const n = catName.toLowerCase();
        if (n.includes('burger') || n.includes('sandwich')) return 'burgers';
        if (n.includes('side') || n.includes('fry') || n.includes('fries')) return 'sides';
        if (n.includes('drink') || n.includes('beverage') || n.includes('shake')) return 'drinks';
        if (n.includes('dessert') || n.includes('sweet') || n.includes('ice cream')) return 'desserts';
        if (n.includes('combo') || n.includes('meal')) return 'combos';
        return 'default';
    }

    function getCategoryGradient(slug) {
        const gradients = {
            burgers:  'linear-gradient(135deg, #ff7941, #a33800)',
            sides:    'linear-gradient(135deg, #f8a91f, #c27a00)',
            drinks:   'linear-gradient(135deg, #4da6ff, #1a5fad)',
            desserts: 'linear-gradient(135deg, #f472b6, #be185d)',
            combos:   'linear-gradient(135deg, #a78bfa, #6d28d9)',
            default:  'linear-gradient(135deg, #ff7941, #a33800)',
        };
        return gradients[slug] || gradients.default;
    }

    // ===== RENDER CATEGORIES (sidebar nav) =====

    function renderCategories() {
        const el = document.getElementById('categories');
        el.innerHTML = menuData.map(cat => {
            const icon = getCategoryIcon(cat.name);
            const count = cat.products ? cat.products.length : 0;
            const isActive = cat.id === activeCategory;
            return `
                <button
                    class="cat-nav-item${isActive ? ' active' : ''}"
                    onclick="selectCategory(${cat.id})"
                    aria-current="${isActive ? 'page' : 'false'}"
                >
                    <span class="mat-icon${isActive ? ' mat-icon-filled' : ''}">${icon}</span>
                    <span class="cat-nav-item-name">${cat.name}</span>
                    <span class="cat-nav-item-count">${count}</span>
                </button>
            `;
        }).join('');
    }

    function selectCategory(id) {
        activeCategory = id;
        renderCategories();
        renderProducts(id);
        // Close mobile sidebar on selection
        const sidebar = document.querySelector('.kiosk-sidebar');
        if (sidebar.classList.contains('mobile-open')) toggleMobileSidebar();
        const cat = menuData.find(c => c.id === id);
        apiFetch('/analiticas/eventos', {
            method: 'POST',
            body: JSON.stringify({ event_type: 'category_viewed', data: { category_id: id, category_name: cat?.name }, session_id: getSessionId() })
        }).catch(() => {});
    }

    // ===== RENDER PRODUCTS (bento grid) =====

    function renderProducts(categoryId) {
        const cat = menuData.find(c => c.id === categoryId);
        const products = cat ? cat.products : [];
        const catSlug = getCategorySlug(cat?.name);
        const catIcon = getCategoryIcon(cat?.name);
        const gradient = getCategoryGradient(catSlug);

        // Update category header
        document.getElementById('category-title').textContent = cat ? cat.name : '';
        document.getElementById('category-desc').textContent = cat ? (cat.description || '') : '';

        const el = document.getElementById('products');
        if (!products || products.length === 0) {
            el.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:60px 20px;color:var(--k-on-surface-muted);font-size:0.95rem;">No items in this category.</div>`;
            return;
        }

        el.innerHTML = products.map((p, i) => {
            const cardSize = p.card_size || 'standard';
            const usesWideLayout = !!p.is_featured || cardSize === 'wide';
            const showFeaturedBadge = !!p.is_featured;
            const iconSize = usesWideLayout ? '56px' : '42px';
            const imageHtml = p.image_url
                ? `<img src="${p.image_url}" alt="${p.name}" loading="lazy">`
                : `<div class="card-image-placeholder" style="background:${gradient};">
                       <span class="mat-icon mat-icon-filled" style="color:rgba(255,255,255,0.6);font-size:${iconSize};">${catIcon}</span>
                   </div>`;

            if (usesWideLayout) {
                return `
                    <div class="product-card product-card-featured" onclick="openCustomization(${categoryId}, ${p.id})" style="animation-delay:0s;">
                        <div class="card-image-wrap">
                            ${imageHtml}
                        </div>
                        <div class="card-body">
                            <div>
                                ${showFeaturedBadge ? `<div class="p-badge">
                                    <span class="mat-icon" style="font-size:13px;font-variation-settings:'FILL' 1;">star</span>
                                    Featured
                                </div>` : ''}
                                <div class="p-name">${p.name}</div>
                                <div class="p-desc">${p.description || ''}</div>
                            </div>
                            <div>
                                <div class="p-price">$${parseFloat(p.price).toFixed(2)}</div>
                                <button class="card-add-btn" tabindex="-1" aria-label="Add ${p.name}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                                    Add to Order
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                return `
                    <div class="product-card product-card-${cardSize === 'tall' ? 'tall' : 'standard'}" onclick="openCustomization(${categoryId}, ${p.id})" style="animation-delay:${i * 0.05}s;">
                        <div class="card-image-wrap">
                            ${imageHtml}
                        </div>
                        <div class="card-body">
                            <div class="p-name">${p.name}</div>
                            <div class="p-desc">${p.description || ''}</div>
                            <div class="p-bottom">
                                <span class="p-price">$${parseFloat(p.price).toFixed(2)}</span>
                                <button class="card-add-btn" tabindex="-1" aria-label="Add ${p.name}"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg></button>
                            </div>
                            <div class="p-prep-time">
                                <span class="mat-icon">schedule</span>
                                ${p.preparation_time_minutes || 5} min prep
                            </div>
                        </div>
                    </div>
                `;
            }
        }).join('');
    }

    // ===== CUSTOMIZATION MODAL =====

    function openCustomization(catId, prodId) {
        const cat = menuData.find(c => c.id === catId);
        const product = cat.products.find(p => p.id === prodId);
        if (!product) return;
        selectedProduct = product;
        modalQty = 1;

        const catSlug = getCategorySlug(cat?.name);
        const catIcon = getCategoryIcon(cat?.name);
        const gradient = getCategoryGradient(catSlug);

        // Product image in modal
        const imageWrap = document.getElementById('modal-product-image-wrap');
        if (product.image_url) {
            imageWrap.innerHTML = `<img src="${product.image_url}" alt="${product.name}">`;
        } else {
            imageWrap.innerHTML = `
                <div class="card-image-placeholder" style="background:${gradient}; position:absolute; inset:0;">
                    <span class="mat-icon mat-icon-filled" style="font-size:52px; color:rgba(255,255,255,0.6);">${catIcon}</span>
                </div>
            `;
        }

        document.getElementById('modal-product-name').textContent = product.name;
        document.getElementById('modal-product-desc').textContent = product.description || '';
        document.getElementById('modal-product-price').textContent = `$${parseFloat(product.price).toFixed(2)}`;
        document.getElementById('modal-qty').textContent = '1';

        const customsEl = document.getElementById('modal-customizations');
        const customs = product.customizations || [];
        if (customs.length > 0) {
            const grouped = {};
            customs.forEach(c => {
                const group = c.type === 'remove' ? 'Remove' : c.type === 'add' ? 'Add Extra' : c.type === 'size' ? 'Size' : 'Sides';
                if (!grouped[group]) grouped[group] = [];
                grouped[group].push(c);
            });

            let html = '';
            for (const [group, items] of Object.entries(grouped)) {
                html += `<div class="custom-section-title">${group}</div>`;
                items.forEach(c => {
                    const priceLabel = parseFloat(c.price_modifier) > 0
                        ? `<span class="custom-option-price">+$${parseFloat(c.price_modifier).toFixed(2)}</span>`
                        : `<span class="custom-option-free">FREE</span>`;
                    html += `
                        <div class="custom-option" onclick="toggleCustom(this)" data-id="${c.id}" data-name="${c.name}" data-type="${c.type}" data-price="${c.price_modifier}">
                            <div class="custom-toggle"></div>
                            <div style="flex:1;">
                                <div class="custom-option-label">${c.name}</div>
                            </div>
                            ${priceLabel}
                        </div>
                    `;
                });
            }
            customsEl.innerHTML = html;
        } else {
            customsEl.innerHTML = `<div style="text-align:center;color:var(--k-on-surface-muted);padding:26px 0;font-size:0.88rem;">No customizations available for this item.</div>`;
        }

        document.getElementById('customization-modal').classList.remove('hidden');
    }

    function toggleCustom(el) {
        const toggle = el.querySelector('.custom-toggle');
        toggle.classList.toggle('checked');
    }

    function closeModal() {
        document.getElementById('customization-modal').classList.add('hidden');
        selectedProduct = null;
    }

    function changeQty(delta) {
        modalQty = Math.max(1, Math.min(99, modalQty + delta));
        document.getElementById('modal-qty').textContent = modalQty;
    }

    function addToCart() {
        const options = document.querySelectorAll('#modal-customizations .custom-option');
        const modifications = { add: [], remove: [], size: [], side: [] };
        let extraCost = 0;

        options.forEach(opt => {
            if (opt.querySelector('.custom-toggle.checked')) {
                const type = opt.dataset.type;
                const name = opt.dataset.name;
                const price = parseFloat(opt.dataset.price);
                modifications[type].push(name);
                extraCost += price;
            }
        });

        Object.keys(modifications).forEach(k => { if (modifications[k].length === 0) delete modifications[k]; });

        const unitPrice = parseFloat(selectedProduct.price) + extraCost;
        cart.push({
            product_id: selectedProduct.id,
            product_name: selectedProduct.name,
            base_price: parseFloat(selectedProduct.price),
            unit_price_with_extras: unitPrice,
            quantity: modalQty,
            modifications: Object.keys(modifications).length > 0 ? modifications : null,
            unit_total: unitPrice * modalQty
        });

        closeModal();
        renderCart();
        showToast(selectedProduct.name, modalQty);

        apiFetch('/analiticas/eventos', {
            method: 'POST',
            body: JSON.stringify({ event_type: 'item_added_to_cart', data: { product_id: selectedProduct.id, product_name: selectedProduct.name }, session_id: getSessionId() })
        }).catch(() => {});
    }

    // ===== CART DRAWER TOGGLE =====

    function toggleCartDrawer() {
        cartDrawerOpen = !cartDrawerOpen;
        document.getElementById('cart-drawer').classList.toggle('open', cartDrawerOpen);
        document.getElementById('cart-drawer-overlay').classList.toggle('open', cartDrawerOpen);
        document.getElementById('cart-drawer').setAttribute('aria-hidden', String(!cartDrawerOpen));
    }

    // ===== RENDER CART (bottom bar + drawer) =====

    function renderCart() {
        const drawerItemsEl = document.getElementById('drawer-items');
        const drawerFooterEl = document.getElementById('drawer-footer');
        const cartBarEl = document.getElementById('cart-bar');
        const cartBarBadge = document.getElementById('cart-bar-badge');
        const cartBarCount = document.getElementById('cart-bar-count');
        const cartBarSubtotal = document.getElementById('cart-bar-subtotal');

        if (cart.length === 0) {
            // Hide cart bar
            cartBarEl.classList.remove('visible');
            cartBarBadge.classList.add('hidden');

            // Reset drawer
            drawerItemsEl.innerHTML = `
                <div class="drawer-empty">
                    <span class="mat-icon">shopping_basket</span>
                    <div class="drawer-empty-text">Tap an item to start<br>building your meal</div>
                </div>`;
            drawerFooterEl.classList.add('hidden');

            // Close drawer if open
            if (cartDrawerOpen) toggleCartDrawer();
            return;
        }

        const totalQty = cart.reduce((s, i) => s + i.quantity, 0);
        const subtotal = cart.reduce((s, item) => s + item.unit_total, 0);
        const tax = subtotal * 0.08;
        const total = subtotal + tax;

        // Update bottom bar
        cartBarEl.classList.add('visible');
        cartBarBadge.textContent = totalQty;
        cartBarBadge.classList.remove('hidden');
        popBadge(cartBarBadge);
        cartBarCount.textContent = `${totalQty} item${totalQty !== 1 ? 's' : ''}`;
        cartBarSubtotal.textContent = `$${subtotal.toFixed(2)}`;

        // Render drawer items with +/- stepper
        drawerItemsEl.innerHTML = cart.map((item, i) => {
            let modsText = '';
            if (item.modifications) {
                const parts = [];
                if (item.modifications.remove) parts.push(item.modifications.remove.join(', '));
                if (item.modifications.add) parts.push(item.modifications.add.join(', '));
                if (item.modifications.size) parts.push(item.modifications.size.join(', '));
                modsText = parts.join(' &bull; ');
            }
            // Minus button: if quantity is 1, show trash icon (removes item entirely)
            const minusIcon = item.quantity <= 1
                ? `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>`
                : `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>`;
            return `
                <div class="drawer-item">
                    <div class="drawer-item-info">
                        <div class="drawer-item-name">${item.product_name}</div>
                        ${modsText ? `<div class="drawer-item-mods">${modsText}</div>` : ''}
                    </div>
                    <div class="drawer-item-right">
                        <div class="drawer-item-price">$${item.unit_total.toFixed(2)}</div>
                        <div class="drawer-qty-stepper">
                            <button class="drawer-qty-btn${item.quantity <= 1 ? ' remove-last' : ''}" onclick="updateCartQty(${i}, -1)" aria-label="${item.quantity <= 1 ? 'Remove' : 'Decrease'}">${minusIcon}</button>
                            <span class="drawer-qty-val">${item.quantity}</span>
                            <button class="drawer-qty-btn" onclick="updateCartQty(${i}, 1)" aria-label="Increase"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg></button>
                        </div>
                    </div>
                </div>`;
        }).join('');

        // Update drawer footer
        document.getElementById('drawer-subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('drawer-tax').textContent = `$${tax.toFixed(2)}`;
        document.getElementById('drawer-total').textContent = `$${total.toFixed(2)}`;
        drawerFooterEl.classList.remove('hidden');
    }

    function updateCartQty(index, delta) {
        const item = cart[index];
        if (!item) return;
        item.quantity += delta;
        if (item.quantity <= 0) {
            cart.splice(index, 1);
        } else {
            item.unit_total = item.unit_price_with_extras * item.quantity;
        }
        renderCart();
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function clearCart() {
        cart = [];
        renderCart();
    }

    // ===== PAYMENT =====

    function goToPayment() {
        if (cart.length === 0) return;
        const subtotal = cart.reduce((s, item) => s + item.unit_total, 0);
        const total = subtotal + (subtotal * 0.08);
        document.getElementById('payment-total').textContent = `$${total.toFixed(2)}`;

        const summaryEl = document.getElementById('payment-summary-items');
        summaryEl.innerHTML = cart.map(item => `
            <div class="payment-summary-item">
                <span><span class="qty-tag">${item.quantity}x</span>${item.product_name}</span>
                <span>$${item.unit_total.toFixed(2)}</span>
            </div>
        `).join('');

        document.getElementById('menu-screen').classList.add('hidden');
        document.getElementById('payment-screen').classList.remove('hidden');
        selectedPayment = null;
        document.querySelectorAll('.pay-method').forEach(el => el.classList.remove('selected'));
        document.getElementById('place-order-btn').disabled = true;

        apiFetch('/analiticas/eventos', {
            method: 'POST',
            body: JSON.stringify({ event_type: 'payment_screen_viewed', data: { total }, session_id: getSessionId() })
        }).catch(() => {});
    }

    function goToMenu() {
        document.getElementById('payment-screen').classList.add('hidden');
        document.getElementById('menu-screen').classList.remove('hidden');
    }

    function selectPayment(method, el) {
        selectedPayment = method;
        document.querySelectorAll('.pay-method').forEach(e => e.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('place-order-btn').disabled = false;
    }

    async function placeOrder() {
        const btn = document.getElementById('place-order-btn');
        btn.disabled = true;
        btn.innerHTML = 'Placing Order<span class="loading-dots"></span>';

        const subtotal = cart.reduce((s, item) => s + item.unit_total, 0);
        const tax = subtotal * 0.08;

        const orderData = {
            items: cart.map(item => ({
                product_id: item.product_id,
                product_name: item.product_name,
                quantity: item.quantity,
                modifications: item.modifications,
                unit_price: item.base_price,
                subtotal: item.unit_total
            })),
            payment_method: selectedPayment,
            subtotal: subtotal,
            tax: tax,
            total: subtotal + tax,
            customer_name: document.getElementById('customer-name').value || null,
            notes: document.getElementById('order-notes').value || null
        };

        try {
            const result = await apiFetch('/pedidos', { method: 'POST', body: JSON.stringify(orderData) });
            const orderNum = result.order_number || result.data?.order_number;
            if (orderNum) {
                showConfirmation(orderNum);
            } else {
                btn.disabled = false;
                btn.textContent = 'Place Order';
            }
        } catch (e) {
            btn.disabled = false;
            btn.textContent = 'Place Order';
        }
    }

    function showConfirmation(orderNumber) {
        document.getElementById('payment-screen').classList.add('hidden');
        document.getElementById('confirmation-screen').classList.remove('hidden');
        document.getElementById('confirm-order-number').textContent = orderNumber;
        // Hide status badge initially — only show when it advances past pending
        const statusEl = document.getElementById('confirm-status');
        statusEl.textContent = '';
        statusEl.className = 'confirm-status hidden';

        spawnConfetti();

        if (statusPollInterval) clearInterval(statusPollInterval);
        statusPollInterval = setInterval(async () => {
            try {
                const res = await apiFetch(`/pedidos/${orderNumber}/estado`);
                const status = res.data?.status || res.status;
                if (status && status !== 'pending') {
                    const el = document.getElementById('confirm-status');
                    el.textContent = status.toUpperCase();
                    el.className = `confirm-status ${status}`;
                    el.classList.remove('hidden');
                    if (status === 'delivered' || status === 'cancelled') {
                        clearInterval(statusPollInterval);
                    }
                }
            } catch (e) {}
        }, 4000);
    }

    function newOrder() {
        if (statusPollInterval) clearInterval(statusPollInterval);
        cart = [];
        selectedPayment = null;
        cartDrawerOpen = false;
        document.getElementById('cart-drawer').classList.remove('open');
        document.getElementById('cart-drawer-overlay').classList.remove('open');
        document.getElementById('customer-name').value = '';
        document.getElementById('order-notes').value = '';
        document.getElementById('confirmation-screen').classList.add('hidden');
        document.getElementById('menu-screen').classList.remove('hidden');
        document.getElementById('place-order-btn').textContent = 'Place Order';
        renderCart();
        document.getElementById('confetti-wrap').innerHTML = '';
    }

    // ===== SESSION ID =====

    function getSessionId() {
        if (!window._sessionId) {
            window._sessionId = 'sess_' + Math.random().toString(36).substring(2, 10);
        }
        return window._sessionId;
    }

    // ===== ANIMATION HELPERS =====

    function popBadge(el) {
        el.classList.remove('pop');
        void el.offsetWidth;
        el.classList.add('pop');
    }

    function showToast(productName, qty) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.innerHTML = `
            <span class="toast-icon">&#10003;</span>
            <div>
                <div class="toast-text">${qty > 1 ? qty + 'x ' : ''}${productName}</div>
                <div class="toast-sub">Added to your order</div>
            </div>
        `;
        container.appendChild(toast);
        setTimeout(() => {
            toast.classList.add('removing');
            toast.addEventListener('animationend', () => toast.remove());
        }, 2600);
    }

    function spawnConfetti() {
        const wrap = document.getElementById('confetti-wrap');
        wrap.innerHTML = '';
        const colors = ['#ff7941','#a33800','#f8a91f','#1a7f52','#ffc5a5','#954400','#f9f6f5'];
        const count = 65;
        for (let i = 0; i < count; i++) {
            const dot = document.createElement('div');
            dot.className = 'confetti-dot';
            const size = Math.random() * 8 + 5;
            dot.style.cssText = `
                left: ${Math.random() * 100}%;
                width: ${size}px;
                height: ${size}px;
                background: ${colors[Math.floor(Math.random() * colors.length)]};
                animation-duration: ${Math.random() * 2.5 + 2}s;
                animation-delay: ${Math.random() * 1.2}s;
                border-radius: ${Math.random() > 0.5 ? '50%' : '2px'};
                opacity: ${Math.random() * 0.6 + 0.4};
            `;
            wrap.appendChild(dot);
        }
        setTimeout(() => { wrap.innerHTML = ''; }, 6000);
    }

    // ===== SIDEBAR RESIZE (desktop) =====
    (function initSidebarResize() {
        const handle = document.getElementById('sidebar-resize-handle');
        const kiosk = document.querySelector('.kiosk');
        const cartBar = document.getElementById('cart-bar');
        if (!handle) return;

        let dragging = false;
        handle.addEventListener('mousedown', e => {
            e.preventDefault();
            dragging = true;
            handle.classList.add('active');
            document.body.style.cursor = 'col-resize';
            document.body.style.userSelect = 'none';
        });
        document.addEventListener('mousemove', e => {
            if (!dragging) return;
            const w = Math.min(Math.max(e.clientX, 180), 500);
            kiosk.style.setProperty('--sidebar-width', w + 'px');
            if (cartBar) cartBar.style.left = w + 'px';
        });
        document.addEventListener('mouseup', () => {
            if (!dragging) return;
            dragging = false;
            handle.classList.remove('active');
            document.body.style.cursor = '';
            document.body.style.userSelect = '';
        });
    })();

    // ===== MOBILE SIDEBAR TOGGLE =====
    function toggleMobileSidebar() {
        const sidebar = document.querySelector('.kiosk-sidebar');
        const overlay = document.getElementById('mobile-sidebar-overlay');
        sidebar.classList.toggle('mobile-open');
        overlay.classList.toggle('visible');
    }
</script>
@endpush
