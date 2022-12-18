/** @type {import('tailwindcss').Config} */
module.exports = {
	content: [
		"./assets/**/*.js",
		"./templates/**/*.html.twig",
	],
	theme: {
		extend: {
			colors: {
				// Thème
				'primary': '#FFCC03',
				'secondary': '#386ABB',

				// Couleurs des catégories
				'water': '#4f92d6',
				'dragon': '#2d70c2',
				'electrik': '#f5d147',
				'fight': '#ce426b',
				'bug': '#91c03b',
				'fire': '#f19b53',
				'fly': '#8faade',
				'ghost': '#526bad',
				'grass': '#63bb5a',
				'ground': '#d67744',
				'ice': '#6fc6b9',
				'normal': '#d7d7d7',
				'poison': '#aa6ec8',
				'psy': '#d1636a',
				'rock': '#c5b68c',
			}
		},
	},
	plugins: [
		require('tw-elements/dist/plugin')
	],
	safelist: [{
		pattern: /(bg|text)-(primary|secondary|water|dragon|electrik|fight|bug|fire|fly|ghost|grass|ground|ice|normal|poison|psy|rock)/,
		variants: ['hover', 'group-hover', 'text', 'active']
	}]
}