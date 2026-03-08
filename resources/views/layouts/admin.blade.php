<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TESRead') — Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" type="image/jpg" href="{{ asset('favicon.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
:root {
    --primary:       #003A8C;
    --primary-dark:  #002870;
    --primary-mid:   #1a52b3;
    --primary-pale:  #EEF3FF;
    --red:           #C8102E;
    --red-dark:      #a00d25;
    --red-pale:      #FFF0F2;
    --success:       #16a34a;
    --warning:       #d97706;
    --danger:        #C8102E;
    --bg:            #F0F4FA;
    --card:          #FFFFFF;
    --border:        #e4eaf5;
    --border-light:  #f0f4fc;
    --text:          #111827;
    --text-2:        #374151;
    --muted:         #6b7280;
    --muted-light:   #9ca3af;
    --sidebar-w:     256px;
    --header-h:      60px;
    --radius:        12px;
    --radius-sm:     8px;
    --radius-xs:     6px;
    --shadow:        0 1px 4px rgba(0,30,100,0.07), 0 4px 16px rgba(0,30,100,0.05);
    --shadow-md:     0 4px 12px rgba(0,30,100,0.10), 0 12px 32px rgba(0,30,100,0.07);
    --shadow-lg:     0 8px 32px rgba(0,30,100,0.14);
}
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
html, body { height: 100%; }
body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); font-size: 14px; line-height: 1.6; }
a { text-decoration: none; color: inherit; }
img { max-width: 100%; }

/* LAYOUT */
.app-shell { display: flex; min-height: 100vh; }

/* SIDEBAR */
.sidebar {
    width: var(--sidebar-w);
    background: #001f5c;
    background-image:
        radial-gradient(ellipse at 0% 0%, rgba(0,58,140,0.6) 0%, transparent 60%),
        radial-gradient(ellipse at 100% 100%, rgba(200,16,46,0.15) 0%, transparent 55%);
    display: flex; flex-direction: column;
    position: fixed; top: 0; left: 0; bottom: 0;
    z-index: 100; overflow-y: auto; overflow-x: hidden;
    transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
}
.sidebar::after {
    content: ''; position: absolute; top: 0; right: 0; bottom: 0; width: 1px;
    background: linear-gradient(180deg, transparent, rgba(255,255,255,0.08) 30%, rgba(255,255,255,0.08) 70%, transparent);
    pointer-events: none;
}
.sidebar-brand {
    padding: 20px 18px 16px; display: flex; align-items: center; gap: 11px; flex-shrink: 0; position: relative;
}
.sidebar-brand::after {
    content: ''; position: absolute; bottom: 0; left: 18px; right: 18px; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
}
.brand-icon {
    width: 36px; height: 36px;
    border-radius: 10px; flex-shrink: 0;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.brand-icon img { width: 100%; height: 100%; object-fit: cover; display: block; }
.brand-text { font-family: 'Sora', sans-serif; font-size: 17px; font-weight: 800; color: #fff; letter-spacing: -0.4px; line-height: 1.1; }
.brand-sub  { font-size: 9.5px; color: rgba(255,255,255,0.45); font-weight: 500; letter-spacing: 0.5px; text-transform: uppercase; margin-top: 1px; }

.sidebar-nav { padding: 12px 10px; flex: 1; }
.nav-section-label {
    font-size: 9.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.4px;
    color: rgba(255,255,255,0.28); padding: 12px 10px 5px; font-family: 'Sora', sans-serif;
}
.nav-item {
    display: flex; align-items: center; gap: 9px; padding: 9px 11px; border-radius: var(--radius-sm);
    color: rgba(255,255,255,0.65); font-weight: 500; font-size: 13px;
    transition: all 0.18s ease; margin-bottom: 1px; position: relative; cursor: pointer;
}
.nav-item:hover { background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.95); }
.nav-item.active { background: rgba(255,255,255,0.12); color: #fff; font-weight: 600; }
.nav-item.active::before {
    content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%);
    width: 3px; height: 20px; background: linear-gradient(180deg, #fff, rgba(255,255,255,0.6));
    border-radius: 0 3px 3px 0;
}
.nav-item i { width: 17px; font-size: 14px; flex-shrink: 0; opacity: 0.85; text-align: center; }
.nav-item.active i { opacity: 1; }
.nav-badge {
    margin-left: auto; background: var(--red); color: #fff; font-size: 9.5px; font-weight: 700;
    padding: 2px 6px; border-radius: 99px; min-width: 18px; text-align: center;
    box-shadow: 0 2px 6px rgba(200,16,46,0.4);
}

/* SIDEBAR FOOTER - single sign out */
.sidebar-footer { padding: 12px 10px 14px; flex-shrink: 0; position: relative; }
.sidebar-footer::before {
    content: ''; position: absolute; top: 0; left: 18px; right: 18px; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.10), transparent);
}
.sidebar-user-card {
    display: flex; align-items: center; gap: 9px; padding: 9px 10px; border-radius: var(--radius-sm); margin-bottom: 8px;
}
.sidebar-avatar {
    width: 34px; height: 34px; border-radius: 50%; object-fit: cover;
    border: 2px solid rgba(255,255,255,0.25); flex-shrink: 0;
}
.sidebar-user-name { font-size: 12.5px; font-weight: 700; color: #fff; line-height: 1.2; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.sidebar-user-role { font-size: 10.5px; color: rgba(255,255,255,0.45); font-weight: 500; text-transform: uppercase; letter-spacing: 0.4px; }
.btn-signout {
    display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%;
    padding: 9px 14px; background: rgba(200,16,46,0.18); border: 1px solid rgba(200,16,46,0.30);
    border-radius: var(--radius-sm); color: rgba(255,180,180,0.9); font-size: 12.5px; font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif; cursor: pointer; transition: all 0.18s; text-align: center;
}
.btn-signout:hover { background: rgba(200,16,46,0.30); border-color: rgba(200,16,46,0.50); color: #fff; }
.btn-signout i { font-size: 12px; }

/* MAIN AREA */
.main-area { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

/* HEADER */
.top-header {
    position: sticky; top: 0; height: var(--header-h);
    background: rgba(255,255,255,0.88); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
    border-bottom: 1px solid var(--border); display: flex; align-items: center; padding: 0 24px;
    z-index: 90; gap: 14px; box-shadow: 0 1px 0 var(--border), 0 4px 24px rgba(0,30,100,0.05);
}
.header-page-title {
    font-size: 15px; font-weight: 700; color: var(--text); flex: 1;
    display: flex; align-items: center; gap: 9px; font-family: 'Sora', sans-serif; letter-spacing: -0.2px;
}
.page-icon {
    width: 30px; height: 30px; background: var(--primary-pale); border-radius: 8px;
    display: flex; align-items: center; justify-content: center; font-size: 14px;
    border: 1px solid rgba(0,58,140,0.10); flex-shrink: 0;
}
.header-date {
    color: var(--muted); font-size: 12px; font-weight: 500; display: flex; align-items: center; gap: 5px;
    background: var(--primary-pale); border: 1px solid rgba(0,58,140,0.10); border-radius: 6px; padding: 5px 11px;
}
.header-date i { color: var(--primary); font-size: 11px; opacity: 0.7; }
.header-actions { display: flex; align-items: center; gap: 8px; }
.header-user-pill {
    display: flex; align-items: center; gap: 8px; background: var(--primary-pale);
    border: 1px solid rgba(0,58,140,0.12); border-radius: 50px; padding: 5px 13px 5px 5px; cursor: default;
}
.header-avatar { width: 28px; height: 28px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(0,58,140,0.2); flex-shrink: 0; }
.header-user-name { font-size: 12.5px; font-weight: 700; color: var(--primary); line-height: 1.2; display: block; }
.header-user-role { font-size: 10px; color: var(--muted); font-weight: 500; display: block; }

/* PAGE CONTENT */
.page-content { padding: 24px 28px; flex: 1; }
.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; }
.page-header h1 { font-size: 20px; font-weight: 800; color: var(--text); letter-spacing: -0.4px; font-family: 'Sora', sans-serif; }
.page-header .page-subtitle { font-size: 12.5px; color: var(--muted); margin-top: 3px; font-weight: 500; }

/* CARDS */
.card { background: var(--card); border-radius: var(--radius); box-shadow: var(--shadow); border: 1px solid var(--border); }
.card-header {
    padding: 16px 20px; border-bottom: 1px solid var(--border-light);
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
}
.card-title { font-size: 14px; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 8px; font-family: 'Sora', sans-serif; }
.card-body { padding: 20px; }

/* STAT CARDS */
.stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 22px; }
.stat-card {
    background: var(--card); border-radius: var(--radius); padding: 20px 18px 18px;
    box-shadow: var(--shadow); border: 1px solid var(--border); position: relative; overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
.stat-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: var(--radius) var(--radius) 0 0;
}
.stat-card.blue::before  { background: linear-gradient(90deg, var(--primary), var(--primary-mid)); }
.stat-card.red::before   { background: linear-gradient(90deg, var(--red), #e8324a); }
.stat-card.green::before { background: linear-gradient(90deg, #16a34a, #22c55e); }
.stat-card.yellow::before{ background: linear-gradient(90deg, #d97706, #f59e0b); }
/* Keep ::after for bg circle deco */
.stat-card::after { content: ''; position: absolute; bottom: -14px; right: -10px; width: 70px; height: 70px; border-radius: 50%; opacity: 0.05; }
.stat-card.blue::after   { background: var(--primary); }
.stat-card.red::after    { background: var(--red); }
.stat-card.green::after  { background: #16a34a; }
.stat-card.yellow::after { background: #d97706; }
.stat-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; margin-bottom: 14px; }
.stat-icon.blue   { background: rgba(0,58,140,0.08);  color: var(--primary); }
.stat-icon.red    { background: rgba(200,16,46,0.08);  color: var(--red); }
.stat-icon.green  { background: rgba(22,163,74,0.10);  color: #16a34a; }
.stat-icon.yellow { background: rgba(217,119,6,0.10);  color: #d97706; }
.stat-number { font-size: 30px; font-weight: 800; color: var(--text); line-height: 1; margin-bottom: 4px; letter-spacing: -1.5px; font-family: 'Sora', sans-serif; }
.stat-label  { font-size: 12px; color: var(--muted); font-weight: 500; }

/* WELCOME BANNER */
.welcome-banner {
    background: linear-gradient(135deg, #002870 0%, #003A8C 40%, #1a3a6b 70%, #C8102E 100%);
    border-radius: var(--radius); padding: 26px 30px; display: flex; align-items: center;
    justify-content: space-between; margin-bottom: 22px; overflow: hidden; position: relative;
}
.welcome-banner::before { content: ''; position: absolute; right: -50px; top: -70px; width: 220px; height: 220px; background: rgba(255,255,255,0.05); border-radius: 50%; }
.welcome-banner::after  { content: ''; position: absolute; right: 60px; bottom: -70px; width: 160px; height: 160px; background: rgba(255,255,255,0.03); border-radius: 50%; }
.welcome-text h2 { font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 5px; font-family: 'Sora', sans-serif; letter-spacing: -0.4px; }
.welcome-text p  { color: rgba(255,255,255,0.75); font-size: 13px; }
.welcome-badge {
    display: flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.14);
    border: 1px solid rgba(255,255,255,0.22); border-radius: 50px; padding: 7px 16px;
    color: #fff; font-size: 12.5px; font-weight: 600; margin-top: 12px; width: fit-content; backdrop-filter: blur(4px);
}

/* TABLES */
.table-wrap { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.data-table thead th {
    background: #f7f9ff; font-weight: 700; font-size: 10.5px; text-transform: uppercase;
    letter-spacing: 0.9px; color: var(--muted); padding: 12px 16px; text-align: left;
    border-bottom: 1.5px solid var(--border); white-space: nowrap; font-family: 'Sora', sans-serif;
}
.data-table tbody td { padding: 13px 16px; border-bottom: 1px solid var(--border-light); vertical-align: middle; color: var(--text-2); }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr { transition: background 0.12s; }
.data-table tbody tr:hover td { background: #f7f9ff; }
.user-row { display: flex; align-items: center; gap: 10px; }
.user-avatar-sm { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 2px solid var(--border); }
.user-name  { font-weight: 600; font-size: 13px; color: var(--text); }
.user-email { font-size: 11.5px; color: var(--muted); }

/* BADGES */
.badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 700; white-space: nowrap; letter-spacing: 0.1px; }
.badge-primary   { background: rgba(0,58,140,0.09);  color: var(--primary); }
.badge-success   { background: rgba(22,163,74,0.10);  color: #15803d; }
.badge-warning   { background: rgba(217,119,6,0.12);  color: #b45309; }
.badge-danger    { background: rgba(200,16,46,0.09);  color: var(--red); }
.badge-secondary { background: #f3f4f6; color: #6b7280; }
.badge-info      { background: rgba(8,145,178,0.10);  color: #0e7490; }
.badge-approved  { background: rgba(22,163,74,0.10);  color: #15803d; }
.badge-pending   { background: rgba(217,119,6,0.12);  color: #b45309; }
.badge-rejected  { background: rgba(200,16,46,0.09);  color: var(--red); }

/* BUTTONS */
.btn { display: inline-flex; align-items: center; gap: 7px; padding: 8px 18px; border-radius: var(--radius-sm); font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; font-weight: 700; cursor: pointer; border: none; transition: all 0.18s; text-decoration: none; white-space: nowrap; letter-spacing: 0.1px; }
.btn-primary { background: linear-gradient(135deg, var(--primary), var(--primary-mid)); color: #fff; box-shadow: 0 3px 12px rgba(0,58,140,0.22); }
.btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,58,140,0.32); color: #fff; }
.btn-danger  { background: linear-gradient(135deg, var(--red), var(--red-dark)); color: #fff; box-shadow: 0 3px 12px rgba(200,16,46,0.22); }
.btn-danger:hover  { transform: translateY(-1px); color: #fff; }
.btn-success { background: linear-gradient(135deg, #16a34a, #15803d); color: #fff; box-shadow: 0 3px 12px rgba(22,163,74,0.22); }
.btn-success:hover { transform: translateY(-1px); color: #fff; }
.btn-outline { background: #fff; border: 1.5px solid var(--border); color: var(--muted); }
.btn-outline:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-pale); }
.btn-sm { padding: 6px 12px; font-size: 12px; border-radius: var(--radius-xs); }
.btn-xs { padding: 4px 9px; font-size: 11px; border-radius: 5px; }
.btn-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-xs); font-size: 13px; background: #fff; border: 1.5px solid var(--border); color: var(--muted); transition: all 0.18s; cursor: pointer; }
.btn-icon:hover        { border-color: var(--primary); color: var(--primary); background: var(--primary-pale); }
.btn-icon.danger:hover { border-color: var(--red); color: var(--red); background: var(--red-pale); }
.btn-icon.success:hover{ border-color: var(--success); color: var(--success); background: rgba(22,163,74,0.06); }

/* FORMS */
.form-group { margin-bottom: 18px; }
.form-label { display: block; font-weight: 700; font-size: 12.5px; color: var(--text-2); margin-bottom: 6px; letter-spacing: 0.1px; }
.form-label .required { color: var(--red); margin-left: 2px; }
.form-control { width: 100%; padding: 9px 14px; border: 1.5px solid #dde3f0; border-radius: var(--radius-sm); font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13.5px; color: var(--text); background: #fff; transition: border-color 0.18s, box-shadow 0.18s; outline: none; }
.form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3.5px rgba(0,58,140,0.09); }
.form-control.is-invalid { border-color: var(--danger); }
.form-control::placeholder { color: #b8c2d8; }
select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23003A8C' d='M6 9L1 4h10z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 13px center; padding-right: 36px; }
textarea.form-control { resize: vertical; min-height: 88px; }
.form-hint { font-size: 11px; color: var(--muted); margin-top: 4px; }
.invalid-feedback { font-size: 11.5px; color: var(--danger); margin-top: 4px; display: block; }
.photo-upload-area { border: 2px dashed #cdd5e8; border-radius: var(--radius); padding: 26px; text-align: center; cursor: pointer; transition: border-color 0.18s, background 0.18s; background: #f8faff; }
.photo-upload-area:hover { border-color: var(--primary); background: rgba(0,58,140,0.02); }
.photo-preview { width: 88px; height: 88px; border-radius: 50%; object-fit: cover; border: 3px solid var(--border); margin-bottom: 10px; }

/* ALERTS */
.alert { padding: 11px 16px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 500; display: flex; align-items: flex-start; gap: 10px; margin-bottom: 18px; line-height: 1.5; }
.alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.alert-danger  { background: #fff5f5; color: #be1235; border: 1px solid #fecdd3; }
.alert-warning { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
.alert-info    { background: var(--primary-pale); color: var(--primary); border: 1px solid rgba(0,58,140,0.15); }

/* SEARCH BAR */
.search-bar { display: flex; align-items: center; background: #fff; border: 1.5px solid var(--border); border-radius: var(--radius-sm); padding: 0 12px; gap: 7px; transition: border-color 0.18s, box-shadow 0.18s; }
.search-bar:focus-within { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(0,58,140,0.07); }
.search-bar input { border: none; outline: none; padding: 8px 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; color: var(--text); background: transparent; width: 210px; }
.search-bar input::placeholder { color: var(--muted-light); }
.search-bar i { color: var(--muted-light); font-size: 13px; }

/* PAGINATION */
.pagination { display: flex; gap: 3px; align-items: center; padding: 14px 16px; border-top: 1px solid var(--border-light); }
.page-item .page-link { display: flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 8px; border-radius: var(--radius-xs); font-size: 12.5px; font-weight: 600; color: var(--muted); border: 1px solid var(--border); background: #fff; transition: all 0.15s; }
.page-item.active .page-link { background: linear-gradient(135deg, var(--primary), var(--primary-mid)); color: #fff; border-color: transparent; box-shadow: 0 2px 8px rgba(0,58,140,0.25); }
.page-item .page-link:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-pale); }

/* AVATARS */
.avatar { border-radius: 50%; object-fit: cover; flex-shrink: 0; }
.avatar-lg { width: 78px; height: 78px; border: 3px solid var(--border); }
.avatar-md { width: 44px; height: 44px; border: 2px solid var(--border); }
.avatar-sm { width: 34px; height: 34px; border: 2px solid var(--border); }
.avatar-xs { width: 26px; height: 26px; }

/* RISK */
.risk-below     { color: var(--danger); font-weight: 700; }
.risk-approaching { color: #b45309; font-weight: 700; }
.risk-meeting   { color: var(--success); font-weight: 700; }
.risk-bar { height: 5px; border-radius: 3px; background: #e9ecf5; overflow: hidden; }
.risk-bar-fill { height: 100%; border-radius: 3px; transition: width 0.6s; }

/* EMPTY STATE */
.empty-state { text-align: center; padding: 48px 24px; }
.empty-state-icon { font-size: 44px; margin-bottom: 14px; opacity: 0.35; }
.empty-state h3 { font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 7px; font-family: 'Sora', sans-serif; }
.empty-state p  { font-size: 13px; color: var(--muted); max-width: 300px; margin: 0 auto; }

/* SCORES */
.score-circle { width: 52px; height: 52px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; flex-shrink: 0; font-family: 'Sora', sans-serif; }
.score-high  { background: rgba(22,163,74,0.10); color: #15803d; }
.score-mid   { background: rgba(217,119,6,0.12);  color: #b45309; }
.score-low   { background: rgba(200,16,46,0.09);  color: var(--red); }

/* GRIDS */
.grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; }
.grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
.grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }

/* UTILITIES */
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
.text-muted    { color: var(--muted); }
.text-small    { font-size: 12px; }
.text-center   { text-align: center; }
.font-bold     { font-weight: 700; }
.font-semibold { font-weight: 600; }

/* DROPDOWN */
.dropdown { position: relative; }
.dropdown-menu { position: absolute; right: 0; top: calc(100% + 6px); background: #fff; border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow-lg); min-width: 180px; z-index: 200; overflow: hidden; display: none; }
.dropdown-menu.show { display: block; }
.dropdown-item { display: flex; align-items: center; gap: 9px; padding: 9px 15px; font-size: 13px; color: var(--text); transition: background 0.12s; cursor: pointer; }
.dropdown-item:hover { background: #f5f8ff; }
.dropdown-item.danger { color: var(--red); }
.dropdown-item.danger:hover { background: var(--red-pale); }
.dropdown-divider { height: 1px; background: var(--border-light); margin: 3px 0; }

/* RESPONSIVE */
@media (max-width: 1100px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 768px) {
    .sidebar { transform: translateX(-100%); }
    .sidebar.open { transform: translateX(0); }
    .main-area { margin-left: 0; }
    .page-content { padding: 16px; }
    .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
    .stat-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .welcome-banner { flex-direction: column; }
    .header-date { display: none; }
}
    </style>
    @stack('styles')
</head>
<body>
<div class="app-shell">

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><img src="{{ asset('images/TES-logo.jpg') }}" alt="TESRead Logo"></div>
            <div>
                <div class="brand-text">TESRead</div>
                <div class="brand-sub">Admin Panel</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Main</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i><span>Dashboard</span>
            </a>

            <div class="nav-section-label" style="margin-top:8px;">Management</div>
            <a href="{{ route('admin.teachers.index') }}" class="nav-item {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                <i class="fas fa-chalkboard-teacher"></i><span>Teacher Accounts</span>
                @php $pendingCount = \App\Models\User::where('role','teacher')->where('account_status','Pending')->count(); @endphp
                @if($pendingCount > 0)<span class="nav-badge">{{ $pendingCount }}</span>@endif
            </a>
            <a href="{{ route('admin.students.index') }}" class="nav-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate"></i><span>Students</span>
            </a>
            <a href="{{ route('admin.classes.index') }}" class="nav-item {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
                <i class="fas fa-chalkboard"></i><span>Classes</span>
            </a>

            <div class="nav-section-label" style="margin-top:8px;">Analytics</div>
            <a href="{{ route('admin.analytics.index') }}" class="nav-item {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i><span>Analytics & Reports</span>
            </a>

            <div class="nav-section-label" style="margin-top:8px;">System</div>
            <a href="{{ route('admin.activity-logs.index') }}" class="nav-item {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                <i class="fas fa-history"></i><span>Activity Logs</span>
            </a>
        </nav>

        {{-- Single sign-out location --}}
        <div class="sidebar-footer">
            <div class="sidebar-user-card">
                <img src="{{ auth()->user()->profilePhotoUrl() }}" alt="{{ auth()->user()->name }}" class="sidebar-avatar">
                <div style="min-width:0;">
                    <div class="sidebar-user-name">{{ Str::limit(auth()->user()->name, 20) }}</div>
                    <div class="sidebar-user-role">Administrator</div>
                </div>
            </div>
            <button type="button" class="btn-signout" onclick="openSignOutModal()">
                <i class="fas fa-sign-out-alt"></i> Sign Out
            </button>
        </div>
    </aside>

    {{-- MAIN AREA --}}
    <div class="main-area">
        <header class="top-header">
            <button class="btn-icon" id="sidebarToggle" style="display:none;flex-shrink:0;" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="header-page-title">
                <div class="page-icon">@yield('page-icon', '🏠')</div>
                @yield('page-heading', 'Dashboard')
            </div>
            <div class="header-date">
                <i class="fas fa-calendar-alt"></i>
                {{ now()->format('l, F j, Y') }}
            </div>
            <div class="header-actions">
                <div class="header-user-pill">
                    <img src="{{ auth()->user()->profilePhotoUrl() }}" alt="{{ auth()->user()->name }}" class="header-avatar">
                    <div class="header-user-info">
                        <span class="header-user-name">{{ Str::limit(auth()->user()->name, 18) }}</span>
                        <span class="header-user-role">Administrator</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="page-content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle" style="margin-top:1px;flex-shrink:0;"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle" style="margin-top:1px;flex-shrink:0;"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle" style="margin-top:1px;flex-shrink:0;"></i>
                    <div>@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

{{-- Sign Out Confirmation Modal --}}
<div id="signOutModal" style="display:none;position:fixed;inset:0;z-index:999;align-items:center;justify-content:center;">
    <div onclick="closeSignOutModal()" style="position:absolute;inset:0;background:rgba(0,10,40,0.55);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);"></div>
    <div style="position:relative;background:#fff;border-radius:16px;padding:32px 28px 24px;width:100%;max-width:380px;margin:16px;box-shadow:0 24px 64px rgba(0,20,80,0.30);text-align:center;animation:modalIn .22s cubic-bezier(0.34,1.56,0.64,1);">
        <div style="width:56px;height:56px;border-radius:50%;background:#fff0f2;border:2px solid #fecdd3;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;font-size:22px;">
            <i class="fas fa-sign-out-alt" style="color:#C8102E;"></i>
        </div>
        <h3 style="font-family:'Sora',sans-serif;font-size:17px;font-weight:800;color:#111827;margin-bottom:8px;letter-spacing:-0.3px;">Sign out of TESRead?</h3>
        <p style="font-size:13px;color:#6b7280;line-height:1.6;margin-bottom:24px;">You are signed in as <strong style="color:#374151;">{{ auth()->user()->name }}</strong>.<br>You'll need to sign in again to access your dashboard.</p>
        <div style="display:flex;gap:10px;">
            <button onclick="closeSignOutModal()" style="flex:1;padding:11px;background:#f3f4f6;border:1.5px solid #e5e7eb;border-radius:9px;font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:700;color:#374151;cursor:pointer;transition:all .18s;">
                Cancel
            </button>
            <form method="POST" action="{{ route('logout') }}" style="flex:1;">
                @csrf
                <button type="submit" style="width:100%;padding:11px;background:linear-gradient(135deg,#C8102E,#a00d25);border:none;border-radius:9px;font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:700;color:#fff;cursor:pointer;box-shadow:0 4px 14px rgba(200,16,46,0.30);transition:all .18s;">
                    <i class="fas fa-sign-out-alt" style="margin-right:6px;"></i>Yes, Sign Out
                </button>
            </form>
        </div>
    </div>
</div>

<div id="sidebarOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:99;backdrop-filter:blur(2px);" onclick="closeSidebar()"></div>

<script>
    function toggleDropdown(id) { document.getElementById(id).classList.toggle('show'); }
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) document.querySelectorAll('.dropdown-menu.show').forEach(m => m.classList.remove('show'));
    });
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').style.display = document.getElementById('sidebar').classList.contains('open') ? 'block' : 'none';
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').style.display = 'none';
    }
    function openSignOutModal() {
        const modal = document.getElementById('signOutModal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeSignOutModal() {
        const modal = document.getElementById('signOutModal');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeSignOutModal();
    });
    if (window.innerWidth <= 768) document.getElementById('sidebarToggle').style.display = 'flex';
    window.addEventListener('resize', () => {
        document.getElementById('sidebarToggle').style.display = window.innerWidth <= 768 ? 'flex' : 'none';
        if (window.innerWidth > 768) closeSidebar();
    });
</script>
<style>
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.88) translateY(12px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
</style>
@stack('scripts')
</body>
</html>
