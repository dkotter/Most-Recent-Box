module.exports = {
	options: {
		banner: '/*! <%= pkg.title %> - v<%= pkg.version %>\n' +
		' * <%=pkg.homepage %>\n' +
		' * Copyright (c) <%= grunt.template.today("yyyy") %>;' +
		
		' */\n'
	},
	minify: {
		expand: true,

		cwd: 'assets/css/',
		src: ['most-recent-box.css'],

		dest: 'assets/css/',
		ext: '.min.css'
	}
};
