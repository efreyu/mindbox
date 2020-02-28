const mix = require('laravel-mix');

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

mix.js('src/resources/js/app.js', 'publishable/js')
   .sass('src/resources/sass/app.scss', 'publishable/css')
    .webpackConfig({
        output: { chunkFilename: 'js/[name].[contenthash].js' },
        resolve: {
            alias: {
                vue$: 'vue/dist/vue.runtime.js',
                '@': path.resolve('resources/js'),
            },
        },
    });
