import find from 'lodash/find';

const object = [
	{ 'name': 'Tomislav' },
	{ 'name': 'Bican' },
	{ 'name': 'Klaudio' }
];

let logger = () => {
	var log = find(object, { 'name': 'Bican' });
	console.log(log);
};

export default function logTheConsole() {
	// Great, it works! You should see a console log with corect object now.
	window.onload = () => logger();
};