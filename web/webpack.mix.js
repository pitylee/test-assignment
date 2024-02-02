
require('mix-env-file');
let mix = require('laravel-mix'),
    path = require('path');
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

mix.env(process.env.ENV_FILE);

mix.setResourceRoot('../');
mix.setPublicPath("public");

mix.alias({
    '@': path.resolve('resources'),
    'ext': path.resolve('node_modules'),
})

mix.js('resources/js/app.js', 'public/js');
mix.vue({ version: 2, extractVueStyles: true });
mix.sass('resources/sass/app.scss', 'public/css');
mix.postCss('resources/css/app.css', 'public/css', [
    require('tailwindcss'),
]);

if (mix.inProduction()) {
    mix.version();
}

if (!mix.inProduction()) {
    // let proxy = process.env.APP_URL.replace(/^(https?:|)\/\//,'');
    mix.options({
        hmrOptions: {
            host: process.env.NODE_HOT_HOST,
            port: process.env.NODE_HOT_PORT,
        }
    });

    mix.webpackConfig({
        mode: "development",
        devtool: "inline-source-map",
        resolve: {
            // extensions: ['.js', '.jsx', '.css', '.scss', '.json', '.vue'],
            fallback: { 'path': path.join(__dirname, '../node_modules') },
            alias: {
                'vue$': 'vue/dist/vue.js',
                '@components': path.resolve(__dirname, '../resources/js/components'),
                '@': path.resolve('resources/js'),
            },
            modules: ['components', 'nodeModules', 'node_modules'],
            descriptionFiles: ["package.json"]
        },
        module: {
            rules: [
                {
                    test: /\.vue$/,
                    loader: 'vue-loader',
                    options: {
                        loaders: {
                            // Customize to your liking
                            js: 'babel-loader',
                            scss: [
                                'style-loader',
                                'css-loader',
                                'sass-loader'
                            ]
                        }
                    }
                },
            ],
        },
        devServer: {
            static: false,
            // static: {
            //     publicPath: "/public/",
            //     serveIndex: true,
            //     watch: true
            // },
            allowedHosts: "all",
            headers: {
                'Access-Control-Allow-Origin': '*'
            },
            host: '0.0.0.0',
            port: 8080,
            hot: true,
            proxy: {
                "*": process.env.MIX_ASSET_URL
            },
        },
    });

    // overriding publicPath as it was using http and causing mixed-content
    mix.override(c => {
        c.output.publicPath = process.env.MIX_ASSET_URL
    });

    mix.browserSync({ 
        host: "localhost", 
        proxy: 'test-neurony-web',
        port: 3000,
        open: false,
        files: [
            'app/**/*.php',
            'resources/views/**/*.php',
            'resources/js/**/*.vue',
            'packages/mixdinternet/frontend/src/**/*.php',
            'public/js/**/*.js',
            'public/css/**/*.css'
        ],
    });
}
