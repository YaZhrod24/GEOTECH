<?php
class ClientDAO extends PDO_Connexion
{

    private $db;

    public function __construct()
    {
        // Connexion PDO héritée de PDO_Connexion
        $this->db = $this->getConnection();
    }


    private function hydrater(array $ligne): Client
    {
        require_once Racine . '/../app/modele/Client.php';
        return new Client(
            (int) $ligne['id_client'],
            (string) $ligne['raison_social'],
            (string) $ligne['email'],
            (string) $ligne['tel'],
            (string) $ligne['adresse'],
            (string) $ligne['cp'],
            (string) $ligne['ville'],
        );
    }
    // Faire plus tard les setter si besoin
}