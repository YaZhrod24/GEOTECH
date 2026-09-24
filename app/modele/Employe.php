<?php
class Employe
{

    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $mdp = null;
    private $tel;
    private $role;

    private function __construct($id, $nom, $prenom, $email, $mdp, $tel, $role)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->mdp = $mdp;
        $this->tel = $tel;
        $this->role = $role;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getNom()
    {
        return $this->nom;
    }
    public function getPrenom()
    {
        return $this->prenom;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getTel()
    {
        return $this->tel;
    }
    public function getRole()
    {
        return $this->role;
    }

    // Faire plus tard les setter si besoin
}