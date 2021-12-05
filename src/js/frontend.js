// Styles
import './../scss/index.scss';

// Attach Specific Route JS
wecodeart = {
	...wecodeart,
	...{
		routes: {
			...wecodeart.routes,
			themeIsSkin: {
				init: () => console.log('Welcome from your skin JS.'),
			}
		}
	}
};