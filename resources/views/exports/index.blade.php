{{-- resources/views/exports/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Dashboard — SIDBM Export')

@section('page-title', 'Dashboard')

@section('content')

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
      </div>
    </div>
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

        <div class="mt-4">
          <div class="flex items-center justify-between mb-4">
            <span class="text-muted" style="font-size: .85rem;">Status EnStorage</span>
            <span class="status {{ $enstoragePing ? 'status--success' : 'status--failed' }}">
              {{ $enstoragePing ? 'Terhubung' : 'Terputus' }}
            </span>
          </div>

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

@endsection
