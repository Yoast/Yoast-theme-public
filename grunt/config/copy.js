// https://github.com/gruntjs/grunt-contrib-copy
module.exports = {
	assets: {
		files: [
			{
				expand: true,
				flatten: true,
				cwd: 'bower_components',
				src: [ 'chosen/chosen.min.css', 'chosen/chosen-sprite*.png' ],
				dest: 'css/includes'
			}
		]
	}
};
