/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

// start Carousel
import './../public/static/scripts/carousel'

const autoHeight = (sidebar, footer) => {
	if (sidebar && footer) {
		sidebar.style.height = window.innerHeight - footer.clientHeight - 110 + 'px'
	}
}

(() => {
	const showSidebar = document.getElementById('showSidebar'),
		sidebar = document.getElementById('sidebar'),
		footer = document.getElementById('footer'),
		overlay = document.getElementById('overlay')

	let tooltipTriggerList = [].slice.call(
		document.querySelectorAll('[data-bs-toggle="tooltip"]')
	)
	tooltipTriggerList.map(function (tooltipTriggerEl) {
		return new Tooltip(tooltipTriggerEl);
	})

	document.addEventListener('click', event => {
		const isClickInside = sidebar && sidebar.contains(event.target),
			isClickBtn = showSidebar && showSidebar.contains(event.target)

		if (!isClickInside && !isClickBtn) {
			sidebar.classList.remove('active')
			overlay.classList.remove('active')
		}
		if (sidebar.classList.contains('active')) {
			overlay.classList.add('active')
		}
	})

	if (showSidebar && sidebar) {
		showSidebar.addEventListener('click', () => {
			if (sidebar.classList.contains('active')) {
				sidebar.classList.remove('active')
			} else {
				sidebar.classList.add('active')
			}
		})
	}

	autoHeight(sidebar, footer)
	window.addEventListener('resize', () => {
		autoHeight(sidebar, footer)
	})
})()