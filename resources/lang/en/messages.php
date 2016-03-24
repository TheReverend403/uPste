<?php

use App\Helpers;

return [
    'file_deleted'          => 'Deleted :name.',
    'file_not_yours'        => 'That file does not belong to you.',
    'api_key_changed'       => 'Your API key was reset. New API key: :api_key',
    'invalid_file_upload'   => 'The file you uploaded is not a valid form upload.',
    'upload_file_not_found' => 'You didn\'t supply a file to upload.',
    'upload_quota_reached'  => 'You have reached the per-user storage quota of ' . Helpers::formatBytes(config('upste.user_storage_quota')),
    'upload_too_large'      => 'File size exceeds max upload size of ' . Helpers::formatBytes(config('upste.upload_limit')),
    'no_uploads_found'      => 'You have no uploads.',
    'could_not_write_image' => 'Error occurred while saving file.',
    'could_not_read_image'  => 'Error occurred while reading file',
    'unsupported_image'     => 'Unsupported image type',
    'admin'                 => [
        'no_uploads_found'        => ':name has no uploads.',
        'unbanned_user'           => 'Unbanned :name.',
        'banned_user'             => 'Banned :name.',
        'deleted_user'            => 'Deleted :name.',
        'account_accepted'        => 'Accepted :name',
        'account_rejected'        => 'Rejected :name.',
        'failed_superuser_action' => 'You cannot :type the superuser account!',
    ],
];
