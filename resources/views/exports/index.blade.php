@extends('layouts.admin')

@section('title', 'Dashboard — SIDBM Export')
@section('navbar_title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')

<<<<<<< HEAD
{{-- ── Welcome Banner ── --}}
<div class="welcome-banner">
  <div class="welcome-banner__content">
    <h1 class="welcome-banner__title">Selamat Datang di SIDBM Export</h1>
    <p class="welcome-banner__text">Sistem Export Data Saldo dan Transaksi untuk keperluan arsip dan analisis data kecamatan di lingkungan Pemerintah Daerah.</p>
    <div class="welcome-banner__stats">
      <div class="welcome-banner__stat">
        <div class="welcome-banner__stat-num">0</div>
        <div class="welcome-banner__stat-label">Total Kecamatan</div>
      </div>
      <div class="welcome-banner__stat">
        <div class="welcome-banner__stat-num">0</div>
        <div class="welcome-banner__stat-label">Total Record Data</div>
      </div>
      <div class="welcome-banner__stat">
        <div class="welcome-banner__stat-num">{{ $lastExport ? $lastExport->created_at->format('d M') : '-' }}</div>
        <div class="welcome-banner__stat-label">Export Terakhir</div>
=======
<div class="page-header">
  <div>
    <h1>Dashboard Export</h1>
    <div class="page-header__sub">Ringkasan aktivitas export data SIDBM</div>
  </div>
  <span class="ping">
    <span class="ping__dot {{ $enstoragePing ? 'ping__dot--ok' : '' }}"></span>
    EnStorage {{ $enstoragePing ? 'Terhubung' : 'Tidak Terhubung' }}
  </span>
</div>

{{-- ── Statistik ── --}}
<div class="stats-grid">
  <div class="stat-card stat--total">
    <div class="stat-card__num">{{ $stats['total'] }}</div>
    <div class="stat-card__label">Total Export</div>
  </div>
  <div class="stat-card stat--success">
    <div class="stat-card__num">{{ $stats['total_success'] }}</div>
    <div class="stat-card__label">Berhasil</div>
  </div>
  <div class="stat-card stat--failed">
    <div class="stat-card__num">{{ $stats['total_failed'] }}</div>
    <div class="stat-card__label">Gagal</div>
  </div>
  <div class="stat-card stat--pending">
    <div class="stat-card__num">{{ $stats['total_pending'] }}</div>
    <div class="stat-card__label">Pending</div>
  </div>
</div>

<div class="grid-2">

  {{-- ── Form Export ── --}}
  <div class="card">
    <h2 class="card__title">Export Data</h2>

    <div class="form-group">
      <label class="form-label">Jenis Data</label>
      <div class="radio-group">
        <label class="radio-option">
          <input type="radio" name="jenis" value="saldo" checked>
          <span class="radio-option__box"><span class="icon">📊</span> Saldo</span>
        </label>
        <label class="radio-option">
          <input type="radio" name="jenis" value="transaksi">
          <span class="radio-option__box"><span class="icon">📋</span> Transaksi</span>
        </label>
        <label class="radio-option">
          <input type="radio" name="jenis" value="semua">
          <span class="radio-option__box"><span class="icon">📦</span> Keduanya</span>
        </label>
>>>>>>> 45ceb9df3b606b959ebdb99b211a606eae9cd357
      </div>
    </div>
<<<<<<< HEAD

    <div class="form-group">
      <label class="form-label" for="exportMode">Mode Export</label>
      <select id="exportMode" class="form-select">
        <option value="manual"> pilih Kecamatan &amp; Tahun</option>
        <option value="bulk">Semua otomatis — semua Kecamatan &amp; Tahun</option>
      </select>
    </div>

    <div id="manualSection">
      <div class="form-group">
        <label class="form-label" for="kecamatanId">Kecamatan</label>
        <select id="kecamatanId" name="kecamatan_id" class="form-select select2">
          <option value="">-- Pilih Kecamatan --</option>
          @foreach ($kecamatanList as $kec)
            <option value="{{ $kec->id }}">{{ $kec->id }} — {{ $kec->nama_kecamatan }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label class="form-label" for="tahun">Tahun</label>
      <select id="tahun" name="tahun" class="form-select select2">
          <option value="">-- Pilih Tahun --</option>
          @foreach ($tahunList as $t)
            <option value="{{ $t }}">{{ $t }}</option>
          @endforeach
        </select>
        <p class="text-muted" style="margin-top:4px; font-size:.8rem;">
          Data sebelum tahun {{ $batasArsip }} tersedia untuk diarsip
        </p>
      </div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
 $(document).ready(function () {

    $('.select2').select2({
        placeholder: "Pilih opsi...",
        allowClear: true,
        width: '100%'
    });

    $('#kecamatanId').on('change', function () {
        checkForm();
    });

    $('#tahun').on('change', function () {
        checkForm();
    });

    checkForm();
});
</script>

      <button id="btnExport" class="btn btn--primary btn--full" disabled>
        <span id="btnText">⬇ Jalankan Export</span>
        <span id="btnLoading" class="hidden">⏳ Sedang mengeksport...</span>
      </button>
    </div>

    <div id="bulkSection" class="hidden">
      <p class="text-muted" style="font-size:.82rem; margin-bottom:10px;">
        Akan mengeksport semua kecamatan &amp; semua tahun secara berurutan (Kec. pertama: tahun paling lama → tahun sekarang, lalu lanjut ke kecamatan berikutnya). Proses berjalan di background — halaman ini boleh ditutup.
      </p>
      <div style="display:flex; gap:8px;">
        <button id="btnBulkExport" class="btn btn--primary btn--full" type="button">
          🚀 Mulai Export Semua Otomatis
        </button>
      </div>
      <p id="bulkProgress" class="text-muted" style="margin-top:8px; font-size:.8rem;"></p>
    </div>
  </div>

  {{-- ── Log ── --}}
  <div>
    <div class="card" id="cardLog" style="display:none;">
      <h2 class="card__title">Hasil Export</h2>
      <div id="logContainer"></div>
    </div>

    <div class="card">
      <h2 class="card__title">
        <span>Log Terbaru</span>
        <a href="{{ route('export.logs') }}">Lihat semua →</a>
      </h2>

      <div id="latestLogs" style="min-height:100px;">
        <p class="text-muted">Memuat...</p>
      </div>
    </div>
  </div>
</div>

<script>
const exportMode    = document.getElementById('exportMode');
const manualSection = document.getElementById('manualSection');
const bulkSection   = document.getElementById('bulkSection');

const btnExport    = document.getElementById('btnExport');
const btnText      = document.getElementById('btnText');
const btnLoading   = document.getElementById('btnLoading');
const cardLog      = document.getElementById('cardLog');
const logContainer = document.getElementById('logContainer');
const selKecamatan = document.getElementById('kecamatanId');
const selTahun     = document.getElementById('tahun');

const btnBulkExport = document.getElementById('btnBulkExport');
const bulkProgress  = document.getElementById('bulkProgress');

const kecamatanData = @json($kecamatanList->map(fn ($k) => ['id' => $k->id, 'nama' => $k->nama_kecamatan])->values());
const tahunData      = @json(collect($tahunList)->sort()->values());

let manualAbortController = null;
let bulkRunning            = false;
let currentBatchId         = null;

exportMode.addEventListener('change', () => {
  const mode = exportMode.value;
  manualSection.classList.toggle('hidden', mode !== 'manual');
  bulkSection.classList.toggle('hidden', mode !== 'bulk');
});

function isBusy() {
  return !!manualAbortController || bulkRunning;
}



function checkForm() {

    const kec = $('#kecamatanId').val();
    const tahun = $('#tahun').val();

    console.log(kec, tahun);

    btnExport.disabled = !(kec && tahun) || isBusy();
}

btnExport.addEventListener('click', async () => {
  if (isBusy()) return;

  const kecamatanId = selKecamatan.value;
  const tahun       = selTahun.value;
  const jenis       = document.querySelector('input[name="jenis"]:checked').value;

let url = '';

switch (jenis) {
    case 'saldo':
        url = '/api/export/saldo';
        break;

    case 'transaksi':
        url = '/api/export/transaksi';
        break;

    case 'semua':
        url = '/api/export/semua';
        break;
}

  manualAbortController = new AbortController();
  setManualLoading(true);
  cardLog.style.display = 'block';
  logContainer.innerHTML = '';
  addLog('info', `🚀 Memulai export ${jenis} — Kecamatan ${kecamatanId}, Tahun ${tahun}...`);

  try {
    const response = await fetch(url, {
      method : 'POST',
      headers: {
        'Content-Type'    : 'application/json',
        'X-CSRF-TOKEN'    : document.querySelector('meta[name="csrf-token"]').content,
        'Accept'          : 'application/json',
      },
      body: JSON.stringify({
        kecamatan_id: kecamatanId,
        tahun: tahun
      }),
      signal: manualAbortController.signal,
    });
=======
  </div>
</div>

{{-- ── Statistik Cards ── --}}
<div class="stats-grid">
  <div class="stat-card stat-card--blue">
    <div class="stat-card__top">
      <div class="stat-card__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
          <circle cx="12" cy="10" r="3"/>
        </svg>
      </div>
      <div class="stat-card__trend stat-card__trend--up">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
        </svg>
        Active
      </div>
    </div>
    <div class="stat-card__num">0</div>
    <div class="stat-card__label">Total Kecamatan</div>
    <div class="stat-card__sub">Unit Kerja Aktif</div>
  </div>

  <div class="stat-card stat-card--green">
    <div class="stat-card__top">
      <div class="stat-card__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="1" x2="12" y2="23"/>
          <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
        </svg>
      </div>
      <div class="stat-card__trend stat-card__trend--neutral">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
        </svg>
        Data
      </div>
    </div>
    <div class="stat-card__num">0</div>
    <div class="stat-card__label">Total Saldo</div>
    <div class="stat-card__sub">Record Data</div>
  </div>

  <div class="stat-card stat-card--orange">
    <div class="stat-card__top">
      <div class="stat-card__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
          <line x1="1" y1="10" x2="23" y2="10"/>
        </svg>
      </div>
      <div class="stat-card__trend stat-card__trend--neutral">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
        </svg>
        Data
      </div>
    </div>
    <div class="stat-card__num">0</div>
    <div class="stat-card__label">Total Transaksi</div>
    <div class="stat-card__sub">Record Data</div>
  </div>

  <div class="stat-card stat-card--purple">
    <div class="stat-card__top">
      <div class="stat-card__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/>
          <polyline points="12 6 12 12 16 14"/>
        </svg>
      </div>
      <div class="stat-card__trend stat-card__trend--up">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="20 6 9 17 4 12"/>
        </svg>
        {{ $lastExport ? 'Updated' : 'Baru' }}
      </div>
    </div>
    <div class="stat-card__num">{{ $lastExport ? $lastExport->created_at->format('d M Y') : '-' }}</div>
    <div class="stat-card__label">Export Terakhir</div>
    <div class="stat-card__sub">
      @if($lastExport)
        {{ $lastExport->created_at->diffForHumans() }}
      @else
        Belum ada export
      @endif
    </div>
  </div>
</div>

{{-- ── Content Grid ── --}}
<div class="content-grid">
  {{-- Left Column --}}
  <div>
    {{-- Quick Info Card --}}
    <div class="card">
      <div class="card__header">
        <h2 class="card__title">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="16" x2="12" y2="12"/>
            <line x1="12" y1="8" x2="12.01" y2="8"/>
          </svg>
          Informasi Aplikasi
        </h2>
        <span class="card__badge">v1.0.0</span>
      </div>
      <div class="card__body">
        <ul class="info-list">
          <li class="info-item">
            <div class="info-item__icon info-item__icon--blue">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
              </svg>
            </div>
            <div class="info-item__content">
              <div class="info-item__label">Nama Sistem</div>
              <div class="info-item__value">SIDBM Export - Sistem Export Data</div>
            </div>
          </li>
          <li class="info-item">
            <div class="info-item__icon info-item__icon--green">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
              </svg>
            </div>
            <div class="info-item__content">
              <div class="info-item__label">Framework</div>
              <div class="info-item__value">Laravel {{ app()->version() }}</div>
            </div>
          </li>
          <li class="info-item">
            <div class="info-item__icon info-item__icon--blue">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <ellipse cx="12" cy="5" rx="9" ry="3"/>
                <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/>
                <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>
              </svg>
            </div>
            <div class="info-item__content">
              <div class="info-item__label">Database</div>
              <div class="info-item__value">SIDBM Database</div>
            </div>
          </li>
          <li class="info-item">
            <div class="info-item__icon info-item__icon--green">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
              </svg>
            </div>
            <div class="info-item__content">
              <div class="info-item__label">Tahun Data</div>
              <div class="info-item__value">2008 - {{ now()->year - 2 }}</div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>

  {{-- Right Column --}}
  <div>
    {{-- Summary Card --}}
    <div class="card">
      <div class="card__header">
        <h2 class="card__title">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21.21 15.89A10 10 0 1 1 8 2.83"/>
            <path d="M22 12A10 10 0 0 0 12 2v10z"/>
          </svg>
          Ringkasan Data
        </h2>
      </div>
      <div class="card__body">
        <div class="summary-box">
          <div class="summary-box__title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
            statistik Keseluruhan
          </div>
          <p class="summary-box__text">Total data yang tersedia untuk di-export dari seluruh kecamatan.</p>
          <div class="summary-box__items">
            <span class="summary-box__tag">0 Kecamatan</span>
            <span class="summary-box__tag">0 Saldo</span>
            <span class="summary-box__tag">0 Transaksi</span>
          </div>
        </div>
>>>>>>> origin/ui/halaman-login

        <div class="mt-4">
          <div class="flex items-center justify-between mb-4">
            <span class="text-muted" style="font-size: .85rem;">Status EnStorage</span>
            <span class="status {{ $enstoragePing ? 'status--success' : 'status--failed' }}">
              {{ $enstoragePing ? 'Terhubung' : 'Terputus' }}
            </span>
          </div>

<<<<<<< HEAD
    if (data.success) {
      addLog('success', '✅ Export selesai!');
    } else {
      addLog('error', '❌ ' + data.message);
    }

    if (data.results?.saldo) {
      const s = data.results.saldo;
      addLog(s.success ? 'success' : 'error', `Saldo: ${s.message}`);
    }
    if (data.results?.transaksi) {
      const t = data.results.transaksi;
      addLog(t.success > 0 ? 'success' : 'error', `Transaksi: ${t.success} berhasil, ${t.failed} gagal`);
    }

    loadLatestLogs();

  } catch (err) {
    if (err.name !== 'AbortError') {
      addLog('error', '❌ Error: ' + err.message);
    }
  } finally {
    manualAbortController = null;
    setManualLoading(false);
  }
});

function setManualLoading(v) {
  btnExport.disabled = v || !(selKecamatan.value && selTahun.value);
  btnText.classList.toggle('hidden', v);
  btnLoading.classList.toggle('hidden', !v);
  exportMode.disabled = v;
}

btnBulkExport.addEventListener('click', startBulkExport);

async function startBulkExport() {
  if (isBusy()) return;

  bulkRunning = true;
  const jenis = document.querySelector('input[name="jenis"]:checked').value;
  cardLog.style.display = 'block';
  logContainer.innerHTML = '';
  addLog('info', `🚀 Memulai export semua...`);

  setBulkLoading(true);

  try {
    const response = await fetch('/api/export/run-all', {
      method : 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept'      : 'application/json',
      },
      body: JSON.stringify({ jenis }),
    });

    const data = await response.json();

    if (!data.success) {
        addLog('error', '❌ ' + data.message);
        bulkRunning = false;
        setBulkLoading(false);
        return;
    }

    addLog('success', `✅ Export dimulai (${data.total_jobs} job dalam antrean)`);
    currentBatchId = data.batch_id;
    pollBatchAndLogs();

    bulkRunning = false;
    setBulkLoading(false);

  } catch (err) {
    addLog('error', '❌ Gagal: ' + err.message);
    bulkRunning = false;
    setBulkLoading(false);
  }
}

async function pollBatchAndLogs() {
    const timer = setInterval(async () => {
        try {
            const response = await fetch(`/api/batch/${currentBatchId}`);
            const data = await response.json();

            bulkProgress.innerHTML = `
                Total: ${data.total}<br>
                Selesai: ${data.finished}<br>
                Pending: ${data.pending}<br>
                Gagal: ${data.failed}
            `;

            loadLatestLogs();

            if (data.finished >= data.total) {
                clearInterval(timer);
                addLog('success', '🎉 Semua export selesai!');
                bulkRunning = false;
                setBulkLoading(false);
                loadLatestLogs();
            }

        } catch (error) {
            console.error('Polling gagal:', error);
        }
    }, 3000);
}

function setBulkLoading(v) {
  btnBulkExport.disabled = v;
  exportMode.disabled = v;
}

function addLog(type, message, detail = '') {
    const icons = { success: '✅', error: '❌', info: 'ℹ️' };
    const div = document.createElement('div');
    div.className = `log-item log-item--${type}`;
    div.innerHTML = `
        <span>${icons[type]}</span>
        <div>
            <div>${message}</div>
            ${detail ? `<div class="log-item__detail">${detail}</div>` : ''}
        </div>`;
    logContainer.appendChild(div);
}

function formatBytes(bytes) {
    if (!bytes) return '-';
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(2) + ' KB';
    return (bytes / 1048576).toFixed(2) + ' MB';
}

function formatTimeAgo(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    const now = new Date();
    const diff = Math.floor((now - date) / 1000);
    if (diff < 60) return diff + ' detik lalu';
    if (diff < 3600) return Math.floor(diff / 60) + ' menit lalu';
    if (diff < 86400) return Math.floor(diff / 3600) + ' jam lalu';
    return Math.floor(diff / 86400) + ' hari lalu';
}

async function loadLatestLogs() {
    try {
        const response = await fetch('/api/export/logs', {
            headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) {
            console.error('HTTP error:', response.status);
            return;
        }

        const data = await response.json();

        if (!data.success || !data.logs) {
            return;
        }

        if (data.logs.length === 0) {
            document.getElementById('latestLogs').innerHTML = '<div style="padding:20px;text-align:center;color:#999;">Belum ada export</div>';
            return;
        }

        let html = '';
        data.logs.forEach(log => {
            const isSuccess = log.status === 'success';
            const isFailed = log.status === 'failed';
            const fileSize = formatBytes(log.file_size);
            const timeAgo = formatTimeAgo(log.created_at);
            const badgeColor = isSuccess ? 'background:#d4edda;color:#155724;' : (isFailed ? 'background:#f8d7da;color:#721c24;' : 'background:#fff3cd;color:#856404;');
            const parts = (log.filename || '').split('_');
            const type = parts[0] || '';
            const tahun = (parts[1] || '').replace('.json', '');
            const openBtn = isSuccess && log.filename
                ? `<button onclick="window.open('/api/export/files?kecamatan=${log.kecamatan_id}&type=${type}&tahun=${tahun}', '_blank')" style="margin-left:4px;padding:2px 8px;font-size:.65rem;cursor:pointer;border:1px solid #ccc;border-radius:3px;background:#e3f2fd;">Buka</button>`
                : '';
            const downloadBtn = isSuccess && log.filename
                ? `<button onclick="downloadLog('${log.kecamatan_id}', '${log.filename}')" style="margin-left:4px;padding:2px 8px;font-size:.65rem;cursor:pointer;border:1px solid #ccc;border-radius:3px;background:#f8f9fa;">Download</button>`
                : '';

            html += `
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 5px;border-bottom:1px solid #eee;">
                    <div>
                        <div style="font-size:.9rem;font-weight:500;">
                            Kec. ${log.kecamatan_id} — ${log.jenis} ${log.tahun}
                            ${log.bulan ? '/ ' + log.bulan : ''}
                        </div>
                        <div style="font-size:.75rem;color:#666;">
                            ${log.filename || '-'}
                            ${log.file_size ? ' • ' + fileSize : ''}
                            ${log.record_count ? ' • ' + log.record_count.toLocaleString() + ' records' : ''}
                        </div>
                        ${log.error_message ? `<div style="font-size:.7rem;color:red;margin-top:2px;">Error: ${log.error_message}</div>` : ''}
                    </div>
                    <div style="text-align:right;flex-shrink:0;margin-left:10px;">
                        <span class="badge" style="${badgeColor}padding:3px 8px;border-radius:3px;font-size:.75rem;">${log.status}</span>
                        <div style="margin-top:4px;">
                            ${openBtn}
                            ${downloadBtn}
                        </div>
                        <div style="font-size:.65rem;color:#999;margin-top:3px;">${timeAgo}</div>
                    </div>
                </div>
            `;
        });

        document.getElementById('latestLogs').innerHTML = html;

    } catch (error) {
        console.error('Polling log gagal:', error);
    }
}

function downloadLog(kecamatanId, filename) {
    if (!filename || !kecamatanId) return;
    const parts = filename.split('_');
    const type = parts[0];
    const tahun = parts[1].replace('.json', '');
    window.location.href = `/api/export/files?kecamatan=${kecamatanId}&type=${type}&tahun=${tahun}&download=1`;
}

loadLatestLogs();
setInterval(loadLatestLogs, 5000);
</script>
=======
          <a href="{{ route('export.logs') }}" class="btn btn--outline" style="width: 100%; justify-content: center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
            </svg>
            Lihat Riwayat Export
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
>>>>>>> origin/ui/halaman-login

@endsection
