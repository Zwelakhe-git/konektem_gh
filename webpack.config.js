import path from 'path';
import MiniCssExtractPlugin from 'mini-css-extract-plugin';
import HtmlWebpackPlugin from 'html-webpack-plugin';

export default {
    mode: "development",
    //context: path.resolve('public/tmp-build/js'),
    
    plugins: [
        //new HtmlWebpackPlugin({template: './public/templates/index.php'}),
        new MiniCssExtractPlugin({
        filename: "[name].css"
    })],
    devServer: {
        hot: false,
        liveReload: true,
        port: 3000,
        open: true,
        static: {
            directory: path.resolve('public/templates')
        },
        client: {
            overlay: {
                errors: true,
                warnings: false
            },
            logging: 'info',
            progress: true
        },
        devMiddleware: {
            publicPath: '/'
        }
    },
    entry: {
        //"react-main-page": "./src/React/index.jsx",
        'auth_script.ff23fde4b342db00f21f_2': [
            './static/js/auth_page_script.js'
        ],
        'apps.7c1234126e356e74d78b_2': './static/js/album-preview-page.js'
    },
    output: {
        filename: "[name].js",
        path: path.resolve('dist'),
        clean: false
    },
    module: {
        rules: [
            {
                test: /\.scss$/i,
                use: [
                MiniCssExtractPlugin.loader,
                    'css-loader',
                    'sass-loader'
                ]
            },
            {
                test: /\.css$/i,
                use: [MiniCssExtractPlugin.loader, 'css-loader']
            },
            {
                test: /\.(js|jsx|)$/i,
                exclude: '/node_modules/',
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env', '@babel/preset-react']
                    }
                }
            },
            {
                test: /\.(png|jpe?g|gif|svg|webp)$/i,
                type: 'asset',
                parser: {
                    dataUrlCondition: {
                    maxSize: 8 * 1024
                    }
                },
                generator: {
                    filename: 'media/images/[name].[hash][ext]'
                }
            }
        ]
    },
    resolve: {
        extensions: ['.ts', '.tsx', '.js', '.jsx'],
        alias: {
            '@styles': path.resolve('./static/css'),
            '@': path.resolve('./')
        }
    }
}