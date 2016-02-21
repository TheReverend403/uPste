uPste
==========

![Language](https://img.shields.io/badge/language-PHP-blue.svg?style=flat-square)
[![License](https://img.shields.io/badge/license-AGPLv3-blue.svg?style=flat-square)](https://www.gnu.org/licenses/agpl-3.0.en.html)
[![IRC](https://img.shields.io/badge/chat-IRC-green.svg?style=flat-square)](https://qchat.rizon.net/?channels=leliana)
[![Flattr this git repo](http://api.flattr.com/button/flattr-badge-large.png)](https://flattr.com/submit/auto?user_id=TheReverend403&url=https://github.com/TheReverend403/uPste&title=uPste&language=PHp&tags=github&category=software)

uPste is a private file hosting application with an emphasis on serving technology communities.

It is released under the [GNU Affero General Public License](https://www.gnu.org/licenses/agpl-3.0.html).

The official demo of this project is available at https://u.pste.pw, although not very useful without an account.

Interested in contributing, want some help or just have some questions? Join us on [irc.rizon.net in #leliana](https://qchat.rizon.net/?channels=leliana)

![Preview](https://a.pste.pw/WAZs.png)

# Requirements

* The PHP GD extension.
* The ability to install [Laravel](http://laravel.com/docs/5.1/installation).
* Any database supported by [Eloquent](http://laravel.com/docs/5.1/database#configuration).
* [Laravel Elixir](https://laravel.com/docs/5.1/elixir#installation).
* A little bit of command line experience.

# Installation

We'll assume you already have a database, setting that up is beyond the scope of this readme.

````bash
git clone https://github.com/TheReverend403/uPste
cd uPste
composer install # Installs laravel, all dependencies, npm dependencies, compiles assets and generates your app key.
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
git pull
composer update
````

If everything went well and you didn't get any errors, you can now bring your site back online with `php artisan up`
