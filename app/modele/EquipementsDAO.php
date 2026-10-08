<?php

class EquipementDAO extends PDO_Connexion
{
    private PDO $db;

    public function __construct()
    {
        $this->db = $this->getConnection();
    }

    public function getAll(): array
    {
        $sql = "
            SELECT
                e.id_equipement,
                e.nom,
                e.type,
                e.num_serie,
                e.id_client,
                c.raison_social AS client
            FROM equipements e
            INNER JOIN clients c ON c.id_client = e.id_client
            ORDER BY c.raison_social, e.nom
        ";

        $statement = $this->db->prepare($sql);
        $statement->execute();
        $resultats = $statement->fetchAll();
        $equipements = [];

        foreach ($resultats as $ligne) {
            $equipements[] = $this->hydrater($ligne);
        }

        return $equipements;
    }

    private function hydrater(array $ligne): Equipement
    {
        require_once Racine . '/../app/modele/Equipements.php';
        return new Equipement(
            (int) $ligne['id_equipement'],
            (string) $ligne['nom'],
            (string) $ligne['type'],
            (string) $ligne['num_serie'],
            (int) $ligne['id_client'],
            (string) $ligne['client']
        );
    }
}
