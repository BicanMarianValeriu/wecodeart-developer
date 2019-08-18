import gulp from 'gulp';
import pump from 'pump';
import through from 'through2';
import gulpBabel from 'gulp-babel';
import gulpUglify from 'gulp-uglify';
import gulpSourcemaps from 'gulp-sourcemaps';
import browserSync from 'browser-sync';

// Webpack Deps
import vinylNamed from 'vinyl-named';
import webpack from 'webpack';
import webpackStream from 'webpack-stream';

import { srcPath, distPath } from './index';

// This runs the same after any compiler
const afterBundler = (mode) => {
	return [
		gulpSourcemaps.init({ loadMaps: true }),
		through.obj(function (file, enc, cb) {
			const isSourceMap = /\.map$/.test(file.path);
			if (!isSourceMap) this.push(file);
			cb();
		}),
		gulpBabel(),
		...((mode === 'production') ? [gulpUglify()] : []),
		gulpSourcemaps.write('./'),
		// Hashing will be done in WebPack ( for routes we dont do hashing as they are meant for lazyloading with JS )
		...((mode === 'production') ? [gulp.dest(distPath('minified/js'))] : [gulp.dest(distPath('unminified/js'))]),
		browserSync.stream()
	];
};

// Build Scripts Task
const buildScripts = (mode) => (done) => {
	let streamMode;
	if (mode === 'development') streamMode = require('./../webpack/config.development.js');
	else if (mode === 'production') streamMode = require('./../webpack/config.production.js');
	else streamMode = undefined;

	['development', 'production'].includes(mode) ? pump([
		gulp.src(srcPath('js')),
		vinylNamed(),
		webpackStream(streamMode, webpack),
		...afterBundler(mode)
	], done) : undefined;
};

export { buildScripts };