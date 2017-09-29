// https://github.com/gruntjs/grunt-contrib-uglify
module.exports = {
	options: {
		preserveComments: false,
		sourceMap: true
	},
	dist: {
		options: {
			banner: '/* -- Yoast.js -- */\n'
		},
		src: [
			'js/src/*.js'
		],
		dest: 'js/yoast.js'
	},
	academy: {
		src: [
			'js/src/academy/*.js'
		],
		dest: 'js/academy.min.js'
	}
};
