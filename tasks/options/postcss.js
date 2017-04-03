module.exports = {
	dist: {
		options: {
			processors: [
				require('autoprefixer')({browsers: 'last 2 versions'})
			]
		},
		files: { 
			'assets/css/most-recent-box.css': [ 'assets/css/most-recent-box.css' ]
		}
	}
};