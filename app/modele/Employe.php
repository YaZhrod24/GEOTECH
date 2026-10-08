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

    public function __construct($id, $nom, $prenom, $email, $mdp, $tel, $role)
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

    public static function getTechniciens(): array
    {
        require_once Racine . '/../app/modele/EmployeDAO.php';
        return (new EmployeDAO())->getTechniciens();
    }

    public function creerTechnicien(string $motDePasse): void
    {
        require_once Racine . '/../app/modele/EmployeDAO.php';
        (new EmployeDAO())->creerTechnicien($this, password_hash($motDePasse, PASSWORD_ARGON2ID));
    }

    public function modifierTechnicien(?string $motDePasse = null): bool
    {
        require_once Racine . '/../app/modele/EmployeDAO.php';
        $motDePasseHash = $motDePasse === null || $motDePasse === ''
            ? null
            : password_hash($motDePasse, PASSWORD_ARGON2ID);

        return (new EmployeDAO())->modifierTechnicien($this, $motDePasseHash);
    }

    public function supprimerTechnicien(): bool
    {
        require_once Racine . '/../app/modele/EmployeDAO.php';
        return (new EmployeDAO())->supprimerTechnicien($this->id);
    }
}