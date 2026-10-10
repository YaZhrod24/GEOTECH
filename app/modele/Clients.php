<?php
class Client
{

    private $id;
    private $raisonSociale;
    private $email;
    private $tel;
    private $adresse;
    private $cp;
    private $ville;

    public function __construct($id, $raisonSociale, $email, $tel, $adresse, $cp, $ville)
    {
        $this->id = $id;
        $this->raisonSociale = $raisonSociale;
        $this->email = $email;
        $this->tel = $tel;
        $this->adresse = $adresse;
        $this->cp = $cp;
        $this->ville = $ville;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getRaisonSociale()
    {
        return $this->raisonSociale;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getTel()
    {
        return $this->tel;
    }
    public function getAdresse()
    {
        return $this->adresse;
    }
    public function getCp()
    {
        return $this->cp;
    }
    public function getVille()
    {
        return $this->ville;
    }

    public static function getAll(): array
    {
        require_once Racine . '/../app/modele/ClientsDAO.php';
        return (new ClientDAO())->getAll();
    }

    public function creer(): bool
    {
        require_once Racine . '/../app/modele/ClientsDAO.php';
        return (new ClientDAO())->creer($this);
    }

    public function modifier(): bool
    {
        require_once Racine . '/../app/modele/ClientsDAO.php';
        return (new ClientDAO())->modifier($this);
    }

    public function supprimer(): bool
    {
        require_once Racine . '/../app/modele/ClientsDAO.php';
        return (new ClientDAO())->supprimer($this);
    }
}