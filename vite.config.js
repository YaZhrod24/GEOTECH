import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import { VitePWA } from 'vite-plugin-pwa'

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd(), '')
  const apiTarget = env.VITE_API_PROXY_TARGET || env.VITE_API_URL || 'https://192.168.1.12'

  const apiProxy = {
    '/login': {
      target: apiTarget,
      changeOrigin: true,
      secure: false,
    },
    '/compte': {
      target: apiTarget,
      changeOrigin: true,
      secure: false,
    },
    '/interventions': {
      target: apiTarget,
      changeOrigin: true,
      secure: false,
    },
  }

  return {
    server: {
      host: true,
      proxy: apiProxy,
    },
    preview: {
      host: true,
      proxy: apiProxy,
    },
    plugins: [
      vue(),
      VitePWA({
        registerType: 'autoUpdate',
        devOptions: {
          enabled: true,
          type: 'module'
        },
        workbox: {
          globPatterns: ['**/*.{js,css,html,svg}']
        },
        manifest: {
          name: 'GeoTech Interventions',
          short_name: 'GeoTech',
          description: 'Espace mobile des techniciens GeoTech.',
          lang: 'fr-FR',
          start_url: '/',
          scope: '/',
          display: 'standalone',
          background_color: '#f5f8f6',
          theme_color: '#00bf63',
          icons: [
            {
              src: '/pwa-192.svg',
              sizes: '192x192',
              type: 'image/svg+xml'
            },
            {
              src: '/pwa-512.svg',
              sizes: '512x512',
              type: 'image/svg+xml'
            }
          ]
        }
      })
    ]
  }
})