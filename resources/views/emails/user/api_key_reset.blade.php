Hi {{ $name }},

Somebody, probably you, just reset your API key at {{ config('upste.site_name') }}.
If this was you, you can safely ignore this email.
If not, we recommend resetting your password at {{ route('account.password.email') }} (you'll need to log out first).