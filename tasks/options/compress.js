module.exports = {
	main: {
		options: {
			mode: 'zip',
			archive: './release/dkk.<%= pkg.version %>.zip'
		},
		expand: true,
		cwd: 'release/<%= pkg.version %>/',
		src: ['**/*'],
		dest: 'dkk/'
	}
};