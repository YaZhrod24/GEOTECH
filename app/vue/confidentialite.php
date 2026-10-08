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
                        <img src="img/logo.svg" alt="Logo" height="60" class="mb-3">
                        <h1 class="fw-bold">Politique de confidentialité</h1>
                        <p class="text-muted">Application MANAGER</p>
                    </div>

                    <div class="text-justify">
                        <p>
                            La présente politique explique comment l'application MANAGER collecte,
                            utilise et protège les données personnelles nécessaires à son fonctionnement.
                            Elle s'applique à tous les utilisateurs de la plateforme.
                        </p>

                        <h4 class="fw-bold text-primary mt-4">1. Responsable du traitement</h4>
                        <p>
                            Le responsable du traitement des données est l'éditeur de l'application MANAGER.
                            Pour toute question relative à la protection de vos données, vous pouvez utiliser
                            le canal de support technique mis à votre disposition dans l'application.
                        </p>

                        <h4 class="fw-bold text-primary mt-4">2. Données collectées</h4>
                        <p>
                            L'application collecte uniquement les données nécessaires à la gestion des comptes
                            et à l'accès aux fonctionnalités proposées. Il peut notamment s'agir de votre
                            identifiant de connexion, de vos informations professionnelles et des données
                            techniques liées à votre session de navigation.
                        </p>

                        <h4 class="fw-bold text-primary mt-4">3. Finalités et base juridique</h4>
                        <p>
                            Ces données sont utilisées pour authentifier les utilisateurs, sécuriser les accès,
                            fournir les services de l'application et assurer son maintien en conditions
                            opérationnelles. Le traitement est fondé sur l'exécution du service demandé et,
                            lorsque cela est nécessaire, sur l'intérêt légitime de l'éditeur à sécuriser sa
                            plateforme.
                        </p>

                        <h4 class="fw-bold text-primary mt-4">4. Conservation et sécurité</h4>
                        <p>
                            Les données sont conservées pendant la durée nécessaire aux finalités pour lesquelles
                            elles ont été collectées, ou pendant la durée imposée par la réglementation applicable.
                            Des mesures techniques et organisationnelles sont mises en œuvre afin de protéger les
                            données contre l'accès non autorisé, la perte, la modification ou la divulgation.
                        </p>

                        <h4 class="fw-bold text-primary mt-4">5. Destinataires et sous-traitants</h4>
                        <p>
                            Les données sont accessibles uniquement aux personnes habilitées et aux prestataires
                            techniques strictement nécessaires au fonctionnement de l'application. Elles ne sont
                            pas vendues ni cédées à des fins commerciales.
                        </p>

                        <h4 class="fw-bold text-primary mt-4">6. Vos droits</h4>
                        <p>
                            Conformément à la réglementation applicable, vous disposez d'un droit d'accès, de
                            rectification, d'effacement, de limitation et, lorsque les conditions sont réunies,
                            d'opposition au traitement de vos données. Vous pouvez exercer ces droits en contactant
                            le support technique. Vous pouvez également introduire une réclamation auprès de la
                            CNIL.
                        </p>

                        <h4 class="fw-bold text-primary mt-4">7. Cookies et sessions</h4>
                        <p>
                            MANAGER utilise les éléments techniques nécessaires au maintien de votre session
                            d'authentification et à la sécurisation de votre navigation. Aucun cookie publicitaire
                            ou de suivi destiné à établir un profil commercial n'est utilisé par l'application.
                        </p>

                        <h4 class="fw-bold text-primary mt-4">8. Mise à jour de la politique</h4>
                        <p>
                            Cette politique peut être mise à jour afin de refléter les évolutions de l'application
                            ou de la réglementation. La version publiée sur cette page est la version applicable.
                        </p>
                    </div>

                    <div class="mt-5 text-center">
                        <a href="/" class="btn btn-primary px-4">Retour à l'accueil</a>
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
