import { compileSVG } from './svg';
import { buildStyles } from './styles';
import { buildScriptsWithWebpack, buildScriptsWithBrowserify } from './scripts';
import { srcPath, distPath, deleteBuild, revisionFiles } from './helpers';

export {
	srcPath, distPath, deleteBuild,
	buildScriptsWithWebpack, 
	buildScriptsWithBrowserify,
	buildStyles,
	compileSVG,
	revisionFiles
};