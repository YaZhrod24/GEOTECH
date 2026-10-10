<script setup>
import { onMounted, ref } from 'vue'
import { getAccount, login } from './api'

const email = ref('')
const password = ref('')
const showPassword = ref(false)
const errorMessage = ref('')
const isSubmitting = ref(false)
const isLoadingSession = ref(true)
const account = ref(null)

const tokenStorageKey = 'geotech_access_token'

async function loadAccount(token) {
  try {
    account.value = await getAccount(token)
  } catch (error) {
    if (error.status === 401 || error.status === 403) {
      localStorage.removeItem(tokenStorageKey)
      account.value = null
      return false
    }

    throw error
  }

  return true
}

onMounted(async () => {
  const savedToken = localStorage.getItem(tokenStorageKey)

  if (savedToken) {
    try {
      await loadAccount(savedToken)
    } catch (error) {
      errorMessage.value = error.message
    }
  }

  isLoadingSession.value = false
})

async function handleSubmit() {
  errorMessage.value = ''
  isSubmitting.value = true

  try {
    const response = await login(email.value, password.value)
    if (typeof response?.token !== 'string' || response.token === '') {
      throw new Error('La réponse de connexion ne contient pas de jeton valide.')
    }

    localStorage.setItem(tokenStorageKey, response.token)
    if (!(await loadAccount(response.token))) {
      throw new Error('La session reçue n’est plus valide. Veuillez vous reconnecter.')
    }

    password.value = ''
  } catch (error) {
    errorMessage.value = error.message
  } finally {
    isSubmitting.value = false
  }
}

function logout() {
  localStorage.removeItem(tokenStorageKey)
  account.value = null
  email.value = ''
  password.value = ''
  errorMessage.value = ''
}
</script>

<template>
  <main class="login-page">
    <section v-if="isLoadingSession" class="login-card loading-card" aria-live="polite">
      <span class="loading-indicator" aria-hidden="true"></span>
      <p>Vérification de votre session…</p>
    </section>

    <section v-else-if="account" class="login-card account-card" aria-labelledby="page-title">
      <a class="brand" href="/" aria-label="GeoTech, accueil">
        <span class="brand-mark" aria-hidden="true">
          <img src="/logo.svg" alt="" />
        </span>
      </a>

      <div class="heading">
        <p class="eyebrow">ESPACE TECHNICIEN</p>
        <h1 id="page-title">Connexion réussie</h1>
        <p class="subtitle">
          Bonjour {{ account.prenom }} {{ account.nom }}, votre compte technicien est prêt.
        </p>
      </div>

      <dl class="account-details">
        <div>
          <dt>Adresse e-mail</dt>
          <dd>{{ account.email }}</dd>
        </div>
        <div v-if="account.tel">
          <dt>Téléphone</dt>
          <dd>{{ account.tel }}</dd>
        </div>
      </dl>

      <p class="next-step-note">
        La connexion fonctionne. Votre planning sera affiché ici dans la prochaine étape.
      </p>
      <button class="submit-button logout-button" type="button" @click="logout">
        Se déconnecter
      </button>
    </section>

    <section v-else class="login-card" aria-labelledby="page-title">
      <a class="brand" href="/" aria-label="GeoTech, accueil">
        <span class="brand-mark" aria-hidden="true">
          <img src="/logo.svg" alt="" />
        </span>
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
