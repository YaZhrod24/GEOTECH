import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import { VitePWA } from 'vite-plugin-pwa'
import basicSsl from '@vitejs/plugin-basic-ssl'

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd(), '')
  const apiTarget = env.VITE_API_PROXY_TARGET || env.VITE_API_URL || 'https://172.27.0.55'
  const useHttps = env.VITE_HTTPS === 'true'

  const apiProxy = {
    '/api': {
      target: apiTarget,
      changeOrigin: true,
      secure: false,
      rewrite: (path) => path.replace(/^\/api/, ''),
    },
  }

  return {
    server: {
      host: true,
      https: useHttps,
      proxy: apiProxy,
    },
    preview: {
      host: true,
      https: useHttps,
      proxy: apiProxy,
    },
    plugins: [
      ...(useHttps ? [basicSsl()] : []),
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
              src: '/logo.svg',
              sizes: 'any',
              type: 'image/svg+xml'
            }
          ]
        }
      })
    ]
  }
})