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

var elixir = require('laravel-elixir');
elixir.config.sourcemaps = false;

elixir(function(mix) {
    mix.sass([
        'global.scss'
    ], 'public/assets/css/global.css');

    mix.sass([
        'error.scss'
    ], 'public/assets/css/error.css');

    mix.sass([
        '../../../node_modules/dropzone/dist/basic.css'
    ], 'public/assets/css/dropzone.css');

    mix.scripts([
        '../../../node_modules/dropzone/dist/dropzone.js',
        'dropzone.js'
    ], 'public/assets/js/dropzone.js');

    mix.scripts([
        '../../../node_modules/jquery/jquery.js',
        '../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/modal.js',
        '../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/collapse.js',
        '../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/dropdown.js',
        '../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap/alert.js',
        'global.js'
    ], 'public/assets/js/global.js');

    mix.version([
        "assets/css/global.css",
        "assets/css/error.css",
        "assets/css/dropzone.css",
        "assets/js/global.js",
        "assets/js/dropzone.js"
    ]);
});
