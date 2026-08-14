<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'SIDBM Export')</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    :root {
      --sidebar     : #526D82;
      --sidebar-hov : #27374D;
      --sidebar-act : #27374D;
      --navbar      : #FFFFFF;
      --content-bg  : #F4F6F9;
      --border      : #E5E7EB;
      --teks        : #111827;
      --teks-muted  : #6B7280;
      --radius-md   : 10px;
      --shadow-nav  : 0 2px 12px rgba(0, 0, 0, 0.06);
      --shadow-card : 0 1px 3px rgba(0,0,0,.04), 0 4px 16px rgba(0,0,0,.04);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html, body {
      font-family: 'Poppins', 'Inter', system-ui, -apple-system, sans-serif;
      color: var(--teks);
      background: var(--content-bg);
      min-height: 100vh;
      -webkit-font-smoothing: antialiased;
    }

    a { text-decoration: none; color: inherit; }
    button { font-family: inherit; }

    /* ── Shell ── */
    .admin-shell {
      display: flex;
      height: 100vh;
      overflow: hidden;
    }

    /* ── Sidebar ── */
    .sidebar {
      width: 250px;
      flex-shrink: 0;
      background: var(--sidebar);
      color: #FFFFFF;
      display: flex;
      flex-direction: column;
      padding: 24px 18px;
      position: sticky;
      top: 0;
      height: 100vh;
      border-radius: 0 16px 16px 0;
    }

    .sidebar__brand {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 6px 8px 24px;
      margin-bottom: 18px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }
    .sidebar__close {
      display: none;
      margin-left: auto;
      width: 36px; height: 36px;
      border: 0; border-radius: 50%;
      background: rgba(255,255,255,.12);
      color: white; cursor: pointer;
      align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .sidebar__close svg { width: 18px; height: 18px; stroke-width: 2; }
    .sidebar__brand-logo {
      width: 40px; height: 40px;
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.12);
      display: flex; align-items: center; justify-content: center;
      font-size: 1.25rem;
    }
    .sidebar__brand-text { line-height: 1.2; }
    .sidebar__brand-text strong { font-weight: 700; font-size: 1rem; letter-spacing: .3px; }
    .sidebar__brand-text small  { display: block; font-size: .72rem; color: rgba(255,255,255,.7); margin-top: 2px; }

    .sidebar__group-label {
      font-size: .7rem;
      font-weight: 600;
      letter-spacing: .8px;
      color: rgba(255, 255, 255, 0.55);
      text-transform: uppercase;
      padding: 14px 12px 8px;
    }

    .sidebar__nav { display: flex; flex-direction: column; gap: 6px; }

    .sidebar__link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 16px;
      border-radius: 999px;
      color: #FFFFFF;
      font-size: .9rem;
      font-weight: 500;
      transition: background .3s ease, color .3s ease, transform .3s ease;
    }
    .sidebar__link svg {
      width: 20px; height: 20px;
      flex-shrink: 0;
      stroke-width: 1.8;
    }
    .sidebar__link:hover {
      background: var(--sidebar-hov);
    }
    .sidebar__link.active {
      background: var(--sidebar-act);
      font-weight: 600;
    }

    .navbar__menu {
      width: 40px; height: 40px;
      border: 0;
      border-radius: 50%;
      display: none;
      align-items: center; justify-content: center;
      color: var(--teks-muted);
      background: transparent;
      cursor: pointer;
      transition: background .2s ease, color .2s ease;
    }
    .navbar__menu.visible {
      display: flex;
    }
    .navbar__menu:hover { background: var(--content-bg); color: var(--teks); }
    .navbar__menu svg { width: 20px; height: 20px; stroke-width: 1.8; }

    .admin-main {
      flex: 1;
      min-width: 0;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      height: 100vh;
    }
    .admin-main > .navbar {
      flex-shrink: 0;
    }
    .admin-content {
      flex: 1;
      padding: 20px 32px 24px;
      overflow-y: auto;
      min-height: 0;
    }

    /* ── Navbar ── */
    .navbar {
      background: var(--navbar);
      height: 70px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 32px;
      box-shadow: var(--shadow-nav);
      position: sticky;
      top: 0;
      z-index: 50;
    }
    .navbar__title {
      font-size: 1.15rem;
      font-weight: 600;
      color: var(--teks);
    }
    .navbar__right {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .navbar__icon {
      width: 40px; height: 40px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      color: var(--teks-muted);
      cursor: pointer;
      transition: background .2s ease, color .2s ease;
      position: relative;
    }
    .navbar__icon:hover { background: var(--content-bg); color: var(--teks); }
    .navbar__icon svg { width: 20px; height: 20px; stroke-width: 1.8; }
    .navbar__icon-dot {
      position: absolute;
      top: 9px; right: 10px;
      width: 8px; height: 8px;
      border-radius: 50%;
      background: #DC2626;
      border: 2px solid #fff;
    }

    .navbar__user {
      display: flex;
      align-items: center;
      gap: 12px;
      padding-left: 16px;
      border-left: 1px solid var(--border);
    }
    .navbar__avatar {
      width: 40px; height: 40px;
      border-radius: 50%;
      background: var(--sidebar);
      color: #fff;
      display: flex; align-items: center; justify-content: center;
      font-weight: 600;
      font-size: .9rem;
      flex-shrink: 0;
      overflow: hidden;
    }
    .navbar__avatar img { width: 100%; height: 100%; object-fit: cover; }
    .navbar__user-info { line-height: 1.25; }
    .navbar__user-name   { font-size: .875rem; font-weight: 600; color: var(--teks); }
    .navbar__user-role   { font-size: .72rem; color: var(--teks-muted); }

    .admin-footer {
      padding: 16px 32px;
      border-top: 1px solid var(--border);
      background: var(--navbar);
      color: var(--teks-muted);
      font-size: .78rem;
      text-align: center;
      flex-shrink: 0;
    }

    /* ── Page header (reusable) ── */
    .page-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 16px;
      gap: 12px;
      flex-shrink: 0;
    }
    .page-header h1 {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--teks);
    }
    .page-header__sub {
      font-size: .82rem;
      color: var(--teks-muted);
      margin-top: 2px;
    }

    /* ── Card ── */
    .card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      padding: 22px;
      box-shadow: var(--shadow-card);
      margin-bottom: 20px;
      min-width: 0;
      display: flex;
      flex-direction: column;
    }
    .card__title {
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 16px;
      padding-bottom: 12px;
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
    }
    .card__title a { font-size: .8rem; color: var(--sidebar); font-weight: 500; }

    /* ── Form ── */
    .form-group  { margin-bottom: 16px; }
    .form-label  { display: block; font-size: .85rem; font-weight: 500; margin-bottom: 6px; color: var(--teks); }
    .form-select, .form-input {
      width: 100%; padding: 10px 12px;
      border: 1px solid var(--border); border-radius: 999px;
      font-size: .9rem; font-family: inherit;
      outline: none; background: white; color: var(--teks);
      transition: border-color .2s ease, box-shadow .2s ease;
    }
    .form-select:focus, .form-input:focus {
      border-color: var(--sidebar);
      box-shadow: 0 0 0 3px rgba(82, 109, 130, .12);
    }
    .form-row { display: grid; gap: 16px; }
    .form-row--3 { grid-template-columns: repeat(3, 1fr); }
    .form-row--2 { grid-template-columns: repeat(2, 1fr); }

    /* ── Radio cards ── */
    .radio-group { display: flex; gap: 8px; flex-wrap: wrap; }
    .radio-option input { display: none; }
    .radio-option__box {
      display: flex; align-items: center; gap: 8px;
      padding: 10px 16px; border: 2px solid var(--border);
      border-radius: 999px; cursor: pointer; transition: all .2s ease;
      font-size: .875rem;
    }
    .radio-option__box .icon { font-size: 1.05rem; }
    .radio-option input:checked + .radio-option__box {
      border-color: var(--sidebar); background: rgba(82,109,130,.08); color: var(--sidebar);
      font-weight: 600;
    }

    /* ── Buttons ── */
    .btn {
      display: inline-flex; align-items: center; justify-content: center; gap: 6px;
      padding: 10px 20px; border: none; border-radius: 999px;
      font-size: .9rem; font-weight: 600; cursor: pointer; transition: all .2s ease;
      font-family: inherit;
    }
    .btn--primary  { background: var(--sidebar); color: white; }
    .btn--primary:hover  { background: var(--sidebar-act); transform: translateY(-1px); }
    .btn--danger   { background: #DC2626; color: white; }
    .btn--full     { width: 100%; }
    .btn:disabled  { opacity: .5; cursor: not-allowed; transform: none !important; }

    /* ── Badge ── */
    .badge {
      display: inline-flex; align-items: center; gap: 4px;
      padding: 3px 10px; border-radius: 999px; font-size: .72rem; font-weight: 600;
    }
    .badge--success { background: #DCFCE7; color: #15803D; }
    .badge--failed  { background: #FEE2E2; color: #B91C1C; }
    .badge--pending { background: #FEF9C3; color: #92400E; }

    /* ── Log ── */
    .log-item {
      display: flex; align-items: flex-start; gap: 10px;
      padding: 10px 14px; border-radius: 8px; margin-bottom: 6px; font-size: .875rem;
    }
    .log-item--success { background: #F0FDF4; border: 1px solid #BBF7D0; color: #15803D; }
    .log-item--error   { background: #FEF2F2; border: 1px solid #FECACA; color: #B91C1C; }
    .log-item--info    { background: #F0F9FF; border: 1px solid #BAE6FD; color: #0369A1; }
    .log-item__detail  { font-size: .8rem; opacity: .75; margin-top: 2px; }

    /* ── Table ── */
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: .875rem; }
    th { background: var(--sidebar); color: white; padding: 11px 14px; text-align: left; font-weight: 600; font-size: .8rem; white-space: nowrap; }
    th:first-child { border-top-left-radius: 8px; }
    th:last-child  { border-top-right-radius: 8px; }
    td { padding: 11px 14px; border-bottom: 1px solid var(--border); vertical-align: middle; }
    tr:hover td { background: #F9FAFB; }
    .table-link { color: var(--sidebar); font-size: .82rem; }
    .text-right { text-align: right; }
    .text-muted { color: var(--teks-muted); }
    .text-sm    { font-size: .8rem; }
    .error-msg  { font-size: .75rem; color: #DC2626; margin-top: 3px; }

    /* ── Page Header Hero (shared) ── */
    .page-header-hero {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      flex-wrap: wrap;
      background: linear-gradient(135deg, #526D82 0%, #27374D 100%);
      padding: 24px 28px;
      border-radius: 16px;
      color: white;
      box-shadow: 0 8px 32px rgba(82, 109, 130, 0.3);
    }
    .page-header-hero__left {
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .page-header-hero__icon {
      width: 56px;
      height: 56px;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .page-header-hero__icon svg {
      width: 28px;
      height: 28px;
    }
    .page-header-hero__text h1 {
      font-size: 1.4rem;
      font-weight: 700;
      margin: 0 0 4px;
      color: white;
    }
    .page-header-hero__text p {
      font-size: .85rem;
      opacity: .85;
      margin: 0;
    }

    /* ── Stats ── */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    .stat-card {
      background: white;
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 20px;
      box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
      transition: all .2s ease;
      position: relative;
      overflow: hidden;
    }
    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
    }
    .stat-card--total::before { background: linear-gradient(90deg, #2563EB, #3B82F6); }
    .stat-card--success::before { background: linear-gradient(90deg, #16A34A, #22C55E); }
    .stat-card--failed::before { background: linear-gradient(90deg, #DC2626, #EF4444); }
    .stat-card--pending::before { background: linear-gradient(90deg, #D97706, #F59E0B); }
    .stat-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 30px rgba(15, 23, 42, 0.1);
    }
    .stat-card__header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
    }
    .stat-card__title {
      font-size: .82rem;
      font-weight: 600;
      color: #64748B;
    }
    .stat-card__badge {
      font-size: .72rem;
      font-weight: 600;
      padding: 4px 10px;
      border-radius: 50px;
    }
    .badge--today { background: #DBEAFE; color: #1D4ED8; }
    .badge--success { background: #DCFCE7; color: #16A34A; }
    .badge--danger { background: #FEE2E2; color: #DC2626; }
    .badge--pending { background: #FEF3C7; color: #D97706; }
    .stat-card__num {
      font-size: 2rem;
      font-weight: 700;
      color: #0F172A;
      line-height: 1;
    }
    .stat-card__num--success { color: #16A34A; }
    .stat-card__num--danger { color: #DC2626; }
    .stat-card__num--warning { color: #D97706; }
    .stat-card__subtitle {
      font-size: .78rem;
      color: #94A3B8;
      margin-top: 4px;
    }

    /* ── Ping Badge ── */
    .ping-badge {
      display: flex;
      align-items: center;
      gap: 10px;
      background: rgba(255, 255, 255, 0.12);
      padding: 10px 18px;
      border-radius: 50px;
      font-size: .82rem;
      backdrop-filter: blur(8px);
    }
    .ping-badge__dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: #EF4444;
      box-shadow: 0 0 8px rgba(239, 68, 68, 0.6);
    }
    .ping-badge__dot--ok {
      background: #22C55E;
      box-shadow: 0 0 8px rgba(34, 197, 94, 0.6);
    }

    /* ── Two-column area ── */
    .grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; align-items: start; }
    .grid-2 > * { min-width: 0; }

    /* ── Log row ── */
    .log-row {
      display: flex; justify-content: space-between; align-items: flex-start;
      padding: 10px 0; border-bottom: 1px solid var(--border);
    }
    .log-row:last-child { border-bottom: none; }
    .log-row__title { font-size: .875rem; font-weight: 500; }
    .log-row__meta  { font-size: .78rem; color: var(--teks-muted); margin-top: 2px; }

    /* ── Utility ── */
    .hidden    { display: none !important; }
    .text-muted{ color: var(--teks-muted); font-size: .875rem; }
    .mt-12     { margin-top: 12px; }

    /* ── Chart Card ── */
    .chart-card {
      background: white;
      border: 1px solid var(--border);
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
      overflow: hidden;
    }
    .chart-header {
      padding: 20px 24px;
      border-bottom: 1px solid var(--border);
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 16px;
      flex-wrap: wrap;
    }
    .chart-title-area {
      flex: 1;
    }
    .chart-title {
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--teks);
      margin: 0 0 4px;
    }
    .chart-subtitle {
      font-size: .82rem;
      color: var(--teks-muted);
      margin: 0;
    }
    .chart-legend {
      display: flex;
      gap: 16px;
      flex-shrink: 0;
    }
    .legend-item {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: .82rem;
      color: var(--teks);
    }
    .legend-dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
    }
    .legend-dot--success { background: #16A34A; }
    .legend-dot--failed { background: #DC2626; }
    .chart-container {
      padding: 20px 24px 24px;
      height: 320px;
    }
    .chart-container canvas {
      width: 100% !important;
      height: 100% !important;
    }

    /* ── Dashboard Bottom Grid ── */
    .dashboard-bottom-grid {
      display: grid;
      grid-template-columns: 1fr 380px;
      gap: 20px;
      align-items: start;
    }

    /* ── Summary Card ── */
    .summary-card {
      background: white;
      border: 1px solid var(--border);
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
      overflow: hidden;
    }
    .summary-header {
      padding: 20px 24px;
      border-bottom: 1px solid var(--border);
    }
    .summary-title {
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--teks);
      margin: 0 0 4px;
    }
    .summary-period {
      font-size: .82rem;
      color: var(--teks-muted);
    }
    .summary-body {
      padding: 16px 24px;
    }
    .summary-list {
      display: flex;
      flex-direction: column;
      gap: 0;
    }
    .summary-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 0;
      border-bottom: 1px solid #F1F5F9;
    }
    .summary-item:last-child {
      border-bottom: none;
    }
    .summary-item__left {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .summary-bullet {
      width: 10px;
      height: 10px;
      border-radius: 50%;
    }
    .summary-bullet--green { background: #22C55E; }
    .summary-bullet--red { background: #EF4444; }
    .summary-bullet--yellow { background: #F59E0B; }
    .summary-bullet--gray { background: #CBD5E1; }
    .summary-item__label {
      font-size: .875rem;
      font-weight: 500;
      color: #64748B;
    }
    .summary-item__value {
      font-size: 1.1rem;
      font-weight: 700;
      color: #0F172A;
    }
    .summary-item__value--green { color: #16A34A; }
    .summary-item__value--red { color: #DC2626; }
    .summary-footer {
      padding: 16px 24px;
      background: #F8FAFC;
      border-top: 1px solid var(--border);
      font-size: .78rem;
      color: var(--teks-muted);
      line-height: 1.6;
    }
    .summary-highlight {
      color: #DC2626;
      font-weight: 600;
      font-weight: 600;
    }
    .dashboard-bottom-grid > * {
      min-width: 0;
    }

    /* ── Status Card ── */
    .status-card,
    .activity-card {
      border-radius: 12px;
      padding: 24px;
    }
    .status-list {
      margin-bottom: 8px;
    }
    .status-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 0;
      border-bottom: 1px solid var(--border);
    }
    .status-item:last-child {
      border-bottom: none;
    }
    .status-dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      flex-shrink: 0;
    }
    .status-dot--ok {
      background: #16A34A;
    }
    .status-dot--error {
      background: #DC2626;
    }
    .status-label {
      font-size: .82rem;
      font-weight: 500;
    }
    .status-value {
      font-size: .75rem;
      color: var(--teks-muted);
      margin-top: 2px;
    }

    /* ── Activity List ── */
    .activity-list {
      max-height: 280px;
      overflow-y: auto;
    }
    .activity-item {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      padding: 10px 0;
      border-bottom: 1px solid var(--border);
    }
    .activity-item:last-child {
      border-bottom: none;
    }
    .activity-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      flex-shrink: 0;
      margin-top: 5px;
    }
    .activity-dot--success {
      background: #16A34A;
    }
    .activity-dot--failed {
      background: #DC2626;
    }
    .activity-dot--pending {
      background: #D97706;
    }
    .activity-text {
      font-size: .82rem;
    }
    .activity-time {
      font-size: .72rem;
      color: var(--teks-muted);
      margin-top: 2px;
    }

    /* ── Notification Dropdown ── */
    .notif-dropdown {
      position: relative;
    }
    .notif-panel {
      position: absolute;
      top: calc(100% + 8px);
      right: -10px;
      width: 380px;
      background: white;
      border: 1px solid var(--border);
      border-radius: 12px;
      box-shadow: 0 8px 32px rgba(0,0,0,.12);
      z-index: 100;
      overflow: hidden;
    }
    .notif-panel__header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 16px;
      border-bottom: 1px solid var(--border);
    }
    .notif-panel__title {
      font-size: .9rem;
      font-weight: 600;
      color: var(--teks);
    }
    .notif-panel__actions {
      display: flex;
      gap: 8px;
    }
    .notif-panel__btn {
      font-size: .75rem;
      color: var(--sidebar);
      background: none;
      border: none;
      cursor: pointer;
      padding: 4px 8px;
      border-radius: 6px;
      transition: background .15s;
    }
    .notif-panel__btn:hover {
      background: var(--content-bg);
    }
    .notif-panel__body {
      max-height: 400px;
      overflow-y: auto;
    }
    .notif-empty {
      padding: 32px 16px;
      text-align: center;
      color: var(--teks-muted);
      font-size: .85rem;
    }
    .notif-empty svg {
      width: 40px;
      height: 40px;
      margin: 0 auto 10px;
      opacity: .4;
    }
    .notif-item {
      display: flex;
      gap: 12px;
      padding: 14px 16px;
      border-bottom: 1px solid var(--border);
      cursor: pointer;
      transition: background .15s;
      position: relative;
    }
    .notif-item:hover {
      background: var(--content-bg);
    }
    .notif-item:last-child {
      border-bottom: none;
    }
    .notif-item.unread {
      background: rgba(82,109,130,.04);
    }
    .notif-item.unread::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      width: 3px;
      background: var(--sidebar);
      border-radius: 0 2px 2px 0;
    }
    .notif-icon {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .notif-icon svg {
      width: 18px;
      height: 18px;
    }
    .notif-icon--success {
      background: #DCFCE7;
      color: #16A34A;
    }
    .notif-icon--error {
      background: #FEE2E2;
      color: #DC2626;
    }
    .notif-icon--info {
      background: #E0F2FE;
      color: #0284C7;
    }
    .notif-icon--warning {
      background: #FEF3C7;
      color: #D97706;
    }
    .notif-content {
      flex: 1;
      min-width: 0;
    }
    .notif-title {
      font-size: .82rem;
      font-weight: 600;
      color: var(--teks);
      margin-bottom: 2px;
    }
    .notif-body {
      font-size: .78rem;
      color: var(--teks-muted);
      line-height: 1.4;
    }
    .notif-time {
      font-size: .72rem;
      color: #94A3B8;
      margin-top: 4px;
    }
    .notif-delete {
      position: absolute;
      top: 10px;
      right: 10px;
      width: 24px;
      height: 24px;
      border-radius: 50%;
      background: none;
      border: none;
      color: #CBD5E1;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity .15s, background .15s;
    }
    .notif-item:hover .notif-delete {
      opacity: 1;
    }
    .notif-delete:hover {
      background: #FEE2E2;
      color: #DC2626;
    }
    .notif-delete svg {
      width: 14px;
      height: 14px;
    }

    /* ── Toast Notification ── */
    .toast-container {
      position: fixed;
      bottom: 24px;
      right: 24px;
      z-index: 9999;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .toast {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 14px 16px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0,0,0,.12);
      border: 1px solid var(--border);
      min-width: 300px;
      max-width: 400px;
      animation: toastIn .3s ease;
    }
    @keyframes toastIn {
      from { transform: translateX(100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }
    .toast.out {
      animation: toastOut .3s ease forwards;
    }
    @keyframes toastOut {
      to { transform: translateX(100%); opacity: 0; }
    }
    .toast-icon {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .toast-icon svg {
      width: 16px;
      height: 16px;
    }
    .toast-icon--success { background: #DCFCE7; color: #16A34A; }
    .toast-icon--error { background: #FEE2E2; color: #DC2626; }
    .toast-icon--info { background: #E0F2FE; color: #0284C7; }
    .toast-icon--warning { background: #FEF3C7; color: #D97706; }
    .toast-content { flex: 1; }
    .toast-title { font-size: .85rem; font-weight: 600; color: var(--teks); }
    .toast-body { font-size: .78rem; color: var(--teks-muted); margin-top: 2px; }
    .toast-close {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      background: none;
      border: none;
      color: #CBD5E1;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .toast-close:hover { background: var(--content-bg); color: var(--teks); }
    .toast-close svg { width: 14px; height: 14px; }
    .notif-badge {
      position: absolute;
      top: -4px;
      right: -6px;
      min-width: 22px;
      height: 22px;
      border-radius: 999px;
      background: #DC2626;
      color: white;
      font-size: .7rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 6px;
      line-height: 1;
    }

    /* ── Responsive ── */
    @media (max-width: 1200px) {
      .admin-content {
        padding: 16px 20px 20px;
      }
      .page-header {
        margin-bottom: 16px;
      }
      .stats-grid {
        gap: 12px;
        margin-bottom: 12px;
      }
      .dashboard-bottom-grid {
        grid-template-columns: 1fr 320px;
      }
      .chart-container {
        height: 200px;
      }
      .summary-card {
        padding: 16px;
      }
      .summary-title {
        font-size: 1rem;
      }
      .summary-item {
        padding: 8px 0;
      }
      .summary-item__value {
        font-size: 15px;
      }
      .activity-card {
        grid-column: 1 / -1;
      }
    }

    @media (max-width: 1024px) {
      .grid-2, .form-row--3 { grid-template-columns: 1fr; }
      .form-row--2          { grid-template-columns: 1fr; }
      .stats-grid           { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .dashboard-bottom-grid {
        grid-template-columns: 1fr;
      }
      .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
      }
      .chart-legend {
        flex-wrap: wrap;
        gap: 8px;
      }
      .chart-header {
        flex-direction: column;
      }
    }

    @media (max-width: 768px) {
      html, body { font-size: 15px; }
      .navbar__menu { display: flex !important; }
      .admin-main { margin-left: 0; }
      .sidebar {
        position: fixed;
        left: -260px;
        top: 0;
        height: 100vh;
        width: 250px;
        z-index: 60;
        transition: left .3s ease;
        border-radius: 0 16px 16px 0;
      }
      .sidebar.mobile-show {
        left: 0;
      }
      .sidebar__close { display: inline-flex; }
      .sidebar__link  { font-size: .88rem; padding: 10px 14px; }
      .admin-content { padding: 16px 14px 28px; }
      .admin-footer  { padding: 10px 14px; font-size: .72rem; }

      .navbar  { padding: 0 16px; height: 60px; }
      .navbar__title { font-size: .95rem; }
      .admin-content { padding: 14px 14px 20px; }
      .admin-footer { padding: 10px 14px; font-size: .72rem; }

      .stats-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
      }
      .stat-card { padding: 14px 12px; }
      .stat-card__num   { font-size: 1.5rem; }
      .stat-card__label { font-size: .76rem; margin-top: 4px; }

      .card, .form-card { padding: 16px; }
      .card__title { font-size: .95rem; }
      .page-header h1 { font-size: 1.05rem; }
      .page-header__sub { font-size: .8rem; }

      .chart-card {
        padding: 16px;
      }
      .chart-container {
        height: 180px;
      }
      .summary-card {
        padding: 16px;
      }
      .summary-title {
        font-size: 1rem;
      }
      .summary-item {
        padding: 8px 0;
      }
      .summary-item__value {
        font-size: 15px;
      }

      .table-wrap {
        margin: 0 -16px;
        padding: 0 16px;
      }
      table {
        font-size: .8rem;
      }
      th, td {
        padding: 10px 10px;
      }
      .table-link {
        font-size: .75rem;
      }
      .error-msg {
        font-size: .7rem;
      }
    }

    @media (max-width: 640px) {
      .stats-grid {
        grid-template-columns: 1fr;
        gap: 12px;
      }
      .stat-card {
        display: flex;
        flex-direction: column;
        gap: 4px;
      }
      .stat-card__header {
        margin-bottom: 8px;
      }
      .stat-card__num {
        font-size: 1.8rem;
      }
      .stat-card__subtitle {
        font-size: .75rem;
      }

      .page-header {
        flex-direction: column;
        align-items: flex-start;
        padding: 16px;
        gap: 10px;
      }
      .page-header > div:first-child {
        width: 100%;
      }
      .page-header h1 {
        font-size: 1.1rem;
      }
      .page-header > span {
        align-self: flex-start;
      }

      .card {
        padding: 14px;
        border-radius: 12px;
      }
      .card__title {
        font-size: .9rem;
        padding-bottom: 10px;
        margin-bottom: 12px;
      }

      .form-row--3,
      .form-row--2 {
        grid-template-columns: 1fr;
        gap: 12px;
      }

      .navbar__user {
        display: none;
      }
      .navbar__user.mobile-show {
        display: flex;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 12px 16px;
        background: white;
        border-top: 1px solid var(--border);
        box-shadow: 0 -4px 12px rgba(0,0,0,.06);
        z-index: 40;
      }

      .summary-footer {
        font-size: .72rem;
        line-height: 1.4;
      }

      .notif-panel {
        width: calc(100vw - 32px);
        right: -8px;
      }
    }

    @media (max-width: 480px) {
      .admin-content {
        padding: 12px 10px 100px;
      }
      .card {
        padding: 12px;
        border-radius: 10px;
      }
      .page-header h1 {
        font-size: 1rem;
      }
      .page-header__sub {
        font-size: .75rem;
      }

      .btn {
        padding: 10px 16px;
        font-size: .85rem;
      }

      .chart-container {
        height: 160px;
      }

      .summary-item__value {
        font-size: 14px;
      }

      .toast {
        min-width: 280px;
        max-width: calc(100vw - 32px);
      }
    }

    @media (prefers-reduced-motion: reduce) {
      *,
      *::before,
      *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
      }
    }
  </style>
</head>
<body>

  <div class="admin-shell">

    {{-- ── Sidebar ── --}}
    <aside class="sidebar">
      <div class="sidebar__brand">
        <div class="sidebar__brand-logo">📦</div>
        <div class="sidebar__brand-text">
          <strong>SIDBM</strong>
          <small>Export</small>
        </div>
        <button type="button" class="sidebar__close" aria-label="Tutup sidebar" onclick="document.querySelector('.sidebar').classList.remove('mobile-show')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 6l12 12M6 18L18 6"/>
          </svg>
        </button>
      </div>

      <div class="sidebar__group-label">Menu Utama</div>
      <nav class="sidebar__nav">
        <a href="{{ route('dashboard') }}"
           class="sidebar__link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 12 12 3l9 9"/>
            <path d="M5 10v10h14V10"/>
          </svg>
          <span>Dashboard</span>
        </a>

        <a href="{{ route('export-data') }}"
           class="sidebar__link {{ request()->routeIs('export-data') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="7 10 12 15 17 10"/>
            <line x1="12" y1="15" x2="12" y2="3"/>
          </svg>
          <span>Export Data</span>
        </a>

        <a href="{{ route('export.logs') }}"
           class="sidebar__link {{ request()->routeIs('export.logs') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <path d="M14 2v6h6"/>
            <path d="M8 13h8M8 17h6"/>
          </svg>
          <span>Log Export</span>
        </a>
      </nav>

      @auth
        <div style="margin-top:auto; padding-top:18px; border-top:1px solid rgba(255,255,255,.08);">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar__link" style="background:none; border:none; width:100%; cursor:pointer; text-align:left;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <path d="M16 17l5-5-5-5"/>
                <path d="M21 12H9"/>
              </svg>
              <span>Logout</span>
            </button>
          </form>
        </div>
      @endauth
    </aside>

    {{-- ── Main ── --}}
    <div class="admin-main">

      {{-- Navbar --}}
      <header class="navbar">
        <div style="display:flex; align-items:center; gap:10px;">
          <button type="button" class="navbar__menu" aria-label="Buka sidebar" onclick="document.querySelector('.sidebar').classList.toggle('mobile-show')" style="display:none;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
          </button>
          <div class="navbar__title">@yield('navbar_title', 'Dashboard')</div>
        </div>

        <div class="navbar__right" x-data="notificationDropdown()" @click.away="open = false">
          <div class="notif-dropdown">
            <div class="navbar__icon" title="Notifikasi" @click="open = !open" :class="{ 'bg-gray-100': open }">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
              </svg>
              <template x-if="unreadCount > 0">
                <span class="notif-badge" x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
              </template>
            </div>

            <div class="notif-panel" x-show="open" x-transition>
              <div class="notif-panel__header">
                <span class="notif-panel__title">Notifikasi</span>
                <div class="notif-panel__actions">
                  <button class="notif-panel__btn" @click="markAllAsRead()" x-show="unreadCount > 0">Tandai semua baca</button>
                </div>
              </div>
              <div class="notif-panel__body">
                <template x-if="loading">
                  <div class="notif-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                    </svg>
                    <div>Memuat...</div>
                  </div>
                </template>
                <template x-if="!loading && notifications.length === 0">
                  <div class="notif-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                      <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                    </svg>
                    <div>Tidak ada notifikasi</div>
                  </div>
                </template>
                <template x-for="notif in notifications" :key="notif.id">
                  <div class="notif-item" :class="{ 'unread': !notif.pivot?.read_at }" @click="markAsRead(notif.id)">
                    <div class="notif-icon" :class="notif.type === 'success' ? 'notif-icon--success' : notif.type === 'error' ? 'notif-icon--error' : notif.type === 'warning' ? 'notif-icon--warning' : 'notif-icon--info'">
                      <svg x-show="notif.type === 'success'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                      </svg>
                      <svg x-show="notif.type === 'error'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                      </svg>
                      <svg x-show="notif.type === 'info' || !notif.type" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                      </svg>
                      <svg x-show="notif.type === 'warning'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                      </svg>
                    </div>
                    <div class="notif-content">
                      <div class="notif-title" x-text="notif.title"></div>
                      <div class="notif-body" x-text="notif.body" x-show="notif.body"></div>
                      <div class="notif-time" x-text="formatTime(notif.created_at)"></div>
                    </div>
                    <button class="notif-delete" @click.stop="deleteNotification(notif.id)" title="Hapus">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                      </svg>
                    </button>
                  </div>
                </template>
              </div>
            </div>
          </div>

          @auth
            <div class="navbar__user">
              <div class="navbar__avatar">
                {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
              </div>
              <div class="navbar__user-info">
                <div class="navbar__user-name">{{ Auth::user()->name }}</div>
                <div class="navbar__user-role">Administrator</div>
              </div>
            </div>
          @endauth
        </div>
      </header>

      {{-- Content --}}
       <main class="admin-content @yield('content_class')">
         @yield('content')
       </main>

       <footer class="admin-footer">abt-pkl-2026</footer>

       @hasSection('scripts')
          @yield('scripts')
        @endif

      </div>

      {{-- Toast Container --}}
      <div class="toast-container" id="toastContainer"></div>

   </div>

   <script>
   function showToast(title, body, type = 'info') {
       const container = document.getElementById('toastContainer');
       const icons = {
           success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
           error: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
           warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
           info: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
       };
       const toast = document.createElement('div');
       toast.className = 'toast';
       toast.innerHTML = `
           <div class="toast-icon toast-icon--${type}">${icons[type]}</div>
           <div class="toast-content">
               <div class="toast-title">${title}</div>
               ${body ? `<div class="toast-body">${body}</div>` : ''}
           </div>
           <button class="toast-close" onclick="this.parentElement.remove()">
               <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                   <line x1="18" y1="6" x2="6" y2="18"/>
                   <line x1="6" y1="6" x2="18" y2="18"/>
               </svg>
           </button>
       `;
       container.appendChild(toast);
       setTimeout(() => { toast.classList.add('out'); setTimeout(() => toast.remove(), 300); }, 5000);
   }
   window.showToast = showToast;
   </script>

</body>
</html>