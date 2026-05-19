export default el => {
  const shareMoreBlocks = el.querySelectorAll('[data-share-more]')
  const feedback = el.querySelector('[data-share-feedback]')

  if (!shareMoreBlocks.length) {
    return
  }

  const unsupportedMessage =
    el.getAttribute('data-share-unsupported-text') ||
    'Chức năng này cần mở trên điện thoại (Chrome/Safari) qua HTTPS.'
  const copiedMessage =
    el.getAttribute('data-share-copied-text') || 'Đã sao chép liên kết!'

  const showFeedback = message => {
    if (!feedback || !message) {
      return
    }

    feedback.textContent = message
    feedback.hidden = false

    window.setTimeout(() => {
      feedback.hidden = true
    }, 2800)
  }

  const copyToClipboard = async text => {
    if (navigator.clipboard && window.isSecureContext) {
      await navigator.clipboard.writeText(text)
      return
    }

    const textarea = document.createElement('textarea')
    textarea.value = text
    textarea.setAttribute('readonly', '')
    textarea.style.position = 'absolute'
    textarea.style.left = '-9999px'
    document.body.appendChild(textarea)
    textarea.select()
    document.execCommand('copy')
    document.body.removeChild(textarea)
  }

  const getSharePayload = block => {
    const url =
      block.getAttribute('data-share-url') ||
      el.getAttribute('data-share-url') ||
      window.location.href
    const title =
      block.getAttribute('data-share-title') ||
      el.getAttribute('data-share-title') ||
      document.title
    const text =
      block.getAttribute('data-share-text') ||
      el.getAttribute('data-share-text') ||
      ''

    const payload = { url }

    if (title) {
      payload.title = title
    }

    if (text && text !== title) {
      payload.text = text
    }

    return payload
  }

  const canUseNativeShare = data => {
    if (typeof navigator.share !== 'function') {
      return false
    }

    if (typeof navigator.canShare === 'function') {
      return navigator.canShare(data)
    }

    return true
  }

  const resolveSharePayload = data => {
    if (canUseNativeShare(data)) {
      return data
    }

    const urlOnly = { url: data.url }

    if (canUseNativeShare(urlOnly)) {
      return urlOnly
    }

    if (data.title) {
      const titleAndUrl = { url: data.url, title: data.title }

      if (canUseNativeShare(titleAndUrl)) {
        return titleAndUrl
      }
    }

    return null
  }

  const openNativeShareSheet = async block => {
    const payload = resolveSharePayload(getSharePayload(block))

    if (!payload) {
      showFeedback(unsupportedMessage)
      return
    }

    await navigator.share(payload)
  }

  const controllers = []

  shareMoreBlocks.forEach(block => {
    const trigger = block.querySelector('[data-share-more-trigger]')
    const menu = block.querySelector('[data-share-menu]')
    const nativeBtn = block.querySelector('[data-native-share]')
    const copyBtn = block.querySelector('[data-copy-link]')

    if (!trigger || !menu) {
      return
    }

    const closeMenu = () => {
      menu.hidden = true
      trigger.setAttribute('aria-expanded', 'false')
    }

    const openMenu = () => {
      menu.hidden = false
      trigger.setAttribute('aria-expanded', 'true')
    }

    const toggleMenu = () => {
      if (menu.hidden) {
        openMenu()
      } else {
        closeMenu()
      }
    }

    trigger.addEventListener('click', event => {
      event.preventDefault()
      event.stopPropagation()
      toggleMenu()
    })

    if (nativeBtn) {
      nativeBtn.addEventListener('click', event => {
        event.preventDefault()
        closeMenu()

        if (typeof navigator.share !== 'function') {
          showFeedback(unsupportedMessage)
          return
        }

        openNativeShareSheet(block).catch(error => {
          if (error && error.name === 'AbortError') {
            return
          }

          showFeedback(unsupportedMessage)
          console.error('product-single: native share failed', error)
        })
      })
    }

    if (copyBtn) {
      copyBtn.addEventListener('click', async event => {
        event.preventDefault()
        closeMenu()

        const url =
          block.getAttribute('data-share-url') ||
          el.getAttribute('data-share-url') ||
          window.location.href

        try {
          await copyToClipboard(url)
          showFeedback(copiedMessage)
        } catch (error) {
          console.error('product-single: copy link failed', error)
        }
      })
    }

    menu.querySelectorAll('a.product-hero__share-menu-item').forEach(link => {
      link.addEventListener('click', () => {
        closeMenu()
      })
    })

    controllers.push({ block, trigger, menu, closeMenu })
  })

  const onDocumentClick = event => {
    controllers.forEach(({ block, closeMenu }) => {
      if (!block.contains(event.target)) {
        closeMenu()
      }
    })
  }

  const onDocumentKeydown = event => {
    if (event.key === 'Escape') {
      controllers.forEach(({ closeMenu }) => closeMenu())
    }
  }

  document.addEventListener('click', onDocumentClick)
  document.addEventListener('keydown', onDocumentKeydown)

  return {
    displayName: 'product-single',
    unmount () {
      document.removeEventListener('click', onDocumentClick)
      document.removeEventListener('keydown', onDocumentKeydown)
    }
  }
}
