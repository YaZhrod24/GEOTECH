<?php
require_once Racine . '/../app/modele/bd.php'; // <-- Indispensable pour trouver PDO_Connexion

class ClientsDAO extends PDO_Connexion
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
            (string) $ligne['ville']
        );
    }

    // Récupérer tous les clients de la base de données
    public function getTousLesClients(): array
    {
        try {
            $req = $this->db->prepare("SELECT * FROM client ORDER BY raison_social ASC");
            $req->execute();
            $resultats = $req->fetchAll(PDO::FETCH_ASSOC);

            $clients = [];
            foreach ($resultats as $ligne) {
                $clients[] = $this->hydrater($ligne);
            }
            return $clients;
        } catch (PDOException $e) {
            return [];
        }
    }
}