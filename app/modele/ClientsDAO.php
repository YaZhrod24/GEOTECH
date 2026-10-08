<?php
class ClientDAO extends PDO_Connexion
{

    private $db;

    public function __construct()
    {
        // Connexion PDO héritée de PDO_Connexion
        $this->db = $this->getConnection();
    }

    public function getAll(): array
    {
        $statement = $this->db->prepare("
            SELECT id_client, raison_social, email, tel, adresse, cp, ville
            FROM clients
            ORDER BY raison_social
        ");
        $statement->execute();

        $clients = [];
        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $ligne) {
            $clients[] = $this->hydrater($ligne);
        }

        return $clients;
    }

    private function hydrater(array $ligne): Client
    {
        require_once Racine . '/../app/modele/Clients.php';
        return new Client(
            (int) $ligne['id_client'],
            (string) $ligne['raison_social'],
            (string) ($ligne['email'] ?? ''),
            (string) ($ligne['tel'] ?? ''),
            (string) ($ligne['adresse'] ?? ''),
            (string) ($ligne['cp'] ?? ''),
            (string) ($ligne['ville'] ?? ''),
        );
    }
    // Faire plus tard les setter si besoin
}