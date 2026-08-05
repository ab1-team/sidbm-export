@extends('layouts.app')

@section('title', 'Lihat Export - ' . $filename)

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ $filename }}</h2>
        <div>
            <a href="{{ route('export.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ url('/api/export/files?kecamatan=' . $kecamatanId . '&type=' . $type . '&tahun=' . $tahun) }}" class="btn btn-primary" target="_blank">Download JSON</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <p class="text-muted mb-3">
                Total: <strong>{{ count($data) }}</strong> record
            </p>

            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            @if($isSaldo)
                                <th>Kode Akun</th>
                                <th>Tahun</th>
                                @for($i = 1; $i <= 12; $i++)
                                    <th>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</th>
                                @endfor
                            @else
                                @php $first = is_array($data[0] ?? null) ? array_keys($data[0]) : []; @endphp
                                @foreach($first as $key)
                                    <th>{{ $key }}</th>
                                @endforeach
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $row)
                            <tr>
                                @if($isSaldo)
                                    <td>{{ $row['kode_akun'] ?? '-' }}</td>
                                    <td>{{ $row['tahun'] ?? '-' }}</td>
                                    @for($i = 1; $i <= 12; $i++)
                                        <td class="text-end">{{ number_format($row["debit_".str_pad($i, 2, '0', STR_PAD_LEFT)] ?? 0, 2) }}</td>
                                    @endfor
                                @else
                                    @foreach($row as $value)
                                        <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
                                    @endforeach
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
