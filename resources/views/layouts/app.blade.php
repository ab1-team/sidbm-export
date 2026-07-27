<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'SIDBM Export')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary     : #1E40AF;
      --primary-lt  : #3B82F6;
      --primary-dk  : #1E3A8A;
      --secondary   : #64748B;
      --success     : #059669;
      --warning     : #D97706;
      --danger      : #DC2626;
      --bg          : #F1F5F9;
      --bg-white    : #FFFFFF;
      --border      : #E2E8F0;
      --text        : #1E293B;
      --text-lt     : #64748B;
      --text-dk     : #0F172A;
      --shadow-sm   : 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow      : 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
      --shadow-md   : 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
      --shadow-lg   : 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
      --shadow-xl   : 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
      --radius-sm   : 6px;
      --radius      : 12px;
      --radius-lg   : 16px;
      --radius-xl   : 20px;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html { font-size: 16px; }

    body {
      font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
      background: var(--bg);
      color: var(--text);
      line-height: 1.5;
      min-height: 100vh;
    }

    /* ── Layout ── */
    .app-layout {
      display: flex;
      min-height: 100vh;
    }

    /* ── Sidebar ── */
    .sidebar {
      width: 260px;
      background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dk) 100%);
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      z-index: 100;
      box-shadow: 4px 0 20px rgba(30, 64, 175, 0.15);
    }
    .sidebar__header {
      padding: 24px 20px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .sidebar__brand {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .sidebar__logo {
      width: 44px;
      height: 44px;
      background: rgba(255,255,255,0.15);
      border-radius: var(--radius);
      display: flex;
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(10px);
    }
    .sidebar__logo svg { width: 24px; height: 24px; stroke: white; }
    .sidebar__title {
      color: white;
      font-size: 1.1rem;
      font-weight: 700;
      line-height: 1.3;
    }
    .sidebar__subtitle {
      color: rgba(255,255,255,0.6);
      font-size: .7rem;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: .5px;
    }
    .sidebar__nav {
      flex: 1;
      padding: 20px 12px;
      overflow-y: auto;
    }
    .sidebar__section-title {
      color: rgba(255,255,255,0.4);
      font-size: .65rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      padding: 0 12px;
      margin-bottom: 8px;
    }
    .sidebar__menu {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 4px;
    }
    .sidebar__link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 14px;
      color: rgba(255,255,255,0.7);
      text-decoration: none;
      border-radius: var(--radius);
      font-size: .875rem;
      font-weight: 500;
      transition: all .2s ease;
      position: relative;
    }
    .sidebar__link:hover {
      background: rgba(255,255,255,0.1);
      color: white;
    }
    .sidebar__link.active {
      background: rgba(255,255,255,0.15);
      color: white;
      font-weight: 600;
    }
    .sidebar__link.active::before {
      content: '';
      position: absolute;
      left: 0;
      top: 50%;
      transform: translateY(-50%);
      width: 3px;
      height: 24px;
      background: white;
      border-radius: 0 3px 3px 0;
    }
    .sidebar__link svg {
      width: 20px;
      height: 20px;
      flex-shrink: 0;
      stroke: currentColor;
    }
    .sidebar__footer {
      padding: 16px 20px;
      border-top: 1px solid rgba(255,255,255,0.1);
    }
    .sidebar__footer-text {
      color: rgba(255,255,255,0.5);
      font-size: .7rem;
      text-align: center;
    }

    /* ── Main Content ── */
    .main {
      flex: 1;
      margin-left: 260px;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    .main__header {
      background: var(--bg-white);
      padding: 0 32px;
      height: 72px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid var(--border);
      position: sticky;
      top: 0;
      z-index: 50;
      box-shadow: var(--shadow-sm);
    }
    .main__left {
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .main__title {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--text-dk);
    }
    .main__breadcrumb {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: .8rem;
      color: var(--text-lt);
    }
    .main__breadcrumb svg { width: 14px; height: 14px; }
    .main__right {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .header__date {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 8px 14px;
      background: var(--bg);
      border-radius: var(--radius);
      font-size: .8rem;
      color: var(--text-lt);
    }
    .header__date svg { width: 16px; height: 16px; }
    .header__avatar {
      width: 40px;
      height: 40px;
      background: linear-gradient(135deg, var(--primary-lt) 0%, var(--primary) 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 700;
      font-size: .9rem;
    }
    .main__body {
      flex: 1;
      padding: 32px;
    }

    /* ── Welcome Banner ── */
    .welcome-banner {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dk) 100%);
      border-radius: var(--radius-lg);
      padding: 32px;
      margin-bottom: 28px;
      position: relative;
      overflow: hidden;
      box-shadow: var(--shadow-lg);
    }
    .welcome-banner::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -10%;
      width: 400px;
      height: 400px;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      border-radius: 50%;
    }
    .welcome-banner::after {
      content: '';
      position: absolute;
      bottom: -30%;
      right: 10%;
      width: 300px;
      height: 300px;
      background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
      border-radius: 50%;
    }
    .welcome-banner__content { position: relative; z-index: 1; }
    .welcome-banner__title {
      color: white;
      font-size: 1.5rem;
      font-weight: 800;
      margin-bottom: 8px;
    }
    .welcome-banner__text {
      color: rgba(255,255,255,0.8);
      font-size: .95rem;
      max-width: 600px;
    }
    .welcome-banner__stats {
      display: flex;
      gap: 32px;
      margin-top: 24px;
    }
    .welcome-banner__stat {
      color: white;
    }
    .welcome-banner__stat-num {
      font-size: 1.75rem;
      font-weight: 800;
      line-height: 1;
    }
    .welcome-banner__stat-label {
      font-size: .75rem;
      opacity: 0.8;
      margin-top: 4px;
    }

    /* ── Stats Grid ── */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 24px;
      margin-bottom: 28px;
    }
    @media (max-width: 1200px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px) { .stats-grid { grid-template-columns: 1fr; } }

    .stat-card {
      background: var(--bg-white);
      border-radius: var(--radius-lg);
      padding: 28px 24px;
      box-shadow: var(--shadow);
      border: 1px solid var(--border);
      transition: all .3s ease;
      position: relative;
      overflow: hidden;
    }
    .stat-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-xl);
      border-color: transparent;
    }
    .stat-card__top {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    .stat-card__icon {
      width: 56px;
      height: 56px;
      border-radius: var(--radius);
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: var(--shadow-md);
    }
    .stat-card__icon svg { width: 28px; height: 28px; stroke: white; }
    .stat-card__trend {
      display: flex;
      align-items: center;
      gap: 4px;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: .75rem;
      font-weight: 600;
    }
    .stat-card__trend--up { background: #DCFCE7; color: var(--success); }
    .stat-card__trend--neutral { background: #F1F5F9; color: var(--secondary); }
    .stat-card__num {
      font-size: 2rem;
      font-weight: 800;
      line-height: 1;
      margin-bottom: 8px;
      letter-spacing: -.02em;
    }
    .stat-card__label {
      font-size: .9rem;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 4px;
    }
    .stat-card__sub {
      font-size: .8rem;
      color: var(--text-lt);
    }

    /* Stat Card Colors */
    .stat-card--blue .stat-card__icon { background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%); }
    .stat-card--blue .stat-card__num { color: var(--primary); }
    .stat-card--blue .stat-card__trend { background: #DBEAFE; color: var(--primary); }

    .stat-card--green .stat-card__icon { background: linear-gradient(135deg, #10B981 0%, #059669 100%); }
    .stat-card--green .stat-card__num { color: var(--success); }
    .stat-card--green .stat-card__trend { background: #DCFCE7; color: var(--success); }

    .stat-card--orange .stat-card__icon { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); }
    .stat-card--orange .stat-card__num { color: var(--warning); }
    .stat-card--orange .stat-card__trend { background: #FEF3C7; color: var(--warning); }

    .stat-card--purple .stat-card__icon { background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%); }
    .stat-card--purple .stat-card__num { color: #7C3AED; }
    .stat-card--purple .stat-card__trend { background: #EDE9FE; color: #7C3AED; }

    /* ── Content Grid ── */
    .content-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 28px;
    }
    @media (max-width: 1024px) { .content-grid { grid-template-columns: 1fr; } }

    /* ── Card ── */
    .card {
      background: var(--bg-white);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow);
      border: 1px solid var(--border);
      overflow: hidden;
    }
    .card__header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 20px 24px;
      border-bottom: 1px solid var(--border);
      background: #FAFBFC;
    }
    .card__title {
      font-size: 1rem;
      font-weight: 700;
      color: var(--text-dk);
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .card__title svg { width: 20px; height: 20px; stroke: var(--primary); }
    .card__badge {
      padding: 4px 12px;
      background: #DBEAFE;
      color: var(--primary);
      border-radius: 20px;
      font-size: .75rem;
      font-weight: 600;
    }
    .card__body { padding: 24px; }
    .card__link {
      color: var(--primary-lt);
      text-decoration: none;
      font-size: .85rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .card__link:hover { text-decoration: underline; }
    .card__link svg { width: 16px; height: 16px; }

    /* ── Info List ── */
    .info-list { list-style: none; }
    .info-item {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 16px 0;
      border-bottom: 1px solid var(--border);
    }
    .info-item:last-child { border-bottom: none; padding-bottom: 0; }
    .info-item:first-child { padding-top: 0; }
    .info-item__icon {
      width: 44px;
      height: 44px;
      border-radius: var(--radius);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .info-item__icon--blue { background: #DBEAFE; }
    .info-item__icon--blue svg { stroke: var(--primary); }
    .info-item__icon--green { background: #DCFCE7; }
    .info-item__icon--green svg { stroke: var(--success); }
    .info-item__icon svg { width: 22px; height: 22px; }
    .info-item__content { flex: 1; }
    .info-item__label {
      font-size: .8rem;
      color: var(--text-lt);
      margin-bottom: 2px;
    }
    .info-item__value {
      font-size: .95rem;
      font-weight: 600;
      color: var(--text-dk);
    }

    /* ── Summary Box ── */
    .summary-box {
      background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
      border-radius: var(--radius);
      padding: 20px;
      border: 1px solid #BFDBFE;
    }
    .summary-box__title {
      font-size: .85rem;
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .summary-box__title svg { width: 18px; height: 18px; }
    .summary-box__text {
      font-size: .85rem;
      color: var(--text-lt);
      line-height: 1.6;
    }
    .summary-box__items {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 16px;
    }
    .summary-box__tag {
      padding: 6px 12px;
      background: white;
      border-radius: var(--radius-sm);
      font-size: .75rem;
      font-weight: 600;
      color: var(--text);
      box-shadow: var(--shadow-sm);
    }

    /* ── Table ── */
    .table-wrap { overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th {
      background: var(--primary);
      color: white;
      padding: 14px 16px;
      text-align: left;
      font-size: .8rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .5px;
    }
    .data-table th:first-child { border-radius: var(--radius-sm) 0 0 0; }
    .data-table th:last-child { border-radius: 0 var(--radius-sm) 0 0; }
    .data-table td {
      padding: 14px 16px;
      border-bottom: 1px solid var(--border);
      font-size: .875rem;
    }
    .data-table tbody tr:hover td { background: #F8FAFC; }
    .data-table tbody tr:last-child td { border-bottom: none; }

    /* ── Status Badge ── */
    .status {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: .75rem;
      font-weight: 600;
    }
    .status::before {
      content: '';
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: currentColor;
    }
    .status--success { background: #DCFCE7; color: var(--success); }
    .status--pending { background: #FEF3C7; color: var(--warning); }
    .status--failed { background: #FEE2E2; color: var(--danger); }

    /* ── Buttons ── */
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 10px 20px;
      border: none;
      border-radius: var(--radius);
      font-size: .875rem;
      font-weight: 600;
      cursor: pointer;
      transition: all .2s ease;
      text-decoration: none;
    }
    .btn svg { width: 18px; height: 18px; }
    .btn--primary { background: var(--primary); color: white; }
    .btn--primary:hover { background: var(--primary-dk); transform: translateY(-1px); }
    .btn--outline {
      background: transparent;
      border: 1px solid var(--border);
      color: var(--text);
    }
    .btn--outline:hover { background: var(--bg); border-color: var(--secondary); }
    .btn--sm { padding: 6px 12px; font-size: .8rem; }
    .btn:disabled { opacity: .5; cursor: not-allowed; transform: none !important; }

    /* ── Form ── */
    .form-group { margin-bottom: 16px; }
    .form-label {
      display: block;
      font-size: .85rem;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 6px;
    }
    .form-select, .form-input {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid var(--border);
      border-radius: var(--radius);
      font-size: .875rem;
      font-family: inherit;
      background: white;
      transition: all .2s ease;
    }
    .form-select:focus, .form-input:focus {
      border-color: var(--primary-lt);
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
      outline: none;
    }
    .form-hint { font-size: .75rem; color: var(--text-lt); margin-top: 4px; }

    /* ── Radio Group ── */
    .radio-group { display: flex; gap: 12px; flex-wrap: wrap; }
    .radio-option input { display: none; }
    .radio-option__box {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 10px 16px;
      border: 2px solid var(--border);
      border-radius: var(--radius);
      cursor: pointer;
      transition: all .2s ease;
      font-size: .85rem;
      font-weight: 500;
    }
    .radio-option input:checked + .radio-option__box {
      border-color: var(--primary);
      background: #EFF6FF;
      color: var(--primary);
    }

    /* ── Spinner ── */
    .spinner {
      display: inline-block;
      width: 16px;
      height: 16px;
      border: 2px solid rgba(255,255,255,.3);
      border-radius: 50%;
      border-top-color: white;
      animation: spin .6s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── Empty State ── */
    .empty-state {
      text-align: center;
      padding: 48px 24px;
      color: var(--text-lt);
    }
    .empty-state svg { width: 64px; height: 64px; stroke: var(--border); margin-bottom: 16px; }
    .empty-state__text { font-size: .9rem; }

    /* ── Responsive ── */
    @media (max-width: 768px) {
      .sidebar { width: 70px; }
      .sidebar__header { padding: 16px 12px; }
      .sidebar__brand { justify-content: center; }
      .sidebar__title, .sidebar__subtitle { display: none; }
      .sidebar__link { justify-content: center; padding: 14px; }
      .sidebar__link span { display: none; }
      .sidebar__section-title { display: none; }
      .sidebar__footer { display: none; }
      .main { margin-left: 70px; }
      .main__header { padding: 0 20px; }
      .main__body { padding: 20px; }
      .welcome-banner { padding: 24px; }
      .welcome-banner__stats { gap: 20px; }
      .content-grid { grid-template-columns: 1fr; }
    }

    /* ── Utility ── */
    .hidden { display: none !important; }
    .text-muted { color: var(--text-lt); }
    .mt-4 { margin-top: 16px; }
    .mb-4 { margin-bottom: 16px; }
    .flex { display: flex; }
    .items-center { align-items: center; }
    .justify-between { justify-content: space-between; }
    .gap-2 { gap: 8px; }
    .gap-4 { gap: 16px; }
  </style>
</head>
<body>

<div class="app-layout">
  {{-- ── Sidebar ── --}}
  <aside class="sidebar">
    <div class="sidebar__header">
      <div class="sidebar__brand">
        <div class="sidebar__logo">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <path d="M12 18v-6"/>
            <path d="M9 15l3-3 3 3"/>
          </svg>
        </div>
        <div>
          <div class="sidebar__title">SIDBM Export</div>
          <div class="sidebar__subtitle">Sistem Informasi</div>
        </div>
      </div>
    </div>

    <nav class="sidebar__nav">
      <div class="sidebar__section-title">Menu Utama</div>
      <ul class="sidebar__menu">
        <li>
          <a href="{{ route('export.index') }}" class="sidebar__link {{ request()->routeIs('export.index') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="7" height="7"/>
              <rect x="14" y="3" width="7" height="7"/>
              <rect x="14" y="14" width="7" height="7"/>
              <rect x="3" y="14" width="7" height="7"/>
            </svg>
            <span>Dashboard</span>
          </a>
        </li>
        <li>
          <a href="{{ route('export.data') }}" class="sidebar__link {{ request()->routeIs('export.data') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
              <polyline points="17 8 12 3 7 8"/>
              <line x1="12" y1="3" x2="12" y2="15"/>
            </svg>
            <span>Export Data</span>
          </a>
        </li>
        <li>
          <a href="{{ route('export.logs') }}" class="sidebar__link {{ request()->routeIs('export.logs') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
              <line x1="16" y1="13" x2="8" y2="13"/>
              <line x1="16" y1="17" x2="8" y2="17"/>
              <polyline points="10 9 9 9 8 9"/>
            </svg>
            <span>Riwayat Export</span>
          </a>
        </li>
      </ul>

      <div class="sidebar__section-title" style="margin-top: 24px;">Lainnya</div>
      <ul class="sidebar__menu">
        <li>
          <a href="#" class="sidebar__link">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="3"/>
              <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
            </svg>
            <span>Pengaturan</span>
          </a>
        </li>
        <li>
          <a href="#" class="sidebar__link">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/>
              <line x1="12" y1="16" x2="12" y2="12"/>
              <line x1="12" y1="8" x2="12.01" y2="8"/>
            </svg>
            <span>Tentang</span>
          </a>
        </li>
      </ul>
    </nav>

    <div class="sidebar__footer">
      <div class="sidebar__footer-text">SIDBM Export v1.0.0</div>
    </div>
  </aside>

  {{-- ── Main ── --}}
  <main class="main">
    <header class="main__header">
      <div class="main__left">
        <h1 class="main__title">@yield('page-title', 'Dashboard')</h1>
      </div>
      <div class="main__right">
        <div class="header__date">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
          <span>{{ now()->format('d F Y') }}</span>
        </div>
        <div class="header__avatar">A</div>
      </div>
    </header>

    <div class="main__body">
      @yield('content')
    </div>
  </main>
</div>

</body>
</html>
