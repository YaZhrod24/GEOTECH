// En développement, Vite proxyfie directement les routes de l'API.
// La version compilée appelle directement l'API depuis un hébergement statique.
const apiBaseUrl = (
  import.meta.env.DEV
    ? ''
    : (import.meta.env.VITE_API_URL || 'https://172.27.0.57')
).replace(/\/+$/, '')

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

  // Lire le texte permet de produire une erreur utile quand le serveur renvoie une page HTML.
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

export async function chargerMissions() {
  try {
    // Récupération du token depuis le Local Storage
    const token = localStorage.getItem('geotech_access_token');
    
    // Ajout du token dans l'en-tête (Header) de la requête
    const reponse = await fetch('https://172.27.0.57/interventions/jour', {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      }
    }); 
    
    const missions = await reponse.json();

    // Sauvegarde uniquement si la requête a réussi
    if (reponse.ok) {
      localStorage.setItem('missions_geotech', JSON.stringify(missions));
    }
    return missions;
  } catch (erreur) {
    // Lecture de la sauvegarde hors ligne
    const sauvegarde = localStorage.getItem('missions_geotech');
    return sauvegarde ? JSON.parse(sauvegarde) : [];
  }
}