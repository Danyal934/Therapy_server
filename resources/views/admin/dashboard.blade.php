@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <style>
        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 24px;
            padding: 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0, 0, 0, 0.05);
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
            background: linear-gradient(90deg, #6366f1, #8b5cf6, #a855f7);
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        }
        
        .quick-action-btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1px solid #e5e7eb;
            color: #374151;
        }
        
        .quick-action-btn:hover {
            background: #f9fafb;
            border-color: #6366f1;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
        }
        
        .trend-up {
            color: #10b981;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: rgba(16, 185, 129, 0.1);
            padding: 4px 8px;
            border-radius: 20px;
        }

        .stat-label {
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 42px;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 8px;
            color: #111827;
        }

        .stat-description {
            font-size: 13px;
            color: #9ca3af;
        }

        /* New Welcome Banner Styles */
        .welcome-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            border-radius: 24px;
            padding: 32px;
            margin-bottom: 32px;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
        }
        
        .welcome-banner::before {
            content: '🎧';
            position: absolute;
            top: -30px;
            right: -30px;
            font-size: 180px;
            opacity: 0.08;
            pointer-events: none;
            transform: rotate(15deg);
        }
        
        .welcome-banner::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.2) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        
        .welcome-stats {
            display: flex;
            gap: 24px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .welcome-stat-item {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 8px 20px;
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .welcome-stat-number {
            font-size: 20px;
            font-weight: 800;
            color: #a855f7;
        }
        
        .welcome-stat-label {
            font-size: 13px;
            opacity: 0.9;
        }
    </style>

    <!-- Welcome Banner - New Design -->
    <div class="welcome-banner">
        <div style="position: relative; z-index: 1;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 20px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                        <div style="background: rgba(139, 92, 246, 0.2); padding: 8px 16px; border-radius: 40px; border: 1px solid rgba(139, 92, 246, 0.4);">
                            <span style="font-size: 13px; font-weight: 500; color: #a855f7;">Admin Dashboard</span>
                        </div>
                        <div style="background: rgba(16, 185, 129, 0.2); padding: 8px 16px; border-radius: 40px; border: 1px solid rgba(16, 185, 129, 0.4);">
                            <span style="font-size: 13px; font-weight: 500; color: #10b981;">● Live</span>
                        </div>
                    </div>
                    <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 12px; background: linear-gradient(135deg, #fff 0%, #a855f7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        Welcome back, Admin! 👋
                    </h1>
                    <p style="opacity: 0.85; margin-bottom: 0; font-size: 15px;">Track your platform's growth and manage content effortlessly.</p>
                </div>
                <div style="background: rgba(255,255,255,0.08); border-radius: 20px; padding: 12px 20px; border: 1px solid rgba(255,255,255,0.1);">
                    <div style="font-size: 12px; opacity: 0.7; margin-bottom: 4px;">Today's Date</div>
                    <div style="font-size: 16px; font-weight: 600;">{{ date('l, F j, Y') }}</div>
                </div>
            </div>
            
           {{--  <div class="welcome-stats">
                <div class="welcome-stat-item">
                    <span class="welcome-stat-number">{{ number_format($usersCount) }}</span>
                    <span class="welcome-stat-label">Total Users</span>
                </div>
                <div class="welcome-stat-item">
                    <span class="welcome-stat-number">{{ number_format($premiumsCount) }}</span>
                    <span class="welcome-stat-label">Premium Audios</span>
                </div>
                <div class="welcome-stat-item">
                    <span class="welcome-stat-number">{{ number_format($reflectionsCount) }}</span>
                    <span class="welcome-stat-label">Reflections</span>
                </div> --}}
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 40px;">
        <!-- Users Card -->
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                <div class="stat-icon">👥</div>
                <div class="trend-up">
                    <span>↑</span> <span>+12%</span>
                </div>
            </div>
            <div>
                <div class="stat-label">Total Users</div>
                <div class="stat-value">{{ number_format($usersCount) }}</div>
                <div class="stat-description">Active registered accounts</div>
            </div>
        </div>

        <!-- Premium Audios Card -->
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                <div class="stat-icon">🎵</div>
                <div class="trend-up">
                    <span>↑</span> <span>+8%</span>
                </div>
            </div>
            <div>
                <div class="stat-label">Premium Audios</div>
                <div class="stat-value">{{ number_format($premiumsCount) }}</div>
                <div class="stat-description">Premium audio tracks available</div>
            </div>
        </div>

        <!-- Reflections Card -->
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                <div class="stat-icon">💭</div>
                <div class="trend-up">
                    <span>↑</span> <span>+15%</span>
                </div>
            </div>
            <div>
                <div class="stat-label">Reflections</div>
                <div class="stat-value">{{ number_format($reflectionsCount) }}</div>
                <div class="stat-description">Reflection exercises</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div style="background: white; border-radius: 24px; padding: 32px; border: 1px solid #f0f0f0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
            <div>
                <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 4px; color: #111827;">Quick Actions</h2>
                <p style="color: #6b7280; font-size: 14px;">Manage your platform content and users</p>
            </div>
        </div>
        
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <a href="{{ route('admin.users.index') }}" class="quick-action-btn">
                <span>👥</span> Manage Users
            </a>
            <a href="{{ route('admin.premiums.index') }}" class="quick-action-btn">
                <span>🎵</span> Premium Audios
            </a>
            <a href="{{ route('admin.reflections.index') }}" class="quick-action-btn">
                <span>💭</span> Reflections
            </a>
            <a href="{{ route('admin.categories.index') }}" class="quick-action-btn">
                <span>📁</span> Categories
            </a>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div style="margin-top: 24px; background: white; border-radius: 24px; padding: 32px; border: 1px solid #f0f0f0;">
        <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 20px; color: #111827;">Recent Activity</h2>
        <div style="text-align: center; padding: 40px; color: #9ca3af;">
            <div style="font-size: 48px; margin-bottom: 12px;">📊</div>
            <p style="color: #6b7280;">Activity feed coming soon</p>
            <p style="font-size: 13px; color: #9ca3af;">Track user engagement and content performance</p>
        </div>
    </div>
@endsection