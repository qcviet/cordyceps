import Swiper from 'swiper'
import { Navigation, Autoplay } from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/navigation'

const MOBILE_MQ = '(max-width: 991.98px)'

function measureMaxSlideHeight (sliderEl) {
  const wrapper = sliderEl.querySelector('.swiper-wrapper')
  const slides = [...sliderEl.querySelectorAll('.swiper-slide')].filter(
    slide => !slide.classList.contains('swiper-slide-duplicate')
  )

  if (!wrapper || !slides.length) {
    return 0
  }

  const wrapperBackup = {
    height: wrapper.style.height,
    transform: wrapper.style.transform,
    transition: wrapper.style.transition,
    display: wrapper.style.display,
    flexDirection: wrapper.style.flexDirection
  }

  const slideBackups = slides.map(slide => ({
    height: slide.style.height,
    position: slide.style.position,
    visibility: slide.style.visibility,
    opacity: slide.style.opacity,
    pointerEvents: slide.style.pointerEvents,
    width: slide.style.width
  }))

  wrapper.style.height = 'auto'
  wrapper.style.transform = 'none'
  wrapper.style.transition = 'none'
  wrapper.style.display = 'flex'
  wrapper.style.flexDirection = 'column'

  slides.forEach(slide => {
    slide.style.height = 'auto'
    slide.style.position = 'relative'
    slide.style.visibility = 'hidden'
    slide.style.opacity = '0'
    slide.style.pointerEvents = 'none'
    slide.style.width = '100%'
  })

  let maxHeight = 0
  slides.forEach(slide => {
    const item = slide.querySelector('.hero-slider__item')
    if (item) {
      maxHeight = Math.max(maxHeight, item.getBoundingClientRect().height)
    }
  })

  wrapper.style.height = wrapperBackup.height
  wrapper.style.transform = wrapperBackup.transform
  wrapper.style.transition = wrapperBackup.transition
  wrapper.style.display = wrapperBackup.display
  wrapper.style.flexDirection = wrapperBackup.flexDirection

  slides.forEach((slide, index) => {
    const backup = slideBackups[index]
    slide.style.height = backup.height
    slide.style.position = backup.position
    slide.style.visibility = backup.visibility
    slide.style.opacity = backup.opacity
    slide.style.pointerEvents = backup.pointerEvents
    slide.style.width = backup.width
  })

  return maxHeight
}

export default el => {
  const sliderEl = el.querySelector('.hero-slider__main')
  if (!sliderEl || sliderEl.swiper) {
    return
  }

  const slideCount = sliderEl.querySelectorAll('.swiper-slide').length
  const enableLoop = slideCount > 1

  const swiper = new Swiper(sliderEl, {
    loop: enableLoop,
    speed: 600,
    watchOverflow: true,
    autoHeight: false,
    modules: [Navigation, Autoplay],
    spaceBetween: 0,
    autoplay: enableLoop
      ? {
          delay: 5500,
          disableOnInteraction: false
        }
      : false,
    navigation: {
      nextEl: el.querySelector('.swiper-button-next'),
      prevEl: el.querySelector('.swiper-button-prev')
    }
  })

  let equalizeTimer

  const equalizeMobileSlideHeights = () => {
    const items = sliderEl.querySelectorAll('.hero-slider__item')
    const isMobile = window.matchMedia(MOBILE_MQ).matches

    items.forEach(item => {
      item.style.minHeight = ''
    })
    sliderEl.style.height = ''

    if (!isMobile) {
      swiper.update()
      return
    }

    const maxHeight = measureMaxSlideHeight(sliderEl)
    if (maxHeight > 0) {
      const heightPx = `${Math.ceil(maxHeight)}px`
      items.forEach(item => {
        item.style.minHeight = heightPx
      })
      sliderEl.style.height = heightPx
    }

    swiper.update()
  }

  const scheduleEqualize = () => {
    window.clearTimeout(equalizeTimer)
    equalizeTimer = window.setTimeout(equalizeMobileSlideHeights, 80)
  }

  scheduleEqualize()

  sliderEl.querySelectorAll('img').forEach(img => {
    if (img.complete) {
      return
    }
    img.addEventListener('load', scheduleEqualize, { once: true })
  })

  let resizeTimer
  const onResize = () => {
    window.clearTimeout(resizeTimer)
    resizeTimer = window.setTimeout(scheduleEqualize, 120)
  }
  window.addEventListener('resize', onResize)

  return {
    displayName: 'hero-slider',
    unmount () {
      window.removeEventListener('resize', onResize)
      window.clearTimeout(resizeTimer)
      window.clearTimeout(equalizeTimer)
      swiper.destroy(true, true)
    }
  }
}
