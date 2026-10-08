<?php
class Equipement
{

    private $id;
    private $nom;
    private $type;
    private $numSerie;
    private $id_client;
    private $client;

    public function __construct($id, $nom, $type, $numSerie, $id_client, $client = null)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->type = $type;
        $this->numSerie = $numSerie;
        $this->id_client = $id_client;
        $this->client = $client;
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
    public function getClient()
    {
        return $this->client;
    }

    public function creer(): bool
    {
        require_once Racine . '/../app/modele/EquipementsDAO.php';
        return (new EquipementDAO())->creer($this);
    }

    public function modifier(): bool
    {
        require_once Racine . '/../app/modele/EquipementsDAO.php';
        return (new EquipementDAO())->modifier($this);
    }

    public function supprimer(): bool
    {
        require_once Racine . '/../app/modele/EquipementsDAO.php';
        return (new EquipementDAO())->supprimer((int) $this->id);
    }

    public static function getAll(): array
    {
        require_once Racine . '/../app/modele/EquipementsDAO.php';
        return (new EquipementDAO())->getAll();
    }
}