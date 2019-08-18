//import glob from 'glob';

export default {
	theme: './',   
	
	entry: {
		css: './assets/css/*.css',
		scss: {
			watch: './src/scss/**/*',
			main: './src/scss/style.scss',
			customizer: './src/scss/customizer/**/*.scss'
		},
		js: {
			watch: './src/js/**/*',
			main: './src/js/wecodeart-bundle.js',
			customizer: './src/js/customizer/**/*.js'
		}
	},

	output: {
		build: './assets/minified/',
	}
};