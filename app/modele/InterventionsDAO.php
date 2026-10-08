<?php
require_once Racine . '/../app/modele/bd.php'; // Ou le chemin exact vers ton fichier de connexion (ex: bd.php)
class InterventionsDAO extends PDO_Connexion
{
    private $db;

    public function __construct()
    {
        $this->db = $this->getConnection();
    }

    private function hydrater(array $ligne): Intervention
    {
        require_once Racine . '/../app/modele/Intervention.php';
        return new Intervention(
            (int) $ligne['id_intervention'],
            (string) ($ligne['desc_panne'] ?? $ligne['description'] ?? ''),
            (string) $ligne['date_intervention'],
            (string) ($ligne['date_cloture'] ?? ''),
            (string) $ligne['statut'],
            (string) ($ligne['rapport'] ?? ''),
            (int) $ligne['id_equipement'],
            (int) ($ligne['id_employe'] ?? 0)
        );
    }

    public function getInterventionsDuJour(): array
    {
        $sql = "SELECT * FROM interventions WHERE DATE(date_intervention) = CURDATE()";
        $stmt = $this->db->query($sql);
        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $interventions = [];
        foreach ($resultats as $ligne) {
            $interventions[] = $this->hydrater($ligne);
        }
        return $interventions;
    }

    // Récupérer les interventions du jour avec les infos jointes (Client, Équipement, Technicien) pour le tableau HTML
    public function getInterventionsDuJourComplet(): array
    {
        $sql = "SELECT i.*, 
                       c.raison_social AS client_nom, 
                       eq.nom AS equipement_nom, 
                       emp.nom AS technicien_nom, 
                       emp.prenom AS technicien_prenom
                FROM interventions i
                LEFT JOIN equipements eq ON i.id_equipement = eq.id_equipement
                LEFT JOIN clients c ON eq.id_client = c.id_client
                LEFT JOIN employes emp ON i.id_employe = emp.id_employe
                WHERE DATE(i.date_intervention) = CURDATE()
                ORDER BY i.date_intervention DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Compteurs pour les cartes du tableau de bord
    public function countAujourdhui(): int
    {
        $sql = "SELECT COUNT(*) FROM interventions WHERE DATE(date_intervention) = CURDATE()";
        return (int) $this->db->query($sql)->fetchColumn();
    }

    public function countParStatut(string $statut): int
    {
        $sql = "SELECT COUNT(*) FROM interventions WHERE statut = :statut";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['statut' => $statut]);
        return (int) $stmt->fetchColumn();
    }

    public function ajouterIntervention($desc_panne, $date_intervention, $statut, $id_equipement, $id_employe): bool
    {
        $sql = "INSERT INTO interventions (desc_panne, date_intervention, statut, id_equipement, id_employe) 
                VALUES (:desc_panne, :date_intervention, :statut, :id_equipement, :id_employe)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'desc_panne' => $desc_panne,
            'date_intervention' => $date_intervention,
            'statut' => $statut,
            'id_equipement' => $id_equipement,
            'id_employe' => $id_employe
        ]);
    }
}