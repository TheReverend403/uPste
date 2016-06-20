<?php

return [
    'site_name'             => env('SITE_NAME', 'uPste'),
    'upload_url'            => str_finish(env('UPLOAD_URL', 'https://a.example.com'), '/'),
    'upload_domain'         => parse_url(env('UPLOAD_URL', 'https://a.example.com'), PHP_URL_HOST),
    'upload_slug_length'    => env('UPLOAD_SLUG_LENGTH', 3),
    'owner_name'            => env('OWNER_NAME', 'Me'),
    'owner_email'           => env('OWNER_EMAIL', 'me@example.com'),
    'owner_gpg'             => env('OWNER_GPG', null),
    'upload_limit'          => env('PER_UPLOAD_LIMIT', 20) * 1000000,
    'irc_channel'           => env('IRC_CHANNEL', null),
    'irc_server'            => env('IRC_SERVER', null),
    'strip_exif'            => env('STRIP_EXIF', true),
    'user_storage_quota'    => env('USER_STORAGE_QUOTA', 0) * 1000000, // Megabytes to bytes
    'recaptcha_enabled'     => env('RECAPTCHA_PUBLIC_KEY', null) && env('RECAPTCHA_PUBLIC_KEY', null),
    'sendfile_method'       => env('SENDFILE_METHOD', null),
    'require_user_approval' => env('REQUIRE_USER_APPROVAL', true),
    'password_hash_rounds'  => env('PASSWORD_HASH_ROUNDS', 10),
];
