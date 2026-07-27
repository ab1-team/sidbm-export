{{-- resources/views/exports/export-data.blade.php --}}

@extends('layouts.app')

@section('title', 'Export Data — SIDBM Export')

@section('page-title', 'Export Data')

@section('content')

{{-- ── Export Page Layout ── --}}
<div class="export-page">
  <div class="export-page__grid">

    {{-- Card: Export Data --}}
    <div class="export-form-card">
      <div class="export-form-card__header">
        <div class="export-form-card__title">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="7 10 12 15 17 10"/>
            <line x1="12" y1="15" x2="12" y2="3"/>
          </svg>
          Export Data ke JSON
        </div>
        <div class="export-form-card__status">
          <span class="status-dot {{ $enstoragePing ? 'status-dot--success' : 'status-dot--danger' }}"></span>
          EnStorage {{ $enstoragePing ? 'Terhubung' : 'Terputus' }}
        </div>
      </div>
      <div class="export-form-card__body">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Jenis Data</label>
            <div class="radio-cards">
              <label class="radio-card" data-value="saldo">
                <input type="radio" name="jenis" value="saldo" checked>
                <div class="radio-card__content">
                  <div class="radio-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <line x1="12" y1="1" x2="12" y2="23"/>
                      <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                  </div>
                  <div class="radio-card__text">
                    <span class="radio-card__title">Saldo</span>
                    <span class="radio-card__desc">Data saldo kecamatan</span>
                  </div>
                </div>
              </label>
              <label class="radio-card" data-value="transaksi">
                <input type="radio" name="jenis" value="transaksi">
                <div class="radio-card__content">
                  <div class="radio-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                      <polyline points="14 2 14 8 20 8"/>
                      <line x1="16" y1="13" x2="8" y2="13"/>
                      <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                  </div>
                  <div class="radio-card__text">
                    <span class="radio-card__title">Transaksi</span>
                    <span class="radio-card__desc">Data transaksi bulanan</span>
                  </div>
                </div>
              </label>
              <label class="radio-card" data-value="semua">
                <input type="radio" name="jenis" value="semua">
                <div class="radio-card__content">
                  <div class="radio-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                      <line x1="8" y1="21" x2="16" y2="21"/>
                      <line x1="12" y1="17" x2="12" y2="21"/>
                    </svg>
                  </div>
                  <div class="radio-card__text">
                    <span class="radio-card__title">Semua Data</span>
                    <span class="radio-card__desc">Saldo & Transaksi</span>
                  </div>
                </div>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Cakupan Export</label>
            <div class="radio-cards radio-cards--horizontal" style="margin-bottom: 12px;">
              <label class="radio-card radio-card--sm" data-value="semua">
                <input type="radio" name="cakupan" value="semua" checked>
                <div class="radio-card__content">
                  <div class="radio-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <rect x="3" y="3" width="7" height="7"/>
                      <rect x="14" y="3" width="7" height="7"/>
                      <rect x="14" y="14" width="7" height="7"/>
                      <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                  </div>
                  <div class="radio-card__text">
                    <span class="radio-card__title">Semua Kecamatan</span>
                  </div>
                </div>
              </label>
              <label class="radio-card radio-card--sm" data-value="tertentu">
                <input type="radio" name="cakupan" value="tertentu">
                <div class="radio-card__content">
                  <div class="radio-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                      <circle cx="12" cy="10" r="3"/>
                    </svg>
                  </div>
                  <div class="radio-card__text">
                    <span class="radio-card__title">Kecamatan Tertentu</span>
                  </div>
                </div>
              </label>
            </div>
            <div class="form-row" style="gap: 10px; margin-bottom: 10px;">
              <div class="form-group" style="margin-bottom: 0; flex: 1;">
                <label class="form-label" for="kecamatanId" style="font-size: .8rem; margin-bottom: 6px;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                  </svg>
                  Kecamatan
                </label>
                <div class="select-wrapper">
                  <select id="kecamatanId" class="form-select" style="padding: 10px 36px 10px 12px; font-size: .85rem;" {{ old('cakupan') != 'tertentu' ? 'disabled' : '' }}>
                    <option value="">-- Pilih --</option>
                    @foreach ($kecamatanList as $kec)
                      <option value="{{ $kec->id }}">{{ $kec->id }} — {{ $kec->nama_kec }}</option>
                    @endforeach
                  </select>
                  <svg class="select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"/>
                  </svg>
                </div>
              </div>
              <div class="form-group" style="margin-bottom: 0; flex: 1;">
                <label class="form-label" for="tahun" style="font-size: .8rem; margin-bottom: 6px;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                  </svg>
                  Tahun
                </label>
                <div class="select-wrapper">
                  <select id="tahun" class="form-select" style="padding: 10px 36px 10px 12px; font-size: .85rem;">
                    <option value="">-- Pilih --</option>
                    @foreach ($tahunList as $t)
                      <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                  </select>
                  <svg class="select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"/>
                  </svg>
                </div>
              </div>
            </div>
            <p class="form-hint" style="margin-top: 0;">Data sebelum tahun {{ $batasArsip }} tersedia untuk export</p>
          </div>
        </div>
        <div class="summary-export" id="summaryExport">
        <div class="summary-export__header">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21.21 15.89A10 10 0 1 1 8 2.83"/>
            <path d="M22 12A10 10 0 0 0 12 2v10z"/>
          </svg>
          Ringkasan Export
        </div>
        <div class="summary-export__body">
          <div class="summary-export__item">
            <span class="summary-export__label">Data yang akan di-export</span>
            <span class="summary-export__value" id="summaryData">-</span>
          </div>
          <div class="summary-export__item">
            <span class="summary-export__label">Jumlah kecamatan</span>
            <span class="summary-export__value" id="summaryKecamatan">-</span>
          </div>
          <div class="summary-export__item">
            <span class="summary-export__label">Periode data</span>
            <span class="summary-export__value" id="summaryPeriode">-</span>
          </div>
          <div class="summary-export__item">
            <span class="summary-export__label">Estimasi ukuran file</span>
            <span class="summary-export__value" id="summarySize">-</span>
          </div>
        </div>
      </div>

      {{-- Row 3: Export Button --}}
      <div class="export-action">
        <button id="btnExport" class="btn btn--export" disabled>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="7 10 12 15 17 10"/>
            <line x1="12" y1="15" x2="12" y2="3"/>
          </svg>
          <span id="btnText">Export ke JSON</span>
          <span id="btnLoading" class="hidden">
            <span class="spinner"></span>
            Memproses...
          </span>
        </button>
        <p class="export-action__hint">Klik tombol untuk memulai proses export data</p>
      </div>

      {{-- Result Log --}}
      <div class="export-result" id="cardLog" style="display: none;">
        <div id="logContainer"></div>
      </div>
    </div>
  </div>

  {{-- Card: Riwayat Export (sejajar dengan Export Data) --}}
  <div class="riwayat-export-card">
    <div class="riwayat-export-card__header">
      <div class="riwayat-export-card__title">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
          <circle cx="12" cy="12" r="10"/>
          <polyline points="12 6 12 12 16 14"/>
        </svg>
        Riwayat Export
      </div>
      <a href="{{ route('export.logs') }}" class="riwayat-export-card__link">Lihat Semua →</a>
    </div>
    <div class="riwayat-export-card__body">
      @forelse ($recentLogs as $log)
        <div class="recent-export-item">
          <div class="recent-export-item__icon recent-export-item__icon--{{ $log->jenis }}">
            @if ($log->jenis === 'saldo')
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="1" x2="12" y2="23"/>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
              </svg>
            @elseif ($log->jenis === 'transaksi')
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
              </svg>
            @else
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                <line x1="8" y1="21" x2="16" y2="21"/>
                <line x1="12" y1="17" x2="12" y2="21"/>
              </svg>
            @endif
          </div>
          <div class="recent-export-item__info">
            <div class="recent-export-item__title">
              Export {{ ucfirst($log->jenis) }}
              <span class="recent-export-item__kec">{{ $log->kecamatan?->nama_kec ?? '-' }}</span>
            </div>
            <div class="recent-export-item__meta">
              {{ $log->created_at->format('d M Y') }} · {{ $log->created_at->format('H:i') }}
              @if($log->tahun)
                · {{ $log->tahun }}
              @endif
            </div>
          </div>
          <div class="recent-export-item__status">
            @if ($log->status === 'success')
              <span class="status-badge status-badge--success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="10" height="10">
                  <polyline points="20 6 9 17 4 12"/>
                </svg>
                Berhasil
              </span>
            @elseif ($log->status === 'failed')
              <span class="status-badge status-badge--failed">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="10" height="10">
                  <line x1="18" y1="6" x2="6" y2="18"/>
                  <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
                Gagal
              </span>
            @else
              <span class="status-badge status-badge--pending">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="10" height="10">
                  <circle cx="12" cy="12" r="10"/>
                </svg>
                Pending
              </span>
            @endif
          </div>
        </div>
      @empty
        <div class="recent-export-empty">
          <div class="recent-export-empty__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="36" height="36">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
              <polyline points="7 10 12 15 17 10"/>
              <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
          </div>
          <div class="recent-export-empty__text">Belum ada log export</div>
          <div class="recent-export-empty__sub">Aktivitas export akan muncul</div>
        </div>
      @endforelse
    </div>
  </div>

  {{-- Info Card --}}
  <div class="export-info-card">
    <div class="export-info-card__header">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/>
        <line x1="12" y1="16" x2="12" y2="12"/>
        <line x1="12" y1="8" x2="12.01" y2="8"/>
      </svg>
      Petunjuk
    </div>
    <div class="export-info-card__body">
      <ol class="info-steps">
        <li>
          <span class="info-steps__num">1</span>
          <div class="info-steps__content">
            <strong>Pilih jenis data</strong> yang ingin di-export (Saldo, Transaksi, atau keduanya)
          </div>
        </li>
        <li>
          <span class="info-steps__num">2</span>
          <div class="info-steps__content">
            <strong>Tentukan cakupan</strong> - Semua kecamatan atau kecamatan tertentu saja
          </div>
        </li>
        <li>
          <span class="info-steps__num">3</span>
          <div class="info-steps__content">
            <strong>Pilih kecamatan</strong> (jika cakupan tertentu) dan <strong>tahun data</strong>
          </div>
        </li>
        <li>
          <span class="info-steps__num">4</span>
          <div class="info-steps__content">
            <strong>Klik Export</strong> dan tunggu hingga proses selesai
          </div>
        </li>
      </ol>
    </div>
  </div>
</div>

<style>
.export-page {
  max-width: 1200px;
}

.export-page__grid {
  display: grid;
  grid-template-columns: 1fr 320px;
  gap: 20px;
  align-items: start;
}

@media (max-width: 1024px) {
  .export-page__grid {
    grid-template-columns: 1fr;
  }
}

.export-form-card {
  background: white;
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow);
  border: 1px solid var(--border);
  overflow: hidden;
}

.export-form-card__header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 24px;
  background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
  border-bottom: 1px solid var(--border);
}

.export-form-card__body {
  padding: 24px;
}

@media (max-width: 768px) {
  .export-form-card__body {
    padding: 16px;
  }
}

.riwayat-export-card {
  background: white;
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow);
  border: 1px solid var(--border);
  overflow: hidden;
}

.riwayat-export-card__header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 20px;
  background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
  border-bottom: 1px solid var(--border);
}

.riwayat-export-card__title {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: .875rem;
  font-weight: 700;
  color: var(--text);
}

.riwayat-export-card__title svg {
  stroke: var(--primary);
}

.riwayat-export-card__link {
  font-size: .75rem;
  color: var(--primary);
  text-decoration: none;
  font-weight: 600;
  transition: color .2s;
}

.riwayat-export-card__link:hover {
  text-decoration: underline;
}

.riwayat-export-card__body {
  padding: 8px 0;
  max-height: 480px;
  overflow-y: auto;
}

.recent-export-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 16px;
  transition: background .15s;
}

.recent-export-item:hover {
  background: #F8FAFC;
}

.recent-export-item__icon {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.recent-export-item__icon svg {
  width: 18px;
  height: 18px;
}

.recent-export-item__icon--saldo {
  background: #EFF6FF;
  color: #2563EB;
}

.recent-export-item__icon--transaksi {
  background: #F5F3FF;
  color: #7C3AED;
}

.recent-export-item__icon--semua {
  background: #F0FDF4;
  color: #16A34A;
}

.recent-export-item__info {
  flex: 1;
  min-width: 0;
}

.recent-export-item__title {
  font-size: .8rem;
  font-weight: 600;
  color: var(--text);
  display: flex;
  align-items: center;
  gap: 6px;
}

.recent-export-item__kec {
  font-size: .7rem;
  font-weight: 400;
  color: var(--text-lt);
  background: var(--bg);
  padding: 1px 6px;
  border-radius: 4px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 120px;
}

.recent-export-item__meta {
  font-size: .7rem;
  color: var(--text-lt);
  margin-top: 2px;
}

.recent-export-item__status {
  flex-shrink: 0;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: .7rem;
  font-weight: 600;
}

.status-badge--success {
  background: #DCFCE7;
  color: #15803D;
}

.status-badge--failed {
  background: #FEE2E2;
  color: #DC2626;
}

.status-badge--pending {
  background: #FEF9C3;
  color: #A16207;
}

.recent-export-empty {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 32px 16px;
  text-align: center;
}

.recent-export-empty__icon {
  color: #CBD5E1;
  margin-bottom: 12px;
}

.recent-export-empty__text {
  font-size: .85rem;
  font-weight: 600;
  color: var(--text-lt);
  margin-bottom: 4px;
}

.recent-export-empty__sub {
  font-size: .75rem;
  color: #94A3B8;
}

.export-form-card__title {
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--primary);
}

.export-form-card__title svg {
  width: 24px;
  height: 24px;
}

.export-form-card__status {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: .85rem;
  color: var(--text-lt);
}

.status-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: var(--danger);
}

.status-dot--success {
  background: var(--success);
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}

.export-form-card__body {
  padding: 28px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;
  margin-bottom: 24px;
}

@media (max-width: 768px) {
  .form-row { grid-template-columns: 1fr; }
}

.form-label {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: .9rem;
  font-weight: 600;
  color: var(--text);
  margin-bottom: 12px;
}

.form-label svg { opacity: 0.6; }

.select-wrapper {
  position: relative;
}

.form-select {
  width: 100%;
  padding: 12px 40px 12px 14px;
  border: 1px solid var(--border);
  border-radius: var(--radius);
  font-size: .9rem;
  font-family: inherit;
  background: white;
  cursor: pointer;
  appearance: none;
  transition: all .2s ease;
}

.form-select:focus {
  border-color: var(--primary-lt);
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  outline: none;
}

.form-select:disabled {
  background: var(--bg);
  color: var(--text-lt);
  cursor: not-allowed;
}

.select-arrow {
  position: absolute;
  right: 14px;
  top: 50%;
  transform: translateY(-50%);
  width: 18px;
  height: 18px;
  stroke: var(--text-lt);
  pointer-events: none;
}

.form-hint {
  font-size: .75rem;
  color: var(--text-lt);
  margin-top: 8px;
}

/* Radio Cards */
.radio-cards {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.radio-cards--horizontal {
  flex-direction: row;
}

.radio-card {
  position: relative;
  cursor: pointer;
}

.radio-card input {
  position: absolute;
  opacity: 0;
  pointer-events: none;
}

.radio-card__content {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 14px 16px;
  border: 2px solid var(--border);
  border-radius: var(--radius);
  transition: all .2s ease;
  background: white;
}

.radio-card:hover .radio-card__content {
  border-color: var(--primary-lt);
  background: #EFF6FF;
}

.radio-card input:checked + .radio-card__content {
  border-color: var(--primary);
  background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.radio-card__icon {
  width: 40px;
  height: 40px;
  border-radius: var(--radius-sm);
  background: var(--bg);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  transition: all .2s ease;
}

.radio-card__icon svg {
  width: 22px;
  height: 22px;
  stroke: var(--text-lt);
  transition: all .2s ease;
}

.radio-card input:checked + .radio-card__content .radio-card__icon {
  background: var(--primary);
}

.radio-card input:checked + .radio-card__content .radio-card__icon svg {
  stroke: white;
}

.radio-card__text {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.radio-card__title {
  font-size: .9rem;
  font-weight: 600;
  color: var(--text);
}

.radio-card__desc {
  font-size: .75rem;
  color: var(--text-lt);
}

.radio-card--sm .radio-card__content {
  padding: 10px 14px;
}

.radio-card--sm .radio-card__icon {
  width: 36px;
  height: 36px;
}

.radio-card--sm .radio-card__icon svg {
  width: 18px;
  height: 18px;
}

/* Summary Export */
.summary-export {
  background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  margin-bottom: 24px;
  overflow: hidden;
}

.summary-export__header {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 14px 20px;
  background: white;
  border-bottom: 1px solid var(--border);
  font-size: .9rem;
  font-weight: 600;
  color: var(--text);
}

.summary-export__header svg {
  width: 18px;
  height: 18px;
  stroke: var(--primary);
}

.summary-export__body {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1px;
  background: var(--border);
}

@media (max-width: 768px) {
  .summary-export__body {
    grid-template-columns: repeat(2, 1fr);
  }
}

.summary-export__item {
  display: flex;
  flex-direction: column;
  gap: 4px;
  padding: 16px 20px;
  background: white;
}

.summary-export__label {
  font-size: .75rem;
  color: var(--text-lt);
}

.summary-export__value {
  font-size: 1rem;
  font-weight: 700;
  color: var(--text-dk);
}

/* Export Action */
.export-action {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
}

.btn--export {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 14px 36px;
  background: linear-gradient(135deg, var(--primary-lt) 0%, var(--primary) 100%);
  color: white;
  border: none;
  border-radius: var(--radius);
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all .2s ease;
  box-shadow: 0 4px 14px rgba(30, 64, 175, 0.3);
}

.btn--export:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(30, 64, 175, 0.4);
}

.btn--export:disabled {
  background: #CBD5E1;
  box-shadow: none;
  cursor: not-allowed;
}

.btn--export svg {
  width: 20px;
  height: 20px;
}

.export-action__hint {
  font-size: .8rem;
  color: var(--text-lt);
}

/* Export Result */
.export-result {
  padding: 20px 28px;
  border-top: 1px solid var(--border);
  background: #FAFBFC;
}

.log-item {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  padding: 12px 16px;
  border-radius: var(--radius-sm);
  margin-bottom: 8px;
  font-size: .875rem;
}

.log-item:last-child { margin-bottom: 0; }

.log-item--success {
  background: #F0FDF4;
  border: 1px solid #BBF7D0;
  color: #15803D;
}

.log-item--error {
  background: #FEF2F2;
  border: 1px solid #FECACA;
  color: #B91C1C;
}

.log-item--info {
  background: #F0F9FF;
  border: 1px solid #BAE6FD;
  color: #0369A1;
}

/* Info Card */
.export-info-card {
  background: white;
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow);
  border: 1px solid var(--border);
  overflow: hidden;
  margin-top: 20px;
}

.export-info-card__header {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 16px 20px;
  background: #FAFBFC;
  border-bottom: 1px solid var(--border);
  font-size: .95rem;
  font-weight: 600;
  color: var(--text);
}

.export-info-card__header svg {
  width: 20px;
  height: 20px;
  stroke: var(--warning);
}

.export-info-card__body {
  padding: 20px;
}

.info-steps {
  list-style: none;
  counter-reset: steps;
}

.info-steps li {
  display: flex;
  gap: 14px;
  padding: 12px 0;
  border-bottom: 1px solid var(--border);
}

.info-steps li:last-child {
  border-bottom: none;
  padding-bottom: 0;
}

.info-steps__num {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: var(--primary);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: .8rem;
  font-weight: 700;
  flex-shrink: 0;
}

.info-steps__content {
  font-size: .875rem;
  color: var(--text-lt);
  line-height: 1.5;
}

.info-steps__content strong {
  color: var(--text);
}
</style>

<script>
const kecamatanSelect = document.getElementById('kecamatanId');
const tahunSelect = document.getElementById('tahun');
const cakupanRadios = document.querySelectorAll('input[name="cakupan"]');
const jenisRadios = document.querySelectorAll('input[name="jenis"]');
const btnExport = document.getElementById('btnExport');

// Handle cakupan change
cakupanRadios.forEach(radio => {
  radio.addEventListener('change', () => {
    kecamatanSelect.disabled = radio.value !== 'tertentu';
    if (radio.value === 'tertentu') {
      kecamatanSelect.value = '';
    }
    updateSummary();
    checkForm();
  });
});

// Handle jenis change
jenisRadios.forEach(radio => {
  radio.addEventListener('change', updateSummary);
});

// Handle select change
kecamatanSelect.addEventListener('change', () => {
  updateSummary();
  checkForm();
});

tahunSelect.addEventListener('change', () => {
  updateSummary();
  checkForm();
});

function checkForm() {
  const cakupan = document.querySelector('input[name="cakupan"]:checked').value;
  const tahun = tahunSelect.value;
  
  let valid = tahun !== '';
  if (cakupan === 'tertentu') {
    valid = valid && kecamatanSelect.value !== '';
  }
  
  btnExport.disabled = !valid;
}

function updateSummary() {
  const cakupan = document.querySelector('input[name="cakupan"]:checked').value;
  const jenis = document.querySelector('input[name="jenis"]:checked').value;
  const kecamatan = kecamatanSelect.value;
  const tahun = tahunSelect.value;
  
  // Update summary
  const jenisLabels = {
    'saldo': 'Data Saldo',
    'transaksi': 'Data Transaksi',
    'semua': 'Saldo & Transaksi'
  };
  
  document.getElementById('summaryData').textContent = jenisLabels[jenis];
  document.getElementById('summaryKecamatan').textContent = cakupan === 'semua' 
    ? 'Semua ({{ $totalKecamatan }})' 
    : (kecamatan ? `1` : '-');
  document.getElementById('summaryPeriode').textContent = tahun ? `Tahun ${tahun}` : '-';
  
  // Estimate size (rough calculation)
  let estimatedSize = '-';
  if (kecamatan && tahun) {
    const baseSize = jenis === 'semua' ? 500 : 200; // KB per kecamatan
    const kecCount = cakupan === 'semua' ? {{ $totalKecamatan }} : 1;
    const totalKB = baseSize * kecCount;
    estimatedSize = totalKB >= 1024 
      ? (totalKB / 1024).toFixed(1) + ' MB' 
      : totalKB + ' KB';
  }
  document.getElementById('summarySize').textContent = estimatedSize;
}

// Export button handler
btnExport.addEventListener('click', async () => {
  const cakupan = document.querySelector('input[name="cakupan"]:checked').value;
  const jenis = document.querySelector('input[name="jenis"]:checked').value;
  const kecamatanId = kecamatanSelect.value;
  const tahun = tahunSelect.value;
  
  setLoading(true);
  document.getElementById('cardLog').style.display = 'block';
  document.getElementById('logContainer').innerHTML = '';
  addLog('info', 'Memulai proses export...');
  
  try {
    const body = { tahun, jenis };
    if (cakupan === 'tertentu') {
      body.kecamatan_id = kecamatanId;
    }
    
    const response = await fetch('{{ route("export.run") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      },
      body: JSON.stringify(body),
    });
    
    const data = await response.json();
    
    if (data.success) {
      addLog('success', 'Export berhasil! Data telah disimpan.');
    } else {
      addLog('error', 'Export gagal: ' + data.message);
    }
    
    if (data.results?.saldo) {
      addLog('info', `Saldo: ${data.results.saldo.success} record berhasil`);
    }
    if (data.results?.transaksi) {
      addLog('info', `Transaksi: ${data.results.transaksi.success} bulan berhasil`);
    }
    
    setTimeout(() => location.reload(), 2000);
  } catch (err) {
    addLog('error', 'Error: ' + err.message);
  } finally {
    setLoading(false);
  }
});

function addLog(type, message) {
  const icons = { success: '✓', error: '✕', info: 'ℹ' };
  const div = document.createElement('div');
  div.className = `log-item log-item--${type}`;
  div.innerHTML = `<strong>${icons[type]}</strong> ${message}`;
  document.getElementById('logContainer').appendChild(div);
}

function setLoading(v) {
  btnExport.disabled = v;
  document.getElementById('btnText').classList.toggle('hidden', v);
  document.getElementById('btnLoading').classList.toggle('hidden', !v);
}

// Initialize
updateSummary();
</script>

@endsection
