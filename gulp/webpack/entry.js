const glob = require('glob');

const entryArray = [
	...glob.sync('./src/js/routes/*.js'), 
	'./src/js/wecodeart-bundle.js',
];

const entryObject = entryArray.reduce((acc, item) => {
	const name = item.replace('./src/js/','').replace('.js', ''); 
	acc[name] = item;
	return acc;
}, {});

module.exports = entryObject;