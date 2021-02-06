const mix = require('laravel-mix');
const webpack = require('webpack');

/*
 |--------------------------------------------------------------------------
 | Custom Mix setup
 |--------------------------------------------------------------------------
 |
 */
mix.webpackConfig({
    plugins: [
        new webpack.ContextReplacementPlugin(
            /moment[\/\\]locale/,
            /(en-gb)\.js/
        )
    ]
});

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/sw.js', 'public/sw.js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');
