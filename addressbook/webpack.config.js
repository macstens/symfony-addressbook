
var Encore = require('@symfony/webpack-encore');

Encore
// the project directory where all compiled assets will be stored
    .setOutputPath('web/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .addEntry('app', './app/Resources/public/js/app.js')
    // will output as web/build/main.css
    .addStyleEntry('main', './app/Resources/public/sass/main.scss')
    .enableSassLoader()
    // allow legacy applications to use $/jQuery as a global variable
    .autoProvidejQuery()
    .enableSourceMaps(true)
    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()
    // create hashed filenames (e.g. app.abc123.css)
    .enableVersioning()
    .enableSingleRuntimeChunk()
    //.disableSingleRuntimeChunk()
;

module.exports = {
    module: {
        loaders: [
            // the url-loader uses DataUrls.
            // the file-loader emits files.
            { test: /\.woff(2)?(\?v=[0-9]\.[0-9]\.[0-9])?$/, loader: "url-loader?limit=10000&mimetype=application/font-woff" },
            { test: /\.(ttf|eot|svg)(\?v=[0-9]\.[0-9]\.[0-9])?$/, loader: "file-loader" }
        ],
        rules: [
            {
            test: /\.css$/,
            use: ['style-loader', 'css-loader']
            }
        ]
    },
};

// export the final configuration
module.exports = Encore.getWebpackConfig();

