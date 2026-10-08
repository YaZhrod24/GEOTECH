<?php
class ClientDAO extends PDO_Connexion
{

    private $db;

    public function __construct()
    {
        // Connexion PDO héritée de PDO_Connexion
        $this->db = $this->getConnection();
    }


    private function hydrater(array $ligne): Intervention
    {
        require_once Racine . '/../app/modele/Intervention.php';
        return new Intervention(
            (int) $ligne['id_intervention'],
            (string) $ligne['desc_panne'],
            (string) $ligne['date_intervention'],
            (string) $ligne['date_cloture'],
            (string) $ligne['statut'],
            (string) $ligne['rapport'],
            (int) $ligne['id_equipement'],
            (int) $ligne['id_employe']
        );
    }

    // Faire plus tard les setter si besoin
}