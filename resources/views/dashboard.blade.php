@extends('layouts.admin')

@section('title', 'Dashboard — SIDBM Export')
@section('navbar_title', 'Dashboard')

@section('content')

<style>
.dashboard-page {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.page-header-hero {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  flex-wrap: wrap;
  background: linear-gradient(135deg, #526D82 0%, #27374D 100%);
  padding: 24px 28px;
  border-radius: 16px;
  color: white;
  box-shadow: 0 8px 32px rgba(82, 109, 130, 0.3);
}

.page-header-hero__left {
  display: flex;
  align-items: center;
  gap: 16px;
}

.page-header-hero__icon {
  width: 56px;
  height: 56px;
  background: rgba(255, 255, 255, 0.15);
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.page-header-hero__icon svg {
  width: 28px;
  height: 28px;
}

.page-header-hero__text h1 {
  font-size: 1.4rem;
  font-weight: 700;
  margin: 0 0 4px;
  color: white;
}

.page-header-hero__text p {
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

.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
}

.stat-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 20px;
  box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
  transition: all .2s ease;
  position: relative;
  overflow: hidden;
}

.stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
}

.stat-card--total::before { background: linear-gradient(90deg, #2563EB, #3B82F6); }
.stat-card--success::before { background: linear-gradient(90deg, #16A34A, #22C55E); }
.stat-card--failed::before { background: linear-gradient(90deg, #DC2626, #EF4444); }
.stat-card--pending::before { background: linear-gradient(90deg, #D97706, #F59E0B); }

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 30px rgba(15, 23, 42, 0.1);
}

.stat-card__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}

.stat-card__title {
  font-size: .82rem;
  font-weight: 600;
  color: #64748B;
}

.stat-card__badge {
  font-size: .72rem;
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 50px;
}

.badge--today { background: #DBEAFE; color: #1D4ED8; }
.badge--success { background: #DCFCE7; color: #16A34A; }
.badge--danger { background: #FEE2E2; color: #DC2626; }
.badge--pending { background: #FEF3C7; color: #D97706; }

.stat-card__body {
  display: flex;
  align-items: baseline;
  gap: 8px;
}

.stat-card__num {
  font-size: 2rem;
  font-weight: 700;
  color: #0F172A;
  line-height: 1;
}

.stat-card__num--success { color: #16A34A; }
.stat-card__num--danger { color: #DC2626; }
.stat-card__num--warning { color: #D97706; }

.stat-card__subtitle {
  font-size: .78rem;
  color: #94A3B8;
}

.dashboard-bottom-grid {
  display: grid;
  grid-template-columns: 1fr 380px;
  gap: 20px;
  align-items: stretch;
}

.chart-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
  overflow: hidden;
}

.chart-header {
  padding: 20px 24px;
  border-bottom: 1px solid var(--border);
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 16px;
  flex-wrap: wrap;
}

.chart-title-area {
  flex: 1;
}

.chart-title {
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--teks);
  margin: 0 0 4px;
}

.chart-subtitle {
  font-size: .82rem;
  color: var(--teks-muted);
  margin: 0;
}

.chart-legend {
  display: flex;
  gap: 16px;
  flex-shrink: 0;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: .82rem;
  color: var(--teks);
}

.legend-dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
}

.legend-dot--success { background: #16A34A; }
.legend-dot--failed { background: #DC2626; }

.chart-container {
  padding: 20px 24px;
}

.chart-container canvas {
  width: 100% !important;
  height: 280px !important;
}

.summary-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
  overflow: hidden;
}

.summary-header {
  padding: 20px 24px;
  border-bottom: 1px solid var(--border);
}

.summary-title {
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--teks);
  margin: 0 0 4px;
}

.summary-period {
  font-size: .82rem;
  color: var(--teks-muted);
}

.summary-body {
  padding: 16px 24px;
}

.summary-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 0;
  border-bottom: 1px solid #F1F5F9;
}

.summary-item:last-child {
  border-bottom: none;
}

.summary-item__left {
  display: flex;
  align-items: center;
  gap: 12px;
}

.summary-bullet {
  width: 10px;
  height: 10px;
  border-radius: 50%;
}

.summary-bullet--green { background: #22C55E; }
.summary-bullet--red { background: #EF4444; }
.summary-bullet--yellow { background: #F59E0B; }
.summary-bullet--gray { background: #CBD5E1; }

.summary-item__label {
  font-size: .875rem;
  font-weight: 500;
  color: #64748B;
}

.summary-item__value {
  font-size: 1.1rem;
  font-weight: 700;
  color: #0F172A;
}

.summary-item__value--green { color: #16A34A; }
.summary-item__value--red { color: #DC2626; }

.summary-footer {
  padding: 16px 24px;
  background: #F8FAFC;
  border-top: 1px solid var(--border);
  font-size: .78rem;
  color: var(--teks-muted);
  line-height: 1.6;
}

.summary-highlight {
  color: #DC2626;
  font-weight: 600;
}

@media (max-width: 1200px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .dashboard-bottom-grid {
    grid-template-columns: 1fr 340px;
  }
}

@media (max-width: 1024px) {
  .dashboard-bottom-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .page-header-hero {
    padding: 20px;
    flex-direction: column;
    align-items: flex-start;
  }
  
  .page-header-hero__text h1 {
    font-size: 1.2rem;
  }
  
  .ping-badge {
    width: 100%;
    justify-content: center;
  }
  
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
  }
  
  .stat-card {
    padding: 16px;
  }
  
  .stat-card__num {
    font-size: 1.6rem;
  }
  
  .chart-header {
    padding: 16px;
    flex-direction: column;
    gap: 12px;
  }
  
  .chart-container {
    padding: 12px 16px;
  }
  
  .chart-container canvas {
    height: 220px !important;
  }
  
  .summary-header {
    padding: 16px;
  }
  
  .summary-body {
    padding: 12px 16px;
  }
  
  .summary-footer {
    padding: 12px 16px;
  }
}

@media (max-width: 640px) {
  .stats-grid {
    grid-template-columns: 1fr;
    gap: 12px;
  }
  
  .stat-card {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
  
  .stat-card__header {
    margin-bottom: 0;
  }
  
  .stat-card__body {
    flex-direction: column;
    gap: 4px;
  }
  
  .chart-legend {
    flex-wrap: wrap;
    gap: 8px;
  }
}

@media (max-width: 480px) {
  .page-header-hero {
    padding: 16px;
  }
  
  .page-header-hero__icon {
    width: 48px;
    height: 48px;
  }
  
  .page-header-hero__text h1 {
    font-size: 1.1rem;
  }
  
  .stat-card__num {
    font-size: 1.5rem;
  }
}
</style>

<div class="dashboard-page">

  <div class="page-header-hero">
    <div class="page-header-hero__left">
      <div class="page-header-hero__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 12 12 3l9 9"/>
          <path d="M5 10v10h14V10"/>
        </svg>
      </div>
      <div class="page-header-hero__text">
        <h1>Dashboard</h1>
        <p>Ringkasan aktivitas export data SIDBM</p>
      </div>
    </div>
    <div class="ping-badge">
      <span class="ping-badge__dot {{ $enstoragePing ? 'ping-badge__dot--ok' : '' }}"></span>
      EnStorage {{ $enstoragePing ? 'Terhubung' : 'Tidak Terhubung' }}
    </div>
  </div>

  <div class="stats-grid">
    <div class="stat-card stat-card--total">
      <div class="stat-card__header">
        <span class="stat-card__title">Total Export</span>
        <span class="stat-card__badge badge--today">Semua</span>
      </div>
      <div class="stat-card__body">
        <span class="stat-card__num">{{ $stats['total'] }}</span>
        <span class="stat-card__subtitle">percobaan</span>
      </div>
    </div>
    <div class="stat-card stat-card--success">
      <div class="stat-card__header">
        <span class="stat-card__title">Berhasil</span>
        <span class="stat-card__badge badge--success">{{ $stats['total_success'] > 0 ? '+' . round(($stats['total_success'] / max($stats['total'], 1)) * 100) . '%' : '0%' }}</span>
      </div>
      <div class="stat-card__body">
        <span class="stat-card__num stat-card__num--success">{{ $stats['total_success'] }}</span>
        <span class="stat-card__subtitle">export berhasil</span>
      </div>
    </div>
    <div class="stat-card stat-card--failed">
      <div class="stat-card__header">
        <span class="stat-card__title">Gagal</span>
        <span class="stat-card__badge badge--danger">{{ $stats['total_failed'] > 0 ? round(($stats['total_failed'] / max($stats['total'], 1)) * 100) . '%' : '0%' }}</span>
      </div>
      <div class="stat-card__body">
        <span class="stat-card__num stat-card__num--danger">{{ $stats['total_failed'] }}</span>
        <span class="stat-card__subtitle">export gagal</span>
      </div>
    </div>
    <div class="stat-card stat-card--pending">
      <div class="stat-card__header">
        <span class="stat-card__title">Pending</span>
        <span class="stat-card__badge badge--pending">Menunggu</span>
      </div>
      <div class="stat-card__body">
        <span class="stat-card__num stat-card__num--warning">{{ $stats['total_pending'] }}</span>
        <span class="stat-card__subtitle">dalam antrean</span>
      </div>
    </div>
  </div>

  <div class="dashboard-bottom-grid">
    <div class="chart-card">
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

    <div class="summary-card">
      <div class="summary-header">
        <h3 class="summary-title">Ringkasan Export</h3>
        <span class="summary-period">Statistik keseluruhan</span>
      </div>
      <div class="summary-body">
        <div class="summary-list">
          <div class="summary-item">
            <div class="summary-item__left">
              <span class="summary-bullet summary-bullet--green"></span>
              <span class="summary-item__label">Total Berhasil</span>
            </div>
            <span class="summary-item__value summary-item__value--green">{{ $stats['total_success'] }}</span>
          </div>
          <div class="summary-item">
            <div class="summary-item__left">
              <span class="summary-bullet summary-bullet--red"></span>
              <span class="summary-item__label">Total Gagal</span>
            </div>
            <span class="summary-item__value summary-item__value--red">{{ $stats['total_failed'] }}</span>
          </div>
          <div class="summary-item">
            <div class="summary-item__left">
              <span class="summary-bullet summary-bullet--yellow"></span>
              <span class="summary-item__label">Tingkat Keberhasilan</span>
            </div>
            <span class="summary-item__value">{{ $stats['total'] > 0 ? round(($stats['total_success'] / $stats['total']) * 100, 1) : 0 }}%</span>
          </div>
          <div class="summary-item">
            <div class="summary-item__left">
              <span class="summary-bullet summary-bullet--gray"></span>
              <span class="summary-item__label">Sedang Pending</span>
            </div>
            <span class="summary-item__value">{{ $stats['total_pending'] }}</span>
          </div>
        </div>
      </div>
      <div class="summary-footer">
        Data statistic diupdate secara realtime dari database export logs.
      </div>
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
                    backgroundColor: 'rgba(220, 38, 38, 0.08)',
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
                    backgroundColor: 'rgba(22, 163, 74, 0.08)',
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
                        color: 'rgba(0, 0, 0, 0.04)',
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
