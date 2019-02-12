import gulp from 'gulp'; 
import pump from 'pump'; 
import browserSync from 'browser-sync';
import autoprefixer from 'autoprefixer';
import gulpSASS from 'gulp-sass'; 
import gulpPostCSS from 'gulp-postcss';
import gulpCleanCSS from 'gulp-clean-css';
import gulpSourcemaps from 'gulp-sourcemaps';

import { srcPath, distPath } from './index';

import paths from '../paths';
import config from './../config';

// Build Styles Task
const buildStyles = (mode) => (done) => {
	let outputStyle;
	if (mode === 'development') outputStyle = 'nested';
	else if (mode === 'production') outputStyle = 'compressed';
	else outputStyle = undefined;

	const postcssPlugins = [
		autoprefixer(config.autoprefixer)
	];

	['development', 'production'].includes(mode) ? pump([
		gulp.src(paths.entry.scss.main),
		gulpSourcemaps.init({ loadMaps: true }),
		gulpSASS({ outputStyle }),
		...((mode === 'production') ? [gulpCleanCSS(config.cleanCSS)] : []),
		gulpPostCSS(postcssPlugins),
		gulpSourcemaps.write('./'),
		gulp.dest(distPath('css')),
		browserSync.stream(),
	], done) : undefined; 
};

export { buildStyles };