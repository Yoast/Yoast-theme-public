// https://github.com/blazersix/grunt-wp-i18n
module.exports = {
	plugin: {
		options: {
			domainPath: 'languages',
			potFilename: 'yoastcom.pot',
			potHeaders: {
				poedit: true,
				'report-msgid-bugs-to': 'translate@yoast.com',
				'language-team': 'Yoast Translate',
				'last-translator': 'Joost de Valk'
			},
			type: 'wp-theme'
		}
	}
};
