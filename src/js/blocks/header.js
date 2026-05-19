const SCROLLED_CLASS = 'header--is-scrolled'
const NAV_OPEN_CLASS = 'header--nav-open'
const NAV_DRAWER_OPEN_CLASS = 'header__nav--open'
const OVERLAY_OPEN_CLASS = 'header__overlay--open'
const HTML_LOCK_CLASS = 'is-header-nav-open'
const SUBMENU_OPEN_CLASS = 'header__submenu-open'
const SUBMENU_CLOSE_DELAY_MS = 160
const MQ_DRAWER = '(max-width: 1024px)'

const supportsInert = typeof HTMLElement !== 'undefined' && 'inert' in HTMLElement.prototype

const isVisible = node => {
  if (!(node instanceof HTMLElement)) return false
  const style = window.getComputedStyle(node)
  return (
    style.visibility !== 'hidden' &&
    style.display !== 'none' &&
    node.getClientRects().length > 0
  )
}

const getTrapSequence = (toggleBtn, navEl) => {
  const searchControls = Array.from(
    navEl.querySelectorAll(
      '.header__search-input, .header__search-submit[type="submit"]'
    )
  ).filter(isVisible)
  const links = Array.from(navEl.querySelectorAll('a[href]')).filter(isVisible)
  return [toggleBtn, ...searchControls, ...links].filter(Boolean)
}

export default el => {
  const toggle = el.querySelector('[data-header-menu-toggle]')
  const overlay = el.querySelector('[data-header-overlay]')
  const nav = el.querySelector('#site-header-navigation')

  if (!toggle || !nav) {
    return
  }

  const mq =
    typeof window !== 'undefined' && window.matchMedia
      ? window.matchMedia(MQ_DRAWER)
      : null

  let scrollRaf = 0
  let lastFocus = null

  const isDrawerMode = () => (mq ? mq.matches : window.innerWidth <= 1023)

  const syncScrollLock = () => {
    const shouldLock =
      el.classList.contains(NAV_OPEN_CLASS) && isDrawerMode()
    document.documentElement.classList.toggle(HTML_LOCK_CLASS, shouldLock)
  }

  const syncNavAccessibility = () => {
    const drawer = isDrawerMode()
    const open = el.classList.contains(NAV_OPEN_CLASS)
    if (!drawer) {
      nav.removeAttribute('aria-hidden')
      if (supportsInert) {
        nav.inert = false
      }
      return
    }
    const hidden = !open
    nav.setAttribute('aria-hidden', hidden ? 'true' : 'false')
    if (supportsInert) {
      nav.inert = hidden
    }
  }

  const setOverlayA11y = open => {
    if (!overlay) return
    overlay.setAttribute('aria-hidden', open ? 'false' : 'true')
  }

  const setNavOpen = open => {
    el.classList.toggle(NAV_OPEN_CLASS, open)
    nav.classList.toggle(NAV_DRAWER_OPEN_CLASS, open)
    if (overlay) {
      overlay.classList.toggle(OVERLAY_OPEN_CLASS, open)
    }
    syncScrollLock()
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false')
    const openLabel = toggle.getAttribute('data-label-open') || ''
    const closeLabel = toggle.getAttribute('data-label-close') || ''
    toggle.setAttribute('aria-label', open ? closeLabel : openLabel)
    setOverlayA11y(open)
    syncNavAccessibility()

    if (open && isDrawerMode()) {
      lastFocus = document.activeElement
      const searchInput = nav.querySelector('.header__search-input')
      const firstLink = nav.querySelector('a[href]')
      const target =
        searchInput && typeof searchInput.focus === 'function'
          ? searchInput
          : firstLink
      if (target && typeof target.focus === 'function') {
        window.requestAnimationFrame(() => target.focus())
      }
    } else if (!open && lastFocus && typeof lastFocus.focus === 'function') {
      lastFocus.focus()
      lastFocus = null
    }
  }

  const closeNav = () => setNavOpen(false)

  const onToggleClick = () => {
    if (!isDrawerMode()) return
    setNavOpen(!el.classList.contains(NAV_OPEN_CLASS))
  }

  const onOverlayClick = () => closeNav()

  const onNavClick = e => {
    const a = e.target.closest && e.target.closest('a[href]')
    if (!a || !isDrawerMode() || !el.classList.contains(NAV_OPEN_CLASS)) return
    closeNav()
  }

  const onDocumentKeydown = e => {
    if (e.key === 'Escape' && el.classList.contains(NAV_OPEN_CLASS)) {
      closeNav()
      return
    }
    if (e.key !== 'Tab' || !isDrawerMode() || !el.classList.contains(NAV_OPEN_CLASS)) {
      return
    }
    const seq = getTrapSequence(toggle, nav).filter(n => {
      if (n === toggle) return true
      return isVisible(n)
    })
    if (seq.length === 0) return
    const active = document.activeElement
    const i = seq.indexOf(active)
    if (e.shiftKey) {
      if (i <= 0) {
        e.preventDefault()
        seq[seq.length - 1].focus()
      }
    } else if (i === seq.length - 1 || i === -1) {
      e.preventDefault()
      seq[0].focus()
    }
  }

  const onScroll = () => {
    if (scrollRaf) return
    scrollRaf = window.requestAnimationFrame(() => {
      scrollRaf = 0
      const y = window.scrollY || document.documentElement.scrollTop
      el.classList.toggle(SCROLLED_CLASS, y > 10)
      syncScrollLock()
    })
  }

  const parentItems = () =>
    [...nav.querySelectorAll('.header__nav-list > .menu-item-has-children')]

  const submenuCloseTimers = new WeakMap()

  const clearSubmenuCloseTimer = item => {
    const timer = submenuCloseTimers.get(item)
    if (timer) {
      window.clearTimeout(timer)
      submenuCloseTimers.delete(item)
    }
  }

  const closeDesktopSubmenus = exceptItem => {
    parentItems().forEach(item => {
      clearSubmenuCloseTimer(item)
      if (exceptItem && item === exceptItem) {
        return
      }
      item.classList.remove(SUBMENU_OPEN_CLASS)
      const link = item.querySelector(':scope > a')
      link?.setAttribute('aria-expanded', 'false')
    })
  }

  const scheduleCloseDesktopSubmenu = item => {
    clearSubmenuCloseTimer(item)
    submenuCloseTimers.set(
      item,
      window.setTimeout(() => {
        submenuCloseTimers.delete(item)
        item.classList.remove(SUBMENU_OPEN_CLASS)
        item.querySelector(':scope > a')?.setAttribute('aria-expanded', 'false')
      }, SUBMENU_CLOSE_DELAY_MS)
    )
  }

  const openDesktopSubmenu = item => {
    const link = item.querySelector(':scope > a')
    clearSubmenuCloseTimer(item)
    closeDesktopSubmenus(item)
    item.classList.add(SUBMENU_OPEN_CLASS)
    link?.setAttribute('aria-expanded', 'true')
  }

  const bindDesktopSubmenus = () => {
    parentItems().forEach(item => {
      const link = item.querySelector(':scope > a')
      const submenu = item.querySelector(':scope > .sub-menu')

      if (!link || !submenu) {
        return
      }

      link.setAttribute('aria-haspopup', 'true')
      link.setAttribute('aria-expanded', 'false')

      const onEnter = () => {
        if (isDrawerMode()) {
          return
        }
        openDesktopSubmenu(item)
      }

      const onLeave = event => {
        if (isDrawerMode()) {
          return
        }
        if (event.relatedTarget instanceof Node && item.contains(event.relatedTarget)) {
          return
        }
        scheduleCloseDesktopSubmenu(item)
      }

      item.addEventListener('mouseenter', onEnter)
      item.addEventListener('mouseleave', onLeave)
      submenu.addEventListener('mouseenter', onEnter)
      submenu.addEventListener('mouseleave', onLeave)

      link.addEventListener('click', event => {
        if (isDrawerMode()) {
          return
        }

        if (!item.classList.contains(SUBMENU_OPEN_CLASS)) {
          event.preventDefault()
          openDesktopSubmenu(item)
        }
      })
    })

    document.addEventListener('click', event => {
      if (isDrawerMode()) {
        return
      }
      if (
        event.target instanceof Node &&
        event.target.closest('.header__nav-list > .menu-item-has-children')
      ) {
        return
      }
      closeDesktopSubmenus()
    })
  }

  bindDesktopSubmenus()

  toggle.addEventListener('click', onToggleClick)
  overlay && overlay.addEventListener('click', onOverlayClick)
  nav.addEventListener('click', onNavClick)
  document.addEventListener('keydown', onDocumentKeydown)
  window.addEventListener('scroll', onScroll, { passive: true })

  const onMqChange = () => {
    if (!isDrawerMode()) {
      closeNav()
    } else {
      closeDesktopSubmenus()
    }
    syncScrollLock()
    syncNavAccessibility()
  }

  if (mq && mq.addEventListener) {
    mq.addEventListener('change', onMqChange)
  } else if (mq && mq.addListener) {
    mq.addListener(onMqChange)
  }

  onScroll()
  syncNavAccessibility()

  return {
    displayName: 'header',
    unmount () {
      if (scrollRaf) cancelAnimationFrame(scrollRaf)
      toggle.removeEventListener('click', onToggleClick)
      overlay && overlay.removeEventListener('click', onOverlayClick)
      nav.removeEventListener('click', onNavClick)
      document.removeEventListener('keydown', onDocumentKeydown)
      window.removeEventListener('scroll', onScroll)
      if (mq && mq.removeEventListener) {
        mq.removeEventListener('change', onMqChange)
      } else if (mq && mq.removeListener) {
        mq.removeListener(onMqChange)
      }
      document.documentElement.classList.remove(HTML_LOCK_CLASS)
      el.classList.remove(NAV_OPEN_CLASS, SCROLLED_CLASS)
      nav.classList.remove(NAV_DRAWER_OPEN_CLASS)
      overlay && overlay.classList.remove(OVERLAY_OPEN_CLASS)
      nav.removeAttribute('aria-hidden')
      if (supportsInert) {
        nav.inert = false
      }
    }
  }
}
