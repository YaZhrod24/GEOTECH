# GeoTech — application technicien

Interface Vue 3 pour l’application terrain GeoTech. L’écran de connexion échange avec l’API, conserve le jeton de session, récupère le compte technicien connecté et affiche son planning du jour.

## Démarrer en local

Depuis un terminal PowerShell, à la racine du dépôt :

```powershell
npm.cmd install
npm.cmd run dev
```

ou

```powershell
npm.cmd run dev -- --host
```

Pour exposer le port sur les cartes réseau de la machine

Ouvrir ensuite l’adresse locale indiquée par Vite dans le terminal, généralement `http://localhost:5173`.
Le mode normal reste en HTTP pour faciliter le développement.

L’URL de l’API est configurée dans `.env` avec `VITE_API_URL`. Laissez cette variable vide pour utiliser
le proxy Vite `/api`, dont la cible est définie par `VITE_API_PROXY_TARGET`. Le proxy fonctionne en mode
développement et en mode preview : le navigateur n’a donc pas besoin de CORS en local. Après un changement
de ces valeurs, redémarrez Vite.

Pour préparer et prévisualiser la version optimisée :

```powershell
npm.cmd run build
npm.cmd run preview
```

Le preview normal est disponible sur `http://localhost:4173`.

Pour tester l’installation PWA depuis une tablette ou un téléphone, utilisez le HTTPS local :

```powershell
npm.cmd run dev:https
```

ou, après le build :

```powershell
npm.cmd run preview:https
```

Les adresses sont alors respectivement `https://<ip-du-pc>:5173` et `https://<ip-du-pc>:4173`.
Le certificat HTTPS local est auto-signé : acceptez l’avertissement du navigateur sur l’appareil.

## Pourquoi Vite ?

Vite est l’outil de développement et de construction du frontend. Pendant le développement, il démarre un serveur local qui recharge rapidement la page quand les fichiers changent. Pour la mise en ligne, `npm.cmd run build` produit les fichiers optimisés dans `dist/`.

Vite n’est ni Vue, ni l’API, ni à lui seul une PWA. Vue sert à construire l’interface; Vite aide à la développer et à la compiler. Le manifeste et le service worker nécessaires à l’installation hors ligne seront ajoutés dans une prochaine étape.

## Fichiers principaux

- `src/App.vue` : connexion et agenda journalier du technicien.
- `src/api.js` : appels HTTP vers l’API (`/login`, `/compte` et `/interventions/jour`).
- `src/style.css` : styles de l’interface et couleurs GeoTech.
- `src/main.js` : démarre Vue et charge les styles.
- `index.html` : document HTML de départ utilisé par Vite.

En production, si le frontend et l’API sont sur des origines différentes, l’API doit autoriser les requêtes CORS `POST` pour `/login` ainsi que les en-têtes `Content-Type` et `Authorization`.
