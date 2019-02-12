'use strict';
const DPLOY = require("dploy");

const settings = {
	scheme: 'sftp',
	host: 'server-address',
	user: 'fpt-username',
	pass: 'ftp-password',
	exclude: ["dploy.js", "dploy-live.js", ".babelrc", ".eslintrc", "gulpfile.babel.js", "package.json", "package-lock.json"], // So these files aren't deployed
	slots: 10,
	path: {
		local: '',
		remote: '/your/remote/path/to/deploy'
	}
};

new DPLOY(settings);
