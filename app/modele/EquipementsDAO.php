<?php
class ClientDAO extends PDO_Connexion
{

    private $db;

    public function __construct()
    {
        // Connexion PDO héritée de PDO_Connexion
        $this->db = $this->getConnection();
    }


    private function hydrater(array $ligne): Equipement
    {
        require_once Racine . '/../app/modele/Equipement.php';
        return new Equipement(
            (int) $ligne['id_equipement'],
            (string) $ligne['nom'],
            (string) $ligne['type'],
            (string) $ligne['num_serie'],
            (int) $ligne['id_client']
        );
    }

    // Faire plus tard les setter si besoin
}