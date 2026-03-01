<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TESRead') — Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
/* ============================================================
   TESRead — Main Stylesheet
   Design: Clean card-based dashboard, DepEd colors
   Primary: #003A8C | Secondary: #C8102E | BG: #F4F6F9
   ============================================================ */

@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:root {
    --primary:       #003A8C;
    --primary-dark:  #002870;
    --primary-mid:   #1a52b3;
    --red:           #C8102E;
    --red-dark:      #a00d25;
    --success:       #28A745;
    --warning:       #FFC107;
    --danger:        #C8102E;
    --bg:            #F4F6F9;
    --card:          #FFFFFF;
    --border:        #e8edf5;
    --text:          #1a2340;
    --muted:         #7a8299;
    --sidebar-w:     248px;
    --header-h:      68px;
    --gradient:      linear-gradient(135deg, #003A8C 0%, #1a3a6b 45%, #C8102E 100%);
    --gradient-h:    linear-gradient(90deg, #003A8C 0%, #1a3a6b 60%, #C8102E 100%);
    --radius:        14px;
    --radius-sm:     8px;
    --shadow:        0 2px 16px rgba(0,40,140,0.09);
    --shadow-lg:     0 8px 32px rgba(0,40,140,0.14);
}

/* ── Reset & Base ── */
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
html, body { height: 100%; }
body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--bg);
    color: var(--text);
    font-size: 14px;
    line-height: 1.6;
}
a { text-decoration: none; color: inherit; }
img { max-width: 100%; }

/* ============================================================
   LAYOUT SHELL
   ============================================================ */
.app-shell {
    display: flex;
    min-height: 100vh;
}

/* ── Sidebar ── */
.sidebar {
    width: var(--sidebar-w);
    background: var(--gradient);
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0; left: 0; bottom: 0;
    z-index: 100;
    overflow-y: auto;
    transition: transform 0.3s ease;
}

.sidebar-brand {
    padding: 22px 20px 18px;
    border-bottom: 1px solid rgba(255,255,255,0.12);
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}

.brand-icon {
    width: 38px; height: 38px;
    background: rgba(255,255,255,0.15);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.brand-text {
    font-size: 18px;
    font-weight: 800;
    color: #fff;
    letter-spacing: -0.3px;
    line-height: 1;
}

.brand-sub {
    font-size: 10px;
    color: rgba(255,255,255,0.65);
    font-weight: 500;
    margin-top: 2px;
}

/* Nav */
.sidebar-nav {
    padding: 14px 12px;
    flex: 1;
}

.nav-section-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    color: rgba(255,255,255,0.45);
    padding: 10px 10px 6px;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: var(--radius-sm);
    color: rgba(255,255,255,0.80);
    font-weight: 500;
    font-size: 13.5px;
    transition: all 0.2s;
    margin-bottom: 2px;
    position: relative;
}

.nav-item:hover {
    background: rgba(255,255,255,0.12);
    color: #fff;
    transform: translateX(2px);
}

.nav-item.active {
    background: rgba(255,255,255,0.18);
    color: #fff;
    font-weight: 600;
}

.nav-item.active::before {
    content: '';
    position: absolute;
    left: 0; top: 50%;
    transform: translateY(-50%);
    width: 3px; height: 22px;
    background: #fff;
    border-radius: 0 3px 3px 0;
}

.nav-item svg, .nav-item i {
    width: 18px;
    font-size: 16px;
    flex-shrink: 0;
    opacity: 0.9;
}

.nav-badge {
    margin-left: auto;
    background: var(--red);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 20px;
    min-width: 20px;
    text-align: center;
}

/* Sidebar bottom (user info) */
.sidebar-footer {
    padding: 14px 12px;
    border-top: 1px solid rgba(255,255,255,0.12);
    flex-shrink: 0;
}

.sidebar-user {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: background 0.2s;
}
.sidebar-user:hover { background: rgba(255,255,255,0.10); }

.sidebar-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255,255,255,0.35);
    flex-shrink: 0;
}

.sidebar-user-name {
    font-size: 13px;
    font-weight: 600;
    color: #fff;
    line-height: 1.2;
}

.sidebar-user-role {
    font-size: 11px;
    color: rgba(255,255,255,0.60);
}

/* ── Main Area ── */
.main-area {
    margin-left: var(--sidebar-w);
    flex: 1;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* ── Top Header ── */
.top-header {
    position: sticky;
    top: 0;
    height: var(--header-h);
    background: var(--gradient-h);
    display: flex;
    align-items: center;
    padding: 0 28px;
    z-index: 90;
    box-shadow: 0 2px 20px rgba(0,40,140,0.18);
    gap: 16px;
}

.header-page-title {
    font-size: 17px;
    font-weight: 700;
    color: #fff;
    flex: 1;
    display: flex;
    align-items: center;
    gap: 10px;
}

.header-page-title .page-icon {
    width: 32px; height: 32px;
    background: rgba(255,255,255,0.15);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px;
}

.header-date {
    color: rgba(255,255,255,0.75);
    font-size: 12px;
    font-weight: 500;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.header-user-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.20);
    border-radius: 50px;
    padding: 6px 14px 6px 6px;
    cursor: pointer;
    transition: background 0.2s;
    color: #fff;
}
.header-user-btn:hover { background: rgba(255,255,255,0.20); }

.header-avatar {
    width: 32px; height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255,255,255,0.4);
}

.header-user-name {
    font-size: 13px;
    font-weight: 600;
    color: #fff;
}

/* ── Page Content ── */
.page-content {
    padding: 28px;
    flex: 1;
}

/* ── Page Header ── */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
}

.page-header h1 {
    font-size: 22px;
    font-weight: 800;
    color: var(--text);
    letter-spacing: -0.3px;
}

.page-header .page-subtitle {
    font-size: 13px;
    color: var(--muted);
    margin-top: 2px;
}

/* ============================================================
   CARDS
   ============================================================ */
.card {
    background: var(--card);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
}

.card-header {
    padding: 18px 22px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.card-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-body { padding: 22px; }

/* ── Stat Cards ── */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 24px;
}

.stat-card {
    background: var(--card);
    border-radius: var(--radius);
    padding: 22px 20px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    position: relative;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
}

.stat-card::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
}

.stat-card.blue::after  { background: linear-gradient(90deg, var(--primary), var(--primary-mid)); }
.stat-card.red::after   { background: linear-gradient(90deg, var(--red), #ff4757); }
.stat-card.green::after { background: linear-gradient(90deg, var(--success), #2ed573); }
.stat-card.yellow::after { background: linear-gradient(90deg, var(--warning), #ffd32a); }

.stat-icon {
    width: 46px; height: 46px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
    margin-bottom: 14px;
}

.stat-icon.blue   { background: rgba(0,58,140,0.10);  color: var(--primary); }
.stat-icon.red    { background: rgba(200,16,46,0.10);  color: var(--red); }
.stat-icon.green  { background: rgba(40,167,69,0.10);  color: var(--success); }
.stat-icon.yellow { background: rgba(255,193,7,0.15);  color: #b8860b; }

.stat-number {
    font-size: 32px;
    font-weight: 800;
    color: var(--text);
    line-height: 1;
    margin-bottom: 4px;
    letter-spacing: -1px;
}

.stat-label {
    font-size: 12.5px;
    color: var(--muted);
    font-weight: 500;
}

/* ── Welcome banner ── */
.welcome-banner {
    background: var(--gradient);
    border-radius: var(--radius);
    padding: 28px 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    overflow: hidden;
    position: relative;
}

.welcome-banner::before {
    content: '';
    position: absolute;
    right: -40px; top: -60px;
    width: 260px; height: 260px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
}

.welcome-banner::after {
    content: '';
    position: absolute;
    right: 80px; bottom: -80px;
    width: 200px; height: 200px;
    background: rgba(255,255,255,0.04);
    border-radius: 50%;
}

.welcome-text h2 {
    font-size: 22px;
    font-weight: 800;
    color: #fff;
    margin-bottom: 6px;
}

.welcome-text p {
    color: rgba(255,255,255,0.80);
    font-size: 13.5px;
}

.welcome-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 50px;
    padding: 8px 18px;
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    margin-top: 14px;
    width: fit-content;
}

/* ============================================================
   TABLES
   ============================================================ */
.table-wrap {
    overflow-x: auto;
    border-radius: var(--radius);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
}

.data-table thead th {
    background: #f8faff;
    font-weight: 700;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--muted);
    padding: 14px 18px;
    text-align: left;
    border-bottom: 1.5px solid var(--border);
    white-space: nowrap;
}

.data-table tbody td {
    padding: 14px 18px;
    border-bottom: 1px solid #f0f3fa;
    vertical-align: middle;
    color: var(--text);
}

.data-table tbody tr:last-child td { border-bottom: none; }

.data-table tbody tr:hover td {
    background: #f5f8ff;
}

/* ── User row ── */
.user-row {
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-avatar-sm {
    width: 36px; height: 36px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 2px solid var(--border);
}

.user-name { font-weight: 600; font-size: 13.5px; color: var(--text); }
.user-email { font-size: 12px; color: var(--muted); }

/* ============================================================
   BADGES
   ============================================================ */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 11px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 600;
    white-space: nowrap;
}

.badge-primary  { background: rgba(0,58,140,0.10);  color: var(--primary); }
.badge-success  { background: rgba(40,167,69,0.12);  color: #1a7a38; }
.badge-warning  { background: rgba(255,193,7,0.18);  color: #8a6200; }
.badge-danger   { background: rgba(200,16,46,0.10);  color: var(--red); }
.badge-secondary { background: #eee; color: #666; }
.badge-info     { background: rgba(23,162,184,0.12); color: #0c7b93; }

/* Status badges */
.badge-approved { background: rgba(40,167,69,0.12);  color: #1a7a38; }
.badge-pending  { background: rgba(255,193,7,0.18);  color: #8a6200; }
.badge-rejected { background: rgba(200,16,46,0.10);  color: var(--red); }

/* ============================================================
   BUTTONS
   ============================================================ */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 20px;
    border-radius: var(--radius-sm);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.2s;
    text-decoration: none;
    white-space: nowrap;
}

.btn-primary {
    background: var(--gradient);
    color: #fff;
    box-shadow: 0 4px 14px rgba(0,58,140,0.25);
}
.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(0,58,140,0.35);
    color: #fff;
}

.btn-danger {
    background: linear-gradient(135deg, var(--red), var(--red-dark));
    color: #fff;
    box-shadow: 0 4px 14px rgba(200,16,46,0.25);
}
.btn-danger:hover {
    transform: translateY(-1px);
    color: #fff;
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #1e8035);
    color: #fff;
    box-shadow: 0 4px 14px rgba(40,167,69,0.25);
}
.btn-success:hover { transform: translateY(-1px); color: #fff; }

.btn-outline {
    background: transparent;
    border: 1.5px solid var(--border);
    color: var(--muted);
}
.btn-outline:hover { border-color: var(--primary); color: var(--primary); background: rgba(0,58,140,0.04); }

.btn-sm { padding: 6px 13px; font-size: 12px; border-radius: 6px; }
.btn-xs { padding: 4px 10px; font-size: 11px; border-radius: 5px; }

.btn-icon {
    width: 34px; height: 34px;
    padding: 0;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: var(--radius-sm);
    font-size: 15px;
    background: transparent;
    border: 1.5px solid var(--border);
    color: var(--muted);
    transition: all 0.2s;
    cursor: pointer;
}
.btn-icon:hover { border-color: var(--primary); color: var(--primary); background: rgba(0,58,140,0.05); }
.btn-icon.danger:hover { border-color: var(--red); color: var(--red); background: rgba(200,16,46,0.05); }
.btn-icon.success:hover { border-color: var(--success); color: var(--success); background: rgba(40,167,69,0.05); }

/* ============================================================
   FORMS
   ============================================================ */
.form-group { margin-bottom: 20px; }

.form-label {
    display: block;
    font-weight: 600;
    font-size: 13px;
    color: var(--text);
    margin-bottom: 7px;
}

.form-label .required { color: var(--red); margin-left: 2px; }

.form-control {
    width: 100%;
    padding: 10px 16px;
    border: 1.5px solid #dde3f0;
    border-radius: var(--radius-sm);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 14px;
    color: var(--text);
    background: #fff;
    transition: border-color 0.2s, box-shadow 0.2s;
    outline: none;
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(0,58,140,0.08);
}

.form-control.is-invalid {
    border-color: var(--danger);
}

.form-control::placeholder { color: #b0b9d0; }

select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23003A8C' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 38px;
}

textarea.form-control { resize: vertical; min-height: 90px; }

.form-hint {
    font-size: 11.5px;
    color: var(--muted);
    margin-top: 5px;
}

.invalid-feedback {
    font-size: 12px;
    color: var(--danger);
    margin-top: 5px;
    display: block;
}

/* ── Photo upload ── */
.photo-upload-area {
    border: 2px dashed #d0d9ee;
    border-radius: var(--radius);
    padding: 28px;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
    background: #f8faff;
}

.photo-upload-area:hover {
    border-color: var(--primary);
    background: rgba(0,58,140,0.03);
}

.photo-preview {
    width: 90px; height: 90px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--border);
    margin-bottom: 12px;
}

/* ============================================================
   ALERTS & FLASH MESSAGES
   ============================================================ */
.alert {
    padding: 13px 18px;
    border-radius: var(--radius-sm);
    font-size: 13.5px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

.alert-success { background: #e8f8ea; color: #1a7a38; border-left: 4px solid var(--success); }
.alert-danger  { background: #ffeaea; color: var(--red-dark); border-left: 4px solid var(--danger); }
.alert-warning { background: #fff8e1; color: #7a5a00; border-left: 4px solid var(--warning); }
.alert-info    { background: #e8f0ff; color: var(--primary); border-left: 4px solid var(--primary); }

/* ============================================================
   SEARCH BAR
   ============================================================ */
.search-bar {
    display: flex;
    align-items: center;
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 0 14px;
    gap: 8px;
    transition: border-color 0.2s;
}

.search-bar:focus-within { border-color: var(--primary); }

.search-bar input {
    border: none; outline: none;
    padding: 9px 0;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13.5px;
    color: var(--text);
    background: transparent;
    width: 220px;
}

.search-bar i { color: var(--muted); font-size: 14px; }

/* ============================================================
   PAGINATION
   ============================================================ */
.pagination {
    display: flex;
    gap: 4px;
    align-items: center;
    padding: 16px 18px;
    border-top: 1px solid var(--border);
}

.page-item .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px; height: 34px;
    border-radius: var(--radius-sm);
    font-size: 13px;
    font-weight: 600;
    color: var(--muted);
    border: 1px solid var(--border);
    background: #fff;
    transition: all 0.2s;
}

.page-item.active .page-link {
    background: var(--gradient);
    color: #fff;
    border-color: transparent;
}

.page-item .page-link:hover {
    border-color: var(--primary);
    color: var(--primary);
}

/* ============================================================
   PROFILE PHOTO CIRCLE
   ============================================================ */
.avatar {
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.avatar-lg { width: 80px; height: 80px; border: 3px solid var(--border); }
.avatar-md { width: 46px; height: 46px; border: 2px solid var(--border); }
.avatar-sm { width: 36px; height: 36px; border: 2px solid var(--border); }
.avatar-xs { width: 28px; height: 28px; }

/* ============================================================
   RISK LEVEL DISPLAY
   ============================================================ */
.risk-below     { color: var(--danger);  font-weight: 700; }
.risk-approaching { color: #8a6200;      font-weight: 700; }
.risk-meeting   { color: var(--success); font-weight: 700; }

.risk-bar { height: 6px; border-radius: 3px; background: #eee; overflow: hidden; }
.risk-bar-fill { height: 100%; border-radius: 3px; transition: width 0.6s; }

/* ============================================================
   EMPTY STATE
   ============================================================ */
.empty-state {
    text-align: center;
    padding: 52px 24px;
    color: var(--muted);
}

.empty-state-icon {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.4;
}

.empty-state h3 {
    font-size: 16px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 8px;
}

.empty-state p { font-size: 13.5px; color: var(--muted); max-width: 320px; margin: 0 auto; }

/* ============================================================
   SCORE DISPLAY
   ============================================================ */
.score-circle {
    width: 56px; height: 56px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800;
    font-size: 15px;
    flex-shrink: 0;
}

.score-high   { background: rgba(40,167,69,0.12);  color: #1a7a38; }
.score-mid    { background: rgba(255,193,7,0.18);  color: #8a6200; }
.score-low    { background: rgba(200,16,46,0.10);  color: var(--red); }

/* ============================================================
   GRID HELPERS
   ============================================================ */
.grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
.grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
.grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }

/* ============================================================
   UTILITIES
   ============================================================ */
.d-flex { display: flex; }
.align-center { align-items: center; }
.justify-between { justify-content: space-between; }
.gap-8  { gap: 8px; }
.gap-12 { gap: 12px; }
.gap-16 { gap: 16px; }
.mt-4   { margin-top: 4px; }
.mt-8   { margin-top: 8px; }
.mt-16  { margin-top: 16px; }
.mt-24  { margin-top: 24px; }
.mb-0   { margin-bottom: 0; }
.mb-16  { margin-bottom: 16px; }
.mb-24  { margin-bottom: 24px; }
.text-muted   { color: var(--muted); }
.text-small   { font-size: 12px; }
.text-center  { text-align: center; }
.font-bold    { font-weight: 700; }
.font-semibold{ font-weight: 600; }

/* ============================================================
   DROPDOWN
   ============================================================ */
.dropdown { position: relative; }
.dropdown-menu {
    position: absolute;
    right: 0; top: calc(100% + 8px);
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-lg);
    min-width: 180px;
    z-index: 200;
    overflow: hidden;
    display: none;
}
.dropdown-menu.show { display: block; }
.dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    font-size: 13.5px;
    color: var(--text);
    transition: background 0.15s;
    cursor: pointer;
}
.dropdown-item:hover { background: #f5f8ff; }
.dropdown-item.danger { color: var(--red); }
.dropdown-item.danger:hover { background: #ffeaea; }
.dropdown-divider { height: 1px; background: var(--border); margin: 4px 0; }

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 1100px) {
    .stat-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
    .sidebar { transform: translateX(-100%); }
    .sidebar.open { transform: translateX(0); }
    .main-area { margin-left: 0; }
    .page-content { padding: 16px; }
    .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
    .stat-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .welcome-banner { flex-direction: column; }
}

    </style>
    @stack('styles')
</head>
<body>

<div class="app-shell">

    {{-- ═══ SIDEBAR ═══ --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">📚</div>
            <div>
                <div class="brand-text">TESRead</div>
                <div class="brand-sub">Admin Panel</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Main</div>

            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </a>

            <div class="nav-section-label" style="margin-top:10px;">Management</div>

            <a href="{{ route('admin.teachers.index') }}"
               class="nav-item {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Teacher Accounts</span>
                @php $pendingCount = \App\Models\User::where('role','teacher')->where('account_status','Pending')->count(); @endphp
                @if($pendingCount > 0)
                    <span class="nav-badge">{{ $pendingCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.students.index') }}"
               class="nav-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate"></i>
                <span>Students</span>
            </a>

            <a href="{{ route('admin.sections.index') }}"
               class="nav-item {{ request()->routeIs('admin.sections.*') ? 'active' : '' }}">
                <i class="fas fa-door-open"></i>
                <span>Assign Classes</span>
            </a>

            <a href="{{ route('admin.reading-levels.index') }}"
               class="nav-item {{ request()->routeIs('admin.reading-levels.*') ? 'active' : '' }}">
                <i class="fas fa-layer-group"></i>
                <span>Reading Levels</span>
            </a>

            <div class="nav-section-label" style="margin-top:10px;">Analytics</div>

            <a href="{{ route('admin.analytics.index') }}"
               class="nav-item {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Analytics & Reports</span>
            </a>

            <div class="nav-section-label" style="margin-top:10px;">System</div>

            <a href="{{ route('admin.activity-logs.index') }}"
               class="nav-item {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                <i class="fas fa-history"></i>
                <span>Activity Logs</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="dropdown">
                <div class="sidebar-user" onclick="toggleDropdown('userMenu')">
                    <img src="{{ auth()->user()->profilePhotoUrl() }}"
                         alt="{{ auth()->user()->name }}"
                         class="sidebar-avatar">
                    <div>
                        <div class="sidebar-user-name">{{ Str::limit(auth()->user()->name, 20) }}</div>
                        <div class="sidebar-user-role">Administrator</div>
                    </div>
                    <i class="fas fa-chevron-up" style="color:rgba(255,255,255,0.5);font-size:10px;margin-left:auto;"></i>
                </div>
                <div class="dropdown-menu" id="userMenu" style="bottom:100%;top:auto;left:0;right:auto;min-width:200px;">
                    <div class="dropdown-item" style="pointer-events:none;opacity:0.6;font-size:12px;">
                        {{ auth()->user()->email }}
                    </div>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item danger" style="width:100%;border:none;background:none;text-align:left;cursor:pointer;">
                            <i class="fas fa-sign-out-alt"></i> Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    {{-- ═══ MAIN AREA ═══ --}}
    <div class="main-area">

        {{-- Top Header --}}
        <header class="top-header">
            <div class="header-page-title">
                <div class="page-icon">@yield('page-icon', '🏠')</div>
                @yield('page-heading', 'Dashboard')
            </div>

            <div class="header-date">
                <i class="fas fa-calendar-alt" style="margin-right:5px;opacity:0.75;"></i>
                {{ now()->format('l, F j, Y') }}
            </div>

            <div class="header-actions">
                <div class="dropdown">
                    <button class="header-user-btn" onclick="toggleDropdown('headerUserMenu')">
                        <img src="{{ auth()->user()->profilePhotoUrl() }}"
                             alt="{{ auth()->user()->name }}"
                             class="header-avatar">
                        <span class="header-user-name">{{ Str::limit(auth()->user()->name, 18) }}</span>
                        <i class="fas fa-chevron-down" style="font-size:10px;opacity:0.7;"></i>
                    </button>
                    <div class="dropdown-menu" id="headerUserMenu">
                        <div class="dropdown-item" style="pointer-events:none;opacity:0.6;font-size:12px;">
                            <i class="fas fa-shield-alt"></i> Administrator
                        </div>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item danger" style="width:100%;border:none;background:none;text-align:left;cursor:pointer;">
                                <i class="fas fa-sign-out-alt"></i> Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="page-content">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</div>

{{-- Overlay for mobile sidebar --}}
<div id="sidebarOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:99;"
     onclick="closeSidebar()"></div>

<script>
    function toggleDropdown(id) {
        const menu = document.getElementById(id);
        menu.classList.toggle('show');
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(m => m.classList.remove('show'));
        }
    });

    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').style.display = 'none';
    }
</script>

@stack('scripts')
</body>
</html>
