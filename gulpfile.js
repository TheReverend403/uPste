var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

// Disable notify-send
process.env.DISABLE_NOTIFIER = true;
elixir.config.sourcemaps = false;

elixir(function(mix) {
    mix.sass('app.scss');
    mix.sass("error.scss", 'public/css/error.css');
    mix.scripts("dropzone.js", 'public/js/dropzone.js');
    mix.scripts([
        "../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/modal.js",
        "../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/dropdown.js",
        "../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/alert.js"
    ], 'public/js/bootstrap.js');
});
