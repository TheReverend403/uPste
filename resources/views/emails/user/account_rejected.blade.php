Hi {{ $name }},

Unfortunately, your request for an account at {{ config('upste.site_name') }} was rejected.
This is most likely because the site owner doesn't know you or wasn't expecting your request.

@if(config('upste.irc_server') && config('upste.irc_channel'))
If you feel this is a mistake, please feel free to come by {{ config('upste.irc_channel') }} on {{ config('upste.irc_server') }} and let us know.
@endif