Hi {{ $user->name }},

Your account at {{ env('DOMAIN') }} was just approved!
Log in at {{ route('login') }} and don't forgot to read the new user notice.