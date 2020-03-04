const { mix } = require('laravel-mix');

mix.styles([
        'public/AdminLTE-2.3.11/bootstrap/css/bootstrap.min.css',
        'public/AdminLTE-2.3.11/dist/css/AdminLTE.min.css',
        'public/AdminLTE-2.3.11/dist/css/skins/skin-blue.min.css'
    ],
    'public/css/css_combine.css').version()
    .styles(['node_modules/sweetalert2/dist/sweetalert2.css'], 'public/css/sweetalert2.css').version()
    .js(['resources/assets/js/app.js'], 'public/js').version()
    .js(['node_modules/sweetalert2/dist/sweetalert2.js'], 'public/js').version()
    .js(['node_modules/sweetalert2/dist/sweetalert2.all.js'], 'public/js').version();


