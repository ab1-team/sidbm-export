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

    .admin-shell.sidebar-collapsed .sidebar {
      width: 76px;
      padding-left: 10px;
      padding-right: 10px;
      overflow: hidden;
    }

    .admin-shell.sidebar-collapsed .sidebar__brand {
      justify-content: center;
      padding-left: 0;
      padding-right: 0;
    }

    .admin-shell.sidebar-collapsed .sidebar__brand-text,
    .admin-shell.sidebar-collapsed .sidebar__group-label,
    .admin-shell.sidebar-collapsed .sidebar__link span {
      display: none;
    }

    .admin-shell.sidebar-collapsed .sidebar__link {
      justify-content: center;
      gap: 0;
      padding-left: 0;
      padding-right: 0;
    }

    .admin-shell.sidebar-collapsed .navbar__menu {
      transform: rotate(180deg);
    }

    .navbar__menu {
      width: 40px; height: 40px;
      border: 0;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      color: var(--teks-muted);
      background: transparent;
      cursor: pointer;
      transition: background .2s ease, color .2s ease, transform .2s ease;
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
    }
    .admin-content {
      padding: 20px 32px 24px;
      flex: 1;
      overflow-y: auto;
      min-height: 0;
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

    /* ── Stats ── */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 16px; }
    .stat-card {
      background: #FFFFFF;
      border: 1px solid #E5E7EB;
      border-radius: 18px;
      box-shadow: 0 8px 24px rgba(15,23,42,0.06);
      padding: 18px 20px;
      min-width: 0;
    }
    .stat-card__header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
    }
    .stat-card__title {
      font-size: 14px;
      font-weight: 600;
      color: #64748B;
    }
    .stat-card__badge {
      font-size: 12px;
      font-weight: 600;
      padding: 4px 10px;
      border-radius: 9999px;
    }
    .badge--today {
      background: #FEF3C7;
      color: #D97706;
    }
    .badge--success {
      background: #DCFCE7;
      color: #16A34A;
    }
    .badge--danger {
      background: #FEE2E2;
      color: #DC2626;
    }
    .badge--pending {
      background: #FEF3C7;
      color: #D97706;
    }
    .stat-card__num {
      font-size: 36px;
      font-weight: 700;
      color: #0F172A;
      line-height: 1;
      margin-bottom: 6px;
    }
    .stat-card__num--success { color: #16A34A; }
    .stat-card__num--danger { color: #DC2626; }
    .stat-card__num--warning { color: #D97706; }
    .stat-card__subtitle {
      font-size: 13px;
      color: #94A3B8;
    }

    /* ── Ping indicator ── */
    .ping { display: inline-flex; align-items: center; gap: 8px; font-size: .82rem; color: var(--teks-muted); }
    .ping__dot {
      width: 9px; height: 9px; border-radius: 50%;
      background: #DC2626;
    }
    .ping__dot--ok { background: #16A34A; }

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
      border-radius: 18px;
      padding: 20px;
      background: #FFFFFF;
      border: 1px solid #E5E7EB;
      box-shadow: 0 8px 24px rgba(15,23,42,0.06);
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }
    .chart-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 16px;
      gap: 16px;
    }
    .chart-title-area {
      flex: 1;
    }
    .chart-title {
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--teks);
      margin: 0 0 4px 0;
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
    .legend-dot--success {
      background: #16A34A;
    }
    .legend-dot--failed {
      background: #DC2626;
    }
    .chart-container {
      position: relative;
      width: 100%;
      height: 220px;
      display: flex;
      flex-direction: column;
    }
    .chart-container canvas {
      flex: 1;
      width: 100% !important;
      height: 100% !important;
    }

    /* ── Dashboard Bottom Grid ── */
    .dashboard-bottom-grid {
      display: grid;
      grid-template-columns: 1fr 380px;
      gap: 16px;
      align-items: stretch;
    }

    /* ── Summary Card ── */
    .summary-card {
      background: #FFFFFF;
      border: 1px solid #E5E7EB;
      border-radius: 18px;
      box-shadow: 0 8px 24px rgba(15,23,42,0.06);
      padding: 20px;
      min-width: 0;
      overflow: hidden;
    }
    .summary-header {
      margin-bottom: 14px;
    }
    .summary-title {
      font-size: 1.1rem;
      font-weight: 600;
      color: #0F172A;
      margin: 0 0 4px 0;
    }
    .summary-period {
      font-size: 13px;
      color: #94A3B8;
    }
    .summary-divider {
      height: 1px;
      background: #E5E7EB;
      margin: 12px 0;
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
      padding: 10px 0;
      border-bottom: 1px solid #F1F5F9;
    }
    .summary-item:last-child {
      border-bottom: none;
    }
    .summary-item__left {
      display: flex;
      align-items: center;
      gap: 10px;
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
      font-size: 13px;
      font-weight: 500;
      color: #64748B;
    }
    .summary-item__value {
      font-size: 16px;
      font-weight: 700;
      color: #0F172A;
    }
    .summary-footer {
      font-size: 12px;
      color: #94A3B8;
      line-height: 1.5;
    }
    .summary-highlight {
      color: #EF4444;
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

    /* ── Responsive ── */
    @media (max-width: 1024px) {
      .grid-2, .form-row--3 { grid-template-columns: 1fr; }
      .form-row--2          { grid-template-columns: 1fr; }
      .stats-grid           { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 768px) {
      html, body { font-size: 15px; }
      .sidebar {
        display: flex;
        position: fixed;
        left: 0; top: 0;
        z-index: 60;
        width: 76px;
        padding: 24px 8px;
        transition: width .2s ease, padding .2s ease;
        border-radius: 0 16px 16px 0;
      }
      .admin-shell.sidebar-open .sidebar { width: 240px; padding: 24px 18px; }
      .admin-shell.sidebar-open::after {
        content: '';
        position: fixed; inset: 0;
        background: rgba(15,23,42,.45);
        z-index: 55;
      }
      .sidebar__close { display: inline-flex; }
      .sidebar__link  { font-size: .88rem; padding: 10px 14px; }
      .sidebar { justify-content: stretch; }
      .admin-shell:not(.sidebar-open) .sidebar__brand { justify-content: center; padding: 6px 0 24px; }
      .admin-shell:not(.sidebar-open) .sidebar__link { justify-content: center; padding: 11px 0; }
      .admin-shell:not(.sidebar-open) .sidebar__close { display: none; }
      .admin-shell.sidebar-open .sidebar__close { display: inline-flex; margin-left: auto; }
      .sidebar__brand-text strong { font-size: .95rem; }
      .sidebar__brand-text small  { font-size: .68rem; }
      .sidebar__group-label { font-size: .68rem; padding: 12px 10px 6px; }
      .admin-shell:not(.sidebar-open) .sidebar__link span,
      .admin-shell:not(.sidebar-open) .sidebar__brand-text,
      .admin-shell:not(.sidebar-open) .sidebar__group-label { display: none; }
      .admin-main { margin-left: 76px; }
      .admin-shell.sidebar-open .admin-main { margin-left: 0; }
      .sidebar { height: 100vh; overflow-y: auto; }
      .admin-shell:not(.sidebar-open) .sidebar { min-height: 100%; height: auto; align-self: stretch; }
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

      .dashboard-bottom-grid {
        grid-template-columns: 1fr;
      }
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
    }

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
        <button type="button" class="sidebar__close" aria-label="Tutup sidebar" onclick="this.closest('.admin-shell').classList.remove('sidebar-open')">
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
          <button type="button" class="navbar__menu" aria-label="Buka atau tutup sidebar" aria-expanded="true" onclick="var s=this.closest('.admin-shell'); if(window.innerWidth<=768){s.classList.toggle('sidebar-open');}else{s.classList.toggle('sidebar-collapsed');} this.setAttribute('aria-expanded', !s.classList.contains('sidebar-collapsed') && (window.innerWidth>768 || s.classList.contains('sidebar-open')))">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
          </button>
          <div class="navbar__title">@yield('navbar_title', 'Dashboard')</div>
        </div>

        <div class="navbar__right">
          <div class="navbar__icon" title="Notifikasi">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
              <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
              <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
            </svg>
            <span class="navbar__icon-dot"></span>
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
       <main class="admin-content">
         @yield('content')
       </main>

       <footer class="admin-footer">abt-pkl-2026</footer>

       @hasSection('scripts')
         @yield('scripts')
       @endif

     </div>

  </div>

</body>
</html>