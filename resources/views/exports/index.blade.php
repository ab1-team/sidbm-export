{{-- resources/views/exports/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Dashboard — SIDBM Export')

@section('content')

{{-- ── Status EnStorage ── --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
  <h1 style="font-size:1.25rem; font-weight:700;">Dashboard Export</h1>
  <span class="ping">
    <span class="ping__dot {{ $enstoragePing ? 'ping__dot--ok' : '' }}"></span>
    EnStorage {{ $enstoragePing ? 'Terhubung' : 'Tidak Terhubung' }}
  </span>
</div>

{{-- ── Statistik ── --}}
<div class="stats-grid">
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

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; align-items:start;">

  {{-- ── Form Export ── --}}
  <div class="card">
    <h2 class="card__title">Export Data</h2>

    {{-- Jenis Data --}}
    <div class="form-group">
      <label class="form-label">Jenis Data</label>
      <div class="radio-group">
        <label class="radio-option">
          <input type="radio" name="jenis" value="saldo" checked>
          <span class="radio-option__box">
            <span class="icon">📊</span> Saldo
          </span>
        </label>
        <label class="radio-option">
          <input type="radio" name="jenis" value="transaksi">
          <span class="radio-option__box">
            <span class="icon">📋</span> Transaksi
          </span>
        </label>
        <label class="radio-option">
          <input type="radio" name="jenis" value="semua">
          <span class="radio-option__box">
            <span class="icon">📦</span> Keduanya
          </span>
        </label>
      </div>
    </div>

    {{-- Mode Export --}}
    <div class="form-group">
      <label class="form-label" for="exportMode">Mode Export</label>
      <select id="exportMode" class="form-select">
        <option value="manual"> pilih Kecamatan &amp; Tahun</option>
        <option value="bulk">Semua otomatis — semua Kecamatan &amp; Tahun</option>
      </select>
    </div>

    {{-- ── Mode Manual: pilih 1 kecamatan + 1 tahun ── --}}
    <div id="manualSection">
      <div class="form-group">
        <label class="form-label" for="kecamatanId">Kecamatan</label>
        <select id="kecamatanId" class="form-select">
          <option value="">-- Pilih Kecamatan --</option>
          @foreach ($kecamatanList as $kec)
            <option value="{{ $kec->id }}">{{ $kec->id }} — {{ $kec->nama_kecamatan }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label class="form-label" for="tahun">Tahun</label>
        <select id="tahun" class="form-select">
          <option value="">-- Pilih Tahun --</option>
          @foreach ($tahunList as $t)
            <option value="{{ $t }}">{{ $t }}</option>
          @endforeach
        </select>
        <p class="text-muted" style="margin-top:4px; font-size:.8rem;">
          Data sebelum tahun {{ $batasArsip }} tersedia untuk diarsip
        </p>
      </div>

      <button id="btnExport" class="btn btn--primary btn--full" disabled>
        <span id="btnText">⬇ Jalankan Export</span>
        <span id="btnLoading" class="hidden">⏳ Sedang mengeksport...</span>
      </button>
    </div>

    {{-- ── Mode Bulk: export semua kecamatan & tahun berurutan ── --}}
    <div id="bulkSection" class="hidden">
      <p class="text-muted" style="font-size:.8rem; margin-bottom:8px;">
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

  {{-- ── Log Hasil ── --}}
  <div>
    <div class="card" id="cardLog" style="display:none;">
      <h2 class="card__title">Hasil Export</h2>
      <div id="logContainer"></div>
    </div>

    {{-- ── Log Terbaru ── --}}
    <div class="card">
      <h2 class="card__title">
        Log Terbaru
        <a href="{{ route('export.logs') }}" style="font-size:.8rem; color:var(--biru-mid); float:right;">
          Lihat semua →
        </a>
      </h2>

      @forelse ($recentLogs as $log)
        <div style="display:flex; justify-content:space-between; align-items:flex-start; padding:8px 0; border-bottom:1px solid var(--border);">
          <div>
            <div style="font-size:.875rem; font-weight:500;">
              Kec. {{ $log->kecamatan_id }} — {{ ucfirst($log->jenis) }} {{ $log->tahun }}
              @if ($log->bulan)
                / {{ $log->bulan_label }}
              @endif
            </div>
            <div class="text-muted" style="font-size:.78rem;">
              {{ $log->filename }} • {{ $log->file_size_human }}
              @if($log->record_count)
                • {{ number_format($log->record_count) }} records
              @endif
            </div>
          </div>
          <div style="text-align:right; flex-shrink:0; margin-left:12px;">
            <span class="badge badge--{{ $log->status }}">{{ $log->status }}</span>
            <div class="text-muted" style="font-size:.75rem; margin-top:2px;">
              {{ $log->created_at?->diffForHumans() }}
            </div>
          </div>
        </div>
      @empty
        <p class="text-muted">Belum ada log export.</p>
      @endforelse
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
const btnBulkCancel = document.getElementById('btnBulkCancel');
const bulkProgress  = document.getElementById('bulkProgress');

// Data kecamatan & tahun dikirim dari server (urutan sesuai daftar di dropdown)
const kecamatanData = @json($kecamatanList->map(fn ($k) => ['id' => $k->id, 'nama' => $k->nama_kecamatan])->values());
const tahunData      = @json(collect($tahunList)->sort()->values()); // urut naik: lama -> sekarang

let manualAbortController = null; // untuk export manual (1 kecamatan/tahun)
let bulkRunning            = false; // true selagi proses bulk (dispatch + polling) berlangsung
let currentBatchId = null;

// ── Toggle tampilan berdasarkan Mode Export ──
exportMode.addEventListener('change', () => {
  const mode = exportMode.value;
  manualSection.classList.toggle('hidden', mode !== 'manual');
  bulkSection.classList.toggle('hidden', mode !== 'bulk');
});

function isBusy() {
  return !!manualAbortController || bulkRunning;
}

// ── Mode manual: aktifkan tombol export ──
selKecamatan.addEventListener('change', checkForm);
selTahun.addEventListener('change', checkForm);

function checkForm() {
  const kec   = selKecamatan.value;
  const tahun = selTahun.value;
  btnExport.disabled = !(kec && tahun) || isBusy();
}

// ── Export manual (1 kecamatan + 1 tahun) ──
btnExport.addEventListener('click', async () => {
  if (isBusy()) return;

  const kecamatanId = selKecamatan.value;
  const tahun       = selTahun.value;
  const jenis       = document.querySelector('input[name="jenis"]:checked').value;

  manualAbortController = new AbortController();
  setManualLoading(true);
  cardLog.style.display = 'block';
  logContainer.innerHTML = '';
  addLog('info', `🚀 Memulai export ${jenis} — Kecamatan ${kecamatanId}, Tahun ${tahun}...`);

  try {
    const response = await fetch('{{ route("export.run") }}', {
      method : 'POST',
      headers: {
        'Content-Type'    : 'application/json',
        'X-CSRF-TOKEN'    : document.querySelector('meta[name="csrf-token"]').content,
        'Accept'          : 'application/json',
      },
      body: JSON.stringify({ kecamatan_id: kecamatanId, tahun, jenis }),
      signal: manualAbortController.signal,
    });

    const data = await response.json();

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
      addLog('info', `Transaksi: ${t.success} bulan berhasil, ${t.failed} bulan dilewati`);
    }

    setTimeout(() => location.reload(), 2000);

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
  addLog('info', `🚀 Mengirim ${kecamatanData.length} kecamatan × ${tahunData.length} tahun ke antrean background...`);

  setBulkLoading(true);

  try {
    const response = await fetch('{{ route("export.run-all") }}', {
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




bulkRunning = false;
setBulkLoading(false);

  } catch (err) {
    addLog('error', '❌ Gagal memulai: ' + err.message);
    bulkRunning = false;
    setBulkLoading(false);
  }
}


function setBulkLoading(v) {
    btnBulkExport.disabled = v;
    exportMode.disabled = v;
}

// ── Util log ──
function addLog(type, message, detail = '') {
  const icons = { success: '✅', error: '❌', info: 'ℹ️' };
  const div   = document.createElement('div');
  div.className = `log-item log-item--${type}`;
  div.innerHTML = `
    <span>${icons[type]}</span>
    <div>
      <div>${message}</div>
      ${detail ? `<div class="log-item__detail">${detail}</div>` : ''}
    </div>`;
  logContainer.appendChild(div);
}

</script>

@endsection