<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="row w-100 shadow-lg rounded-4 overflow-hidden bg-white" style="max-width: 900px;">

        <!-- Left Side: Atypical Brand/Visual Panel -->
        <div
            class="col-md-5 d-none d-md-flex flex-column justify-content-between p-5 text-white bg-primary position-relative">
            <div>
                <span class="badge bg-light text-dark mb-3 px-3 py-2 rounded-pill fw-semibold">-- Futur logo --</span>
                <h2 class="fw-bold display-6">Geotech</h2>
                <p class="text-white-50 mt-2">Accès manager à la gestion des interventions.
                </p>
            </div>
            <div class="text-white-50 small">
                Accès manager
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="col-md-7 p-5 d-flex flex-column justify-content-center">
            <div class="mb-4 text-md-start text-center">
                <h3 class="fw-bold text-dark">Connexion</h3>
                <p class="text-muted small">Veuillez entrer vos identifiants professionnels.</p>
            </div>

            <!-- Formulaire pointant vers votre route MVC -->
            <form action="" method="POST">

                <div class="form-floating mb-3">
                    <input type="email" class="form-control rounded-3" id="email" name="email"
                        placeholder="nom@geotech.com" required>
                    <label for="email">Adresse email</label>
                </div>

                <div class="form-floating mb-4">
                    <input type="password" class="form-control rounded-3" id="password" name="password"
                        placeholder="Mot de passe" required>
                    <label for="password">Mot de passe</label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-dark btn-lg rounded-3 fw-semibold py-3">
                        Se connecter
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>