import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { VitePWA } from 'vite-plugin-pwa'

export default defineConfig({
  server: {
    proxy: {
      '/api': {
        target: 'https://172.27.0.55',
        changeOrigin: true,
        secure: false,
        rewrite: (path) => path.replace(/^\/api/, ''),
      },
    },
  },
  plugins: [
    vue(),
    VitePWA({
      registerType: 'autoUpdate',
      devOptions: {
        enabled: true,
        type: 'module' // Option cruciale pour que le Service Worker fonctionne en mode "dev" avec Vite
      },
      workbox: {
        globPatterns: ['**/*.{js,css,html,svg}']
      },
      manifest: {
        name: 'Geotech',
        short_name: 'Geotech',
        theme_color: '#ffffff',
        icons: [
          {
            src: '/logo.svg',
            sizes: '192x192',
            type: 'image/svg+xml'
          }
        ]
      }
    })
  ]
})