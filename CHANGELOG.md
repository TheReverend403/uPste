Please view this file on the master branch, on stable branches it's out of date.

v 1.0.5
  - Show correct counts on admin user pages and update caches after saving a file.

v 1.0.4
  - Don't call save() after create(), it's redundant.
  - Actually strip EXIF info from images and save them to the right file.

v 1.0.3
  - Make the example nginx config a little safer.

v 1.0.2
  - Add email to /u/preferences.
  - Fix broken view counter.
  - Make user created_at timestamp use user timezone in /a/users.

v 1.0.1
  - Fixed missing translation for deleting users.

v 1.0.0
  - Started doing actual releases with versions.
  - Switched filesystem structure from uploads/$filename to uploads/md5(user_id)/md5(first_character_of_filename) to help performance on certain filesystems.

v 1.0.0 Breaking Changes
  - Due to the directory structure change, you will be required to run `foreach (\App\Models\Upload::all() as $upload) { $upload->migrate(); }` in the `php artisan tinker` console immediately after upgrade in order for your uploads to be available again. It is recommended you make a backup of storage/app/ first.
