import gulp from 'gulp'; 
import pump from 'pump'; 
import browserSync from 'browser-sync';
import autoprefixer from 'autoprefixer';
import gulpSASS from 'gulp-sass'; 
import gulpRevAll from 'gulp-rev-all';
import gulpPostCSS from 'gulp-postcss';
import gulpCleanCSS from 'gulp-clean-css';
import gulpSourcemaps from 'gulp-sourcemaps';

import { srcPath, distPath } from './index';

import config from './../config';

// Build Styles Task
const buildStyles = (mode) => (done) => {
	let outputStyle;
	if (mode === 'development') outputStyle = 'nested';
	else if (mode === 'production') outputStyle = 'compressed';
	else outputStyle = undefined;

	const postcssPlugins = [
		autoprefixer()
	];

	['development', 'production'].includes(mode) ? pump([
		gulp.src(srcPath('scss')),
		gulpSourcemaps.init({ loadMaps: true }),
		gulpSASS({ outputStyle }),
		...((mode === 'production') ? [gulpCleanCSS(config.cleanCSS)] : []),
		gulpPostCSS(postcssPlugins),
		gulpSourcemaps.write('./'),
		...((mode === 'production') ? [gulpRevAll.revision()] : []),
		...((mode === 'production') ? [gulp.dest(distPath('minified/css'))] : [gulp.dest(distPath('unminified/css'))]),
		browserSync.stream(),
	], done) : undefined; 
};

export { buildStyles };