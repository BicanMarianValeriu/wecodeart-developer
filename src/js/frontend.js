// Styles
import './../scss/index.scss';

// Scripts
(function (wecodeart) {
	wecodeart.routes = {
		...wecodeart.routes,
		// See the body class theme-is-skin (camelCase in javascript)
		themeIsSkin: {
			init: () => console.log('Welcome from your skin JS!'),
		}
	};
}).apply(this, [window.wecodeart]);