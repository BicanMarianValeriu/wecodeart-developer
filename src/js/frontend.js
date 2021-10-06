// Styles
import './../scss/index.scss';

// Attach Specific Route JS
wecodeart = {
	...wecodeart,
	...{
		routes: {
			...wecodeart.routes,
			hasTitleHidden: {
				init: () => console.log('This page has hidden title.'),
			}
		}
	}
};