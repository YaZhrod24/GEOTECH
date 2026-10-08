<?php

class InterventionDAO extends PDO_Connexion
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
                i.id_intervention,
                i.desc_panne,
                i.date_intervention,
                i.date_cloture,
                i.statut,
                i.rapport,
                c.raison_social AS client,
                e.nom AS equipement,
                e.type AS type_equipement,
                e.num_serie,
                emp.nom AS technicien_nom,
                emp.prenom AS technicien_prenom
            FROM interventions i
            INNER JOIN equipements e ON e.id_equipement = i.id_equipement
            INNER JOIN clients c ON c.id_client = e.id_client
            INNER JOIN employes emp ON emp.id_employe = i.id_employe
            ORDER BY i.date_intervention DESC, i.id_intervention DESC
        ";

        return $this->db->query($sql)->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $sql = "
            SELECT
                i.id_intervention,
                i.desc_panne,
                i.date_intervention,
                i.date_cloture,
                i.statut,
                i.rapport,
                c.raison_social AS client,
                e.nom AS equipement,
                e.type AS type_equipement,
                e.num_serie,
                emp.nom AS technicien_nom,
                emp.prenom AS technicien_prenom
            FROM interventions i
            INNER JOIN equipements e ON e.id_equipement = i.id_equipement
            INNER JOIN clients c ON c.id_client = e.id_client
            INNER JOIN employes emp ON emp.id_employe = i.id_employe
            WHERE i.id_intervention = :id
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        $intervention = $statement->fetch();

        return $intervention === false ? null : $intervention;
    }
}
