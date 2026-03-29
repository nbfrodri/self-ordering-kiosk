@extends('layouts.app')
@section('title', 'Analytics Dashboard')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
<style>
    /* ===== DESIGN TOKENS (mirrored from kiosk) ===== */
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
        --k-border:              rgba(163,56,0,0.10);
        --k-border-strong:       rgba(163,56,0,0.22);
        --k-shadow:              rgba(47,46,46,0.06);
        --k-shadow-md:           rgba(47,46,46,0.13);
        --k-success:             #10B981;
        --k-success-bg:          rgba(16,185,129,0.10);
        --k-warning:             #F59E0B;
        --k-warning-bg:          rgba(245,158,11,0.12);
        --k-info:                #3B82F6;
        --k-info-bg:             rgba(59,130,246,0.10);
        --k-error:               #dc2626;
        --k-error-bg:            rgba(220,38,38,0.10);
        --k-radius-sm:           10px;
        --k-radius-md:           16px;
        --k-radius-lg:           24px;
        --k-radius-full:         9999px;
        --k-spring:              cubic-bezier(0.34, 1.56, 0.64, 1);
        --k-ease-out:            cubic-bezier(0.16, 1, 0.3, 1);
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
    .hidden { display: none !important; }
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--k-on-surface-faint); border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--k-on-surface-muted); }

    /* ===== MATERIAL ICONS ===== */
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

    /* ===== DASHBOARD SHELL ===== */
    .dash {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        background: var(--k-surface);
    }

    /* ===== TOP BAR ===== */
    .dash-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 32px;
        height: 72px;
        background: #ffffff;
        border-bottom: 1px solid var(--k-border);
        box-shadow: 0 1px 12px var(--k-shadow);
        position: sticky;
        top: 0;
        z-index: 50;
        gap: 16px;
    }

    /* Brand — matches kiosk header */
    .dash-brand {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .dash-logo {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, var(--k-primary-container), var(--k-primary));
        border-radius: var(--k-radius-sm);
        display: grid;
        place-items: center;
        flex-shrink: 0;
        box-shadow: 0 4px 14px rgba(163,56,0,0.30);
    }
    .dash-logo .mat-icon {
        font-size: 22px;
        color: #ffffff;
        font-variation-settings: 'FILL' 1;
    }
    .dash-brand-text {}
    .dash-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--k-on-surface);
        letter-spacing: -0.4px;
        line-height: 1.15;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .dash-title-accent {
        color: var(--k-primary-container);
    }
    .dash-subtitle {
        font-size: 0.68rem;
        color: var(--k-on-surface-muted);
        font-weight: 600;
        margin-top: 2px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    /* Topbar right cluster */
    .dash-topbar-right {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    /* Countdown ring */
    .refresh-ring-wrap {
        display: flex;
        align-items: center;
        gap: 9px;
        cursor: pointer;
        padding: 6px 12px 6px 6px;
        border-radius: var(--k-radius-full);
        border: 1px solid var(--k-border);
        background: var(--k-surface);
        transition: background 0.2s, border-color 0.2s;
    }
    .refresh-ring-wrap:hover {
        background: var(--k-surface-variant);
        border-color: var(--k-border-strong);
    }
    .refresh-ring-svg-wrap {
        position: relative;
        width: 34px;
        height: 34px;
        flex-shrink: 0;
    }
    .refresh-ring-svg {
        width: 34px;
        height: 34px;
        transform: rotate(-90deg);
    }
    .refresh-ring-bg {
        fill: none;
        stroke: var(--k-surface-variant);
        stroke-width: 3;
    }
    .refresh-ring-fill {
        fill: none;
        stroke: var(--k-primary-container);
        stroke-width: 3;
        stroke-linecap: round;
        stroke-dasharray: 94.2;
        stroke-dashoffset: 0;
        transition: stroke-dashoffset 1s linear;
        filter: drop-shadow(0 0 4px rgba(255,121,65,0.50));
    }
    .refresh-ring-label {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        font-weight: 800;
        color: var(--k-primary);
    }
    .refresh-ring-wrap-text {
        font-size: 0.72rem;
        color: var(--k-on-surface-muted);
        font-weight: 600;
        white-space: nowrap;
        letter-spacing: 0.2px;
    }

    /* Refresh button — matches kiosk pill button style */
    .refresh-btn {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 0 20px;
        border: 1.5px solid var(--k-primary-container);
        border-radius: var(--k-radius-full);
        background: linear-gradient(135deg, var(--k-primary-container), var(--k-primary));
        color: #ffffff;
        font-size: 0.82rem;
        font-weight: 700;
        transition: opacity 0.2s, transform 0.15s var(--k-spring), box-shadow 0.2s;
        height: 40px;
        box-shadow: 0 3px 12px rgba(255,121,65,0.28);
        letter-spacing: 0.1px;
    }
    .refresh-btn:hover {
        opacity: 0.88;
        box-shadow: 0 5px 18px rgba(255,121,65,0.38);
    }
    .refresh-btn:active { transform: scale(0.96); }
    .refresh-btn svg { width: 14px; height: 14px; transition: transform 0.3s; flex-shrink: 0; }
    .refresh-btn.spinning svg { animation: spin 0.6s linear; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ===== CONTENT SCROLL AREA ===== */
    .dash-content {
        flex: 1;
        padding: 32px 32px 56px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: var(--k-on-surface-faint) transparent;
    }

    /* ===== KPI CARDS ===== */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }

    .kpi-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.06);
        border-radius: var(--k-radius-lg);
        padding: 26px 22px 0;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 12px var(--k-shadow);
        transition: transform 0.22s var(--k-spring), box-shadow 0.22s var(--k-ease-out);
        animation: kpiIn 0.5s var(--k-ease-out) both;
        display: flex;
        flex-direction: column;
    }
    .kpi-card:nth-child(1) { animation-delay: 0.04s; }
    .kpi-card:nth-child(2) { animation-delay: 0.09s; }
    .kpi-card:nth-child(3) { animation-delay: 0.14s; }
    .kpi-card:nth-child(4) { animation-delay: 0.19s; }
    @keyframes kpiIn {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 36px var(--k-shadow-md);
    }

    /* Icon badge — top right corner */
    .kpi-icon-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }
    .kpi-card.kpi-orders  .kpi-icon-badge { background: rgba(59,130,246,0.11); }
    .kpi-card.kpi-revenue .kpi-icon-badge { background: rgba(16,185,129,0.11); }
    .kpi-card.kpi-avg     .kpi-icon-badge { background: rgba(245,158,11,0.11); }
    .kpi-card.kpi-time    .kpi-icon-badge { background: rgba(255,121,65,0.11); }
    .kpi-card.kpi-orders  .kpi-icon-badge .mat-icon { color: #3B82F6; font-size: 19px; font-variation-settings: 'FILL' 1; }
    .kpi-card.kpi-revenue .kpi-icon-badge .mat-icon { color: #10B981; font-size: 19px; font-variation-settings: 'FILL' 1; }
    .kpi-card.kpi-avg     .kpi-icon-badge .mat-icon { color: #F59E0B; font-size: 19px; font-variation-settings: 'FILL' 1; }
    .kpi-card.kpi-time    .kpi-icon-badge .mat-icon { color: #ff7941; font-size: 19px; font-variation-settings: 'FILL' 1; }

    /* KPI text */
    .kpi-label {
        font-size: 0.72rem;
        color: var(--k-on-surface-muted);
        font-weight: 600;
        margin-bottom: 10px;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        padding-right: 50px; /* avoid icon badge overlap */
    }
    .kpi-value {
        font-size: 2.6rem;
        font-weight: 800;
        letter-spacing: -1.5px;
        line-height: 1;
        color: var(--k-on-surface);
        margin-bottom: 6px;
    }
    .kpi-sub {
        font-size: 0.73rem;
        color: var(--k-on-surface-faint);
        font-weight: 500;
        margin-bottom: 22px;
    }

    /* Bottom accent line */
    .kpi-accent-line {
        height: 3px;
        border-radius: 0;
        margin: auto -22px 0;
        flex-shrink: 0;
    }
    .kpi-card.kpi-orders  .kpi-accent-line { background: linear-gradient(90deg, #3B82F6, rgba(59,130,246,0.25)); }
    .kpi-card.kpi-revenue .kpi-accent-line { background: linear-gradient(90deg, #10B981, rgba(16,185,129,0.25)); }
    .kpi-card.kpi-avg     .kpi-accent-line { background: linear-gradient(90deg, #F59E0B, rgba(245,158,11,0.25)); }
    .kpi-card.kpi-time    .kpi-accent-line { background: linear-gradient(90deg, #ff7941, rgba(255,121,65,0.25)); }

    /* ===== TWO-COLUMN DASHBOARD LAYOUT ===== */
    .dash-cols {
        display: grid;
        grid-template-columns: 3fr 2fr;
        gap: 24px;
        align-items: start;
    }
    .dash-col {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* ===== CARD PANEL ===== */
    .dash-panel {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.06);
        border-radius: var(--k-radius-lg);
        padding: 26px 26px 24px;
        overflow: hidden;
        box-shadow: 0 2px 12px var(--k-shadow);
        transition: box-shadow 0.2s;
    }

    /* Panel heading */
    .panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 22px;
        padding-bottom: 16px;
        border-bottom: 1px solid rgba(0,0,0,0.055);
    }
    .panel-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--k-on-surface);
        letter-spacing: -0.1px;
    }
    .panel-title-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .panel-badge {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        padding: 3px 10px;
        border-radius: var(--k-radius-full);
        background: var(--k-surface);
        color: var(--k-on-surface-muted);
        border: 1px solid rgba(0,0,0,0.06);
    }

    /* ===== STATUS BREAKDOWN ===== */
    .status-bars {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .status-row {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .status-label {
        width: 78px;
        font-size: 0.71rem;
        font-weight: 700;
        text-transform: capitalize;
        letter-spacing: 0.3px;
        flex-shrink: 0;
    }
    .status-label.pending   { color: #D97706; }
    .status-label.preparing { color: #2563EB; }
    .status-label.ready     { color: #059669; }
    .status-label.delivered { color: #059669; }
    .status-label.cancelled { color: #dc2626; }

    .status-bar-track {
        flex: 1;
        height: 20px;
        background: var(--k-surface);
        border-radius: var(--k-radius-full);
        overflow: hidden;
        position: relative;
        border: 1px solid rgba(0,0,0,0.04);
    }
    .status-bar-fill {
        height: 100%;
        border-radius: var(--k-radius-full);
        width: 0%;
        transition: width 1.1s var(--k-ease-out);
        display: flex;
        align-items: center;
        padding: 0 10px;
        font-size: 0.66rem;
        font-weight: 700;
        color: rgba(255,255,255,0.95);
        white-space: nowrap;
        overflow: visible;
    }
    .status-bar-fill.pending   { background: linear-gradient(90deg, #D97706, #F59E0B); }
    .status-bar-fill.preparing { background: linear-gradient(90deg, #2563EB, #3B82F6); }
    .status-bar-fill.ready     { background: linear-gradient(90deg, #059669, #10B981); }
    .status-bar-fill.delivered { background: linear-gradient(90deg, #10B981, #34D399); }
    .status-bar-fill.cancelled { background: linear-gradient(90deg, #dc2626, #f87171); }

    .status-bar-pct {
        font-size: 0.73rem;
        font-weight: 700;
        min-width: 36px;
        text-align: right;
        color: var(--k-on-surface-muted);
        flex-shrink: 0;
    }
    .status-count-badge {
        font-size: 0.92rem;
        font-weight: 700;
        min-width: 24px;
        text-align: right;
        color: var(--k-on-surface);
        flex-shrink: 0;
    }

    /* ===== RECENT ORDERS TABLE ===== */
    .orders-table-wrap {
        overflow-x: auto;
        margin: 0 -26px -24px;
        padding: 0 26px 24px;
    }
    .orders-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 580px;
    }
    .orders-table thead tr {
        border-bottom: 1.5px solid rgba(0,0,0,0.07);
    }
    .orders-table th {
        text-align: left;
        font-size: 0.66rem;
        font-weight: 700;
        color: var(--k-on-surface-faint);
        text-transform: uppercase;
        letter-spacing: 1.8px;
        padding: 10px 14px;
        white-space: nowrap;
    }
    .orders-table td {
        padding: 13px 14px;
        font-size: 0.86rem;
        vertical-align: middle;
        color: var(--k-on-surface);
        border-bottom: 1px solid rgba(0,0,0,0.04);
    }
    /* Warm zebra striping */
    .orders-table tbody tr:nth-child(even) td {
        background: #faf7f6;
    }
    .orders-table tbody tr {
        transition: background 0.14s;
    }
    .orders-table tbody tr:hover td {
        background: var(--k-surface-variant) !important;
    }
    .orders-table .order-num-cell {
        font-size: 0.93rem;
        font-weight: 800;
        letter-spacing: 0.3px;
        color: var(--k-on-surface);
    }

    /* Status badge pills */
    .table-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 11px;
        border-radius: var(--k-radius-full);
        font-size: 0.67rem;
        font-weight: 700;
        letter-spacing: 0.4px;
        text-transform: capitalize;
        white-space: nowrap;
    }
    .table-badge-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .table-badge.pending   { background: rgba(245,158,11,0.13); color: #D97706; }
    .table-badge.preparing { background: rgba(59,130,246,0.12); color: #2563EB; }
    .table-badge.ready     { background: rgba(16,185,129,0.12); color: #059669; }
    .table-badge.delivered { background: rgba(16,185,129,0.12); color: #059669; }
    .table-badge.cancelled { background: rgba(220,38,38,0.10);  color: #dc2626; }
    .table-badge.pending   .table-badge-dot { background: #F59E0B; }
    .table-badge.preparing .table-badge-dot { background: #3B82F6; }
    .table-badge.ready     .table-badge-dot { background: #10B981; }
    .table-badge.delivered .table-badge-dot { background: #10B981; }
    .table-badge.cancelled .table-badge-dot { background: #dc2626; }

    .table-items-summary {
        font-size: 0.81rem;
        color: var(--k-on-surface-muted);
        max-width: 260px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .table-time {
        color: var(--k-on-surface-faint);
        font-size: 0.79rem;
        white-space: nowrap;
    }

    /* ===== POPULAR ITEMS LEADERBOARD ===== */
    .popular-list {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .popular-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: var(--k-radius-sm);
        transition: background 0.16s;
        position: relative;
    }
    .popular-item:hover { background: var(--k-surface); }

    /* #1 gold highlight */
    .popular-item.rank-1 {
        background: rgba(245,158,11,0.07);
        border: 1px solid rgba(245,158,11,0.20);
        padding: 13px 12px;
        border-radius: var(--k-radius-md);
    }
    .popular-item.rank-1:hover { background: rgba(245,158,11,0.12); }

    .popular-rank {
        font-size: 0.82rem;
        font-weight: 800;
        color: var(--k-on-surface-faint);
        width: 24px;
        text-align: center;
        flex-shrink: 0;
    }
    .popular-rank.top { color: #F59E0B; }
    .crown-icon {
        width: 24px;
        text-align: center;
        flex-shrink: 0;
        font-size: 1rem;
        line-height: 1;
    }
    .popular-info {
        flex: 1;
        min-width: 0;
    }
    .popular-name {
        font-weight: 600;
        font-size: 0.88rem;
        color: var(--k-on-surface);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 5px;
    }
    .popular-item.rank-1 .popular-name {
        font-size: 0.96rem;
        font-weight: 700;
    }
    .popular-bar-track {
        width: 100%;
        height: 4px;
        background: var(--k-surface-variant);
        border-radius: var(--k-radius-full);
        overflow: hidden;
    }
    .popular-bar-fill {
        height: 100%;
        border-radius: var(--k-radius-full);
        background: linear-gradient(90deg, #ff7941, #ffc5a5);
        transition: width 0.9s var(--k-ease-out);
    }
    .popular-item.rank-1 .popular-bar-fill {
        background: linear-gradient(90deg, #D97706, #FBBF24);
    }
    .popular-count {
        font-size: 1rem;
        font-weight: 800;
        color: var(--k-on-surface);
        min-width: 28px;
        text-align: right;
        flex-shrink: 0;
    }
    .popular-count-label {
        font-size: 0.62rem;
        font-weight: 500;
        color: var(--k-on-surface-faint);
        text-align: right;
        display: block;
        margin-top: 1px;
    }
    .popular-item.rank-1 .popular-count { color: #D97706; font-size: 1.15rem; }

    /* ===== PAYMENT METHODS — LIST STYLE ===== */
    .payment-list {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .payment-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 10px;
        border-radius: var(--k-radius-sm);
        transition: background 0.16s;
    }
    .payment-row:hover { background: var(--k-surface); }
    .payment-icon-wrap {
        width: 40px;
        height: 40px;
        border-radius: var(--k-radius-sm);
        background: var(--k-surface);
        display: grid;
        place-items: center;
        flex-shrink: 0;
        border: 1px solid rgba(0,0,0,0.06);
    }
    .payment-row-info {
        flex: 1;
        min-width: 0;
    }
    .payment-method-name {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--k-on-surface);
        letter-spacing: 0.2px;
    }
    .payment-txn-count {
        font-size: 0.7rem;
        color: var(--k-on-surface-faint);
        font-weight: 500;
        margin-top: 1px;
    }
    .payment-row-right {
        text-align: right;
        flex-shrink: 0;
    }
    .payment-amount {
        font-size: 1.05rem;
        font-weight: 800;
        color: #10B981;
        letter-spacing: -0.3px;
        display: block;
    }
    .payment-pct-badge {
        font-size: 0.68rem;
        font-weight: 700;
        color: var(--k-on-surface-muted);
        display: block;
        margin-top: 1px;
    }

    /* Donut chart section — below payment list */
    .payment-donut-section {
        margin-top: 22px;
        padding-top: 20px;
        border-top: 1px solid rgba(0,0,0,0.055);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 22px;
    }
    .payment-donut-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }
    .payment-donut {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: conic-gradient(
            #10B981    0deg   var(--cash-deg,   90deg),
            #3B82F6    var(--cash-deg,   90deg)  var(--credit-deg, 180deg),
            #F59E0B    var(--credit-deg, 180deg) var(--debit-deg,  270deg),
            #ff7941    var(--debit-deg, 270deg)  360deg
        );
        position: relative;
        box-shadow: 0 4px 16px var(--k-shadow-md);
    }
    .payment-donut::after {
        content: '';
        position: absolute;
        inset: 18px;
        border-radius: 50%;
        background: #ffffff;
    }
    .payment-donut-label {
        font-size: 0.61rem;
        font-weight: 700;
        letter-spacing: 1.2px;
        color: var(--k-on-surface-muted);
        text-transform: uppercase;
    }
    .payment-legend {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }
    .payment-legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.73rem;
        color: var(--k-on-surface-muted);
        font-weight: 500;
    }
    .payment-legend-dot {
        width: 8px;
        height: 8px;
        border-radius: 2px;
        flex-shrink: 0;
    }

    /* ===== LOADING STATE ===== */
    .loading-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 48px 20px;
        color: var(--k-on-surface-faint);
        font-size: 0.82rem;
        font-weight: 500;
        gap: 14px;
    }
    .loading-spinner {
        width: 32px;
        height: 32px;
        border: 3px solid var(--k-surface-variant);
        border-top-color: var(--k-primary-container);
        border-radius: 50%;
        animation: spin 0.75s linear infinite;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        gap: 10px;
        color: var(--k-on-surface-faint);
        text-align: center;
    }
    .empty-state .mat-icon {
        font-size: 40px;
        opacity: 0.35;
        font-variation-settings: 'FILL' 1;
    }
    .empty-state p {
        font-size: 0.84rem;
        font-weight: 500;
        max-width: 240px;
        line-height: 1.5;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .kpi-grid { grid-template-columns: repeat(2, 1fr); }
        .dash-cols { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
        .kpi-grid { grid-template-columns: 1fr; }
        .dash-topbar { padding: 0 16px; }
        .dash-content { padding: 20px 16px 40px; }
        .refresh-ring-wrap-text { display: none; }
        .dash-panel { padding: 20px 16px; }
    }
</style>
@endpush

@section('content')
<div class="dash">

    <!-- TOP BAR -->
    <div class="dash-topbar">

        <!-- Brand -->
        <div class="dash-brand">
            <div class="dash-logo">
                <span class="mat-icon">restaurant_menu</span>
            </div>
            <div class="dash-brand-text">
                <div class="dash-title">The Culinary <span class="dash-title-accent">Concierge</span></div>
                <div class="dash-subtitle">Analytics</div>
            </div>
        </div>

        <!-- Right: countdown ring + refresh -->
        <div class="dash-topbar-right">
            <div class="refresh-ring-wrap" title="Auto-refreshes every 30 seconds" onclick="refreshData()">
                <div class="refresh-ring-svg-wrap">
                    <svg class="refresh-ring-svg" viewBox="0 0 38 38">
                        <circle class="refresh-ring-bg"   cx="19" cy="19" r="15"/>
                        <circle class="refresh-ring-fill" cx="19" cy="19" r="15" id="refresh-ring-fill"/>
                    </svg>
                    <div class="refresh-ring-label" id="refresh-ring-label">30</div>
                </div>
                <span class="refresh-ring-wrap-text">Auto&nbsp;refresh</span>
            </div>

            <button class="refresh-btn" id="refresh-btn" onclick="refreshData()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 4 23 10 17 10"/>
                    <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="dash-content">

        <!-- KPI CARDS -->
        <div class="kpi-grid" id="kpi-grid">

            <!-- Total Orders -->
            <div class="kpi-card kpi-orders">
                <div class="kpi-icon-badge">
                    <span class="mat-icon">receipt_long</span>
                </div>
                <div class="kpi-label">Total Orders</div>
                <div class="kpi-value" id="kpi-total-orders">--</div>
                <div class="kpi-sub">all time</div>
                <div class="kpi-accent-line"></div>
            </div>

            <!-- Total Revenue -->
            <div class="kpi-card kpi-revenue">
                <div class="kpi-icon-badge">
                    <span class="mat-icon">payments</span>
                </div>
                <div class="kpi-label">Total Revenue</div>
                <div class="kpi-value" id="kpi-revenue">--</div>
                <div class="kpi-sub">completed orders</div>
                <div class="kpi-accent-line"></div>
            </div>

            <!-- Avg Order Value -->
            <div class="kpi-card kpi-avg">
                <div class="kpi-icon-badge">
                    <span class="mat-icon">show_chart</span>
                </div>
                <div class="kpi-label">Avg Order Value</div>
                <div class="kpi-value" id="kpi-avg-value">--</div>
                <div class="kpi-sub">per order</div>
                <div class="kpi-accent-line"></div>
            </div>

            <!-- Avg Prep Time -->
            <div class="kpi-card kpi-time">
                <div class="kpi-icon-badge">
                    <span class="mat-icon">schedule</span>
                </div>
                <div class="kpi-label">Avg Prep Time</div>
                <div class="kpi-value" id="kpi-prep-time">--</div>
                <div class="kpi-sub">minutes estimated</div>
                <div class="kpi-accent-line"></div>
            </div>

        </div><!-- /.kpi-grid -->

        <!-- TWO-COLUMN LAYOUT -->
        <div class="dash-cols">

            <!-- LEFT COLUMN (~60%) -->
            <div class="dash-col">

                <!-- Order Status Breakdown -->
                <div class="dash-panel">
                    <div class="panel-header">
                        <div class="panel-title">
                            <span class="panel-title-dot" style="background:#ff7941;"></span>
                            Order Status Breakdown
                        </div>
                    </div>
                    <div class="status-bars" id="status-bars">
                        <div class="loading-container">
                            <div class="loading-spinner"></div>
                            <span>Loading data...</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Table -->
                <div class="dash-panel">
                    <div class="panel-header">
                        <div class="panel-title">
                            <span class="panel-title-dot" style="background:#3B82F6;"></span>
                            Recent Orders
                        </div>
                    </div>
                    <div class="orders-table-wrap">
                        <table class="orders-table" id="orders-table">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Status</th>
                                    <th>Items</th>
                                    <th>Customer</th>
                                    <th>Est. Prep</th>
                                    <th>Created</th>
                                    <th>Finished</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody id="orders-tbody">
                                <tr>
                                    <td colspan="8">
                                        <div class="loading-container">
                                            <div class="loading-spinner"></div>
                                            <span>Loading data...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div><!-- /.dash-col left -->

            <!-- RIGHT COLUMN (~40%) -->
            <div class="dash-col">

                <!-- Top Selling Items -->
                <div class="dash-panel">
                    <div class="panel-header">
                        <div class="panel-title">
                            <span class="panel-title-dot" style="background:#F59E0B;"></span>
                            Top Selling Items
                        </div>
                    </div>
                    <div class="popular-list" id="popular-items">
                        <div class="loading-container">
                            <div class="loading-spinner"></div>
                            <span>Loading data...</span>
                        </div>
                    </div>
                </div>

                <!-- Revenue by Payment Method -->
                <div class="dash-panel">
                    <div class="panel-header">
                        <div class="panel-title">
                            <span class="panel-title-dot" style="background:#10B981;"></span>
                            Revenue by Payment
                        </div>
                    </div>

                    <!-- Payment method rows -->
                    <div id="payment-methods">
                        <div class="loading-container">
                            <div class="loading-spinner"></div>
                            <span>Loading data...</span>
                        </div>
                    </div>

                    <!-- Donut chart — shown after data loads -->
                    <div id="payment-donut-section" class="payment-donut-section hidden" style="display:none;">
                        <div class="payment-donut-wrap">
                            <div class="payment-donut" id="payment-donut"></div>
                            <div class="payment-donut-label">Revenue Split</div>
                        </div>
                        <div class="payment-legend" id="payment-legend"></div>
                    </div>

                </div>

            </div><!-- /.dash-col right -->

        </div><!-- /.dash-cols -->

    </div><!-- /.dash-content -->
</div><!-- /.dash -->
@endsection

@push('scripts')
<script>
    /* ===== AUTO-REFRESH COUNTDOWN ===== */
    const REFRESH_INTERVAL_S = 30;
    const RING_CIRC          = 94.2; // 2π × 15
    let countdownSec         = REFRESH_INTERVAL_S;
    let autoRefreshTimer;

    function startCountdown() {
        countdownSec = REFRESH_INTERVAL_S;
        clearInterval(autoRefreshTimer);
        autoRefreshTimer = setInterval(() => {
            countdownSec--;
            updateCountdownRing();
            if (countdownSec <= 0) {
                loadAll();
                countdownSec = REFRESH_INTERVAL_S;
            }
        }, 1000);
        updateCountdownRing();
    }

    function updateCountdownRing() {
        const pct    = countdownSec / REFRESH_INTERVAL_S;
        const offset = RING_CIRC * (1 - pct);
        const fill   = document.getElementById('refresh-ring-fill');
        const label  = document.getElementById('refresh-ring-label');
        if (fill)  fill.style.strokeDashoffset  = offset;
        if (label) label.textContent = countdownSec;
    }

    /* ===== BOOT ===== */
    document.addEventListener('DOMContentLoaded', () => {
        loadAll();
        startCountdown();
    });

    async function loadAll() {
        await Promise.all([loadSummary(), loadRecentOrders()]);
    }

    async function refreshData() {
        const btn = document.getElementById('refresh-btn');
        btn.classList.add('spinning');
        await loadAll();
        startCountdown();
        setTimeout(() => btn.classList.remove('spinning'), 600);
    }

    /* ===== SUMMARY ===== */
    async function loadSummary() {
        try {
            const res = await apiFetch('/analiticas/resumen');
            const d   = res.data || res;
            renderKPIs(d);
            renderStatusBars(d.orders_by_status || {});
            renderPopularItems(d.popular_items || []);
            renderPaymentMethods(d.revenue_by_payment_method || []);
        } catch (e) {
            console.error('Failed to load summary:', e);
        }
    }

    /* ===== KPIs ===== */
    function renderKPIs(data) {
        const totalOrders  = data.total_orders || 0;
        const totalRevenue = computeTotalRevenue(data.revenue_by_payment_method || []);
        const avgValue     = totalOrders > 0 ? (totalRevenue / totalOrders) : 0;
        const avgPrep      = data.avg_preparation_minutes || 0;

        animateNumber('kpi-total-orders', totalOrders, '',  false);
        animateNumber('kpi-revenue',      totalRevenue, '$', true);
        animateNumber('kpi-avg-value',    avgValue,    '$', true);
        document.getElementById('kpi-prep-time').textContent = avgPrep.toFixed(1);
    }

    function computeTotalRevenue(methods) {
        if (Array.isArray(methods)) {
            return methods.reduce((sum, m) => sum + (m.total_revenue || 0), 0);
        }
        return Object.values(methods).reduce((sum, v) => sum + (typeof v === 'number' ? v : 0), 0);
    }

    function animateNumber(elId, target, prefix, isDecimal) {
        const el       = document.getElementById(elId);
        const duration = 900;
        const start    = performance.now();

        function step(now) {
            const elapsed  = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const eased    = 1 - Math.pow(1 - progress, 4);
            const current  = target * eased;

            el.textContent = isDecimal
                ? prefix + current.toFixed(2)
                : prefix + Math.round(current).toLocaleString();

            if (progress < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    /* ===== STATUS BARS ===== */
    function renderStatusBars(statuses) {
        const el          = document.getElementById('status-bars');
        const statusOrder = ['pending', 'preparing', 'ready', 'delivered', 'cancelled'];
        const total       = Object.values(statuses).reduce((s, v) => s + v, 0) || 1;

        el.innerHTML = statusOrder.map(status => {
            const count = statuses[status] || 0;
            const pct   = Math.round((count / total) * 100);
            const width = Math.max(pct, count > 0 ? 3 : 0);
            return `
                <div class="status-row">
                    <div class="status-label ${status}">${status}</div>
                    <div class="status-bar-track">
                        <div class="status-bar-fill ${status}" style="width: 0%" data-target="${width}">
                            ${pct > 14 ? count : ''}
                        </div>
                    </div>
                    <div class="status-bar-pct">${pct}%</div>
                    <div class="status-count-badge">${count}</div>
                </div>`;
        }).join('');

        // Animate bars in after paint
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                el.querySelectorAll('.status-bar-fill[data-target]').forEach(bar => {
                    bar.style.width = bar.dataset.target + '%';
                });
            });
        });
    }

    /* ===== POPULAR ITEMS LEADERBOARD ===== */
    function renderPopularItems(items) {
        const el = document.getElementById('popular-items');
        if (items.length === 0) {
            el.innerHTML = `
                <div class="empty-state">
                    <span class="mat-icon">storefront</span>
                    <p>No order data yet. Place some orders from the kiosk first.</p>
                </div>`;
            return;
        }

        const maxCount = Math.max(...items.map(i => i.total_ordered));

        el.innerHTML = items.map((item, i) => {
            const pct      = (item.total_ordered / maxCount) * 100;
            const isFirst  = i === 0;
            const rankHtml = isFirst
                ? `<div class="crown-icon">&#128081;</div>`
                : `<div class="popular-rank ${i < 3 ? 'top' : ''}">${i + 1}</div>`;

            return `
                <div class="popular-item ${isFirst ? 'rank-1' : ''}">
                    ${rankHtml}
                    <div class="popular-info">
                        <div class="popular-name">${item.product_name}</div>
                        <div class="popular-bar-track">
                            <div class="popular-bar-fill" style="width: 0%" data-target="${pct}"></div>
                        </div>
                    </div>
                    <div>
                        <span class="popular-count">${item.total_ordered}</span>
                        <span class="popular-count-label">orders</span>
                    </div>
                </div>`;
        }).join('');

        // Animate bars
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                el.querySelectorAll('.popular-bar-fill[data-target]').forEach(bar => {
                    bar.style.width = bar.dataset.target + '%';
                });
            });
        });
    }

    /* ===== PAYMENT METHODS ===== */
    const PAYMENT_ICONS = {
        cash: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>`,
        credit_card: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>`,
        debit_card: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/><line x1="6" y1="15" x2="10" y2="15"/></svg>`,
        mobile_pay: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ff7941" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>`,
    };
    const PAYMENT_LABELS = { cash: 'Cash', credit_card: 'Credit Card', debit_card: 'Debit Card', mobile_pay: 'Mobile Pay' };
    const DONUT_COLORS   = { cash: '#10B981', credit_card: '#3B82F6', debit_card: '#F59E0B', mobile_pay: '#ff7941' };

    function renderPaymentMethods(methods) {
        const el      = document.getElementById('payment-methods');
        const donutEl = document.getElementById('payment-donut-section');

        const allMethods = ['cash', 'credit_card', 'debit_card', 'mobile_pay'];

        let methodsArr;
        if (!methods || (Array.isArray(methods) && methods.length === 0)) {
            methodsArr = allMethods.map(m => ({ payment_method: m, total_revenue: 0, transaction_count: 0 }));
        } else if (Array.isArray(methods)) {
            methodsArr = methods;
        } else {
            methodsArr = Object.entries(methods).map(([k, v]) => ({
                payment_method: k,
                total_revenue: typeof v === 'number' ? v : 0,
                transaction_count: 0
            }));
        }

        const totalRev = methodsArr.reduce((s, m) => s + (m.total_revenue || 0), 0) || 1;

        el.innerHTML = `<div class="payment-list">${methodsArr.map(m => {
            const pct = Math.round((m.total_revenue || 0) / totalRev * 100);
            return `
                <div class="payment-row">
                    <div class="payment-icon-wrap">${PAYMENT_ICONS[m.payment_method] || PAYMENT_ICONS.cash}</div>
                    <div class="payment-row-info">
                        <div class="payment-method-name">${PAYMENT_LABELS[m.payment_method] || m.payment_method}</div>
                        <div class="payment-txn-count">${m.transaction_count || 0} transactions</div>
                    </div>
                    <div class="payment-row-right">
                        <span class="payment-amount">$${(m.total_revenue || 0).toFixed(2)}</span>
                        <span class="payment-pct-badge">${pct}%</span>
                    </div>
                </div>`;
        }).join('')}</div>`;

        // Build CSS conic-gradient donut
        renderDonutChart(methodsArr, totalRev);
    }

    function renderDonutChart(methods, total) {
        const donutEl  = document.getElementById('payment-donut');
        const legendEl = document.getElementById('payment-legend');
        const wrapEl   = document.getElementById('payment-donut-section');

        if (!donutEl) return;

        let cumulativeDeg = 0;
        const segments    = [];
        const legendItems = [];

        methods.forEach(m => {
            const pct   = (m.total_revenue || 0) / total;
            const deg   = pct * 360;
            const color = DONUT_COLORS[m.payment_method] || 'var(--k-on-surface-faint)';
            const label = PAYMENT_LABELS[m.payment_method] || m.payment_method;
            segments.push(`${color} ${cumulativeDeg.toFixed(1)}deg ${(cumulativeDeg + deg).toFixed(1)}deg`);
            legendItems.push({ color, label, pct: Math.round(pct * 100) });
            cumulativeDeg += deg;
        });

        donutEl.style.background = `conic-gradient(${segments.join(', ')})`;

        legendEl.innerHTML = legendItems.map(item => `
            <div class="payment-legend-item">
                <div class="payment-legend-dot" style="background:${item.color};"></div>
                <span>${item.label} &mdash; ${item.pct}%</span>
            </div>`).join('');

        // Show donut panel
        wrapEl.style.display = 'flex';
    }

    /* ===== RECENT ORDERS ===== */
    async function loadRecentOrders() {
        try {
            const res    = await apiFetch('/pedidos?per_page=20');
            const orders = res.data || [];
            renderRecentOrders(orders);
        } catch (e) {
            console.error('Failed to load recent orders:', e);
        }
    }

    function renderRecentOrders(orders) {
        const tbody = document.getElementById('orders-tbody');

        if (orders.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <span class="mat-icon">receipt_long</span>
                            <p>No orders yet. Place some orders from the kiosk first.</p>
                        </div>
                    </td>
                </tr>`;
            return;
        }

        const statusLabels = {
            pending: 'Pending',
            preparing: 'Preparing',
            ready: 'Ready',
            delivered: 'Finished',
            cancelled: 'Cancelled'
        };

        tbody.innerHTML = orders.map(order => {
            const items        = order.items || [];
            const itemsSummary = items.map(i => `${i.quantity}x ${i.product_name}`).join(', ');
            const created      = new Date(order.created_at);
            const timeStr      = created.toLocaleString('en-US', {
                month: 'short', day: 'numeric',
                hour: '2-digit', minute: '2-digit', hour12: false
            });
            const label = statusLabels[order.status] || order.status;

            // Finished time — show updated_at for delivered/cancelled orders
            let finishedStr = '—';
            if ((order.status === 'delivered' || order.status === 'cancelled') && order.updated_at) {
                const finished = new Date(order.updated_at);
                finishedStr = finished.toLocaleString('en-US', {
                    month: 'short', day: 'numeric',
                    hour: '2-digit', minute: '2-digit', hour12: false
                });
            }

            // Revenue column — positive for delivered, negative for cancelled, dash for in-progress
            let revenueHtml = '<span style="color:var(--k-on-surface-faint)">—</span>';
            const total = order.total || order.subtotal || 0;
            if (order.status === 'delivered' && total > 0) {
                revenueHtml = `<span style="color:#10B981;font-weight:700;">+$${parseFloat(total).toFixed(2)}</span>`;
            } else if (order.status === 'cancelled' && total > 0) {
                revenueHtml = `<span style="color:#dc2626;font-weight:700;">-$${parseFloat(total).toFixed(2)}</span>`;
            }

            return `
                <tr>
                    <td class="order-num-cell">${order.order_number}</td>
                    <td>
                        <span class="table-badge ${order.status}">
                            <span class="table-badge-dot"></span>
                            ${label}
                        </span>
                    </td>
                    <td><div class="table-items-summary">${itemsSummary || '—'}</div></td>
                    <td>${order.customer_name || '<span style="color:var(--k-on-surface-faint)">—</span>'}</td>
                    <td style="color:var(--k-on-surface-muted);">${order.estimated_preparation_minutes || '?'} min</td>
                    <td class="table-time">${timeStr}</td>
                    <td class="table-time">${finishedStr}</td>
                    <td>${revenueHtml}</td>
                </tr>`;
        }).join('');
    }
</script>
@endpush
