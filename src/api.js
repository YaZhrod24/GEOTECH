// En développement, Vite proxyfie directement les routes de l'API.
// La version compilée appelle directement l'API depuis un hébergement statique.
const apiBaseUrl = (
  import.meta.env.DEV
    ? ''
    : (import.meta.env.VITE_API_URL || 'https://192.168.1.12')
).replace(/\/+$/, '')

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
      const contentType = response.headers.get('content-type') || 'type inconnu'
      throw new Error(
        `Le serveur a renvoyé une réponse non JSON (${response.status}, ${contentType}).`,
      )
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

export function getTodayInterventions(token) {
  return apiRequest('/interventions/jour', { token })
}

export function updateInterventionStatus(token, interventionId, updates) {
  return apiRequest(`/interventions/${interventionId}/status`, {
    token,
    method: 'PUT',
    body: updates,
  })
}

export async function chargerMissions() {
  try {
    const token = localStorage.getItem('geotech_access_token')
    const missions = await getTodayInterventions(token)
    localStorage.setItem('missions_geotech', JSON.stringify(missions))
    return missions
  } catch {
    const sauvegarde = localStorage.getItem('missions_geotech')
    return sauvegarde ? JSON.parse(sauvegarde) : []
  }
}
