uPste
==========

![Language](https://img.shields.io/badge/language-PHP-blue.svg?style=flat-square)
[![License](https://img.shields.io/badge/license-AGPLv3-blue.svg?style=flat-square)](https://www.gnu.org/licenses/agpl-3.0.en.html)
[![IRC](https://img.shields.io/badge/chat-IRC-green.svg?style=flat-square)](https://qchat.rizon.net/?channels=leliana)

uPste is a private file hosting application with an emphasis on serving technology communities.

It is released under the [GNU Affero General Public License](https://www.gnu.org/licenses/agpl-3.0.html).

The official demo of this project is available at https://u.pste.pw, although not very useful without an account.

Interested in contributing, want some help or just have some questions? Join us on [irc.rizon.net in #leliana](https://qchat.rizon.net/?channels=leliana)

![Preview](https://a.pste.pw/WAZs.png)

# Requirements

* The PHP GD extension.
* The ability to install [Laravel](http://laravel.com/docs/5.1/installation).
* Any database supported by [Eloquent](http://laravel.com/docs/5.1/database#configuration).
* [Composer](http://getcomposer.org/).
* Node.js (and npm).
* A little bit of command line experience.

# Installation

We'll assume you already have a database, setting that up is beyond the scope of this readme.

````bash
git clone https://github.com/TheReverend403/uPste
cd uPste
npm install -g gulp # You may need to run this as root, unless you follow the instructions at https://github.com/sindresorhus/guides/blob/master/npm-global-without-sudo.md
composer install # Installs laravel, all dependencies, npm dependencies, and compiles assets.
cp .env.example .env
php artisan key:generate # VERY IMPORTANT, DO NOT MISS THIS
````

Open .env in the root directory of uPste and edit the settings within to suit your site. Make sure to read what each one does, and feel free to ask if you're not sure.

````bash
php artisan migrate # Creates your database schema
````

The next, and last part is entirely dependent you and how you want to configure your webserver, 
but you're basically going to want two domains (or subdomains).  
One will be your site UI where users will register, login, and manage their uploads. The root for this host should be $upste_root/public/  
The other will be purely for serving the uploaded files. The root for this host should be $upste_root/storage/app/uploads

That's it, you're done! Now just navigate to your site and register.  
The first user registered will be automatically enabled and made an admin, so take measures to make sure this is you.

# Upgrading

Upgrading is easy. Just run the following commands, and make sure to check .env.example for any new config options you need to set.

````bash
cd /path/to/uPste
php artisan down
git pull
composer update
````

If everything went well and you didn't get any errors, you can now bring your site back online with `php artisan up`