// https://github.com/gruntjs/grunt-contrib-watch
module.exports = {
	css: {
		files: ['**/*.scss'],
		tasks: ['sass']
	},
	js: {
		files: ['js/src/*.js'],
		tasks: ['uglify:dist']
	},
	"js-academy": {
		files: ['js/src/academy/*.js'],
		tasks: ['uglify:academy']
	}
};
