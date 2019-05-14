import { compileSVG } from './svg';
import { buildStyles } from './styles';
import { buildScriptsWithWebpack } from './scripts';
import { srcPath, distPath, deleteBuild } from './helpers';

export {
	srcPath, 
	distPath, 
	deleteBuild,
	buildScriptsWithWebpack,
	buildStyles,
	compileSVG
};