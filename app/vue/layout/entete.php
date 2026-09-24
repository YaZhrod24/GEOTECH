<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titre) ? $titre : 'Geotech Manager'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    
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
        .sidebar.collapsed .brand-link { display: none !important; } /* Cache le gros logo */
        .sidebar.collapsed .header-sidebar { justify-content: center !important; padding: 0 !important; }
        .sidebar.collapsed .nav-link { justify-content: center; padding: 0.8rem 0; margin: 0.2rem 0.5rem; }
        .sidebar.collapsed .nav-icon { margin-right: 0 !important; font-size: 1.4rem; }
        
        /* Remplacement du Burger par le petit logo */
        .sidebar.collapsed .icon-burger { display: none !important; }
        .sidebar.collapsed .icon-logo-small { display: block !important; }
        
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
        
        <div class="header-sidebar d-flex align-items-center p-3 border-bottom" style="height: 70px;">
            <!-- Bouton (Burger en mode ouvert, logo_l en mode fermé) -->
            <button id="toggleSidebar" class="btn border-0 shadow-none px-2 text-secondary d-flex justify-content-center align-items-center">
                <i class="bi bi-list fs-4 icon-burger"></i>
                <img src="/Ecole/GEOTECH/SITE/MANAGER/public/img/logo_l.svg" height="30" class="icon-logo-small d-none">
            </button>
            
            <!-- Lien vers l'accueil (Gros logo, caché si replié) -->
            <a href="/" class="brand-link text-decoration-none d-flex align-items-center text-geotech fw-bold fs-5 ms-2">
                <img src="/Ecole/GEOTECH/SITE/MANAGER/public/img/logo.svg" height="30" class="me-2">
                Geotech
            </a>
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
                <img src="/Ecole/GEOTECH/SITE/MANAGER/public/img/logo.svg" height="30" class="me-2"> Geotech
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse bg-white p-3 position-absolute top-100 start-0 w-100 shadow-sm z-3" id="mobileMenu">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="/">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/planning">Planning</a></li>
                    <li class="nav-item"><a class="nav-link" href="/interventions">Interventions</a></li>
                    <li class="nav-item"><a class="nav-link" href="/clients">Clients</a></li>
                    <li class="nav-item"><a class="nav-link" href="/equipements">Équipements</a></li>
                    <li class="nav-item"><a class="nav-link" href="/techniciens">Techniciens</a></li>
                    <li class="nav-item border-top mt-2 pt-2"><a class="nav-link text-danger" href="/connexion">Déconnexion</a></li>
                </ul>
            </div>
        </nav>

        <main class="p-4 flex-grow-1">