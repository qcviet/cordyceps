import Swiper from 'swiper'
import { Navigation, Autoplay } from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/navigation'

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
    autoHeight: true,
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

  let resizeTimer
  const onResize = () => {
    window.clearTimeout(resizeTimer)
    resizeTimer = window.setTimeout(() => {
      swiper.update()
    }, 120)
  }
  window.addEventListener('resize', onResize)

  return {
    displayName: 'hero-slider',
    unmount () {
      window.removeEventListener('resize', onResize)
      window.clearTimeout(resizeTimer)
      swiper.destroy(true, true)
    }
  }
}
