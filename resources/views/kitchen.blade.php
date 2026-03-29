@extends('layouts.app')
@section('title', 'Kitchen Display')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
<style>
    /* ===== DESIGN TOKENS — mirrors kiosk.blade.php ===== */
    :root {
        --k-primary:             #a33800;
        --k-primary-container:   #ff7941;
        --k-secondary-container: #ffc5a5;
        --k-surface:             #f9f6f5;
        --k-surface-variant:     #efe0d8;
        --k-on-surface:          #2f2e2e;
        --k-on-surface-muted:    #7a6e6a;
        --k-on-surface-faint:    #c2b8b4;
        --k-border:              rgba(163,56,0,0.10);
        --k-border-strong:       rgba(163,56,0,0.20);
        --k-shadow-sm:           rgba(47,46,46,0.06);
        --k-shadow:              rgba(47,46,46,0.10);
        --k-shadow-md:           rgba(47,46,46,0.16);
        --k-success:             #10B981;
        --k-success-bg:          rgba(16,185,129,0.12);
        --k-info:                #3B82F6;
        --k-info-bg:             rgba(59,130,246,0.12);
        --k-warning:             #F59E0B;
        --k-warning-bg:          rgba(245,158,11,0.12);
        --k-error:               #dc2626;
        --k-error-bg:            rgba(220,38,38,0.10);
        --k-radius-sm:           10px;
        --k-radius-md:           16px;
        --k-radius-lg:           24px;
        --k-radius-full:         9999px;
        --k-spring:              cubic-bezier(0.34, 1.56, 0.64, 1);
        --k-ease-out:            cubic-bezier(0.16, 1, 0.3, 1);
        /* Override layout dark vars so the shell inherits light colours */
        --bg-base:               #f9f6f5;
        --text-primary:          #2f2e2e;
        --text-secondary:        #7a6e6a;
        --text-muted:            #c2b8b4;
        --border:                rgba(163,56,0,0.10);
        --accent-primary:        #ff7941;
    }

    /* ===== RESET ===== */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { font-size: 16px; -webkit-tap-highlight-color: transparent; }
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--k-surface);
        color: var(--k-on-surface);
        -webkit-font-smoothing: antialiased;
    }
    button { cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; }
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--k-on-surface-faint); border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--k-on-surface-muted); }

    /* ===== MATERIAL ICONS ===== */
    .mat-icon {
        font-family: 'Material Symbols Outlined';
        font-weight: normal;
        font-style: normal;
        font-size: 20px;
        line-height: 1;
        letter-spacing: normal;
        text-transform: none;
        display: inline-block;
        white-space: nowrap;
        word-wrap: normal;
        direction: ltr;
        -webkit-font-smoothing: antialiased;
        user-select: none;
        vertical-align: middle;
    }
    .mat-icon-filled { font-variation-settings: 'FILL' 1; }

    /* ===== KDS SHELL ===== */
    .kds {
        display: flex;
        flex-direction: column;
        height: 100vh;
        overflow: hidden;
        background: var(--k-surface);
    }

    /* ===== NEW-ORDER FLASH OVERLAY ===== */
    .kds-flash {
        position: fixed;
        inset: 0;
        pointer-events: none;
        z-index: 9999;
        border: 4px solid var(--k-primary-container);
        border-radius: 0;
        opacity: 0;
        box-shadow: inset 0 0 80px rgba(255,121,65,0.15);
    }
    .kds-flash.flash-active {
        animation: newOrderFlash 1.4s var(--k-ease-out) forwards;
    }
    @keyframes newOrderFlash {
        0%   { opacity: 0; }
        12%  { opacity: 1; }
        50%  { opacity: 0.5; }
        100% { opacity: 0; }
    }

    /* ===== TOP BAR ===== */
    .kds-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 28px;
        height: 72px;
        background: #ffffff;
        border-bottom: 1px solid var(--k-border);
        box-shadow: 0 1px 8px var(--k-shadow-sm);
        flex-shrink: 0;
        z-index: 10;
        gap: 20px;
    }

    /* Brand — matches kiosk.blade.php header-brand pattern exactly */
    .kds-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }
    .kds-logo {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, var(--k-primary-container), var(--k-primary));
        border-radius: var(--k-radius-sm);
        display: grid;
        place-items: center;
        flex-shrink: 0;
        box-shadow: 0 4px 14px rgba(163,56,0,0.28);
    }
    .kds-logo .mat-icon {
        font-size: 22px;
        color: #ffffff;
        font-variation-settings: 'FILL' 1;
    }
    .kds-brand-text {
        display: flex;
        flex-direction: column;
        gap: 1px;
    }
    .kds-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--k-primary);
        letter-spacing: -0.3px;
        line-height: 1.15;
        white-space: nowrap;
    }
    .kds-title span {
        color: var(--k-primary-container);
    }
    .kds-subtitle {
        font-size: 0.65rem;
        color: var(--k-on-surface-muted);
        letter-spacing: 1.5px;
        text-transform: uppercase;
        font-weight: 700;
    }

    /* Right side: clock + live indicator */
    .kds-topbar-right {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-shrink: 0;
    }
    .kds-clock {
        font-size: 1.5rem;
        font-weight: 300;
        letter-spacing: 2px;
        color: var(--k-on-surface-muted);
        font-variant-numeric: tabular-nums;
    }
    .kds-live {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 7px 14px;
        border-radius: var(--k-radius-full);
        background: var(--k-success-bg);
        border: 1px solid rgba(16,185,129,0.25);
    }
    .kds-live-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--k-success);
        animation: livePulse 2s ease-in-out infinite;
        flex-shrink: 0;
    }
    @keyframes livePulse {
        0%, 100% { opacity: 1; box-shadow: 0 0 8px rgba(16,185,129,0.7); }
        50%       { opacity: 0.35; box-shadow: none; }
    }
    .kds-live-text {
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--k-success);
        letter-spacing: 1.2px;
        text-transform: uppercase;
    }

    /* ===== KPI STATS ROW ===== */
    .kds-kpi-row {
        display: flex;
        gap: 12px;
        padding: 14px 28px 0;
        background: #ffffff;
        flex-shrink: 0;
    }
    .kds-kpi-card {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 18px;
        background: #ffffff;
        border: 1.5px solid var(--k-border);
        border-radius: var(--k-radius-md);
        box-shadow: 0 2px 10px var(--k-shadow-sm);
        transition: box-shadow 0.2s, transform 0.2s;
        overflow: hidden;
        position: relative;
    }
    .kds-kpi-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        border-radius: 4px 0 0 4px;
    }
    .kds-kpi-card.kpi-pending::before   { background: var(--k-warning); }
    .kds-kpi-card.kpi-preparing::before { background: var(--k-info); }
    .kds-kpi-card.kpi-ready::before     { background: var(--k-success); }
    .kds-kpi-card:hover {
        box-shadow: 0 4px 18px var(--k-shadow);
        transform: translateY(-1px);
    }
    .kpi-accent-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .kpi-accent-dot.pending   { background: var(--k-warning);  box-shadow: 0 0 8px rgba(245,158,11,0.55); }
    .kpi-accent-dot.preparing { background: var(--k-info);     box-shadow: 0 0 8px rgba(59,130,246,0.55); }
    .kpi-accent-dot.ready     { background: var(--k-success);  box-shadow: 0 0 8px rgba(16,185,129,0.55); }
    .kpi-body {
        display: flex;
        flex-direction: column;
        gap: 1px;
        flex: 1;
    }
    .kpi-number {
        font-size: 1.75rem;
        font-weight: 800;
        line-height: 1;
        font-variant-numeric: tabular-nums;
        letter-spacing: -0.5px;
    }
    .kds-kpi-card.kpi-pending   .kpi-number { color: var(--k-warning); }
    .kds-kpi-card.kpi-preparing .kpi-number { color: var(--k-info); }
    .kds-kpi-card.kpi-ready     .kpi-number { color: var(--k-success); }
    .kpi-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.1px;
        color: var(--k-on-surface-muted);
    }

    /* ===== FILTER SEGMENTED CONTROL ===== */
    .kds-filters-wrap {
        padding: 14px 28px 14px;
        border-bottom: 1px solid var(--k-border);
        background: #ffffff;
        flex-shrink: 0;
        /* Continues directly below the kpi row without extra border duplication */
        box-shadow: 0 2px 8px var(--k-shadow-sm);
    }
    .kds-filters {
        display: inline-flex;
        position: relative;
        background: var(--k-surface);
        border: 1.5px solid var(--k-border);
        border-radius: var(--k-radius-full);
        padding: 4px;
        gap: 2px;
    }
    .kds-filter-slider {
        position: absolute;
        top: 4px;
        height: calc(100% - 8px);
        background: var(--k-primary-container);
        border-radius: var(--k-radius-full);
        transition: left 0.3s var(--k-spring), width 0.3s var(--k-spring);
        pointer-events: none;
        z-index: 0;
        box-shadow: 0 2px 10px rgba(255,121,65,0.35);
    }
    .kds-filter {
        position: relative;
        z-index: 1;
        padding: 9px 22px;
        border: none;
        background: transparent;
        color: var(--k-on-surface-muted);
        border-radius: var(--k-radius-full);
        font-size: 0.83rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        transition: color 0.25s;
        white-space: nowrap;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .kds-filter.active {
        color: #ffffff;
    }
    .kds-filter-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 22px;
        height: 22px;
        padding: 0 6px;
        background: rgba(47,46,46,0.09);
        border-radius: var(--k-radius-full);
        font-size: 0.72rem;
        font-weight: 800;
        color: var(--k-on-surface-muted);
        transition: background 0.25s, color 0.25s;
    }
    .kds-filter.active .kds-filter-count {
        background: rgba(255,255,255,0.30);
        color: #ffffff;
    }

    /* ===== ORDERS AREA ===== */
    .kds-orders-area {
        flex: 1;
        overflow-y: auto;
        padding: 24px 28px 32px;
        scrollbar-width: thin;
        scrollbar-color: var(--k-on-surface-faint) transparent;
    }
    .kds-orders-area::-webkit-scrollbar { width: 5px; }
    .kds-orders-area::-webkit-scrollbar-track { background: transparent; }
    .kds-orders-area::-webkit-scrollbar-thumb { background: var(--k-on-surface-faint); border-radius: 10px; }

    .kds-orders-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        align-content: start;
    }

    /* ===== ORDER CARD ===== */
    .order-card {
        background: #ffffff;
        border-radius: var(--k-radius-lg);
        border: 1.5px solid var(--k-border);
        border-left: 4px solid transparent;
        overflow: hidden;
        position: relative;
        transition: border-color 0.3s, box-shadow 0.3s, transform 0.22s var(--k-spring);
        animation: cardIn 0.45s var(--k-ease-out) both;
        box-shadow: 0 2px 12px var(--k-shadow-sm);
    }
    .order-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 32px var(--k-shadow);
    }
    .order-card:active {
        transform: translateY(-1px);
    }

    /* Stagger via nth-child */
    .order-card:nth-child(1)  { animation-delay: 0.00s; }
    .order-card:nth-child(2)  { animation-delay: 0.05s; }
    .order-card:nth-child(3)  { animation-delay: 0.10s; }
    .order-card:nth-child(4)  { animation-delay: 0.15s; }
    .order-card:nth-child(5)  { animation-delay: 0.20s; }
    .order-card:nth-child(6)  { animation-delay: 0.25s; }
    .order-card:nth-child(7)  { animation-delay: 0.30s; }
    .order-card:nth-child(8)  { animation-delay: 0.35s; }
    .order-card:nth-child(9)  { animation-delay: 0.40s; }
    .order-card:nth-child(10) { animation-delay: 0.45s; }
    .order-card:nth-child(11) { animation-delay: 0.50s; }
    .order-card:nth-child(12) { animation-delay: 0.55s; }

    @keyframes cardIn {
        from { opacity: 0; transform: translateY(20px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Left accent borders by status */
    .order-card.pending   { border-left-color: var(--k-warning); }
    .order-card.preparing { border-left-color: var(--k-info); }
    .order-card.ready     { border-left-color: var(--k-success); }

    /* Urgent — red tint and pulsing glow */
    .order-card.urgent-card {
        border-left-color: var(--k-error);
        background: rgba(220,38,38,0.025);
        animation: cardIn 0.45s var(--k-ease-out) both, urgentGlow 2.5s ease-in-out infinite;
    }
    @keyframes urgentGlow {
        0%, 100% { box-shadow: 0 2px 12px var(--k-shadow-sm); }
        50%       { box-shadow: 0 0 0 3px rgba(220,38,38,0.18), 0 10px 32px rgba(220,38,38,0.12); }
    }

    /* Card header tinted strip */
    .order-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 20px 14px 20px;
        border-bottom: 1px solid var(--k-border);
        position: relative;
    }
    .order-card.pending   .order-card-header { background: rgba(245,158,11,0.06); }
    .order-card.preparing .order-card-header { background: rgba(59,130,246,0.06); }
    .order-card.ready     .order-card-header { background: rgba(16,185,129,0.06); }
    .order-card.urgent-card .order-card-header { background: rgba(220,38,38,0.05); }

    .order-num {
        font-size: 2.1rem;
        font-weight: 800;
        letter-spacing: 1px;
        line-height: 1;
    }
    .order-card.pending   .order-num { color: var(--k-warning); }
    .order-card.preparing .order-num { color: var(--k-info); }
    .order-card.ready     .order-num { color: var(--k-success); }
    .order-card.urgent-card .order-num { color: var(--k-error); }

    .order-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
    }
    .order-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: var(--k-radius-full);
        font-size: 0.62rem;
        font-weight: 800;
        letter-spacing: 1.3px;
        text-transform: uppercase;
        border: 1px solid transparent;
    }
    .order-badge.pending   { background: var(--k-warning-bg); color: #92650a; border-color: rgba(245,158,11,0.28); }
    .order-badge.preparing { background: var(--k-info-bg);    color: #1d4ed8; border-color: rgba(59,130,246,0.28); }
    .order-badge.ready     { background: var(--k-success-bg); color: #047857; border-color: rgba(16,185,129,0.28); }

    /* --- Timer ring --- */
    .timer-ring-wrap {
        display: flex;
        align-items: center;
        gap: 9px;
    }
    .timer-ring-text-wrap {
        position: relative;
        width: 44px;
        height: 44px;
        flex-shrink: 0;
    }
    .timer-ring-svg {
        position: absolute;
        inset: 0;
        width: 44px;
        height: 44px;
        transform: rotate(-90deg);
    }
    .timer-ring-bg-circle {
        fill: none;
        stroke: var(--k-surface-variant);
        stroke-width: 3.5;
    }
    .timer-ring-progress {
        fill: none;
        stroke-width: 3.5;
        stroke-linecap: round;
        stroke-dasharray: 94.2;
        stroke-dashoffset: 0;
        transition: stroke-dashoffset 1s linear, stroke 0.5s;
    }
    .order-card.pending   .timer-ring-progress.normal { stroke: var(--k-warning); }
    .order-card.preparing .timer-ring-progress.normal { stroke: var(--k-info); }
    .order-card.ready     .timer-ring-progress.normal { stroke: var(--k-success); }
    .timer-ring-progress.urgent {
        stroke: var(--k-error);
        filter: drop-shadow(0 0 4px rgba(220,38,38,0.7));
        animation: ringUrgentPulse 1.5s ease-in-out infinite;
    }
    @keyframes ringUrgentPulse {
        0%, 100% { filter: drop-shadow(0 0 3px rgba(220,38,38,0.6)); }
        50%       { filter: drop-shadow(0 0 7px rgba(220,38,38,0.95)); }
    }
    .timer-ring-label {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: 0;
    }
    .timer-ring-label.normal { color: var(--k-on-surface-muted); }
    .timer-ring-label.urgent { color: var(--k-error); }

    .order-timer {
        font-size: 0.9rem;
        letter-spacing: 0.8px;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
    }
    .order-timer.urgent { color: var(--k-error); }
    .order-timer.normal { color: var(--k-on-surface-muted); }

    /* Customer row */
    .order-customer {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 9px 20px;
        font-size: 0.83rem;
        font-weight: 600;
        color: var(--k-on-surface-muted);
        border-bottom: 1px solid var(--k-border);
        background: rgba(249,246,245,0.5);
    }
    .order-customer .mat-icon {
        font-size: 16px;
        color: var(--k-on-surface-faint);
    }

    /* Items list */
    .order-items {
        padding: 10px 20px 12px;
    }
    .order-item {
        padding: 9px 0;
        border-bottom: 1px solid rgba(163,56,0,0.06);
    }
    .order-item:last-child { border-bottom: none; }
    .order-item-row {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    /* Quantity badge — orange rounded square matching kiosk cart style */
    .order-item-qty {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 30px;
        height: 26px;
        padding: 0 7px;
        background: var(--k-primary-container);
        color: #ffffff;
        border-radius: 7px;
        font-size: 0.82rem;
        font-weight: 800;
        letter-spacing: 0.3px;
        font-variant-numeric: tabular-nums;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(255,121,65,0.30);
    }
    .order-item-name {
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--k-on-surface);
        line-height: 1.3;
    }

    /* ===== MODIFICATION TAGS — highly visible ===== */
    .order-item-mods {
        margin-top: 7px;
        padding-left: 40px;
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    .mod-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 12px;
        border-radius: var(--k-radius-full);
        font-size: 0.85rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        line-height: 1;
        border: 1.5px solid transparent;
    }
    .mod-tag-icon {
        font-style: normal;
        font-size: 0.85em;
        flex-shrink: 0;
    }
    .mod-tag.remove {
        background: rgba(220,38,38,0.10);
        color: var(--k-error);
        border-color: rgba(220,38,38,0.25);
        text-decoration: line-through;
        text-decoration-color: rgba(220,38,38,0.5);
        text-decoration-thickness: 1.5px;
    }
    .mod-tag.add {
        background: var(--k-success-bg);
        color: #047857;
        border-color: rgba(16,185,129,0.28);
    }
    .mod-tag.size {
        background: var(--k-info-bg);
        color: #1d4ed8;
        border-color: rgba(59,130,246,0.25);
    }

    /* ===== NOTES — warm amber callout ===== */
    .order-notes {
        margin: 0 20px 12px;
        padding: 12px 16px;
        background: #FEF3C7;
        border: 1px solid rgba(245,158,11,0.30);
        border-left: 3px solid var(--k-warning);
        border-radius: var(--k-radius-sm);
        font-size: 0.85rem;
        color: #78350f;
        font-weight: 500;
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }
    .order-notes-icon {
        flex-shrink: 0;
        color: var(--k-warning);
        margin-top: 1px;
    }
    .order-notes-label {
        font-weight: 800;
        font-size: 0.67rem;
        letter-spacing: 1.3px;
        text-transform: uppercase;
        color: #92650a;
        margin-bottom: 4px;
    }

    /* ===== ACTION BUTTONS ===== */
    .order-actions {
        display: flex;
        gap: 8px;
        padding: 12px 20px 18px;
    }
    .order-btn {
        flex: 1;
        height: 56px;
        border: none;
        border-radius: 12px;
        font-size: 0.84rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: opacity 0.2s, transform 0.15s var(--k-spring), box-shadow 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .order-btn:active   { transform: scale(0.97); box-shadow: none !important; }
    .order-btn:hover    { opacity: 0.88; }
    .order-btn:disabled { opacity: 0.5; pointer-events: none; }

    .order-btn-start {
        background: linear-gradient(135deg, #F59E0B, #D97706);
        color: #1a0f00;
        box-shadow: 0 4px 16px rgba(245,158,11,0.30);
    }
    .order-btn-ready {
        background: linear-gradient(135deg, #10B981, #059669);
        color: #ffffff;
        box-shadow: 0 4px 16px rgba(16,185,129,0.28);
    }
    .order-btn-deliver {
        background: linear-gradient(135deg, #3B82F6, #2563EB);
        color: #ffffff;
        box-shadow: 0 4px 16px rgba(59,130,246,0.25);
    }
    .order-btn-cancel {
        background: var(--k-surface);
        color: var(--k-error);
        border: 1.5px solid rgba(220,38,38,0.20);
        flex: none;
        width: 80px;
        font-size: 0.72rem;
        letter-spacing: 1px;
    }
    .order-btn-cancel:hover {
        background: var(--k-error-bg);
        border-color: rgba(220,38,38,0.40);
    }

    /* Loading spinner */
    .btn-spinner {
        width: 18px;
        height: 18px;
        border: 2.5px solid rgba(255,255,255,0.35);
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: spin 0.65s linear infinite;
        display: none;
        flex-shrink: 0;
    }
    .order-btn-start  .btn-spinner { border-color: rgba(26,15,0,0.25); border-top-color: #1a0f00; }
    .order-btn-cancel .btn-spinner { border-color: rgba(220,38,38,0.25); border-top-color: var(--k-error); }
    .order-btn.loading .btn-spinner { display: block; }
    .order-btn.loading .btn-label   { display: none; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ===== EMPTY STATE ===== */
    .kds-empty {
        grid-column: 1 / -1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 80px 20px;
        text-align: center;
    }
    .empty-icon-wrap {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(255,121,65,0.14), rgba(163,56,0,0.08));
        display: grid;
        place-items: center;
        margin-bottom: 28px;
        box-shadow: 0 4px 24px rgba(255,121,65,0.12);
        animation: emptyBreathe 3s ease-in-out infinite;
    }
    .empty-icon-wrap .mat-icon {
        font-size: 44px;
        color: var(--k-primary-container);
        font-variation-settings: 'FILL' 1;
    }
    @keyframes emptyBreathe {
        0%, 100% { transform: scale(1);    box-shadow: 0 4px 24px rgba(255,121,65,0.12); }
        50%       { transform: scale(1.06); box-shadow: 0 8px 36px rgba(255,121,65,0.22); }
    }
    .kds-empty-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--k-on-surface-muted);
        margin-bottom: 8px;
        letter-spacing: -0.2px;
    }
    .kds-empty-sub {
        font-size: 0.88rem;
        color: var(--k-on-surface-faint);
        letter-spacing: 0.3px;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .kds-orders-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 900px) {
        .kds-topbar { padding: 0 16px; height: 66px; gap: 12px; }
        .kds-clock  { font-size: 1.2rem; }
        .kds-kpi-row { padding: 12px 16px 0; gap: 8px; }
        .kpi-number  { font-size: 1.45rem; }
        .kds-filters-wrap { padding: 12px 16px 12px; }
        .kds-orders-area  { padding: 16px 16px 24px; }
        .kds-orders-grid  { gap: 14px; }
    }
    @media (max-width: 640px) {
        .kds-topbar       { flex-wrap: wrap; height: auto; padding: 12px 14px; gap: 10px; }
        .kds-topbar-right { gap: 10px; }
        .kds-subtitle     { display: none; }
        .kds-kpi-row      { padding: 10px 14px 0; gap: 8px; }
        .kds-kpi-card     { padding: 10px 14px; }
        .kpi-number       { font-size: 1.25rem; }
        .kpi-label        { font-size: 0.6rem; }
        .kds-orders-grid  { grid-template-columns: 1fr; }
        .kds-filters      { width: 100%; justify-content: space-between; }
        .kds-filter       { flex: 1; justify-content: center; padding: 9px 12px; }
        .kds-filters-wrap { padding: 10px 14px 10px; }
        .kds-orders-area  { padding: 14px 14px 24px; }
    }
</style>
@endpush

@section('content')
<!-- New-order flash overlay -->
<div class="kds-flash" id="kds-flash"></div>

<div class="kds">

    <!-- TOP BAR -->
    <div class="kds-topbar">
        <!-- Brand — mirrors kiosk.blade.php exactly -->
        <div class="kds-brand">
            <div class="kds-logo">
                <span class="mat-icon mat-icon-filled">restaurant</span>
            </div>
            <div class="kds-brand-text">
                <div class="kds-title">The Culinary <span>Concierge</span></div>
                <div class="kds-subtitle">Kitchen Display</div>
            </div>
        </div>

        <!-- Right: clock + live indicator -->
        <div class="kds-topbar-right">
            <div class="kds-clock" id="kds-clock">00:00</div>
            <div class="kds-live">
                <span class="kds-live-dot"></span>
                <span class="kds-live-text">Live</span>
            </div>
        </div>
    </div>

    <!-- KPI STAT CARDS -->
    <div class="kds-kpi-row">
        <div class="kds-kpi-card kpi-pending">
            <span class="kpi-accent-dot pending"></span>
            <div class="kpi-body">
                <div class="kpi-number" id="stat-pending">0</div>
                <div class="kpi-label">Pending</div>
            </div>
        </div>
        <div class="kds-kpi-card kpi-preparing">
            <span class="kpi-accent-dot preparing"></span>
            <div class="kpi-body">
                <div class="kpi-number" id="stat-preparing">0</div>
                <div class="kpi-label">Cooking</div>
            </div>
        </div>
        <div class="kds-kpi-card kpi-ready">
            <span class="kpi-accent-dot ready"></span>
            <div class="kpi-body">
                <div class="kpi-number" id="stat-ready">0</div>
                <div class="kpi-label">Ready</div>
            </div>
        </div>
    </div>

    <!-- SEGMENTED FILTER CONTROL -->
    <div class="kds-filters-wrap">
        <div class="kds-filters" id="kds-filters">
            <div class="kds-filter-slider" id="filter-slider"></div>
            <button class="kds-filter active" data-status="all"      onclick="filterOrders('all', this)">All<span class="kds-filter-count" id="fc-all">0</span></button>
            <button class="kds-filter"        data-status="pending"   onclick="filterOrders('pending', this)">Pending<span class="kds-filter-count" id="fc-pending">0</span></button>
            <button class="kds-filter"        data-status="preparing" onclick="filterOrders('preparing', this)">Preparing<span class="kds-filter-count" id="fc-preparing">0</span></button>
            <button class="kds-filter"        data-status="ready"     onclick="filterOrders('ready', this)">Ready<span class="kds-filter-count" id="fc-ready">0</span></button>
        </div>
    </div>

    <!-- ORDERS GRID -->
    <div class="kds-orders-area">
        <div class="kds-orders-grid" id="orders-grid">
            <div class="kds-empty">
                <div class="empty-icon-wrap">
                    <span class="mat-icon mat-icon-filled">room_service</span>
                </div>
                <div class="kds-empty-title">All Clear</div>
                <div class="kds-empty-sub">Waiting for incoming orders...</div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    let orders = [];
    let currentFilter = 'all';
    let refreshInterval;
    let timerInterval;
    let previousOrderCount = 0;
    const pendingUpdates = new Set();
    let renderScheduled = false;
    let isPolling = false;

    const RING_CIRCUMFERENCE = 138.2; // 2π × 22

    document.addEventListener('DOMContentLoaded', () => {
        updateClock();
        setInterval(updateClock, 1000);
        positionFilterSlider(document.querySelector('.kds-filter.active'));
        loadOrders();
        refreshInterval = setInterval(loadOrders, 5000);
        timerInterval = setInterval(updateTimers, 1000);
    });

    function updateClock() {
        const now = new Date();
        document.getElementById('kds-clock').textContent =
            now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
    }

    // Debounced render — batches multiple state changes into one DOM update
    function scheduleRender() {
        if (renderScheduled) return;
        renderScheduled = true;
        requestAnimationFrame(() => {
            renderScheduled = false;
            updateStats();
            updateFilterCounts();
            renderOrders();
        });
    }

    async function loadOrders() {
        if (isPolling) return;
        isPolling = true;

        try {
            const data = await apiFetch('/cocina/pedidos-pendientes');
            const newOrders = data.data || data;

            // Flash animation when new orders arrive
            if (newOrders.length > previousOrderCount && previousOrderCount !== 0) {
                triggerNewOrderFlash();
            }
            previousOrderCount = newOrders.length;

            // Merge with local optimistic state: for any order with a pending
            // update, keep the local version so we don't revert the UI
            if (pendingUpdates.size > 0) {
                const localMap = new Map(orders.map(o => [o.order_number, o]));
                orders = newOrders.map(serverOrder => {
                    if (pendingUpdates.has(serverOrder.order_number) && localMap.has(serverOrder.order_number)) {
                        return localMap.get(serverOrder.order_number);
                    }
                    return serverOrder;
                });
                // Keep locally-removed orders filtered out
                const serverNumbers = new Set(newOrders.map(o => o.order_number));
                orders = orders.filter(o => serverNumbers.has(o.order_number) || !pendingUpdates.has(o.order_number));
            } else {
                orders = newOrders;
            }

            scheduleRender();
        } catch (e) {
            console.error('Failed to load orders:', e);
        } finally {
            isPolling = false;
        }
    }

    function triggerNewOrderFlash() {
        const el = document.getElementById('kds-flash');
        el.classList.remove('flash-active');
        // Force reflow to restart animation
        void el.offsetWidth;
        el.classList.add('flash-active');
    }

    function updateStats() {
        const pendingCount   = orders.filter(o => o.status === 'pending').length;
        const preparingCount = orders.filter(o => o.status === 'preparing').length;
        const readyCount     = orders.filter(o => o.status === 'ready').length;
        const total          = Math.max(orders.length, 1);

        document.getElementById('stat-pending').textContent   = pendingCount;
        document.getElementById('stat-preparing').textContent = preparingCount;
        document.getElementById('stat-ready').textContent     = readyCount;

        setRing('ring-pending',   pendingCount,   total);
        setRing('ring-preparing', preparingCount, total);
        setRing('ring-ready',     readyCount,     total);
    }

    function setRing(id, count, total) {
        const pct    = total > 0 ? count / total : 0;
        const offset = RING_CIRCUMFERENCE * (1 - pct);
        const el     = document.getElementById(id);
        if (el) el.style.strokeDashoffset = offset;
    }

    function updateFilterCounts() {
        document.getElementById('fc-all').textContent       = orders.length;
        document.getElementById('fc-pending').textContent   = orders.filter(o => o.status === 'pending').length;
        document.getElementById('fc-preparing').textContent = orders.filter(o => o.status === 'preparing').length;
        document.getElementById('fc-ready').textContent     = orders.filter(o => o.status === 'ready').length;
    }

    function filterOrders(status, btn) {
        currentFilter = status;
        document.querySelectorAll('.kds-filter').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
        positionFilterSlider(btn);
        renderOrders();
    }

    function positionFilterSlider(activeBtn) {
        if (!activeBtn) return;
        const slider = document.getElementById('filter-slider');
        const wrap   = document.getElementById('kds-filters');
        const wrapRect = wrap.getBoundingClientRect();
        const btnRect  = activeBtn.getBoundingClientRect();
        slider.style.left  = (btnRect.left  - wrapRect.left  - 4) + 'px';
        slider.style.width = btnRect.width  + 'px';
    }

    function getTimerStr(createdAt) {
        const elapsed = Math.floor((Date.now() - new Date(createdAt).getTime()) / 1000);
        const mins    = Math.floor(elapsed / 60);
        const secs    = elapsed % 60;
        return {
            text: `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`,
            mins,
            elapsed
        };
    }

    // Progress ring: fills over 0–15 min range (900 seconds), urgent > 10 min
    function timerRingOffset(elapsed) {
        const max    = 900; // 15 minutes
        const pct    = Math.min(elapsed / max, 1);
        const offset = 94.2 * (1 - pct);  // 2π × 15
        return offset;
    }

    function updateTimers() {
        document.querySelectorAll('[data-created]').forEach(el => {
            const { text, mins, elapsed } = getTimerStr(el.dataset.created);
            // Update flat timer text
            el.textContent  = text;
            el.className    = `order-timer ${mins >= 10 ? 'urgent' : 'normal'}`;

            // Update progress ring if present
            const cardEl = el.closest('.order-card');
            if (cardEl) {
                const ringEl  = cardEl.querySelector('.timer-ring-progress');
                const ringLbl = cardEl.querySelector('.timer-ring-label');
                if (ringEl) {
                    ringEl.style.strokeDashoffset = timerRingOffset(elapsed);
                    ringEl.className = `timer-ring-progress ${mins >= 10 ? 'urgent' : 'normal'}`;
                }
                if (ringLbl) {
                    ringLbl.className = `timer-ring-label ${mins >= 10 ? 'urgent' : 'normal'}`;
                    ringLbl.textContent = `${mins}m`;
                }
                // Toggle urgent glow
                if (mins >= 10) {
                    cardEl.classList.add('urgent-card');
                } else {
                    cardEl.classList.remove('urgent-card');
                }
            }
        });
    }

    function renderOrders() {
        const filtered = currentFilter === 'all' ? orders : orders.filter(o => o.status === currentFilter);
        const grid     = document.getElementById('orders-grid');

        if (filtered.length === 0) {
            grid.innerHTML = `
                <div class="kds-empty">
                    <div class="empty-icon-wrap">
                        <span class="mat-icon mat-icon-filled">room_service</span>
                    </div>
                    <div class="kds-empty-title">All Clear</div>
                    <div class="kds-empty-sub">Waiting for incoming orders...</div>
                </div>`;
            return;
        }

        grid.innerHTML = filtered.map(order => {
            const isPending = pendingUpdates.has(order.order_number);
            return buildOrderCard(order, isPending);
        }).join('');
    }

    function buildOrderCard(order, isPending) {
        const { text: timerText, mins: timerMins, elapsed: timerElapsed } = getTimerStr(order.created_at);
        const isUrgent    = timerMins >= 10;
        const ringOffset  = timerRingOffset(timerElapsed);

            // --- Items HTML ---
            const itemsHtml = (order.items || []).map(item => {
                let modsHtml = '';
                if (item.modifications) {
                    const tags = [];
                    if (item.modifications.remove) {
                        item.modifications.remove.forEach(m =>
                            tags.push(`<span class="mod-tag remove"><i class="mod-tag-icon">&#10005;</i> ${m}</span>`)
                        );
                    }
                    if (item.modifications.add) {
                        item.modifications.add.forEach(m =>
                            tags.push(`<span class="mod-tag add"><i class="mod-tag-icon">&#43;</i> ${m}</span>`)
                        );
                    }
                    if (item.modifications.size) {
                        item.modifications.size.forEach(m =>
                            tags.push(`<span class="mod-tag size">${m}</span>`)
                        );
                    }
                    if (tags.length > 0) {
                        modsHtml = `<div class="order-item-mods">${tags.join('')}</div>`;
                    }
                }
                return `
                    <div class="order-item">
                        <div class="order-item-row">
                            <span class="order-item-qty">${item.quantity}x</span>
                            <span class="order-item-name">${item.product_name}</span>
                        </div>
                        ${modsHtml}
                    </div>`;
            }).join('');

            // --- Action buttons HTML ---
            const disabledAttr = isPending ? ' disabled style="opacity:0.5;pointer-events:none;"' : '';
            let actionsHtml = '';
            if (order.status === 'pending') {
                actionsHtml = `
                    <button class="order-btn order-btn-start${isPending ? ' loading' : ''}" onclick="updateStatus(this, '${order.order_number}', 'preparing')"${disabledAttr}>
                        <div class="btn-spinner"></div>
                        <span class="btn-label">START</span>
                    </button>
                    <button class="order-btn order-btn-cancel" onclick="updateStatus(this, '${order.order_number}', 'cancelled')"${disabledAttr}>
                        <div class="btn-spinner"></div>
                        <span class="btn-label">CANCEL</span>
                    </button>`;
            } else if (order.status === 'preparing') {
                actionsHtml = `
                    <button class="order-btn order-btn-ready${isPending ? ' loading' : ''}" onclick="updateStatus(this, '${order.order_number}', 'ready')"${disabledAttr}>
                        <div class="btn-spinner"></div>
                        <span class="btn-label">READY</span>
                    </button>
                    <button class="order-btn order-btn-cancel" onclick="updateStatus(this, '${order.order_number}', 'cancelled')"${disabledAttr}>
                        <div class="btn-spinner"></div>
                        <span class="btn-label">CANCEL</span>
                    </button>`;
            } else if (order.status === 'ready') {
                actionsHtml = `
                    <button class="order-btn order-btn-deliver${isPending ? ' loading' : ''}" onclick="updateStatus(this, '${order.order_number}', 'delivered')"${disabledAttr}>
                        <div class="btn-spinner"></div>
                        <span class="btn-label">DELIVERED</span>
                    </button>`;
            }

            const customerHtml = order.customer_name
                ? `<div class="order-customer"><span class="mat-icon">person</span>${order.customer_name}</div>`
                : '';

            const notesHtml = order.notes
                ? `<div class="order-notes">
                       <svg class="order-notes-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                       <div><div class="order-notes-label">Note</div>${order.notes}</div>
                   </div>`
                : '';

            return `
                <div class="order-card ${order.status}${isUrgent ? ' urgent-card' : ''}" data-order="${order.order_number}" data-status="${order.status}" data-pending="${isPending}">
                    <div class="order-card-header">
                        <div class="order-num">${order.order_number}</div>
                        <div class="order-meta">
                            <span class="order-badge ${order.status}">${order.status}</span>
                            <div class="timer-ring-wrap">
                                <div class="timer-ring-text-wrap">
                                    <svg class="timer-ring-svg" viewBox="0 0 38 38">
                                        <circle class="timer-ring-bg-circle" cx="19" cy="19" r="15"/>
                                        <circle class="timer-ring-progress ${isUrgent ? 'urgent' : 'normal'}"
                                            cx="19" cy="19" r="15"
                                            style="stroke-dashoffset: ${ringOffset}"/>
                                    </svg>
                                    <div class="timer-ring-label ${isUrgent ? 'urgent' : 'normal'}">${timerMins}m</div>
                                </div>
                                <div class="order-timer ${isUrgent ? 'urgent' : 'normal'}" data-created="${order.created_at}">${timerText}</div>
                            </div>
                        </div>
                    </div>
                    ${customerHtml}
                    <div class="order-items">${itemsHtml}</div>
                    ${notesHtml}
                    <div class="order-actions">${actionsHtml}</div>
                </div>`;
    }

    async function updateStatus(btn, orderNumber, newStatus) {
        // Prevent duplicate requests for the same order
        if (pendingUpdates.has(orderNumber)) return;
        pendingUpdates.add(orderNumber);

        // Immediately disable all buttons on this card via DOM (survives re-renders
        // because we also mark the order in pendingUpdates which renderOrders checks)
        const card = btn ? btn.closest('.order-card') : null;
        if (card) {
            card.querySelectorAll('.order-btn').forEach(b => {
                b.disabled = true;
                b.style.opacity = '0.5';
            });
            if (btn) btn.classList.add('loading');
        }

        // Optimistic local state update BEFORE the API call — makes UI feel instant
        if (newStatus === 'delivered' || newStatus === 'cancelled') {
            orders = orders.filter(o => o.order_number !== orderNumber);
        } else {
            const order = orders.find(o => o.order_number === orderNumber);
            if (order) order.status = newStatus;
        }
        scheduleRender();

        // Fire the API call without awaiting render
        try {
            const res = await fetch(`${API_BASE}/cocina/pedidos/${orderNumber}/estado`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ status: newStatus })
            });

            if (!res.ok) {
                const result = await res.json().catch(() => ({}));
                console.error('Status update rejected:', result.message || res.status);
                // Revert — force reload from server
                pendingUpdates.delete(orderNumber);
                isPolling = false; // ensure loadOrders isn't blocked
                await loadOrders();
                return;
            }
            // API confirmed — optimistic state was correct, nothing else to do
        } catch (e) {
            console.error('Network error updating status:', e);
            // Revert — force reload from server
            pendingUpdates.delete(orderNumber);
            isPolling = false;
            await loadOrders();
            return;
        }

        pendingUpdates.delete(orderNumber);
        // Don't re-render here — the optimistic render already happened.
        // The next poll will reconcile with the server.
    }
</script>
@endpush
