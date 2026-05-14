import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'
import './postcss/frontend.css'

import init from 'lib/init-blocks'

document.addEventListener('DOMContentLoaded', () => {
	document.documentElement.classList.add('is-app-loading')

	window.addEventListener('error', (event) => {
		console.error('[cordyceps] Unhandled runtime error:', event.error || event.message)
		document.documentElement.classList.remove('is-app-loading')
		document.documentElement.classList.add('is-app-error')
	})

	window.addEventListener('unhandledrejection', (event) => {
		console.error('[cordyceps] Unhandled promise rejection:', event.reason)
		document.documentElement.classList.remove('is-app-loading')
		document.documentElement.classList.add('is-app-error')
	})

	try {
		init({
			block: 'blocks'
		}).mount()

		document.documentElement.classList.remove('is-app-loading')
	} catch (error) {
		console.error('[cordyceps] Failed to initialize frontend blocks:', error)
		document.documentElement.classList.remove('is-app-loading')
		document.documentElement.classList.add('is-app-error')
	}
})
