// apiBaseUrl récupère l'URL de base de l'API à partir des variables d'environnement. En mode développement, elle est définie sur '/api', sinon elle utilise la variable VITE_API_URL ou une valeur par défaut.
const apiBaseUrl = import.meta.env.DEV
  ? '/api'
  : (import.meta.env.VITE_API_URL || 'http://geotechapi.test').replace(/\/+$/, '')

  // La fonction apiRequest effectue une requête HTTP vers l'API en utilisant fetch. 
  // Elle prend en paramètre le chemin de l'API, un objet d'options contenant le token d'authentification, la méthode HTTP et le corps de la requête. 
  // Elle gère les en-têtes, la sérialisation du corps en JSON, et les erreurs de réseau ou de réponse du serveur.
export async function apiRequest(path, { token, method = 'GET', body } = {}) {
  // Définition des en-têtes de la requête
  const headers = {
    Accept: 'application/json',
  }

  // Si un corps de requête est fourni, on ajoute l'en-tête Content-Type pour indiquer que le corps est en JSON
  if (body !== undefined) {
    headers['Content-Type'] = 'application/json'
  }

  // Si un token est fourni, on ajoute l'en-tête Authorization pour l'authentification
  if (token) {
    headers.Authorization = `Bearer ${token}`
  }

  // On effectue la requête fetch vers l'API avec les paramètres spécifiés. 
  // Si la requête échoue (par exemple, en raison d'une perte de connexion), 
  // on lance une erreur indiquant que le serveur est injoignable.
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

  // On lit la réponse du serveur en tant que texte. Si le texte n'est pas vide, on tente de le parser en JSON.
  const responseText = await response.text()
  let data = null

  if (responseText !== '') {
    try {
      data = JSON.parse(responseText)
    } catch {
      throw new Error('Le serveur a renvoyé une réponse illisible.')
    }
  }

  // Si la réponse du serveur n'est pas OK (statut HTTP 2xx), 
  // on lance une erreur avec le message d'erreur renvoyé par le serveur ou un message générique.
  if (!response.ok) {
    const error = new Error(data?.error || `La requête a échoué (${response.status}).`)
    error.status = response.status
    throw error
  }

  return data
}

// On exporte deux fonctions pour interagir avec l'API : login et getAccount.
export function login(email, password) {
  return apiRequest('/login', {
    method: 'POST',
    body: { email, mdp: password },
  })
}

export function getAccount(token) {
  return apiRequest('/compte', { token })
}

// On exporte une fonction pour récupérer les interventions du jour à partir de l'API.
export function getTodayInterventions(token) {
  return apiRequest('/interventions/jour', { token })
}
