<?php
class EmployeDAO extends PDO_Connexion
{

    private $db;

    public function __construct()
    {
        // Connexion PDO héritée de PDO_Connexion
        $this->db = $this->getConnection();
    }

    public function login($email, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM employes WHERE email = :email LIMIT 1");
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            if (password_verify($password, $result["mdp"])) {
                if ($result["role"] === "MANAGER") {
                    return $this->hydrater($result);
                } else {
                    return "Vous n'avez pas les droits nécessaires pour accéder à cette application."; // Rôle non autorisé
                }
            } else {
                return "Mot de passe incorrect."; // Mot de passe incorrect
            }

        } else {
            return "Aucun employé trouvé avec cet email."; // Aucun employé trouvé
        }


        $employe = [];
        foreach ($result as $ligne) {
            $employe[] = $this->hydrater($ligne);
        }

        return $employe;
    }

    public function getTechniciens(): array
    {
        $stmt = $this->db->prepare("
            SELECT id_employe, nom, prenom, email, tel, role
            FROM employes
            WHERE role = :role
            ORDER BY nom, prenom
        ");
        $stmt->bindValue(':role', 'TECHNICIEN', PDO::PARAM_STR);
        $stmt->execute();

        $techniciens = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $ligne) {
            $techniciens[] = $this->hydrater($ligne);
        }

        return $techniciens;
    }

    public function creerTechnicien(Employe $technicien, string $motDePasseHash): void
    {
        $statement = $this->db->prepare("
            INSERT INTO employes (nom, prenom, email, mdp, tel, role)
            VALUES (:nom, :prenom, :email, :mdp, :tel, :role)
        ");
        $statement->bindValue(':nom', $technicien->getNom(), PDO::PARAM_STR);
        $statement->bindValue(':prenom', $technicien->getPrenom(), PDO::PARAM_STR);
        $statement->bindValue(':email', $technicien->getEmail(), PDO::PARAM_STR);
        $statement->bindValue(':mdp', $motDePasseHash, PDO::PARAM_STR);
        $statement->bindValue(':tel', $technicien->getTel(), $technicien->getTel() === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $statement->bindValue(':role', 'TECHNICIEN', PDO::PARAM_STR);
        $statement->execute();
    }

    public function modifierTechnicien(Employe $technicien, ?string $motDePasseHash): bool
    {
        if ($motDePasseHash === null) {
            $statement = $this->db->prepare("
                UPDATE employes
                SET nom = :nom, prenom = :prenom, email = :email, tel = :tel
                WHERE id_employe = :id AND role = :role
            ");
        } else {
            $statement = $this->db->prepare("
                UPDATE employes
                SET nom = :nom, prenom = :prenom, email = :email, tel = :tel, mdp = :mdp
                WHERE id_employe = :id AND role = :role
            ");
            $statement->bindValue(':mdp', $motDePasseHash, PDO::PARAM_STR);
        }

        $statement->bindValue(':nom', $technicien->getNom(), PDO::PARAM_STR);
        $statement->bindValue(':prenom', $technicien->getPrenom(), PDO::PARAM_STR);
        $statement->bindValue(':email', $technicien->getEmail(), PDO::PARAM_STR);
        $statement->bindValue(':tel', $technicien->getTel(), $technicien->getTel() === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $statement->bindValue(':id', $technicien->getId(), PDO::PARAM_INT);
        $statement->bindValue(':role', 'TECHNICIEN', PDO::PARAM_STR);
        $statement->execute();

        return $statement->rowCount() > 0 || $this->getTechnicienById($technicien->getId()) !== null;
    }

    public function supprimerTechnicien(int $id): bool
    {
        $statement = $this->db->prepare("
            DELETE FROM employes
            WHERE id_employe = :id AND role = :role
        ");
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':role', 'TECHNICIEN', PDO::PARAM_STR);
        $statement->execute();

        return $statement->rowCount() > 0;
    }

    private function getTechnicienById(int $id): ?Employe
    {
        $statement = $this->db->prepare("
            SELECT id_employe, nom, prenom, email, tel, role
            FROM employes
            WHERE id_employe = :id AND role = :role
            LIMIT 1
        ");
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':role', 'TECHNICIEN', PDO::PARAM_STR);
        $statement->execute();
        $ligne = $statement->fetch(PDO::FETCH_ASSOC);

        return $ligne === false ? null : $this->hydrater($ligne);
    }

    private function hydrater(array $ligne): Employe
    {
        require_once Racine . '/../app/modele/Employe.php';
        return new Employe(
            (int) $ligne['id_employe'],
            (string) $ligne['nom'],
            (string) $ligne['prenom'],
            (string) ($ligne['email'] ?? ''),
            (string) ($ligne['mdp'] ?? ''),
            (string) ($ligne['tel'] ?? ''),
            (string) $ligne['role'],
        );
    }
    // Faire plus tard les setter si besoin
}