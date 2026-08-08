@extends('layouts.admin')

@section('title', 'Dashboard — SIDBM Export')
@section('navbar_title', 'Dashboard')

@section('content')

<div class="page-header">
  <div>
    <h1>Dashboard</h1>
    <div class="page-header__sub">Ringkasan aktivitas export data SIDBM</div>
  </div>
  <span class="ping">
    <span class="ping__dot {{ $enstoragePing ? 'ping__dot--ok' : '' }}"></span>
    EnStorage {{ $enstoragePing ? 'Terhubung' : 'Tidak Terhubung' }}
  </span>
</div>

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-card__header">
      <span class="stat-card__title">Total Export Hari Ini</span>
      <span class="stat-card__badge badge--today">Hari Ini</span>
    </div>
    <div class="stat-card__num">{{ $stats['total'] }}</div>
    <div class="stat-card__subtitle">Seluruh percobaan export</div>
  </div>
  <div class="stat-card">
    <div class="stat-card__header">
      <span class="stat-card__title">Berhasil</span>
      <span class="stat-card__badge badge--success">+15,8%</span>
    </div>
    <div class="stat-card__num stat-card__num--success">{{ $stats['total_success'] }}</div>
    <div class="stat-card__subtitle">Dibanding kemarin (19)</div>
  </div>
  <div class="stat-card">
    <div class="stat-card__header">
      <span class="stat-card__title">Gagal</span>
      <span class="stat-card__badge badge--danger">+9,4%</span>
    </div>
    <div class="stat-card__num stat-card__num--danger">{{ $stats['total_failed'] }}</div>
    <div class="stat-card__subtitle">Dibanding kemarin (9.870)</div>
  </div>
  <div class="stat-card">
    <div class="stat-card__header">
      <span class="stat-card__title">Pending</span>
      <span class="stat-card__badge badge--pending">Menunggu</span>
    </div>
    <div class="stat-card__num stat-card__num--warning">{{ $stats['total_pending'] }}</div>
    <div class="stat-card__subtitle">Dalam antrean proses</div>
  </div>
</div>

<div class="dashboard-bottom-grid">
  <div class="card chart-card">
    <div class="chart-header">
      <div class="chart-title-area">
        <h3 class="chart-title">Trend Export</h3>
        <p class="chart-subtitle">Jumlah export berhasil dan gagal dalam 7 hari terakhir</p>
      </div>
      <div class="chart-legend">
        <span class="legend-item"><span class="legend-dot legend-dot--success"></span> Berhasil</span>
        <span class="legend-item"><span class="legend-dot legend-dot--failed"></span> Gagal</span>
      </div>
    </div>
    <div class="chart-container">
      <canvas id="exportChart"></canvas>
    </div>
  </div>

  <div class="card summary-card">
    <div class="summary-header">
      <h3 class="summary-title">Ringkasan 7 Hari</h3>
      <span class="summary-period">01 Agu – 07 Agu 2026</span>
    </div>
    <div class="summary-divider"></div>
    <div class="summary-list">
      <div class="summary-item">
        <div class="summary-item__left">
          <span class="summary-bullet summary-bullet--green"></span>
          <span class="summary-item__label">Total Berhasil</span>
        </div>
        <span class="summary-item__value">133</span>
      </div>
      <div class="summary-item">
        <div class="summary-item__left">
          <span class="summary-bullet summary-bullet--red"></span>
          <span class="summary-item__label">Total Gagal</span>
        </div>
        <span class="summary-item__value">68.614</span>
      </div>
      <div class="summary-item">
        <div class="summary-item__left">
          <span class="summary-bullet summary-bullet--yellow"></span>
          <span class="summary-item__label">Tingkat Keberhasilan</span>
        </div>
        <span class="summary-item__value">0,19%</span>
      </div>
      <div class="summary-item">
        <div class="summary-item__left">
          <span class="summary-bullet summary-bullet--gray"></span>
          <span class="summary-item__label">Puncak Kegagalan</span>
        </div>
        <span class="summary-item__value">05 Agu</span>
      </div>
    </div>
    <div class="summary-divider"></div>
    <div class="summary-footer">
      Kegagalan export didominasi kesalahan validasi format data pada <span class="summary-highlight">05 Agustus</span>. Disarankan meninjau log kegagalan pada tanggal tersebut.
    </div>
  </div>
</div>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
(function() {
    const canvas = document.getElementById('exportChart');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    const chartLabels = ['01 Aug', '02 Aug', '03 Aug', '04 Aug', '05 Aug', '06 Aug', '07 Aug'];
    const successData = [8, 15, 12, 22, 18, 28, 14];
    const failedData = [8420, 9150, 10280, 8760, 11340, 9870, 10794];

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    const exportChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [
                {
                    label: 'Gagal',
                    data: failedData,
                    borderColor: '#DC2626',
                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#DC2626',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#DC2626',
                    pointHoverBorderWidth: 3,
                    yAxisID: 'y',
                },
                {
                    label: 'Berhasil',
                    data: successData,
                    borderColor: '#16A34A',
                    backgroundColor: 'rgba(22, 163, 74, 0.1)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.5,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#16A34A',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#16A34A',
                    pointHoverBorderWidth: 3,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(39, 55, 77, 0.95)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 14,
                    borderColor: 'rgba(82, 109, 130, 0.3)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        title: function(context) {
                            return context[0].label;
                        },
                        label: function(context) {
                            return context.dataset.label + ': ' + formatNumber(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6B7280',
                        font: {
                            family: 'Poppins',
                            size: 11
                        }
                    }
                },
                y: {
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true,
                    max: 12000,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                    },
                    ticks: {
                        color: '#6B7280',
                        font: {
                            family: 'Poppins',
                            size: 11
                        },
                        padding: 8,
                        stepSize: 2000,
                        callback: function(value) {
                            return formatNumber(value);
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    max: 60,
                    grid: {
                        drawOnChartArea: false,
                    },
                    ticks: {
                        color: '#6B7280',
                        font: {
                            family: 'Poppins',
                            size: 11
                        },
                        padding: 8,
                        stepSize: 10,
                    }
                }
            }
        }
    });
})();
</script>
@endsection
