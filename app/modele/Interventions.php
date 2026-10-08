<?php
class Intervention
{

    private $id;
    private $descPanne;
    private $dateIntervention;
    private $dateCloture;
    private $statut;
    private $rapport;
    private $idEquipement;
    private $idEmploye;
    private $client;
    private $nomEquipement;
    private $typeEquipement;
    private $numSerie;
    private $technicienNom;
    private $technicienPrenom;

    public function __construct($id, $descPanne, $dateIntervention, $dateCloture, $statut, $rapport, $idEquipement, $idEmploye)
    {
        $this->id = $id;
        $this->descPanne = $descPanne;
        $this->dateIntervention = $dateIntervention;
        $this->dateCloture = $dateCloture;
        $this->statut = $statut;
        $this->rapport = $rapport;
        $this->idEquipement = $idEquipement;
        $this->idEmploye = $idEmploye;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getDescPanne()
    {
        return $this->descPanne;
    }
    public function getDateIntervention()
    {
        return $this->dateIntervention;
    }
    public function getDateCloture()
    {
        return $this->dateCloture;
    }
    public function getStatut()
    {
        return $this->statut;
    }
    public function getRapport()
    {
        return $this->rapport;
    }
    public function getIdEquipement()
    {
        return $this->idEquipement;
    }
    public function getIdEmploye()
    {
        return $this->idEmploye;
    }

    public function setInformationsAssociees(array $ligne): void
    {
        $this->client = $ligne['client'];
        $this->nomEquipement = $ligne['equipement'];
        $this->typeEquipement = $ligne['type_equipement'];
        $this->numSerie = $ligne['num_serie'];
        $this->technicienNom = $ligne['technicien_nom'];
        $this->technicienPrenom = $ligne['technicien_prenom'];
    }

    public function getClient()
    {
        return $this->client;
    }
    public function getNomEquipement()
    {
        return $this->nomEquipement;
    }
    public function getTypeEquipement()
    {
        return $this->typeEquipement;
    }
    public function getNumSerie()
    {
        return $this->numSerie;
    }
    public function getTechnicienNom()
    {
        return $this->technicienNom;
    }
    public function getTechnicienPrenom()
    {
        return $this->technicienPrenom;
    }

    public function setDescPanne(string $descPanne): void
    {
        $this->descPanne = $descPanne;
    }

    public function setDateIntervention(string $dateIntervention): void
    {
        $this->dateIntervention = $dateIntervention;
    }

    public function setDateCloture(?string $dateCloture): void
    {
        $this->dateCloture = $dateCloture;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function setRapport(?string $rapport): void
    {
        $this->rapport = $rapport;
    }

    public function setIdEquipement(int $idEquipement): void
    {
        $this->idEquipement = $idEquipement;
    }

    public function setIdEmploye(int $idEmploye): void
    {
        $this->idEmploye = $idEmploye;
    }

    public static function getAll(): array
    {
        require_once Racine . '/../app/modele/InterventionsDAO.php';
        return (new InterventionDAO())->getAll();
    }

    public static function getById(int $id): ?Intervention
    {
        require_once Racine . '/../app/modele/InterventionsDAO.php';
        return (new InterventionDAO())->getById($id);
    }

    public static function getPourPlanning(string $debutUtc, string $finUtc): array
    {
        require_once Racine . '/../app/modele/InterventionsDAO.php';
        return (new InterventionDAO())->getPourPeriode($debutUtc, $finUtc);
    }

    public function creer(): void
    {
        require_once Racine . '/../app/modele/InterventionsDAO.php';
        (new InterventionDAO())->creer($this);
    }

    public function enregistrerStatut(): bool
    {
        require_once Racine . '/../app/modele/InterventionsDAO.php';
        return (new InterventionDAO())->modifierStatut($this);
    }

    public function enregistrerInformations(): bool
    {
        require_once Racine . '/../app/modele/InterventionsDAO.php';
        return (new InterventionDAO())->modifierInformations($this);
    }
}