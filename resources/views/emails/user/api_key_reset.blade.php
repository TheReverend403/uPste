Hi {{ $user->name }},

Somebody, probably you, just reset your API key at {{ config('upste.site_name') }}.
If this was you, you can safely ignore this email.
If not, we recommend resetting your password at {{ $passwordRoute }} (you'll need to be logged out first).