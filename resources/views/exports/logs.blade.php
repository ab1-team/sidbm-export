@extends('layouts.admin')

@section('title', 'Log Export — SIDBM Export')
@section('navbar_title', 'Log Export')

@section('content')

<style>
.logs-page {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.page-header-modern {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  flex-wrap: wrap;
  background: linear-gradient(135deg, var(--sidebar) 0%, var(--sidebar-hov) 100%);
  padding: 24px 28px;
  border-radius: 16px;
  color: white;
  box-shadow: 0 8px 32px rgba(82, 109, 130, 0.3);
}

.page-header-modern__left {
  display: flex;
  align-items: center;
  gap: 16px;
}

.page-header-modern__icon {
  width: 56px;
  height: 56px;
  background: rgba(255, 255, 255, 0.15);
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.page-header-modern__icon svg {
  width: 28px;
  height: 28px;
}

.page-header-modern__text h1 {
  font-size: 1.4rem;
  font-weight: 700;
  margin: 0 0 4px;
  color: white;
}

.page-header-modern__text p {
  font-size: .85rem;
  opacity: .85;
  margin: 0;
}

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

.filter-card {
  background: white;
  border-radius: 16px;
  border: 1px solid var(--border);
  box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
  overflow: hidden;
}

.filter-card__header {
  padding: 16px 20px 14px;
  border-bottom: 1px solid var(--border);
  display: flex;
  align-items: center;
  gap: 12px;
  flex-shrink: 0;
  background: #F8FAFC;
}

.filter-card__icon {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #EFF6FF;
  color: #2563EB;
}

.filter-card__icon svg {
  width: 22px;
  height: 22px;
}

.filter-card__title {
  font-size: 1rem;
  font-weight: 600;
  color: var(--teks);
  margin: 0;
}

.filter-card__subtitle {
  font-size: .8rem;
  color: var(--teks-muted);
  margin: 2px 0 0;
}

.filter-card__body {
  padding: 16px 20px 20px;
}

.filter-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
  margin-bottom: 16px;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
  transition: transform 0.3s ease;
}

.filter-group:hover {
  transform: translateY(-2px);
}

.filter-group:focus-within .filter-label {
  color: #2563EB;
  animation: labelBounce 0.3s ease;
}

@keyframes labelBounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-2px); }
}

.filter-label {
  font-size: .78rem;
  font-weight: 600;
  color: var(--teks-muted);
  text-transform: uppercase;
  letter-spacing: .5px;
}

.filter-select-wrapper {
  position: relative;
}

.filter-select-wrapper::after {
  content: '';
  position: absolute;
  right: 14px;
  top: 50%;
  transform: translateY(-50%);
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-top: 5px solid var(--teks-muted);
  pointer-events: none;
}

.filter-select {
  width: 100%;
  padding: 11px 36px 11px 14px;
  border: 1px solid var(--border);
  border-radius: 50px;
  font-size: .875rem;
  font-family: inherit;
  background: white;
  color: var(--teks);
  cursor: pointer;
  appearance: none;
  transition: all .2s ease;
}

.filter-select:focus {
  outline: none;
  border-color: #2563EB;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
  animation: filterPulse 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes filterPulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.02); }
  100% { transform: scale(1); }
}

.filter-group:focus-within .filter-label {
  color: #2563EB;
  animation: labelBounce 0.3s ease;
}

@keyframes labelBounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-2px); }
}

.filter-select-wrapper::after {
  content: '';
  position: absolute;
  right: 14px;
  top: 50%;
  transform: translateY(-50%);
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-top: 5px solid var(--teks-muted);
  pointer-events: none;
  transition: transform 0.3s ease, border-color 0.3s ease;
}

.filter-select-wrapper:focus-within::after {
  transform: translateY(-50%) rotate(180deg);
  border-top-color: #2563EB;
}

.filter-actions {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}

.btn-filter {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 20px;
  border: none;
  border-radius: 50px;
  font-size: .875rem;
  font-weight: 600;
  cursor: pointer;
  transition: all .2s ease;
  font-family: inherit;
}

.btn-filter svg {
  width: 16px;
  height: 16px;
}

.btn-filter--primary {
  background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
  color: white;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.btn-filter--primary:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
}

.btn-filter--secondary {
  background: #F1F5F9;
  color: #64748B;
}

.btn-filter--secondary:hover {
  background: #E2E8F0;
}

.table-card {
  background: white;
  border-radius: 16px;
  border: 1px solid var(--border);
  box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
  overflow: hidden;
}

.table-card__header {
  padding: 16px 20px 14px;
  border-bottom: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 12px;
}

.table-card__title {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: .95rem;
  font-weight: 600;
  color: var(--teks);
  margin: 0;
}

.table-card__icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #F0FDF4;
  color: #16A34A;
}

.table-card__icon svg {
  width: 20px;
  height: 20px;
}

.table-card__count {
  font-size: .78rem;
  color: var(--teks-muted);
  background: #F1F5F9;
  padding: 4px 10px;
  border-radius: 50px;
}

.table-wrap {
  overflow-x: auto;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
  font-size: .875rem;
}

.data-table thead {
  background: linear-gradient(135deg, var(--sidebar) 0%, var(--sidebar-hov) 100%);
}

.data-table th {
  color: white;
  padding: 14px 16px;
  text-align: left;
  font-weight: 600;
  font-size: .78rem;
  text-transform: uppercase;
  letter-spacing: .5px;
  white-space: nowrap;
  border: none;
}

.data-table th:first-child {
  border-top-left-radius: 0;
}

.data-table th:last-child {
  border-top-right-radius: 0;
}

.data-table td {
  padding: 14px 16px;
  border-bottom: 1px solid #F1F5F9;
  vertical-align: middle;
  color: var(--teks);
}

.data-table tbody tr {
  transition: background .15s ease;
}

.data-table tbody tr:hover {
  background: #F8FAFC;
}

.data-table tbody tr:last-child td {
  border-bottom: none;
}

.cell-kecamatan {
  display: flex;
  align-items: center;
  gap: 10px;
}

.cell-kecamatan__avatar {
  width: 34px;
  height: 34px;
  border-radius: 8px;
  background: #EFF6FF;
  color: #2563EB;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: .8rem;
  flex-shrink: 0;
}

.cell-kecamatan__info {
  min-width: 0;
}

.cell-kecamatan__id {
  font-weight: 600;
  font-size: .9rem;
  color: var(--teks);
}

.cell-kecamatan__nama {
  font-size: .72rem;
  color: var(--teks-muted);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 120px;
}

.cell-jenis {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 5px 10px;
  border-radius: 6px;
  font-size: .78rem;
  font-weight: 600;
}

.cell-jenis--saldo {
  background: #DBEAFE;
  color: #1D4ED8;
}

.cell-jenis--transaksi {
  background: #D1FAE5;
  color: #047857;
}

.cell-jenis--semua {
  background: #EDE9FE;
  color: #6D28D9;
}

.cell-file {
  max-width: 200px;
}

.cell-file__name {
  font-size: .82rem;
  color: var(--teks);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  display: block;
}

.cell-file__size {
  font-size: .72rem;
  color: var(--teks-muted);
}

.cell-status {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.badge-status {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 5px 10px;
  border-radius: 6px;
  font-size: .75rem;
  font-weight: 600;
}

.badge-status__dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
}

.badge-status--success {
  background: #DCFCE7;
  color: #15803D;
}

.badge-status--success .badge-status__dot {
  background: #16A34A;
}

.badge-status--failed {
  background: #FEE2E2;
  color: #B91C1C;
}

.badge-status--failed .badge-status__dot {
  background: #DC2626;
}

.badge-status--pending {
  background: #FEF3C7;
  color: #92400E;
}

.badge-status--pending .badge-status__dot {
  background: #D97706;
}

.cell-error {
  max-width: 180px;
}

.cell-error__text {
  font-size: .72rem;
  color: #DC2626;
  background: #FEF2F2;
  padding: 4px 8px;
  border-radius: 4px;
  display: inline-block;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 100%;
}

.cell-actions {
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-action {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 34px;
  height: 34px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: all .2s ease;
}

.btn-action svg {
  width: 16px;
  height: 16px;
}

.btn-action--view {
  background: #EFF6FF;
  color: #2563EB;
}

.btn-action--view:hover {
  background: #DBEAFE;
}

.btn-action--download {
  background: #F0FDF4;
  color: #16A34A;
}

.btn-action--download:hover {
  background: #DCFCE7;
}

.empty-state {
  padding: 64px 24px;
  text-align: center;
  color: var(--teks-muted);
}

.empty-state__icon {
  width: 72px;
  height: 72px;
  background: #F1F5F9;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 16px;
}

.empty-state__icon svg {
  width: 36px;
  height: 36px;
  opacity: .4;
}

.empty-state__text {
  font-size: 1rem;
  font-weight: 600;
  color: var(--teks);
  margin-bottom: 4px;
}

.empty-state__sub {
  font-size: .85rem;
  color: var(--teks-muted);
}

.pagination-wrapper {
  padding: 16px 20px;
  border-top: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 12px;
}

.pagination-info {
  font-size: .82rem;
  color: var(--teks-muted);
}

.pagination-links {
  display: flex;
  align-items: center;
  gap: 6px;
}

.pagination-links a,
.pagination-links span {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 36px;
  height: 36px;
  padding: 0 10px;
  border-radius: 8px;
  font-size: .82rem;
  font-weight: 500;
  transition: all .2s ease;
}

.pagination-links a {
  background: #F1F5F9;
  color: var(--teks);
  text-decoration: none;
}

.pagination-links a:hover {
  background: #E2E8F0;
}

.pagination-links .active {
  background: var(--sidebar);
  color: white;
}

.pagination-links .disabled {
  opacity: .4;
  cursor: not-allowed;
}

.pagination-links svg {
  width: 16px;
  height: 16px;
}

@media (max-width: 1200px) {
  .filter-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 1024px) {
  .filter-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .data-table th,
  .data-table td {
    padding: 12px 12px;
  }
}

@media (max-width: 768px) {
  .page-header-modern {
    padding: 20px;
    flex-direction: column;
    align-items: flex-start;
  }
  
  .page-header-modern__text h1 {
    font-size: 1.2rem;
  }
  
  .filter-card__body {
    padding: 16px;
  }
  
  .filter-grid {
    grid-template-columns: 1fr;
    gap: 12px;
  }
  
  .table-card__header {
    padding: 14px 16px;
  }
  
  .data-table {
    min-width: 800px;
  }
  
  .data-table th,
  .data-table td {
    padding: 12px 10px;
    font-size: .82rem;
  }
  
  .cell-kecamatan__avatar {
    width: 28px;
    height: 28px;
    font-size: .7rem;
  }
  
  .cell-kecamatan__nama {
    display: none;
  }
  
  .pagination-wrapper {
    flex-direction: column;
    align-items: stretch;
  }
  
  .pagination-info {
    text-align: center;
  }
  
  .pagination-links {
    justify-content: center;
    flex-wrap: wrap;
  }
}

@media (max-width: 640px) {
  .filter-actions {
    flex-direction: column;
  }
  
  .btn-filter {
    width: 100%;
    justify-content: center;
  }
  
  .cell-file {
    display: none;
  }
  
  .cell-actions {
    flex-direction: column;
    gap: 4px;
  }
  
  .btn-action {
    width: 30px;
    height: 30px;
  }
  
  .btn-action svg {
    width: 14px;
    height: 14px;
  }
}
</style>

<div class="logs-page">

  <div class="page-header-modern">
    <div class="page-header-modern__left">
      <div class="page-header-modern__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
          <line x1="16" y1="13" x2="8" y2="13"/>
          <line x1="16" y1="17" x2="8" y2="17"/>
          <polyline points="10 9 9 9 8 9"/>
        </svg>
      </div>
      <div class="page-header-modern__text">
        <h1>Log Export</h1>
        <p>Riwayat aktivitas export data SIDBM</p>
      </div>
    </div>
    <div class="ping-badge">
      <span class="ping-badge__dot {{ $enstoragePing ? 'ping-badge__dot--ok' : '' }}"></span>
      EnStorage {{ $enstoragePing ? 'Terhubung' : 'Tidak Terhubung' }}
    </div>
  </div>

  <div class="filter-card">
    <div class="filter-card__header">
      <div class="filter-card__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
        </svg>
      </div>
      <div>
        <h2 class="filter-card__title">Filter</h2>
        <p class="filter-card__subtitle">Cari dan filter log export</p>
      </div>
    </div>
    <div class="filter-card__body">
      <form method="GET" action="{{ route('export.logs') }}">
        <div class="filter-grid">
          <div class="filter-group">
            <label class="filter-label">Kecamatan</label>
            <div class="filter-select-wrapper">
              <select name="kecamatan_id" class="filter-select">
                <option value="">Semua</option>
                @foreach ($kecamatanList as $kec)
                  <option value="{{ $kec->id }}" {{ $kecamatanId == $kec->id ? 'selected' : '' }}>
                    {{ $kec->id }} — {{ $kec->nama_kecamatan }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="filter-group">
            <label class="filter-label">Jenis</label>
            <div class="filter-select-wrapper">
              <select name="jenis" class="filter-select">
                <option value="">Semua</option>
                <option value="saldo" {{ $jenis === 'saldo' ? 'selected' : '' }}>Saldo</option>
                <option value="transaksi" {{ $jenis === 'transaksi' ? 'selected' : '' }}>Transaksi</option>
              </select>
            </div>
          </div>
          <div class="filter-group">
            <label class="filter-label">Status</label>
            <div class="filter-select-wrapper">
              <select name="status" class="filter-select">
                <option value="">Semua</option>
                <option value="success" {{ $status === 'success' ? 'selected' : '' }}>Success</option>
                <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
              </select>
            </div>
          </div>
          <div class="filter-group">
            <label class="filter-label">Tahun</label>
            <div class="filter-select-wrapper">
              <select name="tahun" class="filter-select">
                <option value="">Semua</option>
                @foreach ($tahunList as $t)
                  <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="filter-actions">
          <button type="submit" class="btn-filter btn-filter--primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/>
              <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            Terapkan Filter
          </button>
          <a href="{{ route('export.logs') }}" class="btn-filter btn-filter--secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 6h18"/>
              <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
              <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
            </svg>
            Reset
          </a>
        </div>
      </form>
    </div>
  </div>

  <div class="table-card">
    <div class="table-card__header">
      <span class="table-card__title">
        <span class="table-card__icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
          </svg>
        </span>
        Data Log Export
      </span>
      <span class="table-card__count">{{ $logs->total() }} total data</span>
    </div>
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Kecamatan</th>
            <th>Jenis</th>
            <th>Tahun</th>
            <th>Bulan</th>
            <th>File</th>
            <th>Record</th>
            <th>Status</th>
            <th>Waktu</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($logs as $log)
            <tr>
              <td>
                <div class="cell-kecamatan">
                  <span class="cell-kecamatan__avatar">{{ $log->kecamatan_id }}</span>
                  <div class="cell-kecamatan__info">
                    <div class="cell-kecamatan__id">Kec. {{ $log->kecamatan_id }}</div>
                    <div class="cell-kecamatan__nama">{{ $log->nama_kecamatan ?? '-' }}</div>
                  </div>
                </div>
              </td>
              <td>
                <span class="cell-jenis cell-jenis--{{ $log->jenis }}">
                  @if($log->jenis === 'saldo')
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <line x1="12" y1="1" x2="12" y2="23"/>
                      <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                    Saldo
                  @else
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                    Transaksi
                  @endif
                </span>
              </td>
              <td>{{ $log->tahun }}</td>
              <td>{{ $log->bulan_label ?: '-' }}</td>
              <td class="cell-file">
                @if ($log->file_url)
                  <a href="{{ $log->file_url }}" target="_blank" class="cell-file__name">{{ $log->filename }}</a>
                @else
                  <span class="cell-file__name text-muted">{{ $log->filename ?: '-' }}</span>
                @endif
                @if ($log->file_size_human)
                  <span class="cell-file__size">{{ $log->file_size_human }}</span>
                @endif
              </td>
              <td>{{ $log->record_count ? number_format($log->record_count) : '-' }}</td>
              <td>
                <div class="cell-status">
                  <span class="badge-status badge-status--{{ $log->status }}">
                    <span class="badge-status__dot"></span>
                    {{ ucfirst($log->status) }}
                  </span>
                  @if ($log->error_message)
                    <span class="cell-error" title="{{ $log->error_message }}">
                      <span class="cell-error__text">{{ Str::limit($log->error_message, 40) }}</span>
                    </span>
                  @endif
                </div>
              </td>
              <td class="text-muted">{{ $log->created_at?->format('d M Y') }}<br><span style="font-size:.72rem;">{{ $log->created_at?->format('H:i') }}</span></td>
              <td>
                <div class="cell-actions">
                  @if ($log->file_url)
                    <a href="{{ $log->file_url }}" target="_blank" class="btn-action btn-action--view" title="Lihat file">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                        <polyline points="15 3 21 3 21 9"/>
                        <line x1="10" y1="14" x2="21" y2="3"/>
                      </svg>
                    </a>
                    <a href="{{ $log->file_url }}?download=1" class="btn-action btn-action--download" title="Download">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                      </svg>
                    </a>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9">
                <div class="empty-state">
                  <div class="empty-state__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="11" cy="11" r="8"/>
                      <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                  </div>
                  <div class="empty-state__text">Tidak ada data ditemukan</div>
                  <div class="empty-state__sub">Coba ubah filter atau kosongkan pencarian</div>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($logs->hasPages())
      <div class="pagination-wrapper">
        <div class="pagination-info">
          Menampilkan {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} data
        </div>
        <div class="pagination-links">
          @if($logs->onFirstPage())
            <span class="disabled">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"/>
              </svg>
            </span>
          @else
            <a href="{{ $logs->previousPageUrl() }}">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"/>
              </svg>
            </a>
          @endif

          @foreach($logs->getUrlRange(max(1, $logs->currentPage() - 2), min($logs->lastPage(), $logs->currentPage() + 2)) as $page => $url)
            @if($page == $logs->currentPage())
              <span class="active">{{ $page }}</span>
            @else
              <a href="{{ $url }}">{{ $page }}</a>
            @endif
          @endforeach

          @if($logs->hasMorePages())
            <a href="{{ $logs->nextPageUrl() }}">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"/>
              </svg>
            </a>
          @else
            <span class="disabled">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"/>
              </svg>
            </span>
          @endif
        </div>
      </div>
    @endif
  </div>

</div>

@endsection
