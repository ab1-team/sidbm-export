<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'SIDBM Export')</title>
  <style>
    :root {
      --biru     : #1E3A5F;
      --biru-mid : #2563EB;
      --biru-lt  : #DBEAFE;
      --hijau    : #16A34A;
      --merah    : #DC2626;
      --kuning   : #D97706;
      --abu      : #F1F5F9;
      --border   : #E2E8F0;
      --teks     : #1E293B;
      --teks-muted: #64748B;
      --radius   : 10px;
      --shadow   : 0 1px 3px rgba(0,0,0,.08), 0 4px 16px rgba(0,0,0,.05);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Segoe UI', system-ui, sans-serif;
      background : var(--abu);
      color      : var(--teks);
      min-height : 100vh;
    }

    /* ── Navbar ── */
    .navbar {
      background   : var(--biru);
      padding      : 0 24px;
      height       : 56px;
      display      : flex;
      align-items  : center;
      justify-content: space-between;
      box-shadow   : 0 2px 8px rgba(0,0,0,.2);
    }
    .navbar__brand { color: white; font-weight: 700; font-size: 1rem; text-decoration: none; }
    .navbar__brand span { color: #93C5FD; }
    .navbar__nav   { display: flex; gap: 4px; }
    .navbar__link  {
      color: #CBD5E1; text-decoration: none; padding: 6px 14px;
      border-radius: 6px; font-size: .875rem; transition: all .15s;
    }
    .navbar__link:hover, .navbar__link.active { background: rgba(255,255,255,.12); color: white; }

    /* ── Layout ── */
    .container { max-width: 1100px; margin: 0 auto; padding: 24px 16px 64px; }

    /* ── Card ── */
    .card {
      background   : white;
      border       : 1px solid var(--border);
      border-radius: var(--radius);
      padding      : 24px;
      box-shadow   : var(--shadow);
      margin-bottom: 20px;
    }
    .card__title {
      font-size: 1rem; font-weight: 600;
      margin-bottom: 16px; padding-bottom: 12px;
      border-bottom: 1px solid var(--border);
    }

    /* ── Form ── */
    .form-group  { margin-bottom: 16px; }
    .form-label  { display: block; font-size: .875rem; font-weight: 500; margin-bottom: 6px; }
    .form-select, .form-input {
      width: 100%; padding: 9px 12px;
      border: 1px solid var(--border); border-radius: 8px;
      font-size: .9rem; outline: none; background: white;
      transition: border-color .15s;
    }
    .form-select:focus, .form-input:focus { border-color: var(--biru-mid); }
    .form-row { display: grid; gap: 16px; }
    .form-row--3 { grid-template-columns: repeat(3, 1fr); }
    .form-row--2 { grid-template-columns: repeat(2, 1fr); }

    /* ── Radio cards ── */
    .radio-group { display: flex; gap: 8px; flex-wrap: wrap; }
    .radio-option input { display: none; }
    .radio-option__box {
      display: flex; align-items: center; gap: 8px;
      padding: 10px 16px; border: 2px solid var(--border);
      border-radius: 8px; cursor: pointer; transition: all .15s;
      font-size: .875rem;
    }
    .radio-option__box .icon { font-size: 1.1rem; }
    .radio-option input:checked + .radio-option__box {
      border-color: var(--biru-mid); background: var(--biru-lt); color: var(--biru-mid);
      font-weight: 600;
    }

    /* ── Buttons ── */
    .btn {
      display: inline-flex; align-items: center; justify-content: center; gap: 6px;
      padding: 10px 20px; border: none; border-radius: 8px;
      font-size: .9rem; font-weight: 600; cursor: pointer; transition: all .15s;
    }
    .btn--primary  { background: var(--biru-mid); color: white; }
    .btn--primary:hover  { background: #1D4ED8; transform: translateY(-1px); }
    .btn--danger   { background: var(--merah); color: white; }
    .btn--full     { width: 100%; }
    .btn:disabled  { opacity: .5; cursor: not-allowed; transform: none !important; }

    /* ── Badge ── */
    .badge {
      display: inline-flex; align-items: center; gap: 4px;
      padding: 2px 8px; border-radius: 999px; font-size: .75rem; font-weight: 600;
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
    th { background: var(--biru); color: white; padding: 10px 12px; text-align: left; font-weight: 600; }
    td { padding: 9px 12px; border-bottom: 1px solid var(--border); }
    tr:hover td { background: var(--abu); }

    /* ── Stats ── */
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px; }
    .stat-card  {
      background: white; border: 1px solid var(--border); border-radius: var(--radius);
      padding: 20px; text-align: center; box-shadow: var(--shadow);
    }
    .stat-card__num   { font-size: 2rem; font-weight: 700; }
    .stat-card__label { font-size: .8rem; color: var(--teks-muted); margin-top: 4px; }
    .stat--success .stat-card__num { color: var(--hijau); }
    .stat--failed  .stat-card__num { color: var(--merah); }
    .stat--pending .stat-card__num { color: var(--kuning); }

    /* ── Ping indicator ── */
    .ping { display: inline-flex; align-items: center; gap: 6px; font-size: .8rem; }
    .ping__dot {
      width: 8px; height: 8px; border-radius: 50%;
      background: var(--merah);
    }
    .ping__dot--ok { background: var(--hijau); }

    /* ── Utility ── */
    .hidden { display: none !important; }
    .text-muted { color: var(--teks-muted); font-size: .875rem; }
    .mt-12 { margin-top: 12px; }
  </style>
</head>
<body>

  <nav class="navbar">
    <a href="{{ route('export.index') }}" class="navbar__brand">
      📦 SIDBM <span>Export</span>
    </a>
    <div class="navbar__nav">
      <a href="{{ route('export.index') }}" class="navbar__link {{ request()->routeIs('export.index') ? 'active' : '' }}">
        Dashboard
      </a>
      <a href="{{ route('export.logs') }}" class="navbar__link {{ request()->routeIs('export.logs') ? 'active' : '' }}">
        Log Export
      </a>
    </div>
  </nav>

  <div class="container">
    @yield('content')
  </div>

</body>
</html>
