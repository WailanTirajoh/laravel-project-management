const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js').vue()
  .sass('resources/sass/app.scss', 'public/css')
  .css('resources/css/custom.css', 'public/css')
  .css('resources/css/tooltip.css', 'public/css')
  .options({
    postCss: [
      require('postcss-import'),
      require('tailwindcss'),
    ]
  })
  .webpackConfig(require('./webpack.config'));

if (mix.inProduction()) {
  mix.version();
}

mix.browserSync('http://project-management.test');
// mix.browserSync('http://127.0.0.1:8000');
