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
//
// mix.sass('resources/sass/plugins/tree-view/tree-view.scss', 'public/adminhtml/assets/plugins/tree-view');
//
//
// mix.sass('resources/sass/role/role-create.scss', 'public/adminhtml/css/role');
//
// mix.sass('resources/sass/login.page.scss', 'public/adminhtml/css')
//     .js('resources/js/adminjs.js', 'public/adminhtml/js')
//     .sass('resources/sass/profile.scss', 'public/adminhtml/css')
//     .sass('resources/sass/datatables.scss', 'public/adminhtml/css');

mix.js('resources/js/app.js', 'public/frontend/js')
    .js('resources/js/adminjs.js', 'public/adminhtml/js')
    .sass('resources/sass/admincss.scss', 'public/adminhtml/css')
    .sass('resources/sass/app.scss', 'public/frontend/css')
    .js('resources/js/booking/index.js', 'public/frontend/js/booking');
