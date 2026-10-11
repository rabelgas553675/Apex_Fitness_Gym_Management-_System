<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
  <meta name="csrf-token" content="{{ csrf_token() }}"/>

  {{-- Apply the saved theme before first paint (same key/logic as staff/instructor layouts & resources/js/app.js). Default: dark. --}}
  <script>
    (function () {
      try {
        var r = document.documentElement;
        var light = localStorage.getItem('apex-color-theme') === 'light';
        r.classList.toggle('dark', !light);
        r.dataset.theme = light ? 'light' : 'dark';
      } catch (e) {}
    })();
  </script>

  <title>@yield('title', 'APEX FITNESS GYM')</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    /* Design tokens + navbar are kept IDENTICAL to layouts/instructor and layouts/staff — keep them in sync. */
    :root {
      --bg:       #131417;
      --bg-top:   #1f2024;
      --surface:  #1a1b1f;
      --surface2: #212227;
      --surface3: #2a2b31;
      --border:   rgba(255,255,255,0.07);
      --accent:   #e0a93b;
      --accent-2: #f3c866;
      --accent-dark: #b8862a;
      --accent-soft: rgba(224,169,59,0.12);
      --text:     #ffffff;
      --muted:    #a3a5ad;
      --text-soft: #d2d3d8;
      --success:  #4ade80;
      --danger:   #f87171;
      --warning:  #fbbf24;
      --info:     #60a5fa;
      --radius:   14px;

      --navbar-h: 60px;
      --nav-px:   36px;
      /* Safe-area insets are 0 on normal screens, so they only matter on notched phones */
      --safe-top:    env(safe-area-inset-top, 0px);
      --safe-right:  env(safe-area-inset-right, 0px);
      --safe-bottom: env(safe-area-inset-bottom, 0px);
      --safe-left:   env(safe-area-inset-left, 0px);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; -webkit-text-size-adjust: 100%; text-size-adjust: 100%; }

    body {
      background: linear-gradient(180deg, var(--bg-top) 0%, var(--bg) 380px) fixed, var(--bg);
      color: var(--text);
      font-family: 'DM Sans', sans-serif;
      font-size: 15px;
      min-height: 100vh;
      min-height: 100dvh;
      overflow-x: hidden;
      -webkit-tap-highlight-color: transparent;
    }
    body.menu-open { overflow: hidden; }
    img, svg, video, canvas { max-width: 100%; }
    img, video { height: auto; }

    /* ── NAVBAR (same as instructor) ──
       backdrop-filter lives on ::before (NOT on .navbar): a backdrop-filter makes the
       element the containing block for position:fixed children, which would break the
       mobile drawer (it would be sized/positioned against the 60px bar, not the screen). */
    .navbar {
      background: rgba(20,21,24,0.92);
      border-bottom: 1px solid rgba(255,255,255,0.07);
      padding: var(--safe-top) max(var(--nav-px), var(--safe-right)) 0 max(var(--nav-px), var(--safe-left));
      height: calc(var(--navbar-h) + var(--safe-top));
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 200;
      gap: 12px;
    }
    .navbar::before {
      content: '';
      position: absolute;
      inset: 0;
      z-index: -1;
      pointer-events: none;
      -webkit-backdrop-filter: blur(10px);
      backdrop-filter: blur(10px);
    }
    .navbar-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; flex-shrink: 1; min-width: 0; }
    .brand-icon {
      width: 34px; height: 34px;
      background: linear-gradient(145deg, #4a4b52, #2c2d33);
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .brand-icon svg { width: 18px; height: 18px; fill: none; stroke: #e6e6e8; stroke-width: 2.5; }
    .brand-name {
      font-family: 'DM Sans', sans-serif;
      font-weight: 700;
      font-size: 15px;
      color: #e4e5e8;
      letter-spacing: 2.5px;
      line-height: 1;
      white-space: nowrap;
      min-width: 0;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .navbar-nav { display: flex; align-items: center; gap: 2px; }
    .nav-item {
      display: flex;
      align-items: center;
      gap: 7px;
      padding: 8px 14px;
      border-radius: 8px;
      color: #c6c8ce;
      font-size: 13px;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.15s;
      white-space: nowrap;
      position: relative;
    }
    .nav-item:hover { color: #fff; background: rgba(255,255,255,0.05); }
    .nav-item.active { color: #fff; background: rgba(255,255,255,0.05); font-weight: 600; }
    .nav-item.active::after {
      content: '';
      position: absolute;
      bottom: -11px;
      left: 14px; right: 14px;
      height: 2px;
      background: var(--accent-2);
      border-radius: 2px;
    }
    .nav-item svg { width: 15px; height: 15px; stroke: currentColor; fill: none; flex-shrink: 0; }
    .nav-item.active svg { stroke: var(--accent-2); }

    .navbar-right { display: flex; align-items: center; gap: 14px; flex-shrink: 0; }
    .role-chip {
      font-size: 10px;
      font-weight: 800;
      padding: 5px 14px;
      border-radius: 6px;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      white-space: nowrap;
    }
    .role-member {
      background: linear-gradient(135deg, var(--accent-2), var(--accent-dark));
      color: #1a1a1a;
      box-shadow: 0 0 14px rgba(224,169,59,0.25);
    }
    .user-chip { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 500; color: #e4e5e8; white-space: nowrap; }
    .user-avatar {
      width: 30px; height: 30px;
      border-radius: 50%;
      background: var(--accent-soft);
      border: 1px solid rgba(224,169,59,0.3);
      display: flex; align-items: center; justify-content: center;
      font-weight: 700;
      font-size: 12px;
      color: var(--accent-2);
      overflow: hidden;
      flex-shrink: 0;
      transition: border-color 0.15s, box-shadow 0.15s;
    }
    .user-avatar img { width: 100%; height: 100%; object-fit: cover; }

    /* Profile shortcut: the avatar + name replace the old "Profile" menu item */
    .user-chip--link { padding: 2px 12px 2px 4px; border-radius: 8px; text-decoration: none; transition: all 0.15s; }
    .user-chip--link:hover { background: rgba(255,255,255,0.05); color: #fff; }
    .user-chip--link:hover .user-avatar { border-color: var(--accent-2); }
    .user-chip--link:focus-visible { outline: 2px solid var(--accent-2); outline-offset: 2px; }
    .user-chip--link.active { background: rgba(255,255,255,0.05); color: #fff; }
    .user-chip--link.active .user-avatar { border-color: var(--accent-2); box-shadow: 0 0 0 2px var(--accent-soft); }

    .btn-logout-top {
      display: flex;
      align-items: center;
      gap: 6px;
      padding: 7px 14px;
      border-radius: 8px;
      background: rgba(248,113,113,0.04);
      border: 1px solid rgba(248,113,113,0.45);
      color: #f87171;
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
      font-family: 'DM Sans', sans-serif;
      transition: all 0.15s;
      white-space: nowrap;
    }
    .btn-logout-top:hover { background: rgba(248,113,113,0.12); border-color: #f87171; }
    .btn-logout-top svg { width: 14px; height: 14px; stroke: currentColor; fill: none; }
    .navbar-logout-form { margin: 0; }

    /* Brand header inside the mobile drawer (hidden on desktop) */
    .nav-brand-head { display: none; }

    /* ── MOBILE NAV TOGGLE ── */
    .nav-toggle {
      display: none;
      background: transparent;
      border: 1px solid rgba(255,255,255,0.15);
      border-radius: 8px;
      width: 38px; height: 38px;
      align-items: center; justify-content: center;
      cursor: pointer;
      flex-shrink: 0;
      color: #fff;
      touch-action: manipulation;
    }
    /* Invisible extra hit area: same look, easier to tap (~44px) */
    .nav-toggle::after { content: ''; position: absolute; inset: -3px; }
    .nav-toggle svg { width: 20px; height: 20px; stroke: currentColor; fill: none; stroke-width: 2; }
    .nav-toggle .icon-close { display: none; }
    .nav-toggle.open .icon-menu { display: none; }
    .nav-toggle.open .icon-close { display: block; }
    .nav-scrim { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 150; }
    .nav-scrim.show { display: block; }

    /* ── PAGE TITLE BAR ── */
    .page-titlebar {
      background: rgba(0,0,0,0.25);
      border-bottom: 1px solid var(--border);
      padding: 16px max(36px, var(--safe-right)) 16px max(36px, var(--safe-left));
    }
    .page-titlebar h1 {
      font-size: 15px;
      font-weight: 700;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: var(--text);
      overflow-wrap: anywhere;
    }

    /* ── PAGE CONTENT ── */
    .page-wrap {
      max-width: 1200px;
      margin: 0 auto;
      padding: 36px max(36px, var(--safe-right)) calc(36px + var(--safe-bottom)) max(36px, var(--safe-left));
      min-width: 0;
    }
    .page-wrap > * { min-width: 0; }
    .table-responsive, .table-wrap { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .page-wrap pre { max-width: 100%; overflow-x: auto; }

    /* ── ALERTS ── */
    .alert {
      padding: 12px 16px;
      border-radius: var(--radius);
      font-size: 13px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      overflow-wrap: anywhere;
    }
    .alert-success { background: rgba(74,222,128,0.08); border: 1px solid rgba(74,222,128,0.2); color: var(--success); }
    .alert-danger  { background: rgba(248,113,113,0.08); border: 1px solid rgba(248,113,113,0.2); color: var(--danger); }

    /* ── BUTTONS ── */
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      padding: 9px 18px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      font-family: 'DM Sans', sans-serif;
      cursor: pointer;
      border: none;
      text-decoration: none;
      transition: all 0.15s;
      touch-action: manipulation;
    }
    .btn-primary   { background: linear-gradient(135deg, var(--accent-2), var(--accent-dark)); color: #1a1a1a; }
    .btn-primary:hover  { filter: brightness(1.08); transform: translateY(-1px); }
    .btn-secondary { background: #0e0e10; color: var(--text); border: 1px solid var(--border); }
    .btn-secondary:hover { border-color: rgba(224,169,59,0.45); color: var(--accent); }
    .btn-sm { padding: 6px 14px; font-size: 12px; }

    /* ── FORMS — 16px on inputs prevents iOS Safari auto-zoom on focus ── */
    .form-label {
      display: block;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--muted);
      margin-bottom: 7px;
    }
    .form-control {
      width: 100%;
      padding: 10px 14px;
      background: var(--surface2);
      border: 1px solid var(--border);
      border-radius: 8px;
      color: var(--text);
      font-family: 'DM Sans', sans-serif;
      font-size: 16px;
      outline: none;
      transition: border-color 0.15s;
    }
    .form-control:focus { border-color: var(--accent); }
    .form-control option { background: var(--surface2); }

    /* ═══════════════════════════════════════════════
       RESPONSIVE (same breakpoints as instructor)
       ═══════════════════════════════════════════════ */
    @media (max-width: 1024px) {
      :root { --nav-px: 20px; }
      .nav-item { padding: 8px 10px; }
      .user-chip span.user-name-text { display: none; }
      .user-chip--link { padding: 2px; }
      .page-titlebar { padding: 14px max(20px, var(--safe-right)) 14px max(20px, var(--safe-left)); }
      .page-wrap { padding: 28px max(20px, var(--safe-right)) calc(28px + var(--safe-bottom)) max(20px, var(--safe-left)); }
    }

    @media (max-width: 768px) {
      :root { --nav-px: 14px; }
      .navbar { gap: 8px; }
      .navbar-brand { flex: 1 1 auto; gap: 8px; }
      .brand-name { font-size: 13px; letter-spacing: 2px; }
      .brand-icon { width: 28px; height: 28px; }

      /* Toggle sits ABOVE the drawer so it can always close it */
      .nav-toggle { display: flex; order: 3; position: relative; z-index: 160; }

      /* Drawer: full-height panel on the right, hidden (and unfocusable) until opened */
      .navbar-nav {
        position: fixed;
        top: 0; right: 0; bottom: 0;
        height: 100vh;
        height: 100dvh;
        width: min(80vw, 300px);
        background: #131417;
        border-left: 1px solid rgba(255,255,255,0.08);
        box-shadow: -12px 0 32px rgba(0,0,0,0.45);
        flex-direction: column;
        align-items: stretch;
        gap: 0;
        padding: var(--safe-top) 0 var(--safe-bottom);
        transform: translateX(100%);
        visibility: hidden;
        transition: transform 0.25s ease, visibility 0s 0.25s;
        z-index: 150;
        overflow-y: auto;
        overscroll-behavior: contain;
        -webkit-overflow-scrolling: touch;
      }
      .navbar-nav.open { transform: translateX(0); visibility: visible; transition: transform 0.25s ease, visibility 0s; }

      /* Logo + name at the top of the drawer; right padding leaves room for the close button */
      .nav-brand-head {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
        min-height: var(--navbar-h);
        padding: 0 64px 0 20px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        text-decoration: none;
      }
      .nav-brand-head .brand-icon { width: 34px; height: 34px; }
      .nav-brand-head .brand-name { font-size: 13px; letter-spacing: 2px; }

      .nav-item {
        padding: 14px 20px;
        border-radius: 0;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        border-left: 3px solid transparent;
        width: 100%;
        flex-shrink: 0;
      }
      .nav-item.active { border-left-color: var(--accent-2); background: var(--accent-soft); }
      .nav-item.active::after { display: none; }
      html:root[data-theme="light"] .navbar-nav { background: #111112; border-bottom-color: rgba(255,255,255,0.08); }
      html:root[data-theme="light"] .nav-item { border-bottom-color: rgba(255,255,255,0.08); }
      html:root[data-theme="light"] .nav-item.active { border-left-color: var(--accent-2); background: var(--accent-soft); }

      .navbar-right { gap: 8px; }
      /* Role chip ("MEMBER") stays visible on mobile, just more compact */
      .navbar-right .role-chip { display: inline-block; font-size: 9px; padding: 4px 9px; letter-spacing: 1px; }
      .user-chip { gap: 0; }
      .btn-logout-top span.logout-text { display: none; }
      .btn-logout-top { padding: 8px; }

      .page-titlebar { padding: 14px max(14px, var(--safe-right)) 14px max(14px, var(--safe-left)); }
      .page-titlebar h1 { font-size: clamp(13px, 3.6vw, 15px); letter-spacing: clamp(1px, 0.4vw, 2px); }
      .page-wrap { padding: 20px max(14px, var(--safe-right)) calc(20px + var(--safe-bottom)) max(14px, var(--safe-left)); }

      .btn { min-height: 40px; }
      .btn-sm { min-height: 34px; }
    }

    /* Small phones: brand name stays visible */
    @media (max-width: 480px) {
      :root { --nav-px: 12px; }
      .navbar { gap: 6px; }
      .navbar-brand { gap: 7px; }
      .brand-name { display: block; font-size: 11.5px; letter-spacing: 1.2px; }
      .navbar-right { gap: 6px; }

      .nav-brand-head { padding-right: 60px; }
      .nav-brand-head .brand-name { font-size: 12px; letter-spacing: 1.5px; }
      .navbar-nav { width: 84vw; max-width: 320px; }

      .page-titlebar { padding: 12px max(12px, var(--safe-right)) 12px max(12px, var(--safe-left)); }
      .page-wrap { padding: 14px max(10px, var(--safe-right)) calc(14px + var(--safe-bottom)) max(10px, var(--safe-left)); }
    }

    @media (max-width: 389px) {
      .navbar-right .role-chip { display: none !important; }
      .brand-name { font-size: 11px; letter-spacing: 1px; }
    }
    @media (max-width: 340px) {
      .brand-name { font-size: 10px; letter-spacing: .6px; }
    }

    /* Phones in landscape: tighter rows so the drawer fits */
    @media (max-width: 768px) and (max-height: 480px) {
      .nav-item { padding-top: 10px; padding-bottom: 10px; }
    }

    /* No drawer slide-flash while resizing / rotating */
    .nav-resizing .navbar-nav { transition: none !important; }
    @media (prefers-reduced-motion: reduce) {
      .navbar-nav { transition: none !important; }
      html { scroll-behavior: auto; }
    }

    /* ═══════════════════════════════════════════════════════════════
       LIGHT THEME — GOLD & BLACK (same rules as instructor/staff layouts)
       Applies only when <html data-theme="light"> (set by app.js).
       ═══════════════════════════════════════════════════════════════ */

    html:root[data-theme="light"] {
      color-scheme: light;

      --bg:#f6f4ee;        --bg-top:#fbf9f4;
      --surface:#ffffff;   --surface2:#f5f2ea;   --surface3:#e8e3d6;
      --border:rgba(20,16,8,0.10);

      --accent:#a97a17;    --accent-2:#e0a93b;   --accent-dark:#b8862a;
      --accent-soft:rgba(184,134,42,0.12);
      --gold:#b8862a;
      --black:#111111;

      --text:#111111;      --text-soft:#3a3833;  --muted:#6f6a5e;

      --success:#15803d;   --danger:#dc2626;
      --warning:#b45309;   --info:#2563eb;

      --shadow-card:0 1px 2px rgba(20,16,8,.05), 0 4px 16px rgba(20,16,8,.06);
    }

    html:root[data-theme="light"] body {
      background: linear-gradient(180deg, var(--bg-top) 0%, var(--bg) 380px) fixed, var(--bg);
      color: var(--text);
    }

    html:root[data-theme="light"] .navbar { background: rgba(15,15,16,0.96); border-color: rgba(255,255,255,0.08); }
    html:root[data-theme="light"] .brand-icon { background: linear-gradient(145deg,#2a2a2c,#0b0b0c); border-color: rgba(224,169,59,0.45); }
    html:root[data-theme="light"] .brand-icon svg { stroke: var(--accent-2); }
    html:root[data-theme="light"] .brand-name,
    html:root[data-theme="light"] .user-chip { color: #f1f1f3; }
    html:root[data-theme="light"] .role-member {
      background: linear-gradient(135deg, var(--accent-2), var(--accent-dark)) !important;
      color: #111 !important;
      border: 1px solid rgba(0,0,0,0.15);
      box-shadow: none;
    }
    html:root[data-theme="light"] .nav-item { color: #c6c8ce !important; }
    html:root[data-theme="light"] .nav-item svg { stroke: #c6c8ce; }
    html:root[data-theme="light"] .nav-item:hover { color: #fff !important; background: rgba(255,255,255,0.07); }
    html:root[data-theme="light"] .nav-item:hover svg { stroke: #fff; }
    html:root[data-theme="light"] .nav-item.active { color: #fff !important; background: rgba(255,255,255,0.07); }
    html:root[data-theme="light"] .nav-item.active svg { stroke: var(--accent-2); }
    html:root[data-theme="light"] .nav-item.active::after { background: var(--accent-2); }
    html:root[data-theme="light"] .user-chip--link:hover,
    html:root[data-theme="light"] .user-chip--link.active { color: #fff; background: rgba(255,255,255,0.07); }
    html:root[data-theme="light"] .user-chip--link:hover .user-avatar,
    html:root[data-theme="light"] .user-chip--link.active .user-avatar { border-color: var(--accent-2); }
    html:root[data-theme="light"] .user-avatar { background: rgba(224,169,59,0.14); border-color: rgba(224,169,59,0.45); color: var(--accent-2); }
    html:root[data-theme="light"] .btn-logout-top { background: transparent; border-color: rgba(248,113,113,0.45); color: #f87171; }
    html:root[data-theme="light"] .btn-logout-top:hover { background: rgba(248,113,113,0.12); }
    html:root[data-theme="light"] .nav-toggle { border-color: rgba(255,255,255,0.15); color: #fff; }

    html:root[data-theme="light"] .page-titlebar { background: rgba(255,255,255,.6); border-color: var(--border); }
    html:root[data-theme="light"] .page-titlebar h1 { border-left: 3px solid var(--accent-2); padding-left: 12px; }

    html:root[data-theme="light"] :is(.btn-primary) { color: #111; box-shadow: 0 1px 2px rgba(20,16,8,.15); }
    html:root[data-theme="light"] .btn-primary:hover { box-shadow: 0 6px 18px rgba(184,134,42,.35); }
    html:root[data-theme="light"] .btn-secondary { background: var(--black); color: #fff; border-color: var(--black); }
    html:root[data-theme="light"] .btn-secondary:hover { color: var(--accent-2); border-color: var(--accent-2); }

    /* Use `background-color`, NOT the `background` shorthand: the shorthand would reset the select arrow image. */
    html:root[data-theme="light"] .form-control { background-color: var(--surface); border-color: var(--border); color: var(--text); }
    html:root[data-theme="light"] .form-control:focus { border-color: var(--accent-dark); box-shadow: 0 0 0 3px var(--accent-soft); }
    html:root[data-theme="light"] .form-control option { background: var(--surface); color: var(--text); }
    html:root[data-theme="light"] input[type="date"] { color-scheme: light !important; }

    html:root[data-theme="light"] .alert-success { color: var(--success); }
    html:root[data-theme="light"] .alert-danger  { color: var(--danger); }

    html:root[data-theme="light"] .theme-toggle {
      background: var(--black);
      color: var(--accent-2);
      border-color: rgba(224,169,59,.4);
      box-shadow: var(--shadow-card);
    }
  </style>
</head>
<body>

{{-- Read active section before navbar renders --}}
@php $active = View::getSection('active') ?? View::getSection('active_nav') ?? ''; @endphp

{{-- NAVBAR (same structure as instructor) --}}
<nav class="navbar">

  <a href="{{ route('member.dashboard') }}" class="navbar-brand" aria-label="APEX FITNESS GYM">
    <div class="brand-icon">
      <svg viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
      </svg>
    </div>
    <span class="brand-name">APEX FITNESS GYM</span>
  </a>

  <button type="button" class="nav-toggle" id="navToggle" aria-label="Toggle navigation" aria-expanded="false" aria-controls="navbarNav">
    <svg class="icon-menu" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
    <svg class="icon-close" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
  </button>

  <div class="navbar-nav" id="navbarNav">

    {{-- Brand header: only shown inside the drawer on mobile --}}
    <a href="{{ route('member.dashboard') }}" class="nav-brand-head" aria-label="APEX FITNESS GYM">
      <div class="brand-icon">
        <svg viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
      </div>
      <span class="brand-name">APEX FITNESS GYM</span>
    </a>

    <a href="{{ route('member.dashboard') }}"
       class="nav-item {{ $active === 'dashboard' ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2">
        <rect x="3" y="3" width="7" height="7" rx="1"/>
        <rect x="14" y="3" width="7" height="7" rx="1"/>
        <rect x="3" y="14" width="7" height="7" rx="1"/>
        <rect x="14" y="14" width="7" height="7" rx="1"/>
      </svg>
      Dashboard
    </a>

    {{-- Show Waiting only if member is pending --}}
    @if(Auth::user()->memberProfile && Auth::user()->memberProfile->coach_status === 'pending')
      <a href="{{ route('member.waiting') }}"
         class="nav-item {{ $active === 'waiting' ? 'active' : '' }}"
         style="color: var(--accent);">
        <svg viewBox="0 0 24 24" stroke-width="2">
          <circle cx="12" cy="12" r="10"></circle>
          <polyline points="12 6 12 12 16 14"></polyline>
        </svg>
        Waiting Approval
      </a>
    @endif

    <a href="{{ route('member.schedule') }}"
       class="nav-item {{ $active === 'schedule' ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2">
        <rect x="3" y="4" width="18" height="18" rx="2"/>
        <line x1="16" y1="2" x2="16" y2="6"/>
        <line x1="8" y1="2" x2="8" y2="6"/>
        <line x1="3" y1="10" x2="21" y2="10"/>
      </svg>
      My Schedule
    </a>

    {{-- "Profile" menu item removed: the avatar/name chip in the top bar now opens the profile page --}}

    <a href="{{ route('member.attendance-history') }}"
       class="nav-item {{ $active === 'attendance' ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2">
        <rect x="3" y="4" width="18" height="18" rx="2"/>
        <line x1="8" y1="2" x2="8" y2="6"/>
        <line x1="16" y1="2" x2="16" y2="6"/>
        <path d="M8 11h8M8 15h5"/>
      </svg>
      Attendance
    </a>

    <a href="{{ route('member.payments') }}"
       class="nav-item {{ $active === 'payments' ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" stroke-width="2">
        <rect x="1" y="4" width="22" height="16" rx="2" stroke-linecap="round" stroke-linejoin="round"/>
        <line x1="1" y1="10" x2="23" y2="10" stroke-linecap="round"/>
      </svg>
      Payments
    </a>

  </div>

  <div class="navbar-right">
    <span class="role-chip role-member">{{ strtoupper(auth()->user()->role) }}</span>

    {{-- Profile shortcut --}}
    <a href="{{ route('member.profile') }}"
       class="user-chip user-chip--link {{ $active === 'profile' ? 'active' : '' }}"
       title="My Profile"
       aria-label="My Profile">
      <div class="user-avatar">
        @if(auth()->user()->photo)
          <img src="{{ asset('storage/'.auth()->user()->photo) }}" alt=""/>
        @else
          {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        @endif
      </div>
      <span class="user-name-text">{{ auth()->user()->name }}</span>
    </a>

    <form method="POST" action="{{ route('logout') }}" class="navbar-logout-form">
      @csrf
      <button type="submit" class="btn-logout-top" aria-label="Logout">
        <svg viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
          <polyline points="16 17 21 12 16 7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        <span class="logout-text">Logout</span>
      </button>
    </form>
  </div>
</nav>

<div class="nav-scrim" id="navScrim"></div>

{{-- PAGE TITLE BAR: add @section('page_title', 'Dashboard') in a view to show it --}}
@hasSection('page_title')
  <div class="page-titlebar">
    <h1>@yield('page_title')</h1>
  </div>
@endif

{{-- MAIN CONTENT --}}
<main class="page-wrap">
  @if(session('success'))
    <div class="alert alert-success">✓ {{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">✕ {{ session('error') }}</div>
  @endif

  @yield('content')
</main>

<script>
  (function () {
    var toggle = document.getElementById('navToggle');
    var nav    = document.getElementById('navbarNav');
    var scrim  = document.getElementById('navScrim');
    if (!toggle || !nav) return;

    var mq = window.matchMedia('(max-width: 768px)');

    function isOpen() { return nav.classList.contains('open'); }

    function setOpen(open, restoreFocus) {
      if (open && !mq.matches) return;            // the drawer only exists on mobile
      nav.classList.toggle('open', open);
      toggle.classList.toggle('open', open);
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      scrim.classList.toggle('show', open);
      document.body.classList.toggle('menu-open', open);

      if (open) {
        var first = nav.querySelector('.nav-item');
        if (first) first.focus({ preventScroll: true });
      } else if (restoreFocus) {
        toggle.focus({ preventScroll: true });
      }
    }

    toggle.addEventListener('click', function () { setOpen(!isOpen(), true); });
    scrim.addEventListener('click', function () { setOpen(false, true); });
    nav.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () { setOpen(false, false); });
    });

    document.addEventListener('keydown', function (e) {
      if (!isOpen()) return;
      if (e.key === 'Escape') { setOpen(false, true); return; }

      // Keep Tab focus inside the open drawer (plus the close button)
      if (e.key === 'Tab') {
        var items = Array.prototype.slice.call(nav.querySelectorAll('a[href]'))
          .filter(function (el) { return el.offsetParent !== null; });
        items.push(toggle);
        var firstEl = items[0], lastEl = items[items.length - 1];
        if (e.shiftKey && document.activeElement === firstEl) {
          e.preventDefault(); lastEl.focus();
        } else if (!e.shiftKey && document.activeElement === lastEl) {
          e.preventDefault(); firstEl.focus();
        }
      }
    });

    // Close automatically when the viewport grows past the mobile breakpoint
    var onBreakpoint = function (e) { if (!e.matches) setOpen(false, false); };
    if (mq.addEventListener) mq.addEventListener('change', onBreakpoint);
    else mq.addListener(onBreakpoint);

    // No slide animation while resizing / rotating
    var resizeTimer;
    window.addEventListener('resize', function () {
      document.body.classList.add('nav-resizing');
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        document.body.classList.remove('nav-resizing');
      }, 150);
    });
  })();
</script>

</body>
</html>