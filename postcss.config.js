module.exports = ({ file, options, env }) => ({
	plugins: {
		'autoprefixer': {},
		'postcss-preset-env': {},
		'cssnano': env === 'production' ? options.cssnano : false
	}
});