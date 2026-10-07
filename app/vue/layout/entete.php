<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titre) ? $titre : 'Geotech Manager'; ?></title>
    <link rel="stylesheet" href="/CSS/custom.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Titan+One&display=swap" rel="stylesheet">
    
    <style>
        .text-geotech { color: #00bf63 !important; }
        
        .sidebar {
            width: 250px;
            background-color: #ffffff;
            border-right: 1px solid #e9ecef;
            transition: width 0.3s ease;
            overflow-x: hidden;
            z-index: 1040;
        }
        
        /* État replié */
        .sidebar.collapsed { width: 75px; }
        .sidebar.collapsed .nav-text { display: none; }
        .sidebar.collapsed .nav-link { justify-content: center; padding: 0.8rem 0; margin: 0.2rem 0.5rem; }
        .sidebar.collapsed .nav-icon { margin-right: 0 !important; font-size: 1.4rem; }
        
        /* Centrage de l'en-tête et icônes (cachées par défaut) */
        .header-sidebar { padding: 0 !important; }
        .icon-arrow-left, .icon-arrow-right { display: none !important; font-size: 2rem; }
        
        /* --- ÉTAT DÉPLIÉ (par défaut) --- */
        .sidebar:not(.collapsed) .icon-logo-small { display: none !important; }
        .sidebar:not(.collapsed) .icon-logo-big { display: block !important; }
        
        /* Survol DÉPLIÉ : on cache le gros logo et on affiche la flèche GAUCHE */
        .sidebar:not(.collapsed) #toggleSidebar:hover .icon-logo-big { display: none !important; }
        .sidebar:not(.collapsed) #toggleSidebar:hover .icon-arrow-left { display: block !important; color: #00bf63; }

        /* --- ÉTAT REPLIÉ --- */
        .sidebar.collapsed .icon-logo-big { display: none !important; }
        .sidebar.collapsed .icon-logo-small { display: block !important; }
        
        /* Survol REPLIÉ : on cache le petit logo et on affiche la flèche DROITE */
        .sidebar.collapsed #toggleSidebar:hover .icon-logo-small { display: none !important; }
        .sidebar.collapsed #toggleSidebar:hover .icon-arrow-right { display: block !important; color: #00bf63; }
        
        /* Gestion du logo et des flèches au survol */
        .icon-arrow-left, .icon-arrow-right { display: none !important; font-size: 1.4rem; }
        
        /* Au survol (ouvert) : cache le logo, affiche flèche gauche */
        #toggleSidebar:hover .icon-logo-small { display: none !important; }
        #toggleSidebar:hover .icon-arrow-left { display: block !important; color: #00bf63; }
        
        /* Au survol (fermé) : cache flèche gauche, affiche flèche droite */
        .sidebar.collapsed #toggleSidebar:hover .icon-arrow-left { display: none !important; }
        .sidebar.collapsed #toggleSidebar:hover .icon-arrow-right { display: block !important; color: #00bf63; }
        
        /* Liens */
        .sidebar .nav-link {
            color: #495057; font-weight: 500; border-radius: 0.5rem; margin: 0.2rem 1rem; padding: 0.8rem 1rem; white-space: nowrap; transition: 0.2s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: rgba(0, 191, 99, 0.1); color: #00bf63; }
        .sidebar .nav-icon { font-size: 1.25rem; min-width: 30px; text-align: center; transition: 0.2s; }
        
        .sidebar .logout-link { color: #dc3545; }
        .sidebar .logout-link:hover { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('sidebar');
            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                });
            }
        });
    </script>
</head>
<body class="bg-light">

<div class="d-flex min-vh-100">

    <!-- 1. SIDEBAR -->
    <aside class="sidebar d-none d-lg-flex flex-column position-sticky top-0" style="height: 100vh;" id="sidebar">
        
        <div class="header-sidebar d-flex justify-content-center align-items-center border-bottom" style="height: 70px;">
            <!-- Le bouton prend toute la zone (w-100 h-100) pour centrer parfaitement les logos -->
            <button id="toggleSidebar" class="btn border-0 shadow-none w-100 h-100 d-flex justify-content-center align-items-center">
                
                <!-- Gros logo (visible quand déplié) -->
                <img src="/Ecole/GEOTECH/SITE/MANAGER/public/img/logo.svg" class="icon-logo-big img-fluid" style="max-height: 35px;">
                
                <!-- Petit logo (visible quand replié) -->
                <img src="/Ecole/GEOTECH/SITE/MANAGER/public/img/logo_l.svg" class="icon-logo-small" height="30">
                
                <!-- Flèches "caret" au survol -->
                <i class="bi bi-caret-left-fill icon-arrow-left"></i>
                <i class="bi bi-caret-right-fill icon-arrow-right"></i>
            </button>
        </div>
        
        <ul class="nav flex-column mt-3 mb-auto">
            <li class="nav-item"><a class="nav-link d-flex align-items-center" href="/"><i class="bi bi-grid-1x2-fill nav-icon me-3"></i><span class="nav-text">Dashboard</span></a></li>
            <li class="nav-item"><a class="nav-link d-flex align-items-center" href="/planning"><i class="bi bi-calendar-event nav-icon me-3"></i><span class="nav-text">Planning</span></a></li>
            <li class="nav-item"><a class="nav-link d-flex align-items-center" href="/interventions"><i class="bi bi-tools nav-icon me-3"></i><span class="nav-text">Interventions</span></a></li>
            <li class="nav-item"><a class="nav-link d-flex align-items-center" href="/clients"><i class="bi bi-buildings nav-icon me-3"></i><span class="nav-text">Clients</span></a></li>
            <li class="nav-item"><a class="nav-link d-flex align-items-center" href="/equipements"><i class="bi bi-router nav-icon me-3"></i><span class="nav-text">Équipements</span></a></li>
            <li class="nav-item"><a class="nav-link d-flex align-items-center" href="/techniciens"><i class="bi bi-person-badge nav-icon me-3"></i><span class="nav-text">Techniciens</span></a></li>
        </ul>

        <div class="p-3 border-top">
            <a class="nav-link logout-link d-flex align-items-center" href="/connexion">
                <i class="bi bi-box-arrow-right nav-icon me-3"></i><span class="nav-text">Déconnexion</span>
            </a>
        </div>
    </aside>

    <!-- 2. CONTENU PRINCIPAL -->
    <div class="flex-grow-1 d-flex flex-column w-100" style="min-width: 0;">
        
        <nav class="navbar bg-white shadow-sm px-3 border-bottom d-lg-none" style="height: 70px;">
            <a class="navbar-brand text-geotech fw-bold d-flex align-items-center" href="/">
                <img src="/Ecole/GEOTECH/SITE/MANAGER/public/img/logo.svg" height="30" class="me-2 img-fluid" style="max-width: 150px;">
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse bg-white p-3 position-absolute top-100 start-0 w-100 shadow-sm z-3" id="mobileMenu">
                <ul class="navbar-nav w-100">
                    <li class="nav-item">
                        <a class="nav-link fs-5 d-flex justify-content-center align-items-center gap-3 py-2" href="/">
                            <i class="bi bi-grid-1x2-fill"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-5 d-flex justify-content-center align-items-center gap-3 py-2" href="/planning">
                            <i class="bi bi-calendar-event"></i> Planning
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-5 d-flex justify-content-center align-items-center gap-3 py-2" href="/interventions">
                            <i class="bi bi-tools"></i> Interventions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-5 d-flex justify-content-center align-items-center gap-3 py-2" href="/clients">
                            <i class="bi bi-buildings"></i> Clients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-5 d-flex justify-content-center align-items-center gap-3 py-2" href="/equipements">
                            <i class="bi bi-router"></i> Équipements
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-5 d-flex justify-content-center align-items-center gap-3 py-2" href="/techniciens">
                            <i class="bi bi-person-badge"></i> Techniciens
                        </a>
                    </li>
                    <li class="nav-item border-top mt-3 pt-3">
                        <a class="nav-link fs-5 text-danger d-flex justify-content-center align-items-center gap-3" href="/connexion">
                            <i class="bi bi-box-arrow-right"></i> Déconnexion
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="p-4 flex-grow-1">
