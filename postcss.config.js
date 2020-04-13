module.exports = ({file, options, env}) => ({
	parser: false,
	map: env !== 'production',
	plugins: {
		'autoprefixer': {},
		'postcss-preset-env': {},
		'cssnano': env === 'production' ? {} : false
	}
});