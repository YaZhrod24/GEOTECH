import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { VitePWA } from 'vite-plugin-pwa'

export default defineConfig({
  server: {
    proxy: {
      '/api': {
        target: 'http://geotechapi.test',
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/api/, ''),
      },
    },
  },
  plugins: [
    vue(),
    VitePWA({
      registerType: 'autoUpdate',
      workbox: {
        globPatterns: ['**/*.{js,css,html,svg}'] // Met en cache les fichiers vitaux
      },
      manifest: {
        name: 'Geotech',
        short_name: 'Geotech',
        theme_color: '#ffffff',
        icons: [
          {
            src: '/logo.svg', // Ton logo existant[cite: 1]
            sizes: '192x192',
            type: 'image/svg+xml'
          }
        ]
      }
    })
  ]
})