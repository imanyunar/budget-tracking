@extends('layouts.app')

@section('title', 'Settings · FinanceTracker')

@section('content')
<style>
    .settings-card { background:var(--surface); border:1px solid var(--border); border-radius:16px; overflow:hidden; box-shadow:var(--shadow-sm); }
    .settings-card-header { padding:16px 22px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:12px; background:var(--surface-2); }
    .settings-card-body { padding:22px; }
    .section-icon { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .toggle-row { display:flex; align-items:center; justify-content:space-between; padding:13px 0; border-bottom:1px solid var(--border); }
    .toggle-row:last-child { border-bottom:none; }
    .toggle-label { font-size:13.5px; font-weight:500; color:var(--text); }
    .toggle-sublabel { font-size:11.5px; color:var(--muted); margin-top:2px; }
    .toggle-switch { position:relative; width:42px; height:24px; flex-shrink:0; cursor:pointer; }
    .toggle-switch input { opacity:0; width:0; height:0; position:absolute; }
    .toggle-track { position:absolute; inset:0; border-radius:12px; background:var(--border-2); transition:all 0.2s; cursor:pointer; }
    .toggle-thumb { position:absolute; top:3px; left:3px; width:18px; height:18px; border-radius:50%; background:#fff; transition:all 0.2s; box-shadow:0 1px 4px rgba(0,0,0,0.12); }
    .toggle-switch input:checked ~ .toggle-track { background:var(--primary); }
    .toggle-switch input:checked ~ .toggle-track .toggle-thumb { transform:translateX(18px); }
    .danger-zone { background:rgba(244,63,94,0.04); border:1px solid rgba(244,63,94,0.18); border-radius:14px; padding:20px 22px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; }
</style>

<div style="max-width:800px;margin:0 auto;display:flex;flex-direction:column;gap:16px;">

    <!-- Header -->
    <div>
        <h1 class="page-title-main">Settings</h1>
        <p class="page-title-sub">Personal preferences & configuration</p>
    </div>

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Profile Card -->
        <div class="settings-card" style="margin-bottom:16px;">
            <div class="settings-card-header">
                <div class="section-icon" style="background:var(--primary-dim);border:1px solid var(--primary-mid);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div style="flex:1;">
                    <div style="font-size:14px;font-weight:700;color:var(--text);">Profile Identity</div>
                    <div style="font-size:12px;color:var(--muted);margin-top:1px;">User account details</div>
                </div>
                <button type="submit" class="btn-primary" style="padding:9px 18px;font-size:13px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/></svg>
                    Save Changes
                </button>
            </div>
            <div class="settings-card-body" style="display:flex;align-items:center;gap:24px;flex-wrap:wrap;">
                <!-- Avatar -->
                <div style="position:relative;flex-shrink:0;">
                    <div style="width:80px;height:80px;border-radius:14px;overflow:hidden;border:2px solid var(--border);box-shadow:var(--shadow-sm);">
                        <img id="avatarPreview" src="{{ isset($user->settings['avatar_path']) ? asset('storage/' . $user->settings['avatar_path']) : 'https://api.dicebear.com/7.x/initials/svg?seed='.urlencode($user->name).'&backgroundColor=6366f1&textColor=ffffff' }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                    <input type="hidden" name="avatar_seed" id="avatarSeed" value="{{ $user->avatar_seed }}">
                    <input type="file" name="avatar_image" id="avatarUpload" accept="image/*" onchange="previewAvatar(event)" style="display:none;">
                    <button type="button" onclick="document.getElementById('avatarUpload').click()" style="position:absolute;bottom:-8px;right:-8px;width:28px;height:28px;border-radius:8px;background:var(--surface);border:1.5px solid var(--border);color:var(--text-2);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all 0.15s;box-shadow:var(--shadow-sm);" onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-2)'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                    </button>
                </div>
                <!-- Info -->
                <div style="flex:1;min-width:200px;">
                    <div style="font-size:18px;font-weight:800;color:var(--text);letter-spacing:-0.02em;">{{ Auth::user()->name }}</div>
                    <div style="display:flex;gap:8px;margin-top:8px;flex-wrap:wrap;">
                        <span class="badge badge-up">Active</span>
                        <span class="badge badge-blue">Verified</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Two column -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:16px;">

            <!-- Personal Info -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="section-icon" style="background:var(--blue-dim);border:1px solid rgba(59,130,246,0.2);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:700;color:var(--text);">Personal Info</div>
                        <div style="font-size:12px;color:var(--muted);margin-top:1px;">Identity data</div>
                    </div>
                </div>
                <div class="settings-card-body" style="display:flex;flex-direction:column;gap:14px;">
                    <div>
                        <label class="input-label">Display Name</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="input-field">
                    </div>
                    <div>
                        <label class="input-label">Email Address</label>
                        <input type="email" value="{{ Auth::user()->email }}" class="input-field" readonly disabled style="opacity:0.5;cursor:not-allowed;background:var(--surface-2);">
                    </div>
                </div>
            </div>

            <!-- Alert Config -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="section-icon" style="background:var(--warn-dim);border:1px solid rgba(245,158,11,0.2);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.268 21a2 2 0 0 0 3.464 0"/><path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"/></svg>
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:700;color:var(--text);">Notifications</div>
                        <div style="font-size:12px;color:var(--muted);margin-top:1px;">Alert configuration</div>
                    </div>
                </div>
                <div class="settings-card-body">
                    <div class="toggle-row">
                        <div>
                            <div class="toggle-label">Daily Budget Report</div>
                            <div class="toggle-sublabel">Push notifications</div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="daily_report" value="1" {{ ($user->settings['daily_report'] ?? true) ? 'checked' : '' }}>
                            <div class="toggle-track"><div class="toggle-thumb"></div></div>
                        </label>
                    </div>
                    <div class="toggle-row">
                        <div>
                            <div class="toggle-label">Stock Market Alerts</div>
                            <div class="toggle-sublabel">Critical priority only</div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="stock_alerts" value="1" {{ ($user->settings['stock_alerts'] ?? false) ? 'checked' : '' }}>
                            <div class="toggle-track"><div class="toggle-thumb"></div></div>
                        </label>
                    </div>
                    <div class="toggle-row">
                        <div>
                            <div class="toggle-label">Multi-Currency Export</div>
                            <div class="toggle-sublabel">Spreadsheet compatible</div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="spreadsheet_compat" value="1" {{ ($user->settings['spreadsheet_compat'] ?? true) ? 'checked' : '' }}>
                            <div class="toggle-track"><div class="toggle-thumb"></div></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Danger Zone -->
    <div class="danger-zone">
        <div>
            <div style="font-size:14.5px;font-weight:700;color:var(--danger);">Danger Zone</div>
            <div style="font-size:12.5px;color:rgba(244,63,94,0.65);margin-top:4px;">Permanently delete all financial records and reset all balances.</div>
        </div>
        <form action="{{ route('settings.clear') }}" method="POST" onsubmit="return confirm('WARNING: This will delete ALL transactions and reset balances. This cannot be undone.')">
            @csrf
            <button type="submit" class="btn-danger">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                Clear All Data
            </button>
        </form>
    </div>
</div>

<script>
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => { document.getElementById('avatarPreview').src = e.target.result; };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection