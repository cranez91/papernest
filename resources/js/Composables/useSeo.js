import { usePage } from '@inertiajs/vue3'

export function useSeo({ title, description, image = '/images/default-og.jpg', url = null }) {
  const siteName = 'Papelería Andy'

  const page = usePage()
  const baseUrl = page.props.appUrl

  const fullTitle = title ? `${title} | ${siteName}` : siteName
  const fullUrl = url || `${baseUrl}${window.location.pathname}`

  return {
    fullTitle,
    description,
    image,
    fullUrl,
    siteName,
  }
}
