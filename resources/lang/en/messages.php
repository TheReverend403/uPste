<?php

return [
    'file_deleted'              => 'Deleted :name.',
    'api_key_changed'           => 'Your API key was reset. New API key: :api_key',
    'invalid_file_upload'       => 'The file you\'re trying to upload isn\'t a valid form upload.',
    'upload_file_not_found'     => 'You didn\'t supply a file to upload.',
    'upload_quota_reached'      => 'You have reached the per-user storage quota of :limit.',
    'upload_too_large'          => 'This file exceeds the max upload size of :limit.',
    'no_uploads_found'          => 'You do not have anything uploaded.',
    'could_not_write_image'     => 'Error occurred while saving file.',
    'could_not_read_image'      => 'Error occurred while reading file.',
    'unsupported_image'         => 'Unsupported image type.',
    'preferences_saved'         => 'Preferences updated!',
    'banned'                    => 'You have been banned. Contact an admin if you believe that this is an error.',
    'not_logged_in'             => 'You must log in to access this page.',
    'not_activated'             => 'Your account has not been approved yet. You will be notified via email when your account status changes.',
    'activation_pending'        => 'Your account request has been successfully registered. You will receive a notification email at :email when an admin accepts or rejects your request.',
    'email_confirmed'           => 'Your email has been confirmed, and you have been automatically logged in.',
    'confirmation_pending'      => 'Please check your email for a confirmation code from ' . config('mail.from.address'),
    'not_confirmed'             => 'Your account has not been confirmed. Please check your email for a confirmation code from ' . config('mail.from.address'),
    'no_such_confirmation_code' => 'We couldn\'t find a pending registration with that confirmation code.',
    'delete_all_uploads'        => 'Delete all uploads',
    'confirm_deletion'          => 'Are you sure that you want to delete all of your uploads?',
    'all_uploads_deleted'       => 'All of your uploads have been deleted.',
    'admin'                     => [
        'no_uploads_found'        => ':name has no uploads.',
        'unbanned_user'           => 'Unbanned :name.',
        'banned_user'             => 'Banned :name.',
        'deleted_user'            => 'Deleted :name.',
        'account_accepted'        => 'Accepted :name',
        'account_rejected'        => 'Rejected :name.',
        'failed_superuser_action' => 'You cannot :type the superuser account!',
    ],
];
