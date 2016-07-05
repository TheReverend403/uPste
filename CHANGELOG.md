Please view this file on the master branch, on stable branches it's out of date.

v 1.1.2
  - Totally refactor caching so models pretty much handle their own caching with no outside interference.

v 1.1.1
  - Clean up email subjects and fix routes when using daemon queue worker.

v 1.1.0
  - Add deletion URLs for sharex compatibility.

v 1.0.10
  - Fixed all the bugs in image previews.

v 1.0.9
  - Add image previews on thumbnail hover.

v 1.0.8
  - Switch to fit() for image resizing as it retains aspect ratio.

v 1.0.7
  - Add bcrypt work factor to env
  - Add Honeypot service to further reduce spambots registering.

v 1.0.6
  - Unify references to site under site\_name variable.
  - Hide updated\_at from uploads and rename getThumbnail() to getThumbnailUrl().
  - Clean up admin/uploads a little.

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
  - Make user created\_at timestamp use user timezone in /a/users.

v 1.0.1
  - Fixed missing translation for deleting users.

v 1.0.0
  - Started doing actual releases with versions.
  - Switched filesystem structure from uploads/$filename to uploads/md5(user\_id)/md5(first\_character\_of\_filename) to help performance on certain filesystems.

v 1.0.0 Breaking Changes
  - Due to the directory structure change, you will be required to run `foreach (\App\Models\Upload::all() as $upload) { $upload->migrate(); }` in the `php artisan tinker` console immediately after upgrade in order for your uploads to be available again. It is recommended you make a backup of storage/app/ first.
