import gulp from 'gulp';
import pump from 'pump';
import gulpBabel from 'gulp-babel';
import gulpUglify from 'gulp-uglify';
import gulpSourcemaps from 'gulp-sourcemaps';
import browserSync from 'browser-sync';

// Webpack Deps
import through from 'through2';
import vinylNamed from 'vinyl-named';
import webpack from 'webpack';
import webpackStream from 'webpack-stream';

// Browserify Deps
import glob from 'glob'; // Used for webpack but in the config
import babelify from 'babelify';
import browserify from 'browserify';
import vinylBuffer from "vinyl-buffer";
import vinylSource from 'vinyl-source-stream';

import paths from '../paths';
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
		gulp.dest(distPath('js')),
		browserSync.stream()
	];
};

// Build Scripts Task
const buildScriptsWithWebpack = (mode) => (done) => {
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

const buildScriptsWithBrowserify = (mode) => (done) => {
	glob('./src/js/routes/*.js', function (err, files) {
		if (err) done(err);
		files.map(function (entry) {
			let route = browserify({ entries: [entry], debug: true })
				.transform(babelify, { presets: ['@babel/preset-env'] });

			const name = entry.replace('./src/js/', '');

			pump([route.bundle(), vinylSource(name), vinylBuffer(), ...afterBundler(mode)], done);
		});
	});

	// Main Bundle
	let bundle = browserify({ entries: [paths.entry.js.main], debug: true })
		.transform(babelify, { presets: ['@babel/preset-env'] });

	['development', 'production'].includes(mode) ? pump([
		bundle.bundle(),
		vinylSource('wecodeart-bundle.js'),
		vinylBuffer(),
		...afterBundler(mode)
	], done) : undefined;
};

export { buildScriptsWithWebpack, buildScriptsWithBrowserify };