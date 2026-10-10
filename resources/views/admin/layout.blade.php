<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f7f9fc;
            color: #1a1f36;
            line-height: 1.5;
        }
        
        /* Header */
        header {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 32px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 24px;
        }
        
        .brand {
            font-weight: 600;
            font-size: 18px;
            color: #1a1f36;
        }
        
        .user-email {
            font-size: 13px;
            color: #6b7280;
        }
        
        nav {
            display: flex;
            gap: 4px;
            align-items: center;
        }
        
        nav a {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #4b5563;
            transition: all 0.2s;
        }
        
        nav a:hover {
            background: #f3f4f6;
            color: #1a1f36;
            text-decoration: none;
        }
        
        .logout-btn {
            background: none;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            color: #dc2626;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.2s;
        }
        
        .logout-btn:hover {
            background: #fef2f2;
        }
        
        /* Main */
        main {
            padding: 32px;
            max-width: 1280px;
            margin: 0 auto;
        }
        
        /* Cards */
        .card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            transition: all 0.2s;
        }
        
        /* Grid */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }
        
        /* Form Elements */
        .row {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            align-items: flex-end;
        }
        
        .muted {
            color: #6b7280;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px;
            display: block;
        }
        
        /* Buttons */
        .btn {
            background: #1a1f36;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn:hover {
            background: #2d3a5e;
            transform: translateY(-1px);
        }
        
        .btn.secondary {
            background: #f3f4f6;
            color: #1a1f36;
            border: 1px solid #e2e8f0;
        }
        
        .btn.secondary:hover {
            background: #e5e7eb;
        }
        
        .btn.danger {
            background: #dc2626;
        }
        
        .btn.danger:hover {
            background: #b91c1c;
        }
        
        /* Inputs */
        .input, select, textarea {
            background: #ffffff;
            border: 1px solid #d1d5db;
            color: #1a1f36;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #1a1f36;
            box-shadow: 0 0 0 3px rgba(26, 31, 54, 0.1);
        }
        
        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            text-align: left;
            padding: 12px 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        th {
            color: #6b7280;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        tr:hover {
            background: #f9fafb;
        }
        
        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #e2e8f0;
        }
        
        .badge.green {
            background: #ecfdf5;
            color: #059669;
            border-color: #d1fae5;
        }
        
        .badge.red {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fee2e2;
        }
        
        /* Flash Messages */
        .flash {
            padding: 14px 18px;
            border-radius: 12px;
            background: #ecfdf5;
            border: 1px solid #d1fae5;
            color: #059669;
            margin-bottom: 24px;
        }
        
        .errors {
            padding: 14px 18px;
            border-radius: 12px;
            background: #fef2f2;
            border: 1px solid #fee2e2;
            color: #dc2626;
            margin-bottom: 24px;
        }
        
        .errors ul {
            margin-top: 8px;
            margin-left: 20px;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            gap: 8px;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            margin-top: 24px;
        }
        
        .pagination a, .pagination span {
            padding: 8px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #ffffff;
            color: #4b5563;
            font-size: 13px;
            transition: all 0.2s;
        }
        
        .pagination a:hover {
            background: #f3f4f6;
            border-color: #cbd5e1;
            text-decoration: none;
        }
        
        .pagination .active {
            background: #1a1f36;
            border-color: #1a1f36;
            color: #ffffff;
        }
        
        .pagination .disabled {
            opacity: 0.5;
        }
        
        /* Checkbox */
        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #1a1f36;
        }
        
        /* Links */
        a {
            color: #1a1f36;
            text-decoration: none;
        }
        
        a:hover {
            color: #4b5563;
            text-decoration: underline;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                height: auto;
                padding: 16px;
                gap: 12px;
            }
            
            .header-left {
                width: 100%;
                justify-content: space-between;
            }
            
            nav {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }
            
            main {
                padding: 16px;
            }
            
            .grid {
                gap: 16px;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="header-left">
        <span class="brand">Admin</span>
        @auth
            <span class="user-email">{{ auth()->user()->email }}</span>
        @endauth
    </div>
    <nav>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.users.index') }}">Users</a>
        <a href="{{ route('admin.categories.index') }}">Categories</a>
        <a href="{{ route('admin.premiums.index') }}">Premiums</a>
        <a href="{{ route('admin.reflections.index') }}">Reflections</a>
        <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">
            @csrf
            <button class="logout-btn" type="submit">Logout</button>
        </form>
    </nav>
</header>
<main>
    @if (session('status'))
        <div class="flash">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="errors">
            <strong>Please fix the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @yield('content')
</main>
</body>
</html>