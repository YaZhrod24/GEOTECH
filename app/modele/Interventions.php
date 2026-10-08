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
    public function getIdEquipement()
    {
        return $this->idEquipement;
    }
    public function getIdEmploye()
    {
        return $this->idEmploye;
    }

    // Faire plus tard les setter si besoin
}