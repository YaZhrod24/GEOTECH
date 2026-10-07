<?php
// Inclusion de l'en-tête (header)
require_once Racine . '/../app/vue/layout/entete.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <img src="/Ecole/GEOTECH/SITE/MANAGER/public/img/logo.svg" alt="Logo" height="60" class="mb-3">
                        <h1 class="fw-bold">Conditions Générales d'Utilisation</h1>
                        <p class="text-muted">Application MANAGER</p>
                    </div>

                    <div class="text-justify">
                        <h4 class="fw-bold text-primary mt-4">1. Objet et champ d'application</h4>
                        <p>Les présentes Conditions Générales d'Utilisation (ci-après "CGU") ont pour objet de définir les modalités et conditions d'accès et d'utilisation de l'application web "MANAGER". Cette plateforme logicielle est structurée selon une architecture technique de type MVC (Modèle-Vue-Contrôleur). L'accès à l'application implique l'acceptation sans réserve des présentes CGU par l'utilisateur.</p>

                        <h4 class="fw-bold text-primary mt-4">2. Accès au site et architecture des services</h4>
                        <p>L'accès à la plateforme est techniquement centralisé par un contrôleur principal chargé de gérer et de diriger l'ensemble des requêtes des utilisateurs. Le routage est sécurisé et optimisé côté serveur : toute URL ne correspondant pas à un fichier existant est automatiquement redirigée vers le point d'entrée unique de l'application via les règles de configuration du serveur.</p>
                        <p>L'accès aux espaces privatifs et personnalisés nécessite l'authentification de l'utilisateur et l'initialisation d'une session de navigation. L'utilisateur est seul responsable de la confidentialité de ses identifiants d'accès à sa session.</p>

                        <h4 class="fw-bold text-primary mt-4">3. Propriété intellectuelle</h4>
                        <p>L'ensemble des éléments composant l'application MANAGER, incluant le code source défini à partir de la racine, la structure architecturale MVC, ainsi que la charte graphique et les actifs visuels tels que les logos au format vectoriel SVG, sont la propriété exclusive de l'éditeur ou de ses ayants droit. Toute représentation, reproduction, modification ou exploitation non autorisée de ces éléments est strictement interdite et constitutive d'une contrefaçon.</p>

                        <h4 class="fw-bold text-primary mt-4">4. Gestion des données personnelles</h4>
                        <p>Dans le cadre de l'utilisation du service, les interactions de l'utilisateur avec les vues et les modèles de données sont traitées par le contrôleur principal. Les données liées à la connexion en cours sont maintenues temporairement par le système de gestion des sessions. Le traitement de ces données est effectué dans le strict respect de la réglementation en vigueur concernant la protection des données à caractère personnel (RGPD).</p>

                        <h4 class="fw-bold text-primary mt-4">5. Responsabilité et disponibilité des services</h4>
                        <p>L'éditeur met en œuvre les solutions techniques appropriées afin d'assurer une disponibilité continue du service. Néanmoins, l'éditeur n'est tenu qu'à une obligation de moyens. Sa responsabilité ne saurait être engagée en cas d'indisponibilité du service résultant de pannes techniques, d'opérations de maintenance sur le répertoire physique de l'application, ou de cas de force majeure.</p>

                        <h4 class="fw-bold text-primary mt-4">6. Engagements de l'Utilisateur</h4>
                        <p>L'utilisateur s'engage à utiliser l'application de manière licite, loyale et conforme à sa destination professionnelle. Il est formellement interdit de tenter de contourner les règles de réécriture d'URL ou d'altérer le fonctionnement du point d'entrée et du contrôleur principal. Tout manquement à ces règles pourra entraîner la suspension immédiate et de plein droit de la session utilisateur.</p>

                        <h4 class="fw-bold text-primary mt-4">7. Droit applicable et résolution des litiges</h4>
                        <p>Les présentes CGU sont régies et interprétées conformément au droit en vigueur. À défaut de résolution amiable, tout litige relatif à l'interprétation ou à l'exécution des présentes sera soumis à la compétence exclusive des tribunaux du ressort du siège social de l'éditeur.</p>
                    </div>

                    <div class="mt-5 text-center">
                        <a href="index.php" class="btn btn-primary px-4">Retour à l'accueil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Inclusion du pied de page (footer)
require_once Racine . '/../app/vue/layout/pied.php';
?>