<?php

return [
    'site_name'          => env('SITE_NAME', 'uPste'),
    'domain'             => env('DOMAIN', 'example.com'),
    'upload_url'         => str_finish(env('UPLOAD_URL', 'https://a.example.com'), '/'),
    'upload_slug_length' => env('UPLOAD_SLUG_LENGTH', 3),
    'owner_name'         => env('OWNER_NAME', 'Me'),
    'owner_email'        => env('OWNER_EMAIL', 'me@example.com'),
    'owner_gpg'          => env('OWNER_GPG', 'https://example.com/gpg/key.asc'),
    'upload_limit'       => env('UPLOAD_LIMIT', '20 MB'),
    'irc_channel'        => env('IRC_CHANNEL', '#example'),
    'irc_server'         => env('IRC_SERVER', 'irc.example.com'),
    'strip_exif'         => env('STRIP_EXIF', true),
    'upload_domain'      => env('UPLOAD_DOMAIN', 'a.example.com')
];