module.exports = {
	publicPath:'/public/vue-app/index/',
	devServer: {
		proxy: {
			'/': {
				target: 'http://dev.touchczy.top',
				 ws: true,
				changeOrigin: true,
				pathRewrite: {}
			}
		}
	}
}
