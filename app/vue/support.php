<?php
// Inclusion de l'en-tête (header)
require_once Racine . '/../app/vue/layout/entete.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <section class="card border-0 shadow-sm overflow-hidden mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-8">
                            <span class="badge text-bg-primary rounded-pill px-3 py-2 mb-3">
                                <i class="bi bi-headset me-1" aria-hidden="true"></i>
                                Centre d'aide
                            </span>
                            <h1 class="fw-bold mb-3">Support technique</h1>
                            <p class="lead text-muted mb-0">
                                Une difficulté avec MANAGER ? Retrouvez ici les premiers réflexes à adopter
                                et les réponses aux questions les plus fréquentes.
                            </p>
                        </div>
                        <div class="col-lg-4 text-center">
                            <i class="bi bi-life-preserver text-primary" style="font-size: 7rem;" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </section>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <article class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex p-3 mb-3">
                                <i class="bi bi-exclamation-triangle fs-4" aria-hidden="true"></i>
                            </div>
                            <h2 class="h5 fw-bold">Signaler un incident</h2>
                            <p class="text-muted mb-0">
                                Décrivez le problème rencontré à votre responsable ou à l'administrateur
                                de votre organisation.
                            </p>
                        </div>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex p-3 mb-3">
                                <i class="bi bi-person-check fs-4" aria-hidden="true"></i>
                            </div>
                            <h2 class="h5 fw-bold">Accès et connexion</h2>
                            <p class="text-muted mb-0">
                                Vérifiez votre adresse professionnelle et demandez la réinitialisation de
                                votre accès à un administrateur habilité.
                            </p>
                        </div>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex p-3 mb-3">
                                <i class="bi bi-shield-check fs-4" aria-hidden="true"></i>
                            </div>
                            <h2 class="h5 fw-bold">Données et sécurité</h2>
                            <p class="text-muted mb-0">
                                Ne transmettez jamais votre mot de passe. Pour une demande liée à vos données,
                                utilisez le canal prévu par votre organisation.
                            </p>
                        </div>
                    </article>
                </div>
            </div>

            <section class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-list-check text-primary fs-3 me-3" aria-hidden="true"></i>
                        <div>
                            <h2 class="h4 fw-bold mb-1">Avant de demander de l'aide</h2>
                            <p class="text-muted mb-0">Ces vérifications permettent de résoudre les incidents courants.</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex gap-3">
                                <i class="bi bi-check-circle-fill text-primary mt-1" aria-hidden="true"></i>
                                <span>Actualisez la page et vérifiez votre connexion Internet.</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-3">
                                <i class="bi bi-check-circle-fill text-primary mt-1" aria-hidden="true"></i>
                                <span>Contrôlez que vous utilisez l'adresse de connexion professionnelle.</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-3">
                                <i class="bi bi-check-circle-fill text-primary mt-1" aria-hidden="true"></i>
                                <span>Notez l'heure et l'écran sur lequel l'erreur apparaît.</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-3">
                                <i class="bi bi-check-circle-fill text-primary mt-1" aria-hidden="true"></i>
                                <span>Ajoutez, si possible, une capture d'écran sans donnée sensible.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-question-circle text-primary fs-3 me-3" aria-hidden="true"></i>
                        <h2 class="h4 fw-bold mb-0">Questions fréquentes</h2>
                    </div>
                    <div class="accordion accordion-flush" id="supportFaq">
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="faqHeadingOne">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faqOne" aria-expanded="false"
                                    aria-controls="faqOne">
                                    Que faire si je ne peux pas me connecter ?
                                </button>
                            </h3>
                            <div id="faqOne" class="accordion-collapse collapse" aria-labelledby="faqHeadingOne"
                                data-bs-parent="#supportFaq">
                                <div class="accordion-body text-muted">
                                    Vérifiez votre adresse professionnelle et votre mot de passe, puis réessayez.
                                    Si le problème persiste, contactez l'administrateur de votre organisation pour
                                    faire vérifier votre compte.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="faqHeadingTwo">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faqTwo" aria-expanded="false"
                                    aria-controls="faqTwo">
                                    Comment décrire efficacement un problème ?
                                </button>
                            </h3>
                            <div id="faqTwo" class="accordion-collapse collapse" aria-labelledby="faqHeadingTwo"
                                data-bs-parent="#supportFaq">
                                <div class="accordion-body text-muted">
                                    Indiquez l'action réalisée, le résultat attendu, le résultat observé et l'heure
                                    de l'incident. Précisez également si le problème concerne un seul utilisateur
                                    ou toute votre équipe.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="faqHeadingThree">
                                <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faqThree" aria-expanded="false"
                                    aria-controls="faqThree">
                                    Puis-je envoyer mon mot de passe au support ?
                                </button>
                            </h3>
                            <div id="faqThree" class="accordion-collapse collapse" aria-labelledby="faqHeadingThree"
                                data-bs-parent="#supportFaq">
                                <div class="accordion-body text-muted">
                                    Non. Le support ne doit jamais vous demander votre mot de passe. Ne partagez
                                    aucune information confidentielle et masquez les données sensibles sur les
                                    captures d'écran.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="text-center mt-4">
                <a href="/" class="btn btn-primary px-4">
                    <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
</div>

<?php
// Inclusion du pied de page (footer)
require_once Racine . '/../app/vue/layout/pied.php';
?>
