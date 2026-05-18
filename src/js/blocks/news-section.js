import Swiper from 'swiper'
import { Navigation, Autoplay } from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/navigation'

export default el => {
  const sliderEl = el.querySelector('.js-news-section-swiper')
  if (!sliderEl || sliderEl.swiper) {
    return
  }

  const slideCount = sliderEl.querySelectorAll('.swiper-slide').length
  const enableLoop = slideCount > 3

  const swiper = new Swiper(sliderEl, {
    modules: [Navigation, Autoplay],
    speed: 520,
    watchOverflow: true,
    autoHeight: false,
    grabCursor: true,
    spaceBetween: 16,
    slidesPerView: 1,
    slidesPerGroup: 1,
    loop: enableLoop,
    autoplay: enableLoop
      ? {
          delay: 5000,
          disableOnInteraction: false,
          pauseOnMouseEnter: true
        }
      : false,
    navigation: {
      prevEl: el.querySelector('.js-news-section-prev'),
      nextEl: el.querySelector('.js-news-section-next')
    },
    breakpoints: {
      576: {
        slidesPerView: 2,
        spaceBetween: 20,
        slidesPerGroup: 1
      },
      992: {
        slidesPerView: 3,
        spaceBetween: 24,
        slidesPerGroup: 1
      }
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
    displayName: 'news-section',
    unmount () {
      window.removeEventListener('resize', onResize)
      window.clearTimeout(resizeTimer)
      swiper.destroy(true, true)
    }
  }
}
