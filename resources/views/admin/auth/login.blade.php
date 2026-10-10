<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #f7f9fc 0%, #edf2f7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
        }

        .card {
            background: #ffffff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }

        .card-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .card-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #1a1f36;
            margin-bottom: 8px;
        }

        .card-header p {
            font-size: 14px;
            color: #6b7280;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #1a1f36;
            margin-bottom: 8px;
            letter-spacing: 0.3px;
        }

        input {
            width: 100%;
            padding: 12px 16px;
            font-size: 14px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            background: #ffffff;
            color: #1a1f36;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        input:focus {
            outline: none;
            border-color: #1a1f36;
            box-shadow: 0 0 0 3px rgba(26, 31, 54, 0.1);
        }

        input::placeholder {
            color: #9ca3af;
        }

        button {
            width: 100%;
            padding: 12px 16px;
            background: #1a1f36;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 8px;
            font-family: inherit;
        }

        button:hover {
            background: #2d3a5e;
            transform: translateY(-1px);
        }

        button:active {
            transform: translateY(0);
        }

        .errors {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 24px;
        }

        .errors ul {
            margin: 0;
            padding-left: 20px;
            color: #dc2626;
            font-size: 13px;
        }

        .errors li {
            margin: 4px 0;
        }

        .footer-text {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: #9ca3af;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .card {
                padding: 28px 20px;
            }
            
            .card-header h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card">
            <div class="card-header">
                <h2>Welcome back</h2>
                <p>Sign in to your admin account</p>
            </div>

            @if ($errors->any())
                <div class="errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="form-group">
                    <label>Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" required autofocus>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit">Sign in</button>
            </form>

            <div class="footer-text">
                Secure admin access only
            </div>
        </div>
    </div>
</body>
</html>