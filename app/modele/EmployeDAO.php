<?php
class EmployeDAO extends PDO_Connexion
{

    private $db;

    public function __construct()
    {
        // Connexion PDO héritée de PDO_Connexion
        $this->db = $this->getConnection();
    }

    public function login()
    {
        $stmt = $this->db->prepare("SELECT * FROM employe");
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $employe = [];
        foreach ($result as $ligne) {
            $employe[] = $this->hydrater($ligne);
        }

        return $employe;
    }

    private function hydrater(array $ligne): Employe
    {
        return new Employe(
            (int) $ligne['id_employe'],
            (string) $ligne['nom'],
            (string) $ligne['prenom'],
            (string) $ligne['email'],
            (string) $ligne['mdp'],
            (string) $ligne['tel'],
            (string) $ligne['role'],
        );
    }
    // Faire plus tard les setter si besoin
}