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
            SELECT id_employe, nom, prenom, role
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