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
                i.id_equipement,
                i.id_employe,
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

        $statement = $this->db->prepare($sql);
        $statement->execute();
        $resultats = $statement->fetchAll();
        $interventions = [];

        foreach ($resultats as $ligne) {
            $interventions[] = $this->hydrater($ligne);
        }

        return $interventions;
    }

    public function getById(int $id): ?Intervention
    {
        $sql = "
            SELECT
                i.id_intervention,
                i.id_equipement,
                i.id_employe,
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

        return $intervention === false ? null : $this->hydrater($intervention);
    }

    public function creer(Intervention $intervention): void
    {
        $sql = "
            INSERT INTO interventions
                (desc_panne, date_intervention, statut, rapport, id_equipement, id_employe)
            VALUES
                (:desc_panne, :date_intervention, :statut, :rapport, :id_equipement, :id_employe)
        ";

        $statement = $this->db->prepare($sql);
        $statement->bindValue(':desc_panne', $intervention->getDescPanne(), PDO::PARAM_STR);
        $statement->bindValue(':date_intervention', $intervention->getDateIntervention(), PDO::PARAM_STR);
        $statement->bindValue(':statut', $intervention->getStatut(), PDO::PARAM_STR);
        $statement->bindValue(':rapport', $intervention->getRapport(), $intervention->getRapport() === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $statement->bindValue(':id_equipement', $intervention->getIdEquipement(), PDO::PARAM_INT);
        $statement->bindValue(':id_employe', $intervention->getIdEmploye(), PDO::PARAM_INT);
        $statement->execute();
    }

    public function modifierStatut(Intervention $intervention): bool
    {
        $sql = "
            UPDATE interventions
            SET statut = :statut,
                date_cloture = CASE
                    WHEN :statut_cloture = 'CLOTUREE' THEN UTC_TIMESTAMP()
                    ELSE NULL
                END
            WHERE id_intervention = :id
        ";

        $statement = $this->db->prepare($sql);
        $statement->bindValue(':statut', $intervention->getStatut(), PDO::PARAM_STR);
        $statement->bindValue(':statut_cloture', $intervention->getStatut(), PDO::PARAM_STR);
        $statement->bindValue(':id', $intervention->getId(), PDO::PARAM_INT);
        $statement->execute();

        return $this->getById($intervention->getId()) !== null;
    }

    public function modifierInformations(Intervention $intervention): bool
    {
        $sql = "
            UPDATE interventions
            SET desc_panne = :desc_panne,
                date_intervention = :date_intervention,
                rapport = :rapport,
                id_equipement = :id_equipement,
                id_employe = :id_employe
            WHERE id_intervention = :id
                AND statut = :statut_ouverte
        ";

        $statement = $this->db->prepare($sql);
        $statement->bindValue(':desc_panne', $intervention->getDescPanne(), PDO::PARAM_STR);
        $statement->bindValue(':date_intervention', $intervention->getDateIntervention(), PDO::PARAM_STR);
        $statement->bindValue(':rapport', $intervention->getRapport(), $intervention->getRapport() === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $statement->bindValue(':id_equipement', $intervention->getIdEquipement(), PDO::PARAM_INT);
        $statement->bindValue(':id_employe', $intervention->getIdEmploye(), PDO::PARAM_INT);
        $statement->bindValue(':id', $intervention->getId(), PDO::PARAM_INT);
        $statement->bindValue(':statut_ouverte', 'OUVERTE', PDO::PARAM_STR);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }

        $intervention = $this->getById($intervention->getId());
        return $intervention !== null && $intervention->getStatut() === 'OUVERTE';
    }

    private function hydrater(array $ligne): Intervention
    {
        require_once Racine . '/../app/modele/Interventions.php';
        $intervention = new Intervention(
            (int) $ligne['id_intervention'],
            (string) $ligne['desc_panne'],
            (string) $ligne['date_intervention'],
            (string) $ligne['date_cloture'],
            (string) $ligne['statut'],
            (string) $ligne['rapport'],
            (int) $ligne['id_equipement'],
            (int) $ligne['id_employe']
        );
        $intervention->setInformationsAssociees($ligne);

        return $intervention;
    }
}
