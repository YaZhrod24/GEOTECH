<script setup>
import { onMounted, ref } from 'vue'
// Récupération des fonctions d'API pour la connexion et la récupération du compte depuis le fichier api.js
import { getAccount, getTodayInterventions, login } from './api.js'

// variables de gestion du compte et de la session
const email = ref('')
const password = ref('')
const showPassword = ref(false)
const errorMessage = ref('')
const isSubmitting = ref(false)
const isLoadingSession = ref(true)
const account = ref(null)

// variables de gestion du planning et des interventions
const interventions = ref([])
const isLoadingPlanning = ref(false)
const planningError = ref('')
const sessionToken = ref('')

// nom du token dans le localStorage pour la gestion de la session
const tokenStorageKey = 'geotech_access_token'
const accountStorageKey = 'account_geotech'
const missionsStorageKey = 'missions_geotech'

function readStoredJson(key) {
  const storedValue = localStorage.getItem(key)
  if (!storedValue) {
    return null
  }

  try {
    return JSON.parse(storedValue)
  } catch {
    localStorage.removeItem(key)
    return null
  }
}

// Fonction pour charger les informations du compte à partir du token
async function loadAccount(token) {
  try {
    account.value = await getAccount(token)
    // Succès : on sauvegarde le profil localement
    localStorage.setItem(accountStorageKey, JSON.stringify(account.value))
  } catch (error) {
    if (error.status === 401 || error.status === 403) {
      localStorage.removeItem(tokenStorageKey)
      account.value = null
      return false
    }
    
    // Échec (hors ligne) : on lit le profil sauvegardé
    const sauvegardeCompte = readStoredJson(accountStorageKey)
    if (sauvegardeCompte) {
      account.value = sauvegardeCompte
      return true // On force la réussite pour passer à l'étape des missions
    }
    throw error
  }
  return true
}

async function loadPlanning(token) {
  planningError.value = ''
  isLoadingPlanning.value = true

  try {
    const response = await getTodayInterventions(token)
    interventions.value = response
    localStorage.setItem(missionsStorageKey, JSON.stringify(response))
  } catch (error) {
    if (error.status === 401 || error.status === 403) {
      localStorage.removeItem(tokenStorageKey)
      account.value = null
      sessionToken.value = ''
      interventions.value = []
      planningError.value = 'Votre session a expiré. Veuillez vous reconnecter.'
    } else {
      // Lecture de la sauvegarde hors ligne
      const sauvegarde = readStoredJson(missionsStorageKey)
      if (sauvegarde) {
        interventions.value = sauvegarde
        planningError.value = '' // IMPORTANT : On vide l'erreur pour débloquer l'affichage
      } else {
        planningError.value = error.message
      }
    }
  } finally {
    isLoadingPlanning.value = false
  }
}

// Fonction pour démarrer une session en chargeant le compte et le planning à partir du token
async function startSession(token) {
  // On tente de charger les informations du compte avec le token fourni.
  if (!(await loadAccount(token))) {
    return false
  }

  sessionToken.value = token
  await loadPlanning(token)
  return true
}

onMounted(async () => {
  const savedToken = localStorage.getItem(tokenStorageKey)
  const sauvegardeCompte = readStoredJson(accountStorageKey)
  const sauvegardeMissions = readStoredJson(missionsStorageKey)

  if (savedToken) {
    sessionToken.value = savedToken
    if (sauvegardeCompte) {
      account.value = sauvegardeCompte
    }
    if (Array.isArray(sauvegardeMissions)) {
      interventions.value = sauvegardeMissions
    }

    if (!account.value) {
      try {
        await startSession(savedToken)
      } catch (error) {
        errorMessage.value = error.message
      }
    } else {
      isLoadingSession.value = false
      startSession(savedToken).catch((error) => {
        if (!account.value) {
          errorMessage.value = error.message
        }
      })
    }
  }

  isLoadingSession.value = false
})

// Gestion de la soumission du formulaire de connexion
async function handleSubmit() {
// Réinitialisation des messages d'erreur et de l'état de soumission
  errorMessage.value = ''
  isSubmitting.value = true

  try {
    // Appel de la fonction login du fichier api.js avec l'email et le mot de passe
    const response = await login(email.value, password.value)
    // Vérification que la réponse contient un jeton valide
    if (typeof response?.token !== 'string' || response.token === '') {
      throw new Error('La réponse de connexion ne contient pas de jeton valide.')
    }

    // Stockage du jeton dans le localStorage pour les futures requêtes
    localStorage.setItem(tokenStorageKey, response.token)
    if (!(await startSession(response.token))) {
      // Si le compte n'a pas pu être chargé, on supprime le jeton et on affiche un message d'erreur
      localStorage.removeItem(tokenStorageKey)
      throw new Error('La session reçue n’est plus valide. Veuillez vous reconnecter.')
    }

    password.value = ''
  } catch (error) {
    errorMessage.value = error.message
  } finally {
    // Réinitialisation de l'état de soumission du formulaire
    isSubmitting.value = false
  }
}

function logout() {
  localStorage.removeItem(tokenStorageKey)
  account.value = null
  email.value = ''
  password.value = ''
  errorMessage.value = ''
  planningError.value = ''
  interventions.value = []
  sessionToken.value = ''
}

// Fonction pour formater la date et l'heure d'une intervention pour l'affichage
function formatPlanningDate(dateValue) {
  if (!dateValue) {
    return 'Heure non renseignée'
  }

  // On remplace l'espace entre la date et l'heure par un "T" pour créer un format ISO 8601, 
  // puis on ajoute "Z" pour indiquer que c'est en UTC.
  const date = new Date(`${dateValue.replace(' ', 'T')}Z`)
  if (Number.isNaN(date.getTime())) {
    return 'Heure non renseignée'
  }

  // On utilise Intl.DateTimeFormat pour formater la date en heure et minute selon le fuseau horaire de Paris
  return new Intl.DateTimeFormat('fr-FR', {
    hour: '2-digit',
    minute: '2-digit',
    timeZone: 'Europe/Paris',
  }).format(date)
}

// Fonction pour formater l'adresse d'une intervention pour l'affichage
function formatAddress(intervention) {
  // On filtre les valeurs nulles ou vides et on joint l'adresse et la ville avec une virgule
  return [intervention.adresse, intervention.ville].filter(Boolean).join(', ')
}

function statusLabel(status) {
  return {
    OUVERTE: 'En attente',
    EN_COURS: 'En cours',
    CLOTUREE: 'Clôturée',
  }[status] || status || 'Statut inconnu'
}

</script>

<template>
  <!-- ------------------------------ Chargement de la session ------------------------------ -->
  <main class="login-page">
    <section v-if="isLoadingSession" class="login-card loading-card" aria-live="polite">
      <span class="loading-indicator" aria-hidden="true"></span>
      <p>Vérification de votre session…</p>
    </section>

    <!-- ------------------------------ Page de planning si l'utilisateur est connecté ------------------------------ -->
    <section v-else-if="account" class="planning-page" aria-labelledby="planning-title">
      <header class="planning-header">
        <div class="brand">
            <img src="/logo.svg" alt="" />
        </div>
        <button class="text-button" type="button" @click="logout">Déconnexion</button>
      </header>

      <div class="planning-intro">
        <p class="eyebrow">ESPACE TECHNICIEN</p>
        <h1 id="planning-title">Bonjour {{ account.prenom }}</h1>
        <p class="subtitle">Voici vos interventions prévues aujourd’hui.</p>
      </div>

      <div class="planning-toolbar">
        <strong>
          {{ new Intl.DateTimeFormat('fr-FR', { dateStyle: 'full' }).format(new Date()) }}
        </strong>
        <button
          class="refresh-button"
          type="button"
          :disabled="isLoadingPlanning"
          @click="loadPlanning(sessionToken)"
        >
          {{ isLoadingPlanning ? 'Actualisation…' : 'Actualiser' }}
        </button>
      </div>

      <div v-if="isLoadingPlanning && interventions.length === 0" class="planning-state" aria-live="polite">
        <span class="loading-indicator" aria-hidden="true"></span>
        <span>Chargement de votre planning…</span>
      </div>
      <p v-else-if="planningError" class="form-message error-message" role="alert">
        {{ planningError }}
      </p>
      <div v-else-if="interventions.length === 0" class="planning-state empty-state">
        <strong>Aucune intervention aujourd’hui</strong>
        <span>Votre journée est libre pour le moment.</span>
      </div>
      <ol v-else class="intervention-list">
        <li v-for="intervention in interventions" :key="intervention.id_intervention" class="intervention-item">
          <time class="intervention-time">
            {{ formatPlanningDate(intervention.date_intervention) }}
          </time>
          <article class="intervention-card">
            <div class="intervention-card-header">
              <h2>{{ intervention.client_nom || 'Client non renseigné' }}</h2>
              <span class="status-badge" :class="`status-${intervention.statut?.toLowerCase()}`">
                {{ statusLabel(intervention.statut) }}
              </span>
            </div>
            <p v-if="formatAddress(intervention)" class="intervention-location">
              {{ formatAddress(intervention) }}
            </p>
            <dl class="intervention-details">
              <div v-if="intervention.equipement_nom">
                <dt>Équipement</dt>
                <dd>{{ intervention.equipement_nom }}</dd>
              </div>
              <div v-if="intervention.equipement_type">
                <dt>Type</dt>
                <dd>{{ intervention.equipement_type }}</dd>
              </div>
              <div v-if="intervention.desc_panne">
                <dt>Motif</dt>
                <dd>{{ intervention.desc_panne }}</dd>
              </div>
            </dl>
          </article>
        </li>
      </ol>
    </section>

    <!-- ------------------------------ Page de connexion si aucune des conditions n'est remplie ------------------------------ -->
    <section v-else class="login-card" aria-labelledby="page-title">
      <a class="brand login-brand" href="/" aria-label="GeoTech, accueil">
          <img src="/logo.svg" alt="" />
      </a>

      <div class="heading">
        <p class="eyebrow">ESPACE TECHNICIEN</p>
        <h1 id="page-title">Bienvenue</h1>
        <p class="subtitle">Connectez-vous pour accéder à vos interventions.</p>
      </div>

      <form class="login-form" @submit.prevent="handleSubmit">
        <label for="email">Adresse e-mail</label>
        <input
          id="email"
          v-model.trim="email"
          name="email"
          type="email"
          autocomplete="username"
          placeholder="nom@entreprise.fr"
          required
        />

        <div class="password-label">
          <label for="password">Mot de passe</label>
        </div>
        <div class="password-field">
          <input
            id="password"
            v-model="password"
            name="password"
            :type="showPassword ? 'text' : 'password'"
            autocomplete="current-password"
            placeholder="Votre mot de passe"
            required
          />
          <button
            class="password-toggle"
            type="button"
            :aria-pressed="showPassword"
            :aria-label="showPassword ? 'Masquer le mot de passe' : 'Afficher le mot de passe'"
            @click="showPassword = !showPassword"
          >
            {{ showPassword ? 'Masquer' : 'Afficher' }}
          </button>
        </div>

        <button class="submit-button" type="submit" :disabled="isSubmitting">
          <span v-if="isSubmitting" class="loading-indicator button-spinner" aria-hidden="true"></span>
          {{ isSubmitting ? 'Connexion en cours…' : 'Se connecter' }}
          <span v-if="!isSubmitting" aria-hidden="true">→</span>
        </button>
        <p v-if="errorMessage" class="form-message error-message" role="alert">
          {{ errorMessage }}
        </p>
      </form>

      <p class="security-note">
        <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
          <path
            d="M10 2.5 16 5v4.6c0 3.7-2.5 6.3-6 7.9-3.5-1.6-6-4.2-6-7.9V5l6-2.5Z"
          />
          <path d="m7.5 9.8 1.7 1.7 3.5-3.7" />
        </svg>
        Accès réservé aux techniciens GeoTech
      </p>
    </section>

    <footer class="page-footer">
      <span>GeoTech Interventions</span>
      <span>Application terrain</span>
    </footer>
  </main>
</template>
