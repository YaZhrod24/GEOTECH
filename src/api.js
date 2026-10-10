const apiBaseUrl = import.meta.env.DEV
  ? '/api'
  : (import.meta.env.VITE_API_URL || 'http://geotechapi.test').replace(/\/+$/, '')

export async function apiRequest(path, { token, method = 'GET', body } = {}) {
  const headers = {
    Accept: 'application/json',
  }

  if (body !== undefined) {
    headers['Content-Type'] = 'application/json'
  }

  if (token) {
    headers.Authorization = `Bearer ${token}`
  }

  let response
  try {
    response = await fetch(`${apiBaseUrl}${path}`, {
      method,
      headers,
      body: body === undefined ? undefined : JSON.stringify(body),
    })
  } catch {
    throw new Error('Impossible de joindre le serveur. Vérifiez votre connexion réseau.')
  }

  const responseText = await response.text()
  let data = null

  if (responseText !== '') {
    try {
      data = JSON.parse(responseText)
    } catch {
      throw new Error('Le serveur a renvoyé une réponse illisible.')
    }
  }

  if (!response.ok) {
    const error = new Error(data?.error || `La requête a échoué (${response.status}).`)
    error.status = response.status
    throw error
  }

  return data
}

export function login(email, password) {
  return apiRequest('/login', {
    method: 'POST',
    body: { email, mdp: password },
  })
}

export function getAccount(token) {
  return apiRequest('/compte', { token })
}
