class Carousel {

	/** Constructeur
	 * @param element
	 * @param options
	 */
	constructor(element, options = {}) {
		this.element = element
		this.isMobile = false
		this.currentItem = 0
		this.timer = 0
		this.moveCallbacks = []
		this.offset = 0
		this.options = Object.assign({}, {
			slidesToScroll: 1,
			slidesVisible: 2,
			loop: false,
			infinite: true
		}, options)
		const childrens = [].slice.call(element.children)

		// Modification du DOM
		this.root = this.createElement('carousel')
		this.container = this.createElement('carousel-container')
		this.root.setAttribute('tabindex', '0')
		this.root.appendChild(this.container)
		this.element.appendChild(this.root)
		this.items = childrens.map((child) => {
			let item = this.createElement('carousel-items')
			item.appendChild(child)
			return item
		})
		if (this.options.infinite) {
			this.offset = this.options.slidesVisible + this.options.slidesToScroll
			this.items = [
				...this.items.slice(this.items.length - this.offset).map(item => item.cloneNode(true)),
				...this.items,
				...this.items.slice(0, this.offset).map(item => item.cloneNode(true))
			]
			this.goToItem(this.offset, false)
		}
		this.items.forEach(item => this.container.appendChild(item))
		this.setCalcWidth()
		this.setNavigation()
		this.moveCallbacks.forEach(cb => cb(this.currentItem))
		this.setResize()

		// Évenements
		this.start()
		window.addEventListener('resize', this.setResize.bind(this))
		this.root.addEventListener('keyup', e => {
			if (e.key === 'ArrowRight' || e.key === 'Right') {
				this.reset()
				this.next()
			} else if (e.key === 'ArrowLeft' || e.key === 'Left') {
				this.reset()
				this.prev()
			}
		})
		if (this.options.infinite) {
			this.container.addEventListener('transitionend', this.resetInfinite.bind(this))
		}
	}

	/**
	 * Lancement du carousel
	 */
	start () {
		this.timer = window.setInterval(() => {
			this.next()
		}, 3000)
	}

	/**
	 * Remise à zéro du timer
	 */
	reset () {
		clearInterval(this.timer)
		this.start()
	}

	/**
	 * Créé les éléments du carousel
	 * @param {string} className
	 * @returns HTMLElement
	 */
	createElement (className) {
		let div = document.createElement('div')
		div.setAttribute('class', className)

		return div
	}

	/**
	 * Applique les bonnes dimensions aux éléments du carousel
	 */
	setCalcWidth () {
		let ratio = this.items.length / this.slidesVisible
		this.container.style.width = (ratio * 100) + "%"
		this.items.forEach(item => item.style.width = ((100 / this.slidesVisible) / ratio) + "%")
	}

	/**
	 * Créé les boutons de navigation
	 */
	setNavigation () {
		let nextBtn = this.createElement('carousel-next'),
			prevBtn = this.createElement('carousel-prev')

		if (this.items.length !== this.slidesVisible) {
			this.root.appendChild(nextBtn)
			this.root.appendChild(prevBtn)
			nextBtn && nextBtn.addEventListener('click', () => {
				this.reset()
				this.next()
			})
			prevBtn && prevBtn.addEventListener('click', () => {
				this.reset()
				this.prev()
			})
		}

		this.setMove(index => {
			if (index === 0) {
				prevBtn.classList.add('carousel-prev-hidden')
			} else {
				prevBtn.classList.remove('carousel-prev-hidden')
			}
			if (this.items[this.currentItem + this.slidesVisible] === undefined) {
				nextBtn.classList.add('carousel-next-hidden')
			} else {
				nextBtn.classList.remove('carousel-next-hidden')
			}
		})

	}

	/**
	 * Modifie le carousel à un rendu responsive
	 */
	setResize () {
		let mobile = window.innerWidth < 850
		if (mobile !== this.isMobile) {
			this.isMobile = mobile
			this.setCalcWidth()
		}
	}

	setMove (cb) {
		this.moveCallbacks.push(cb)
	}

	/**
	 * Lance le carousel vers la droite
	 */
	next () {
		this.goToItem(this.currentItem + this.slidesToScroll)
	}

	/**
	 * Lance le carousel vers la gauche
	 */
	prev () {
		this.goToItem(this.currentItem - this.slidesToScroll)
	}

	/**
	 * Déplace le carousel vers l'élément cible
	 * @param {number} index
	 * @param {boolean} animation
	 */
	goToItem (index, animation = true) {
		if (index < 0) {
			if (this.options.loop) {
				index = this.items.length - this.slidesVisible
			} else {
				return
			}
		} else if (index >= this.items.length || (this.items[this.currentItem + this.slidesVisible] === undefined && index > this.currentItem)){
			if (this.options.loop) {
				index = 0
			} else {
				return
			}
		}
		let translateX = index * -100 / this.items.length
		if (animation === false) {
			this.container.style.transition = 'none'
		}
		this.container.style.transform = 'translate3d('+translateX+'%, 0, 0)'
		this.container.offsetHeight
		if (animation === false) {
			this.container.style.transition = ''
		}
		this.currentItem = index
		this.moveCallbacks.forEach(cb => cb(index))
	}

	/**
	 * Déplace le container pour donner l'impression d'un slide infini
	 */
	resetInfinite () {
		if (this.currentItem <= this.options.slidesToScroll) {
			this.goToItem(this.currentItem + (this.items.length - 2 * this.offset), false)
		} else if (this.currentItem >= this.items.length - this.offset) {
			this.goToItem(this.currentItem - (this.items.length - 2 * this.offset), false)
		}
	}

	/**
	 * @return {number}
	 */
	get slidesToScroll () {
		return this.isMobile ? 1 : this.options.slidesToScroll
	}

	/**
	 * @return {number}
	 */
	get slidesVisible () {
		return this.isMobile ? 1 : this.options.slidesVisible
	}

}

document.addEventListener('DOMContentLoaded', () => {
	const carousel1 = document.querySelector('#carousel-0'),
		carousel2 = document.querySelector('#carousel-1')

	if (carousel1) {
		new Carousel(carousel1)
		carousel1.classList.remove('opacity-0')
		carousel1.classList.add('opacity-100')
	}

	if (carousel2) {
		new Carousel(carousel2)
		carousel2.classList.remove('opacity-0')
		carousel2.classList.remove('opacity-100')
	}
})

