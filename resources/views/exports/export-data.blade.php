@extends('layouts.admin')

@section('title', 'Export Data — SIDBM Export')
@section('navbar_title', 'Export Data')

@section('content')

<style>
.export-page {
  display: flex;
  flex-direction: column;
  gap: 16px;
  padding-bottom: 20px;
}

.page-header-modern {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  flex-wrap: wrap;
  background: linear-gradient(135deg, var(--sidebar) 0%, var(--sidebar-hov) 100%);
  padding: 24px 28px;
  border-radius: 16px;
  color: white;
  box-shadow: 0 8px 32px rgba(82, 109, 130, 0.3);
}

.page-header-modern__left {
  display: flex;
  align-items: center;
  gap: 16px;
}

.page-header-modern__icon {
  width: 56px;
  height: 56px;
  background: rgba(255, 255, 255, 0.15);
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.page-header-modern__icon svg {
  width: 28px;
  height: 28px;
}

.page-header-modern__text h1 {
  font-size: 1.4rem;
  font-weight: 700;
  margin: 0 0 4px;
  color: white;
}

.page-header-modern__text p {
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

.export-grid {
  display: grid;
  grid-template-columns: 1fr 1.1fr;
  gap: 16px;
  align-items: stretch;
}

.export-card {
  background: white;
  border-radius: 16px;
  border: 1px solid var(--border);
  box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
  overflow: visible;
  display: flex;
  flex-direction: column;
}

.export-card__header {
  padding: 12px 16px 10px;
  border-bottom: 1px solid var(--border);
  display: flex;
  align-items: center;
  gap: 10px;
  flex-shrink: 0;
}

.logs-card {
  background: white;
  border-radius: 16px;
  border: 1px solid var(--border);
  box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
  overflow: visible;
  display: flex;
  flex-direction: column;
  position: relative;
}

.logs-card__header {
  padding: 16px 20px 14px;
  border-bottom: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-shrink: 0;
}

.export-card__icon {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.export-card__icon--blue {
  background: #EFF6FF;
  color: #2563EB;
}

.export-card__icon--green {
  background: #F0FDF4;
  color: #16A34A;
}

.export-card__icon--purple {
  background: #F5F3FF;
  color: #7C3AED;
}

.export-card__icon svg {
  width: 18px;
  height: 18px;
}

.export-card__title {
  font-size: .95rem;
  font-weight: 600;
  color: var(--teks);
  margin: 0;
}

.export-card__subtitle {
  font-size: .72rem;
  color: var(--teks-muted);
  margin: 1px 0 0;
}

.export-card__body {
  padding: 14px 16px 16px;
  overflow: visible;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.form-section {
  margin-bottom: 12px;
  flex-shrink: 0;
  position: relative;
  z-index: 1;
}

.form-section:last-child {
  margin-bottom: 0;
}

.form-section__label {
  font-size: .7rem;
  font-weight: 600;
  color: var(--teks-muted);
  text-transform: uppercase;
  letter-spacing: .5px;
  margin-bottom: 5px;
  display: block;
}

.export-card__body .form-label {
  font-size: .78rem;
  font-weight: 500;
  margin-bottom: 5px;
  display: block;
}

.radio-cards {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 8px;
}

.radio-card {
  position: relative;
}

.radio-card input {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

.radio-card__label {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
  padding: 10px 8px;
  background: #F9FAFB;
  border: 2px solid var(--border);
  border-radius: 50px;
  cursor: pointer;
  transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
  text-align: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.radio-card__label:hover {
  border-color: #CBD5E1;
  background: #F1F5F9;
  transform: translateY(-1px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.radio-card input:checked + .radio-card__label {
  border-color: var(--sidebar);
  background: rgba(82, 109, 130, 0.08);
  transform: translateY(-2px);
  box-shadow: 0 4px 20px rgba(82, 109, 130, 0.2);
}

.radio-card__icon {
  width: 26px;
  height: 26px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform .3s ease;
}

.radio-card__label:hover .radio-card__icon,
.radio-card input:checked + .radio-card__label .radio-card__icon {
  transform: scale(1.05);
}

.radio-card__icon--blue {
  background: #DBEAFE;
  color: #2563EB;
}

.radio-card__icon--green {
  background: #DCFCE7;
  color: #16A34A;
}

.radio-card__icon--purple {
  background: #EDE9FE;
  color: #7C3AED;
}

.radio-card__icon svg {
  width: 14px;
  height: 14px;
}

.radio-card__text {
  font-size: .75rem;
  font-weight: 600;
  color: var(--teks);
}

.radio-card input:checked + .radio-card__label .radio-card__text {
  color: var(--sidebar);
}

.select-wrapper {
  position: relative;
}

.select-wrapper::after {
  content: '';
  position: absolute;
  right: 16px;
  top: 50%;
  transform: translateY(-50%) rotate(0deg);
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-top: 5px solid var(--teks-muted);
  pointer-events: none;
  transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
}

.select-wrapper:hover::after {
  border-top-color: var(--sidebar);
  transform: translateY(-50%) rotate(0deg);
}

.select-wrapper:focus-within::after {
  transform: translateY(-50%) rotate(180deg);
  border-top-color: var(--sidebar);
}

.form-select-modern {
  width: 100%;
  padding: 12px 40px 12px 16px;
  border: 2px solid var(--border);
  border-radius: 50px;
  font-size: .9rem;
  font-family: inherit;
  background: white;
  color: var(--teks);
  cursor: pointer;
  appearance: none;
  transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.form-select-modern:hover {
  border-color: #CBD5E1;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  transform: translateY(-1px);
}

.form-select-modern:focus {
  outline: none;
  border-color: var(--sidebar);
  box-shadow: 0 4px 20px rgba(82, 109, 130, 0.2);
  transform: translateY(-1px);
}

.select2-container--classic .select2-selection--single,
.select2-container--default .select2-selection--single {
  border-radius: 50px !important;
  border: 2px solid var(--border) !important;
  padding: 8px 40px 8px 16px !important;
  transition: all .3s ease !important;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04) !important;
}

.select2-container--classic .select2-selection--single:hover,
.select2-container--default .select2-selection--single:hover {
  border-color: #CBD5E1 !important;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08) !important;
}

.select2-container--default .select2-selection--single:focus,
.select2-container--classic .select2-selection--single:focus {
  border-color: var(--sidebar) !important;
  box-shadow: 0 4px 20px rgba(82, 109, 130, 0.2) !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
  padding-left: 0 !important;
}

.select2-dropdown {
  border-radius: 16px !important;
  border: 1px solid var(--border) !important;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12) !important;
  overflow: hidden;
  padding: 6px;
}

.select2-results__option {
  border-radius: 10px !important;
  padding: 10px 14px !important;
  margin-bottom: 4px;
  transition: all .2s ease;
}

.select2-results__option:hover {
  background: #F1F5F9 !important;
}

.select2-results__option--highlighted[aria-selected] {
  background: rgba(82, 109, 130, 0.1) !important;
  color: var(--sidebar) !important;
}

.select2-results__option[aria-selected="true"] {
  background: rgba(82, 109, 130, 0.15) !important;
  color: var(--sidebar) !important;
  font-weight: 600;
}

.select2-container--default .select2-search--dropdown .select2-search__field {
  border-radius: 50px !important;
  border: 1px solid var(--border) !important;
  padding: 10px 16px !important;
  text-align: center !important;
}

.select2-container--default .select2-search--dropdown .select2-search__field:focus {
  border-color: var(--sidebar) !important;
  outline: none !important;
  text-align: center !important;
}

.select2-dropdown-animated .select2-results > .select2-results__options {
  max-height: 250px;
  overflow-y: auto;
}

.select2-selection--single.select2-opening {
  border-color: var(--sidebar) !important;
  box-shadow: 0 4px 20px rgba(82, 109, 130, 0.25) !important;
  transform: scale(1.01);
}

@keyframes select2Bounce {
  0% { transform: scale(1); }
  50% { transform: scale(1.02); }
  100% { transform: scale(1); }
}

.select2-container--default .select2-selection--single {
  animation: none;
}

.custom-select-wrapper {
  position: relative;
}

.custom-select {
  width: 100%;
  padding: 14px 45px 14px 18px;
  border: 2px solid var(--border);
  border-radius: 50px;
  font-size: .9rem;
  font-family: inherit;
  background: white;
  color: var(--teks);
  cursor: pointer;
  appearance: none;
  -webkit-appearance: none;
  transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
  outline: none;
}

.custom-select:hover {
  border-color: #CBD5E1;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  transform: translateY(-1px);
}

.custom-select:focus {
  border-color: var(--sidebar);
  box-shadow: 0 4px 20px rgba(82, 109, 130, 0.2);
  transform: translateY(-1px);
}

.custom-select-arrow {
  position: absolute;
  right: 18px;
  top: 50%;
  transform: translateY(-50%);
  width: 20px;
  height: 20px;
  pointer-events: none;
  color: var(--teks-muted);
  transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
}

.custom-select:focus + .custom-select-arrow,
.custom-select-wrapper:hover .custom-select-arrow {
  color: var(--sidebar);
  transform: translateY(-50%) rotate(180deg);
}

.custom-select option {
  padding: 12px 16px;
  border-radius: 10px;
  transition: background .2s ease;
}

.custom-select option:hover {
  background: #F1F5F9;
}

.select2-dropdown-animated .select2-results__option {
  animation: slideUp .2s ease-out;
}

.select2-dropdown-animated .select2-results__option:nth-child(1) { animation-delay: 0ms; }
.select2-dropdown-animated .select2-results__option:nth-child(2) { animation-delay: 30ms; }
.select2-dropdown-animated .select2-results__option:nth-child(3) { animation-delay: 60ms; }
.select2-dropdown-animated .select2-results__option:nth-child(4) { animation-delay: 90ms; }
.select2-dropdown-animated .select2-results__option:nth-child(5) { animation-delay: 120ms; }
.select2-dropdown-animated .select2-results__option:nth-child(6) { animation-delay: 150ms; }
.select2-dropdown-animated .select2-results__option:nth-child(7) { animation-delay: 180ms; }
.select2-dropdown-animated .select2-results__option:nth-child(8) { animation-delay: 210ms; }

.mode-tabs {
  display: flex;
  background: #F1F5F9;
  border-radius: 50px;
  padding: 3px;
  gap: 3px;
  position: relative;
  overflow: hidden;
}

.mode-tabs::before {
  content: '';
  position: absolute;
  top: 4px;
  left: 4px;
  width: calc(50% - 4px);
  height: calc(100% - 8px);
  background: white;
  border-radius: 50px;
  box-shadow: 0 2px 10px rgba(82, 109, 130, 0.2);
  transition: all .35s cubic-bezier(0.4, 0, 0.2, 1);
  z-index: 0;
}

.mode-tabs.bulk-active::before {
  transform: translateX(100%);
}

.mode-tab {
  flex: 1;
  padding: 6px 10px;
  border: none;
  background: transparent;
  border-radius: 50px;
  font-size: .72rem;
  font-weight: 600;
  color: var(--teks-muted);
  cursor: pointer;
  transition: all .25s ease;
  font-family: inherit;
  position: relative;
  z-index: 1;
}

.mode-tab:hover {
  color: var(--teks);
}

.mode-tab.active {
  color: var(--sidebar);
}

.mode-content {
  display: none;
  padding-top: 8px;
  overflow: hidden;
  flex: 1;
  min-height: 0;
}

.mode-content.active {
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.form-hint {
  font-size: .7rem;
  color: var(--teks-muted);
  margin-top: 4px;
  display: flex;
  align-items: flex-start;
  gap: 5px;
}

.form-hint svg {
  width: 13px;
  height: 13px;
  flex-shrink: 0;
  margin-top: 1px;
}

.btn-export {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  width: 100%;
  padding: 10px 16px;
  background: linear-gradient(135deg, var(--sidebar) 0%, var(--sidebar-hov) 100%);
  color: white;
  border: none;
  border-radius: 50px;
  font-size: .82rem;
  font-weight: 600;
  cursor: pointer;
  transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
  font-family: inherit;
  box-shadow: 0 4px 14px rgba(82, 109, 130, 0.35);
  position: relative;
  overflow: hidden;
  flex-shrink: 0;
  margin-top: auto;
}

.btn-export::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transition: left .5s ease;
}

.btn-export:hover:not(:disabled)::before {
  left: 100%;
}

.btn-export:hover:not(:disabled) {
  transform: translateY(-3px) scale(1.02);
  box-shadow: 0 8px 25px rgba(82, 109, 130, 0.5);
}

.btn-export:active:not(:disabled) {
  transform: translateY(-1px) scale(0.98);
  box-shadow: 0 4px 12px rgba(82, 109, 130, 0.35);
}

.btn-export:disabled {
  opacity: .5;
  cursor: not-allowed;
}

.btn-export svg {
  width: 20px;
  height: 20px;
  transition: transform .3s ease;
}

.btn-export:hover:not(:disabled) svg {
  transform: translateX(3px);
}

.btn-export--bulk {
  background: linear-gradient(135deg, #059669 0%, #047857 100%);
  box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35);
}

.btn-export--bulk::before {
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
}

.btn-export--bulk:hover:not(:disabled) {
  box-shadow: 0 8px 25px rgba(5, 150, 105, 0.5);
}

.progress-bar {
  width: 100%;
  height: 6px;
  background: #E5E7EB;
  border-radius: 3px;
  overflow: hidden;
  margin-top: 8px;
}

.progress-bar__fill {
  height: 100%;
  background: linear-gradient(90deg, #22C55E, #16A34A);
  border-radius: 3px;
  transition: width .3s ease;
}

.bulk-progress {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 6px;
  margin-bottom: 8px;
}

.bulk-progress__item {
  background: #F9FAFB;
  border-radius: 6px;
  padding: 6px;
  text-align: center;
}

.bulk-progress__num {
  font-size: .88rem;
  font-weight: 700;
  color: var(--teks);
}

.bulk-progress__num--success { color: #16A34A; }
.bulk-progress__num--failed { color: #DC2626; }
.bulk-progress__num--pending { color: #D97706; }

.bulk-progress__label {
  font-size: .58rem;
  color: var(--teks-muted);
  margin-top: 1px;
}

.logs-card__title {
  font-size: .95rem;
  font-weight: 600;
  color: var(--teks);
}

.logs-card__link {
  font-size: .8rem;
  color: var(--sidebar);
  font-weight: 500;
  transition: opacity .2s;
}

.logs-card__link:hover {
  opacity: .7;
}

.logs-card__body {
  padding: 0;
  overflow-y: auto;
  flex: 1;
}

.logs-list {
  display: flex;
  flex-direction: column;
}

.logs-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  padding: 14px 20px;
  border-bottom: 1px solid var(--border);
  transition: background .15s;
}

.logs-item:last-child {
  border-bottom: none;
}

.logs-item:hover {
  background: #F9FAFB;
}

.logs-item__left {
  display: flex;
  align-items: center;
  gap: 12px;
  flex: 1;
  min-width: 0;
}

.logs-item__icon {
  width: 38px;
  height: 38px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.logs-item__icon--success {
  background: #DCFCE7;
  color: #16A34A;
}

.logs-item__icon--failed {
  background: #FEE2E2;
  color: #DC2626;
}

.logs-item__icon--pending {
  background: #FEF3C7;
  color: #D97706;
}

.logs-item__icon svg {
  width: 18px;
  height: 18px;
}

.logs-item__info {
  flex: 1;
  min-width: 0;
}

.logs-item__title {
  font-size: .85rem;
  font-weight: 600;
  color: var(--teks);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.logs-item__meta {
  font-size: .75rem;
  color: var(--teks-muted);
  margin-top: 2px;
  display: flex;
  align-items: center;
  gap: 6px;
  flex-wrap: wrap;
}

.logs-item__meta span {
  display: flex;
  align-items: center;
  gap: 3px;
}

.logs-item__actions {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-shrink: 0;
}

.btn-sm {
  padding: 6px;
  font-size: .75rem;
  font-weight: 600;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  transition: all .2s;
  font-family: inherit;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
  min-width: 32px;
}

.btn-sm svg {
  width: 16px;
  height: 16px;
}

.btn-sm--primary {
  background: #EFF6FF;
  color: #2563EB;
}

.btn-sm--primary:hover {
  background: #DBEAFE;
}

.btn-sm--secondary {
  background: #F1F5F9;
  color: #64748B;
}

.btn-sm--secondary:hover {
  background: #E2E8F0;
}

.empty-state {
  padding: 48px 24px;
  text-align: center;
  color: var(--teks-muted);
}

.empty-state svg {
  width: 48px;
  height: 48px;
  opacity: .4;
  margin-bottom: 12px;
}

.empty-state__text {
  font-size: .9rem;
  font-weight: 500;
}

.empty-state__sub {
  font-size: .78rem;
  margin-top: 4px;
}

.loading-spinner {
  display: inline-block;
  width: 18px;
  height: 18px;
  border: 2px solid rgba(255,255,255,.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spin .6s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes pulse {
  0%, 100% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.05); opacity: 0.8; }
}

.export-card {
  animation: slideUp .5s ease-out;
}

.logs-card {
  animation: slideUp .5s ease-out .15s both;
}

.mode-content.active {
  animation: fadeIn .4s ease-out;
}

.bulk-progress__item {
  animation: slideUp .4s ease-out;
}

.bulk-progress__item:nth-child(1) { animation-delay: 0s; }
.bulk-progress__item:nth-child(2) { animation-delay: 0.1s; }
.bulk-progress__item:nth-child(3) { animation-delay: 0.2s; }
.bulk-progress__item:nth-child(4) { animation-delay: 0.3s; }



@media (max-width: 1024px) {
  .export-grid {
    grid-template-columns: 1fr;
  }
  
  .radio-cards {
    grid-template-columns: repeat(3, 1fr);
  }
  
  .bulk-progress {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .page-header-modern {
    padding: 16px;
    flex-direction: column;
    align-items: flex-start;
  }

  .page-header-modern__text h1 {
    font-size: 1.2rem;
  }

  .page-header-modern__icon {
    width: 48px;
    height: 48px;
  }

  .page-header-modern__icon svg {
    width: 24px;
    height: 24px;
  }

  .ping-badge {
    width: 100%;
    justify-content: center;
  }

  .export-card__body {
    padding: 14px;
  }

  .radio-cards {
    grid-template-columns: 1fr;
    gap: 8px;
  }

  .radio-card__label {
    flex-direction: row;
    justify-content: flex-start;
    padding: 12px 14px;
    text-align: left;
    border-radius: 16px;
  }

  .select2-container--default .select2-selection--single {
    border-radius: 16px !important;
    padding: 12px 45px 12px 16px !important;
  }

  .export-card__body {
    padding: 14px;
  }
  
  .mode-content {
    padding-top: 12px;
    overflow: visible;
  }
  
  .logs-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }
  
  .logs-item__actions {
    width: 100%;
  }
  
  .logs-item__actions .btn-sm {
    flex: 1;
    justify-content: center;
  }
  
  .bulk-progress {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .export-grid {
    grid-template-columns: 1fr;
    gap: 16px;
  }
  
  .export-card,
  .logs-card {
    width: 100%;
    overflow: visible;
  }
  
  .form-section {
    position: relative;
    overflow: visible;
    z-index: 1;
  }
  
  .custom-dropdown {
    z-index: 1;
  }
}
</style>

<style>
/* Custom Dropdown Styles */
.custom-dropdown {
  position: relative;
  width: 100%;
  z-index: 101;
}

#dropdownKecamatan {
  z-index: 103;
}

#dropdownTahun {
  z-index: 102;
}

.custom-dropdown.is-active {
  z-index: 104 !important;
}

.export-card {
  position: relative;
}

.export-card__body {
  position: relative;
  z-index: 1;
}

.dropdown-overflow-wrapper {
  position: relative;
  z-index: 100;
}

@media (max-width: 768px) {
  body.dropdown-open {
    overflow: hidden;
    position: fixed;
    width: 100%;
    height: 100%;
  }
  
  .export-card.dropdown-active,
  .logs-card.dropdown-active {
    overflow: hidden;
    position: relative;
  }
  
  .export-card__body.dropdown-active,
  .logs-card__body.dropdown-active {
    overflow: hidden;
  }
  
  .custom-dropdown {
    z-index: 201;
  }
  
  .custom-dropdown.is-active {
    z-index: 202 !important;
  }
  
  .export-card {
    overflow: visible;
  }
  
  .export-card__body {
    overflow: visible;
  }
  
  .logs-card {
    overflow: visible;
  }
}





.custom-dropdown__trigger {
  width: 100%;
  padding: 8px 36px 8px 12px;
  border: 2px solid var(--border);
  border-radius: 50px;
  background: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 8px;
  transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
  min-height: 38px;
  position: relative;
  z-index: 107;
}

.custom-dropdown__trigger:hover {
  border-color: #CBD5E1;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.custom-dropdown__trigger:focus {
  outline: none;
  border-color: #6366F1;
  box-shadow: 0 4px 20px rgba(99, 102, 241, 0.2);
}

.custom-dropdown__trigger.is-open {
  border-color: #6366F1;
  border-bottom-left-radius: 16px;
  border-bottom-right-radius: 16px;
  box-shadow: 0 8px 30px rgba(99, 102, 241, 0.35), 0 4px 15px rgba(0, 0, 0, 0.12);
  border-bottom: none;
  background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(99, 102, 241, 0.02));
}

.custom-dropdown.is-open .custom-dropdown__trigger {
  box-shadow: 0 8px 30px rgba(99, 102, 241, 0.35), 0 4px 15px rgba(0, 0, 0, 0.12);
  border-color: #6366F1;
  background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(99, 102, 241, 0.02));
}

.custom-dropdown__text {
  font-size: .85rem;
  color: var(--teks-muted);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  flex: 1;
  text-align: left;
}

.custom-dropdown__text--selected {
  color: var(--teks);
  font-weight: 500;
}

.custom-dropdown__chevron {
  width: 18px;
  height: 18px;
  color: var(--teks-muted);
  flex-shrink: 0;
  transition: transform .2s ease-out;
}

.custom-dropdown__trigger.is-open .custom-dropdown__chevron {
  transform: rotate(180deg);
  color: #6366F1;
}

.custom-dropdown__panel {
  position: absolute;
  top: calc(100% - 2px);
  left: -2px;
  right: -2px;
  background: white;
  border: 2px solid var(--border);
  border-top: none;
  border-radius: 0 0 20px 20px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  z-index: 101;
  overflow: hidden;
  visibility: hidden;
  opacity: 0;
  transform: translateY(-8px);
  transition: visibility 0s .2s, opacity .2s ease-out, transform .2s ease-out, box-shadow .2s ease-out;
  display: block !important;
}

.custom-dropdown__panel.is-open {
  visibility: visible;
  opacity: 1;
  transform: translateY(0);
  transition: visibility 0s 0s, opacity .2s ease-out, transform .2s ease-out, box-shadow .2s ease-out;
  border-radius: 16px 16px 20px 20px;
  box-shadow: 0 8px 30px rgba(99, 102, 241, 0.25), 0 4px 15px rgba(0, 0, 0, 0.12);
}

.custom-dropdown__search {
  padding: 6px;
  border-bottom: 1px solid var(--border);
  position: relative;
}

.custom-dropdown__search-icon {
  position: absolute;
  left: 18px;
  top: 50%;
  transform: translateY(-50%);
  width: 14px;
  height: 14px;
  color: var(--teks-muted);
  pointer-events: none;
}

.custom-dropdown__search-input {
  width: 100%;
  padding: 8px 14px 8px 36px;
  border: 1px solid var(--border);
  border-radius: 50px;
  font-size: .82rem;
  font-family: inherit;
  background: #F9FAFB;
  color: var(--teks);
  transition: all .2s ease;
}

.custom-dropdown__search-input:focus {
  outline: none;
  border-color: #6366F1;
  background: white;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
}

.custom-dropdown__search-input::placeholder {
  color: var(--teks-muted);
}

.custom-dropdown__options {
  max-height: 180px;
  overflow-y: auto;
  padding: 4px;
}

.custom-dropdown__options::-webkit-scrollbar {
  width: 6px;
}

.custom-dropdown__options::-webkit-scrollbar-track {
  background: transparent;
}

.custom-dropdown__options::-webkit-scrollbar-thumb {
  background: #CBD5E1;
  border-radius: 3px;
}

.custom-dropdown__options::-webkit-scrollbar-thumb:hover {
  background: #94A3B8;
}

.custom-dropdown__option {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 8px 12px;
  border-radius: 8px;
  cursor: pointer;
  transition: all .2s ease;
  font-size: .85rem;
  color: var(--teks);
  margin: 2px 4px;
}

.custom-dropdown__option:hover {
  background: rgba(99, 102, 241, 0.08);
  transform: translateX(4px);
  box-shadow: 0 2px 8px rgba(99, 102, 241, 0.15);
}

.custom-dropdown__option.is-selected {
  background: rgba(99, 102, 241, 0.12);
  color: #6366F1;
  font-weight: 600;
  box-shadow: 0 2px 8px rgba(99, 102, 241, 0.2);
}

.custom-dropdown__option-check {
  width: 18px;
  height: 18px;
  color: #6366F1;
  opacity: 0;
  transition: opacity .2s ease, transform .2s ease;
}

.custom-dropdown__option.is-selected .custom-dropdown__option-check {
  opacity: 1;
  transform: scale(1.1);
}

.custom-dropdown__empty {
  padding: 24px 16px;
  text-align: center;
  color: var(--teks-muted);
  font-size: .85rem;
}

.custom-dropdown__empty svg {
  width: 32px;
  height: 32px;
  opacity: .4;
  margin-bottom: 8px;
}
</style>

<div class="export-page">

  <div class="page-header-modern">
    <div class="page-header-modern__left">
      <div class="page-header-modern__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
          <polyline points="7 10 12 15 17 10"/>
          <line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
      </div>
      <div class="page-header-modern__text">
        <h1>Export Data</h1>
        <p>Eksport data saldo dan transaksi SIDBM ke EnStorage</p>
      </div>
    </div>
    <div class="ping-badge">
      <span class="ping-badge__dot {{ $enstoragePing ? 'ping-badge__dot--ok' : '' }}"></span>
      EnStorage {{ $enstoragePing ? 'Terhubung' : 'Tidak Terhubung' }}
    </div>
  </div>

  <div class="export-grid">

    <div class="export-card">
      <div class="export-card__header">
        <div class="export-card__icon export-card__icon--blue">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="12" y1="18" x2="12" y2="12"/>
            <line x1="9" y1="15" x2="15" y2="15"/>
          </svg>
        </div>
        <div>
          <h2 class="export-card__title">Form Export</h2>
          <p class="export-card__subtitle">Pilih jenis data dan parameter export</p>
        </div>
      </div>
      <div class="export-card__body">

        <div class="form-section">
          <label class="form-section__label">Jenis Data</label>
          <div class="radio-cards">
            <label class="radio-card">
              <input type="radio" name="jenis" value="saldo" checked>
              <span class="radio-card__label">
                <span class="radio-card__icon radio-card__icon--blue">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                  </svg>
                </span>
                <span class="radio-card__text">Saldo</span>
              </span>
            </label>
            <label class="radio-card">
              <input type="radio" name="jenis" value="transaksi">
              <span class="radio-card__label">
                <span class="radio-card__icon radio-card__icon--green">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                  </svg>
                </span>
                <span class="radio-card__text">Transaksi</span>
              </span>
            </label>
            <label class="radio-card">
              <input type="radio" name="jenis" value="semua">
              <span class="radio-card__label">
                <span class="radio-card__icon radio-card__icon--purple">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                    <line x1="8" y1="21" x2="16" y2="21"/>
                    <line x1="12" y1="17" x2="12" y2="21"/>
                  </svg>
                </span>
                <span class="radio-card__text">Keduanya</span>
              </span>
            </label>
          </div>
        </div>

        <div class="form-section">
          <label class="form-section__label">Mode Export</label>
          <div class="mode-tabs" id="modeTabs">
            <button type="button" class="mode-tab active" data-mode="manual">Manual</button>
            <button type="button" class="mode-tab" data-mode="bulk">Bulk / Otomatis</button>
          </div>
        </div>

        <div id="manualContent" class="mode-content active">
          <div class="form-section">
            <label class="form-label">Kecamatan</label>
            <div class="custom-dropdown" id="dropdownKecamatan">
              <button type="button" class="custom-dropdown__trigger" aria-haspopup="listbox" aria-expanded="false">
                <span class="custom-dropdown__text">-- Pilih Kecamatan --</span>
                <svg class="custom-dropdown__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="6 9 12 15 18 9"/>
                </svg>
              </button>
              <div class="custom-dropdown__panel">
                <div class="custom-dropdown__search">
                  <svg class="custom-dropdown__search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                  </svg>
                  <input type="text" class="custom-dropdown__search-input" placeholder="Cari kecamatan...">
                </div>
                <div class="custom-dropdown__options" role="listbox">
                  @foreach ($kecamatanList as $kec)
                    <div class="custom-dropdown__option" data-value="{{ $kec->id }}" role="option">
                      <span>{{ $kec->id }} — {{ $kec->nama_kecamatan }}</span>
                      <svg class="custom-dropdown__option-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                      </svg>
                    </div>
                  @endforeach
                </div>
                <div class="custom-dropdown__empty" style="display:none;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                  </svg>
                  <div>Tidak ditemukan</div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-section">
            <label class="form-label">Tahun</label>
            <div class="custom-dropdown" id="dropdownTahun">
              <button type="button" class="custom-dropdown__trigger" aria-haspopup="listbox" aria-expanded="false">
                <span class="custom-dropdown__text">-- Pilih Tahun --</span>
                <svg class="custom-dropdown__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="6 9 12 15 18 9"/>
                </svg>
              </button>
              <div class="custom-dropdown__panel">
                <div class="custom-dropdown__search">
                  <svg class="custom-dropdown__search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                  </svg>
                  <input type="text" class="custom-dropdown__search-input" placeholder="Cari tahun...">
                </div>
                <div class="custom-dropdown__options" role="listbox">
                  @foreach ($tahunList as $t)
                    <div class="custom-dropdown__option" data-value="{{ $t }}" role="option">
                      <span>{{ $t }}</span>
                      <svg class="custom-dropdown__option-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                      </svg>
                    </div>
                  @endforeach
                </div>
                <div class="custom-dropdown__empty" style="display:none;">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                  </svg>
                  <div>Tidak ditemukan</div>
                </div>
              </div>
            </div>
            <p class="form-hint">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="16" x2="12" y2="12"/>
                <line x1="12" y1="8" x2="12.01" y2="8"/>
              </svg>
              Data sebelum tahun {{ $batasArsip }} tersedia untuk diarsip
            </p>
          </div>

          <button id="btnExport" class="btn-export" disabled>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
              <polyline points="7 10 12 15 17 10"/>
              <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            <span id="btnText">Jalankan Export</span>
            <span id="btnLoading" class="loading-spinner" style="display:none;"></span>
          </button>
        </div>

        <div id="bulkContent" class="mode-content">
          <p class="form-hint" style="margin-bottom: 8px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/>
              <polyline points="12 6 12 12 16 14"/>
            </svg>
            Akan mengeksport semua kecamatan &amp; semua tahun secara berurutan. Proses berjalan di background.
          </p>
          
          <div id="bulkProgressArea" style="display:none;">
            <div class="bulk-progress">
              <div class="bulk-progress__item">
                <div class="bulk-progress__num" id="bulkTotal">0</div>
                <div class="bulk-progress__label">Total</div>
              </div>
              <div class="bulk-progress__item">
                <div class="bulk-progress__num bulk-progress__num--success" id="bulkSelesai">0</div>
                <div class="bulk-progress__label">Selesai</div>
              </div>
              <div class="bulk-progress__item">
                <div class="bulk-progress__num bulk-progress__num--pending" id="bulkPending">0</div>
                <div class="bulk-progress__label">Pending</div>
              </div>
              <div class="bulk-progress__item">
                <div class="bulk-progress__num bulk-progress__num--failed" id="bulkGagal">0</div>
                <div class="bulk-progress__label">Gagal</div>
              </div>
            </div>
            <div class="progress-bar">
              <div class="progress-bar__fill" id="bulkProgressFill" style="width: 0%;"></div>
            </div>
          </div>

          <button id="btnBulkExport" class="btn-export btn-export--bulk">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
              <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
            </svg>
            <span id="bulkBtnText">Mulai Export Semua</span>
            <span id="bulkBtnLoading" class="loading-spinner" style="display:none;"></span>
          </button>
        </div>

      </div>
    </div>

    <div class="logs-card">
      <div class="logs-card__header">
        <span class="logs-card__title">Log Terbaru</span>
        <a href="{{ route('export.logs') }}" class="logs-card__link">Lihat semua →</a>
      </div>
      <div class="logs-card__body" id="latestLogsContainer">
        <div class="empty-state">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
          </svg>
          <div class="empty-state__text">Memuat...</div>
          <div class="empty-state__sub">Riwayat export akan muncul di sini</div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  initCustomDropdowns();
});

function initCustomDropdowns() {
  document.querySelectorAll('.custom-dropdown').forEach(function(dropdown) {
    const trigger = dropdown.querySelector('.custom-dropdown__trigger');
    const panel = dropdown.querySelector('.custom-dropdown__panel');
    const searchInput = dropdown.querySelector('.custom-dropdown__search-input');
    const optionsContainer = dropdown.querySelector('.custom-dropdown__options');
    const options = dropdown.querySelectorAll('.custom-dropdown__option');
    const emptyState = dropdown.querySelector('.custom-dropdown__empty');
    const textSpan = dropdown.querySelector('.custom-dropdown__text');

    let isOpen = false;
    let selectedValue = null;
    let selectedText = null;

    function openDropdown(e) {
      if (e) e.preventDefault();
      if (isOpen) return;
      
      isOpen = true;
      trigger.classList.add('is-open');
      dropdown.classList.add('is-active', 'is-open');
      trigger.setAttribute('aria-expanded', 'true');
      
      const rect = trigger.getBoundingClientRect();
      const panelWidth = panel.offsetWidth || rect.width;
      const isMobile = window.innerWidth <= 768;
      
      if (isMobile) {
        // For mobile, use absolute positioning within the form-section
        panel.style.position = 'absolute';
        panel.style.left = '-2px';
        panel.style.right = '-2px';
        panel.style.top = 'calc(100% + 2px)';
        panel.style.width = 'auto';
        panel.style.minWidth = 'auto';
        panel.style.maxHeight = '180px';
        panel.style.overflowY = 'auto';
        panel.style.zIndex = '999';
        
        // Allow the form-section to overflow
        const formSection = dropdown.closest('.form-section');
        if (formSection) {
          formSection.style.overflow = 'visible';
          formSection.style.zIndex = '998';
        }
        
        // Scroll dropdown into view if needed
        setTimeout(function() {
          panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 10);
      }

      panel.classList.add('is-open');
      
      setTimeout(function() {
        if (searchInput) searchInput.focus();
      }, 50);
    }

    function closeDropdown() {
      if (!isOpen) return;
      
      isOpen = false;
      trigger.classList.remove('is-open');
      dropdown.classList.remove('is-active', 'is-open');
      trigger.setAttribute('aria-expanded', 'false');
      panel.classList.remove('is-open');
      
      panel.style.position = '';
      panel.style.left = '';
      panel.style.right = '';
      panel.style.top = '';
      panel.style.width = '';
      panel.style.minWidth = '';
      panel.style.maxHeight = '';
      panel.style.overflowY = '';
      panel.style.zIndex = '';
      
      // Reset form-section overflow
      const formSection = dropdown.closest('.form-section');
      if (formSection) {
        formSection.style.overflow = '';
        formSection.style.zIndex = '';
      }

      if (searchInput) {
        searchInput.value = '';
        filterOptions('');
      }
    }

    function filterOptions(query) {
      let visibleCount = 0;
      const lowerQuery = query.toLowerCase();

      options.forEach(function(option) {
        const optionText = option.querySelector('span').textContent.toLowerCase();
        if (optionText.includes(lowerQuery)) {
          option.style.display = 'flex';
          visibleCount++;
        } else {
          option.style.display = 'none';
        }
      });

      if (visibleCount === 0) {
        emptyState.style.display = 'block';
        optionsContainer.style.display = 'none';
      } else {
        emptyState.style.display = 'none';
        optionsContainer.style.display = 'block';
      }
    }

    function selectOption(option) {
      const value = option.getAttribute('data-value');
      const text = option.querySelector('span').textContent;

      options.forEach(function(opt) {
        opt.classList.remove('is-selected');
      });
      option.classList.add('is-selected');

      selectedValue = value;
      selectedText = text;
      textSpan.textContent = text;
      textSpan.classList.add('custom-dropdown__text--selected');

      closeDropdown();
      trigger.setAttribute('data-selected', value);

      if (dropdown.id === 'dropdownKecamatan') {
        window.selectedKecamatan = value;
      } else if (dropdown.id === 'dropdownTahun') {
        window.selectedTahun = value;
      }
      checkForm();
    }

    trigger.addEventListener('mousedown', function(e) {
      e.preventDefault();
      if (isOpen) {
        closeDropdown();
      } else {
        document.querySelectorAll('.custom-dropdown__panel.is-open').forEach(function(openPanel) {
          openPanel.classList.remove('is-open');
          openPanel.closest('.custom-dropdown').querySelector('.custom-dropdown__trigger').classList.remove('is-open');
        });
        openDropdown();
      }
    });

    searchInput.addEventListener('input', function() {
      filterOptions(this.value);
    });

    searchInput.addEventListener('click', function(e) {
      e.stopPropagation();
    });

    options.forEach(function(option) {
      option.addEventListener('click', function() {
        selectOption(this);
      });
    });

    panel.addEventListener('click', function(e) {
      e.stopPropagation();
    });

    document.addEventListener('click', function(e) {
      if (!dropdown.contains(e.target)) {
        closeDropdown();
      }
    });

    trigger.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeDropdown();
      } else if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        if (isOpen) {
          closeDropdown();
        } else {
          openDropdown();
        }
      } else if (e.key === 'ArrowDown' && isOpen) {
        e.preventDefault();
        const visibleOptions = Array.from(options).filter(function(opt) {
          return opt.style.display !== 'none';
        });
        if (visibleOptions.length > 0) {
          const currentIndex = visibleOptions.findIndex(function(opt) {
            return opt.classList.contains('is-selected');
          });
          const nextIndex = currentIndex < visibleOptions.length - 1 ? currentIndex + 1 : 0;
          visibleOptions[nextIndex].focus();
        }
      }
    });
  });
}

window.selectedKecamatan = null;
window.selectedTahun = null;

const modeTabsContainer = document.getElementById('modeTabs');
const modeTabs = document.querySelectorAll('.mode-tab');
const manualContent = document.getElementById('manualContent');
const bulkContent = document.getElementById('bulkContent');

modeTabs.forEach(tab => {
  tab.addEventListener('click', () => {
    modeTabs.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    
    if (tab.dataset.mode === 'manual') {
      manualContent.classList.add('active');
      bulkContent.classList.remove('active');
      modeTabsContainer.classList.remove('bulk-active');
    } else {
      manualContent.classList.remove('active');
      bulkContent.classList.add('active');
      modeTabsContainer.classList.add('bulk-active');
    }
  });
});

function checkForm() {
  const kec = window.selectedKecamatan;
  const tahun = window.selectedTahun;
  document.getElementById('btnExport').disabled = !(kec && tahun) || isBusy();
}

function isBusy() {
  return !!manualAbortController || bulkRunning;
}

let manualAbortController = null;
let bulkRunning = false;
let currentBatchId = null;

const btnExport = document.getElementById('btnExport');
const btnText = document.getElementById('btnText');
const btnLoading = document.getElementById('btnLoading');

btnExport.addEventListener('click', async () => {
  if (isBusy()) return;

  const kecamatanId = window.selectedKecamatan;
  const tahun = window.selectedTahun;
  const jenis = document.querySelector('input[name="jenis"]:checked').value;

  let url = '';
  switch (jenis) {
    case 'saldo': url = '/api/export/saldo'; break;
    case 'transaksi': url = '/api/export/transaksi'; break;
    case 'semua': url = '/api/export/semua'; break;
  }

  manualAbortController = new AbortController();
  setManualLoading(true);

  try {
    const response = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      },
      body: JSON.stringify({ kecamatan_id: kecamatanId, tahun: tahun }),
      signal: manualAbortController.signal,
    });

    const data = await response.json();
    loadLatestLogs();

  } catch (err) {
    if (err.name !== 'AbortError') {
      console.error('Export error:', err);
    }
  } finally {
    manualAbortController = null;
    setManualLoading(false);
  }
});

function setManualLoading(v) {
  btnExport.disabled = v || !(window.selectedKecamatan && window.selectedTahun);
  btnText.style.display = v ? 'none' : 'inline';
  btnLoading.style.display = v ? 'inline-block' : 'none';
}

const btnBulkExport = document.getElementById('btnBulkExport');
const bulkBtnText = document.getElementById('bulkBtnText');
const bulkBtnLoading = document.getElementById('bulkBtnLoading');
const bulkProgressArea = document.getElementById('bulkProgressArea');

btnBulkExport.addEventListener('click', startBulkExport);

async function startBulkExport() {
  if (isBusy()) return;

  bulkRunning = true;
  const jenis = document.querySelector('input[name="jenis"]:checked').value;
  bulkProgressArea.style.display = 'block';

  setBulkLoading(true);

  try {
    const response = await fetch('/api/export/run-all', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      },
      body: JSON.stringify({ jenis }),
    });

    const data = await response.json();

    if (!data.success) {
      bulkRunning = false;
      setBulkLoading(false);
      return;
    }
    currentBatchId = data.batch_id;
    pollBatchAndLogs();

  } catch (err) {
    addLog('error', 'Gagal: ' + err.message);
    bulkRunning = false;
    setBulkLoading(false);
  }
}

async function pollBatchAndLogs() {
  const timer = setInterval(async () => {
    try {
      const response = await fetch(`/api/batch/${currentBatchId}`);
      const data = await response.json();

      document.getElementById('bulkTotal').textContent = data.total;
      document.getElementById('bulkSelesai').textContent = data.finished;
      document.getElementById('bulkPending').textContent = data.pending;
      document.getElementById('bulkGagal').textContent = data.failed;
      
      const percent = data.total > 0 ? Math.round((data.finished / data.total) * 100) : 0;
      document.getElementById('bulkProgressFill').style.width = percent + '%';

      loadLatestLogs();

      const totalDone = (data.processed || 0) + (data.failed || 0);
      if (data.finished >= data.total) {
        clearInterval(timer);
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
  bulkBtnText.style.display = v ? 'none' : 'inline';
  bulkBtnLoading.style.display = v ? 'inline-block' : 'none';
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

    if (!response.ok) return;

    const data = await response.json();
    if (!data.success || !data.logs) return;

    const latestLogs = data.logs.slice(0, 8);

    if (latestLogs.length === 0) {
      document.getElementById('latestLogsContainer').innerHTML = `
        <div class="empty-state">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
          </svg>
          <div class="empty-state__text">Belum ada export</div>
          <div class="empty-state__sub">Mulai export data pertama Anda</div>
        </div>
      `;
      return;
    }

    let html = '<div class="logs-list">';
    latestLogs.forEach(log => {
      const statusClass = log.status === 'success' ? 'success' : log.status === 'failed' ? 'failed' : 'pending';
      const statusIcon = {
        success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
        failed: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
        pending: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>'
      };
      
      const parts = (log.filename || '').split('_');
      const type = parts[0] || '';
      const tahun = (parts[1] || '').replace('.json', '');
      const openBtn = log.status === 'success' && log.filename
        ? `<button class="btn-sm btn-sm--primary" onclick="window.open('/api/export/files?kecamatan=${log.kecamatan_id}&type=${type}&tahun=${tahun}', '_blank')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
           </button>`
        : '';
      const downloadBtn = log.status === 'success' && log.filename
        ? `<button class="btn-sm btn-sm--secondary" onclick="downloadLog('${log.kecamatan_id}', '${log.filename}')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
           </button>`
        : '';

      html += `
        <div class="logs-item">
          <div class="logs-item__left">
            <div class="logs-item__icon logs-item__icon--${statusClass}">
              ${statusIcon[statusClass]}
            </div>
            <div class="logs-item__info">
              <div class="logs-item__title">Kec. ${log.kecamatan_id} — ${ucfirst(log.jenis)} ${log.tahun}${log.bulan ? ' / ' + log.bulan : ''}</div>
              <div class="logs-item__meta">
                <span>${log.filename || '-'}</span>
                ${log.file_size ? `<span>• ${formatBytes(log.file_size)}</span>` : ''}
                ${log.record_count ? `<span>• ${log.record_count.toLocaleString()} records</span>` : ''}
                <span>• ${formatTimeAgo(log.created_at)}</span>
              </div>
            </div>
          </div>
          <div class="logs-item__actions">
            ${openBtn}
            ${downloadBtn}
          </div>
        </div>
      `;
    });
    html += '</div>';

    document.getElementById('latestLogsContainer').innerHTML = html;

  } catch (error) {
    console.error('Load logs gagal:', error);
  }
}

function ucfirst(str) {
  return str.charAt(0).toUpperCase() + str.slice(1);
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

@endsection
