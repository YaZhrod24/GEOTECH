<?php
class Equipement
{

    private $id;
    private $nom;
    private $type;
    private $numSerie;
    private $id_client;

    public function __construct($id, $nom, $type, $numSerie, $id_client)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->type = $type;
        $this->numSerie = $numSerie;
        $this->id_client = $id_client;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getNom()
    {
        return $this->nom;
    }
    public function getType()
    {
        return $this->type;
    }
    public function getNumSerie()
    {
        return $this->numSerie;
    }
    public function getIdClient()
    {
        return $this->id_client;
    }

    // Faire plus tard les setter si besoin
}