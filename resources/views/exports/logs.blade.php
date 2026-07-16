{{-- resources/views/exports/logs.blade.php --}}

@extends('layouts.app')

@section('title', 'Log Export — SIDBM Export')

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
  <h1 style="font-size:1.25rem; font-weight:700;">Log Export</h1>
  <a href="{{ route('export.index') }}" class="btn btn--primary" style="width:auto;">
    ← Dashboard
  </a>
</div>

{{-- ── Filter ── --}}
<div class="card">
  <h2 class="card__title">Filter</h2>
  <form method="GET" action="{{ route('export.logs') }}">
    <div class="form-row form-row--3">
      <div class="form-group">
        <label class="form-label">Kecamatan</label>
        <select name="kecamatan_id" class="form-select">
          <option value="">Semua Kecamatan</option>
          @foreach ($kecamatanList as $kec)
            <option value="{{ $kec->id }}" {{ $kecamatanId == $kec->id ? 'selected' : '' }}>
              {{ $kec->id }} — {{ $kec->nama_kecamatan }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Jenis</label>
        <select name="jenis" class="form-select">
          <option value="">Semua Jenis</option>
          <option value="saldo"     {{ $jenis === 'saldo'     ? 'selected' : '' }}>Saldo</option>
          <option value="transaksi" {{ $jenis === 'transaksi' ? 'selected' : '' }}>Transaksi</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="">Semua Status</option>
          <option value="success" {{ $status === 'success' ? 'selected' : '' }}>Success</option>
          <option value="failed"  {{ $status === 'failed'  ? 'selected' : '' }}>Failed</option>
          <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
        </select>
      </div>
    </div>
    <button type="submit" class="btn btn--primary" style="width:auto;">Filter</button>
    <a href="{{ route('export.logs') }}" style="margin-left:8px; font-size:.875rem; color:var(--teks-muted);">Reset</a>
  </form>
</div>

{{-- ── Tabel Log ── --}}
<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Kecamatan</th>
          <th>Jenis</th>
          <th>Tahun</th>
          <th>Bulan</th>
          <th>File</th>
          <th>Records</th>
          <th>Ukuran</th>
          <th>Status</th>
          <th>Waktu</th>
          <th>Oleh</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($logs as $log)
          <tr>
            <td>{{ $log->kecamatan_id }}</td>
            <td>{{ ucfirst($log->jenis) }}</td>
            <td>{{ $log->tahun }}</td>
            <td>{{ $log->bulan_label }}</td>
            <td>
              @if ($log->file_url)
                <a href="{{ $log->file_url }}" target="_blank" style="color:var(--biru-mid); font-size:.8rem;">
                  {{ $log->filename }}
                </a>
              @else
                <span class="text-muted">{{ $log->filename }}</span>
              @endif
            </td>
            <td>{{ $log->record_count ? number_format($log->record_count) : '-' }}</td>
            <td>{{ $log->file_size_human }}</td>
            <td>
              <span class="badge badge--{{ $log->status }}">{{ $log->status }}</span>
              @if ($log->error_message)
                <div style="font-size:.75rem; color:var(--merah); margin-top:2px;">
                  {{ Str::limit($log->error_message, 60) }}
                </div>
              @endif
            </td>
            <td style="font-size:.8rem; color:var(--teks-muted);">
              {{ $log->created_at?->format('d/m/Y H:i') }}
            </td>
            <td style="font-size:.8rem; color:var(--teks-muted);">
              {{ $log->triggered_by ?? '-' }}
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="10" style="text-align:center; color:var(--teks-muted); padding:32px;">
              Tidak ada log yang sesuai filter.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  <div style="margin-top:16px;">
    {{ $logs->withQueryString()->links() }}
  </div>
</div>

@endsection
