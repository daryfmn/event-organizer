<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login — nevermind.hcn</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-page">

<div class="login-card">
    <div class="login-brand">nevermind<span>.</span>hcn</div>
    <div class="login-sub">Sign in to manage events and quotations</div>

    @if ($errors->any())
        <div class="alert alert-error" style="margin-bottom:16px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.attempt') }}">
        @csrf
        <div class="form-group" style="margin-bottom:14px;">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@nevermindhcn.co.id" required autofocus />
        </div>
        <div class="form-group" style="margin-bottom:6px;">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="••••••••" required />
        </div>
        <div class="field-hint" style="margin-bottom:22px;">
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;">
                <input type="checkbox" name="remember" style="width:auto;" /> Remember me
            </label>
        </div>
        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:11px;">Sign in</button>
    </form>

    <div class="login-footer">Staff access only — accounts are created by your administrator</div>
</div>

</body>
</html>
