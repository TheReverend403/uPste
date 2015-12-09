<p>Hi {{ $user->name }},</p>
<p>Your account at {{ env('DOMAIN') }} was just approved!</p>
<p>Log in at <a href="{{ route('login') }}">{{ route('login') }}</a>, and don't forgot to read the new user notice.</p>