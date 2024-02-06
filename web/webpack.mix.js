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

mix.js('resources/js/app.js', 'public/js/app.js');
mix.vue({ version: 2, extractVueStyles: true });
mix.sass('resources/sass/app.scss', 'public/css/app.css');
mix.postCss('resources/css/app.css', 'public/css/modules.css', [
    require('tailwindcss'),
]);

const resourcesDir = path.resolve(__dirname, './resources');
const nodeModulesDir = path.resolve(__dirname, './node_modules');

const aliases = {
    'vue$': mix.inProduction() ? 'vue/dist/vue.min.js' : 'vue/dist/vue.js',
    '~components': path.resolve('resources/js/components/'),
    '~common': path.resolve('resources/js/components/common/'),
    '~libraries': path.resolve('resources/js/libraries/'),
    '~models': path.resolve('resources/js/models'),
    '~store': path.resolve('resources/js/store.js'),
    '~': path.resolve('resources/js/'),
};

mix.alias(aliases);
mix.extract(['vue', 'jquery', 'tw-elements']);

if (mix.inProduction()) {
    mix.version();
}

mix.webpackConfig(webpack => {
    return {
        mode: process.env.NODE_ENV === "development" ? "development" : "production",
        devtool: "inline-source-map",
        resolve: {
            extensions: ['.*', '.js', '.jsx', '.css', '.scss', '.json', '.vue'],
            fallback: {'path': nodeModulesDir},
            alias: aliases,
            modules: [
                resourcesDir,
                nodeModulesDir,
            ],
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
        plugins: [new webpack.EnvironmentPlugin({...process.env})],
        devServer: {
            // static: {
            //     publicPath: "/public/",
            //     serveIndex: true,
            //     watch: true
            // },
            compress: true,
            allowedHosts: "all",
            headers: {
                'Access-Control-Allow-Origin': '*'
            },
            host: '0.0.0.0',
            port: process.env.NODE_HOT_PORT,
            hot: true,
            proxy: {
                '*': {
                    target: process.env.MIX_ASSET_HOT_PROXY_URL,
                    // logLevel: 'debug' /*optional*/
                }
            },
        },
    }
});

if (!mix.inProduction()) {
    mix.options({
        hmrOptions: {
            // host: '0.0.0.0',
            host: process.env.NODE_HOT_HOST,
            port: process.env.NODE_HOT_PORT,
        }
    });

    mix.browserSync({
        host: "localhost",
        proxy: 'test-neurony-web',
        port: process.env.NODE_BROWSER_SYNC_PORT,
        open: false,
        files: [
            'app/**/*.php',
            'resources/views/**/*.php',
            'resources/css/**/*.css',
            'resources/sass/**/*.scss',
            'resources/js/**/*.vue',
            'public/js/**/*.js',
            'public/css/**/*.css'
        ],
        cors: true,
    });
}
