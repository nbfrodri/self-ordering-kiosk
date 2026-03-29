<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kiosk Self-Ordering')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-base: #161619;
            --bg-surface: rgba(255,255,255,0.04);
            --bg-surface-hover: rgba(255,255,255,0.07);
            --bg-glass: rgba(255,255,255,0.06);
            --bg-glass-strong: rgba(255,255,255,0.10);
            --text-primary: #FAFAF9;
            --text-secondary: #A8A29E;
            --text-muted: #57534E;
            --accent-primary: #FF6B35;
            --accent-secondary: #E63946;
            --accent-gradient: linear-gradient(135deg, #FF6B35, #E63946);
            --accent-glow: rgba(255,107,53,0.25);
            --success: #10B981;
            --success-glow: rgba(16,185,129,0.2);
            --info: #3B82F6;
            --warning: #F59E0B;
            --border: rgba(255,255,255,0.08);
            --border-focus: rgba(255,107,53,0.4);
            --radius-sm: 10px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-full: 9999px;
            --font-display: 'Outfit', sans-serif;
            --font-body: 'Plus Jakarta Sans', sans-serif;
            --spring: cubic-bezier(0.34, 1.56, 0.64, 1);
            --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
        }
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { font-size: 16px; -webkit-tap-highlight-color: transparent; }
        body {
            font-family: var(--font-body);
            background: var(--bg-base);
            color: var(--text-primary);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .hidden { display: none !important; }
        button { cursor: pointer; font-family: var(--font-body); }
        input, textarea { font-family: var(--font-body); }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.14); }
        ::selection { background: rgba(255,107,53,0.3); color: var(--text-primary); }
    </style>
    @stack('styles')
</head>
<body>
    @yield('content')
    <script>
        const API_BASE = '/api';
        async function apiFetch(endpoint, options = {}) {
            const res = await fetch(`${API_BASE}${endpoint}`, {
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', ...options.headers },
                ...options
            });
            return res.json();
        }
    </script>
    @stack('scripts')
</body>
</html>
