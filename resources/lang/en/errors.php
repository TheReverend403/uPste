<?php

return [
    'invalid_file_upload'   => 'The file you uploaded is not a valid form upload.',
    'upload_file_not_found' => 'You didn\'t supply a file to upload.',
    'upload_quota_reached'  => 'You have reached the per-user storage quota of ' . Helpers::formatBytes(config('upste.user_storage_quota')),
    'upload_too_large'      => 'File size exceeds max upload size of ' . Helpers::formatBytes(config('upste.upload_limit')),
];
