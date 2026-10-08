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

    public function creer(Equipement $equipement): bool
    {
        $statement = $this->db->prepare("
            INSERT INTO equipements (nom, type, num_serie, id_client)
            VALUES (:nom, :type, :num_serie, :id_client)
        ");
        $statement->bindValue(':nom', $equipement->getNom(), PDO::PARAM_STR);
        $statement->bindValue(':type', $equipement->getType(), PDO::PARAM_STR);
        $statement->bindValue(':num_serie', $equipement->getNumSerie(), PDO::PARAM_STR);
        $statement->bindValue(':id_client', $equipement->getIdClient(), PDO::PARAM_INT);

        return $statement->execute();
    }

    public function modifier(Equipement $equipement): bool
    {
        $statement = $this->db->prepare("
            UPDATE equipements
            SET nom = :nom, type = :type, num_serie = :num_serie, id_client = :id_client
            WHERE id_equipement = :id
        ");
        $statement->bindValue(':nom', $equipement->getNom(), PDO::PARAM_STR);
        $statement->bindValue(':type', $equipement->getType(), PDO::PARAM_STR);
        $statement->bindValue(':num_serie', $equipement->getNumSerie(), PDO::PARAM_STR);
        $statement->bindValue(':id_client', $equipement->getIdClient(), PDO::PARAM_INT);
        $statement->bindValue(':id', $equipement->getId(), PDO::PARAM_INT);
        $statement->execute();

        return $statement->rowCount() > 0 || $this->existe((int) $equipement->getId());
    }

    public function supprimer(int $id): bool
    {
        $statement = $this->db->prepare('DELETE FROM equipements WHERE id_equipement = :id');
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->rowCount() > 0;
    }

    private function existe(int $id): bool
    {
        $statement = $this->db->prepare('SELECT 1 FROM equipements WHERE id_equipement = :id LIMIT 1');
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchColumn() !== false;
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
