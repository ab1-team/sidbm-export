{{-- resources/views/exports/logs.blade.php --}}

@extends('layouts.app')

@section('title', 'Log Export — SIDBM Export')

@section('page-title', 'Log Export')

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
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
  <div class="table-wrap" style="padding: 0 8px;">
    <table>
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Jenis</th>
          <th>Kecamatan</th>
          <th>Tahun</th>
          <th>File</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="7" style="text-align:center; color:var(--teks-muted); padding:32px;">
            Tidak ada log export
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

@endsection
