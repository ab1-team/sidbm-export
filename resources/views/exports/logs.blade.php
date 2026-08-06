@extends('layouts.admin')

@section('title', 'Log Export — SIDBM Export')
@section('navbar_title', 'Log Export')

@section('page-title', 'Log Export')

@section('content')

<<<<<<< HEAD
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
  <a href="{{ route('export.index') }}" class="btn btn--primary" style="width:auto;">
    ← Dashboard
  </a>
=======
<div class="page-header">
  <div>
    <h1>Log Export</h1>
    <div class="page-header__sub">Riwayat aktivitas export data</div>
  </div>
>>>>>>> 45ceb9df3b606b959ebdb99b211a606eae9cd357
</div>

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
    <a href="{{ route('export.logs') }}" style="margin-left:10px; font-size:.875rem; color:var(--teks-muted);">Reset</a>
  </form>
</div>

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
<<<<<<< HEAD
=======
          <th>Record</th>
          <th>Ukuran</th>
>>>>>>> 45ceb9df3b606b959ebdb99b211a606eae9cd357
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
<<<<<<< HEAD
        <tr>
          <td colspan="7" style="text-align:center; color:var(--teks-muted); padding:32px;">
            Tidak ada log export
          </td>
        </tr>
      </tbody>
    </table>
  </div>
=======
        @forelse ($logs as $log)
          <tr>
            <td>{{ $log->kecamatan_id }}</td>
            <td>{{ ucfirst($log->jenis) }}</td>
            <td>{{ $log->tahun }}</td>
            <td>{{ $log->bulan_label }}</td>
            <td>
              @if ($log->file_url)
                <a href="{{ $log->file_url }}" target="_blank" class="table-link">
                  {{ $log->filename }}
                </a>
              @else
                <span class="text-muted">{{ $log->filename }}</span>
              @endif
            </td>
            <td class="text-right">{{ $log->record_count ? number_format($log->record_count) : '-' }}</td>
            <td>{{ $log->file_size_human }}</td>
            <td>
              <span class="badge badge--{{ $log->status }}">{{ $log->status }}</span>
              @if ($log->error_message)
                <div class="error-msg">{{ Str::limit($log->error_message, 60) }}</div>
              @endif
            </td>
            <td class="text-muted text-sm">{{ $log->created_at?->format('d/m/Y H:i') }}</td>
            <td class="text-muted text-sm">{{ $log->triggered_by ?? '-' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="11" style="text-align:center; color:var(--teks-muted); padding:32px;">Tidak ada log yang sesuai filter.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div style="margin-top:16px;">{{ $logs->withQueryString()->links() }}</div>
>>>>>>> 45ceb9df3b606b959ebdb99b211a606eae9cd357
</div>

@endsection