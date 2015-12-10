Hi {{ $user->name }},

Unfortunately, your request for an account at {{ env('DOMAIN') }} was rejected.
This is most likely because the site owner doesn't know you or wasn't expecting your request.

If you feel this is a mistake, please feel free to come by {{ env('IRC_CHANNEL') }} on {{ env('IRC_SERVER') }} and let us know.