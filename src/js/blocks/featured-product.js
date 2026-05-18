const config = window.cordycepsFeaturedProduct || {}

export default el => {
  const tabs = el.querySelectorAll('[data-fp-category]')
  const grid = el.querySelector('[data-fp-grid]')
  const wrapper = el.querySelector('[data-fp-grid-wrapper]')
  const loading = el.querySelector('[data-fp-loading]')

  if (!tabs.length || !grid || !wrapper) {
    return
  }

  const setActiveTab = activeButton => {
    tabs.forEach(tab => {
      const isActive = tab === activeButton
      tab.classList.toggle('fp__tab--active', isActive)
      tab.setAttribute('aria-pressed', isActive ? 'true' : 'false')
    })
  }

  const setLoading = isLoading => {
    wrapper.classList.toggle('is-loading', isLoading)

    if (loading) {
      loading.hidden = !isLoading
      loading.setAttribute('aria-hidden', isLoading ? 'false' : 'true')
    }
  }

  const loadCategory = async categoryId => {
    if (!config.ajaxUrl || !config.nonce || !config.action) {
      return
    }

    setLoading(true)

    try {
      const body = new FormData()
      body.append('action', config.action)
      body.append('nonce', config.nonce)
      body.append('category_id', String(categoryId))
      body.append('scope_term_ids', el.getAttribute('data-fp-scope') || '')

      const response = await fetch(config.ajaxUrl, {
        method: 'POST',
        credentials: 'same-origin',
        body
      })

      const payload = await response.json()

      if (!response.ok || !payload.success) {
        throw new Error(payload?.data?.message || 'Request failed')
      }

      const html = payload.data?.html || ''
      grid.innerHTML = html || `<p class="fp__empty">${config.emptyText || ''}</p>`
    } catch (error) {
      grid.innerHTML = `<p class="fp__empty">${config.errorText || ''}</p>`
      console.error('featured-product:', error)
    } finally {
      setLoading(false)
    }
  }

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const categoryId = tab.getAttribute('data-fp-category')

      if (!categoryId || tab.classList.contains('fp__tab--active')) {
        return
      }

      setActiveTab(tab)
      loadCategory(categoryId)
    })
  })

  return {
    displayName: 'featured-product'
  }
}
