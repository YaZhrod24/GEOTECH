<?php
class ClientDAO extends PDO_Connexion
{

    private $db;

    public function __construct()
    {
        // Connexion PDO héritée de PDO_Connexion
        $this->db = $this->getConnection();
    }

    public function getAll(): array
    {
        $statement = $this->db->prepare("
            SELECT id_client, raison_social, email, tel, adresse, cp, ville
            FROM clients
            ORDER BY raison_social
        ");
        $statement->execute();

        $clients = [];
        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $ligne) {
            $clients[] = $this->hydrater($ligne);
        }

        return $clients;
    }

    public function creer(Client $client): bool
    {
        $statement = $this->db->prepare("
            INSERT INTO clients (raison_social, email, tel, adresse, cp, ville)
            VALUES (:raison_social, :email, :tel, :adresse, :cp, :ville)
        ");
        $this->lierInformations($statement, $client);

        try {
            return $statement->execute();
        } catch (PDOException $exception) {
            $this->gererErreurIntegrite($exception);
        }
    }

    public function modifier(Client $client): bool
    {
        $statement = $this->db->prepare("
            UPDATE clients
            SET raison_social = :raison_social,
                email = :email,
                tel = :tel,
                adresse = :adresse,
                cp = :cp,
                ville = :ville
            WHERE id_client = :id
        ");
        $this->lierInformations($statement, $client);
        $statement->bindValue(':id', $client->getId(), PDO::PARAM_INT);

        try {
            $statement->execute();
        } catch (PDOException $exception) {
            $this->gererErreurIntegrite($exception);
        }

        return $statement->rowCount() > 0 || $this->existe((int) $client->getId());
    }

    public function supprimer(Client $client): bool
    {
        $statement = $this->db->prepare('DELETE FROM clients WHERE id_client = :id');
        $statement->bindValue(':id', $client->getId(), PDO::PARAM_INT);

        try {
            $statement->execute();
        } catch (PDOException $exception) {
            $this->gererErreurIntegrite($exception, true);
        }

        return $statement->rowCount() > 0;
    }

    private function lierInformations(PDOStatement $statement, Client $client): void
    {
        $statement->bindValue(':raison_social', $client->getRaisonSociale(), PDO::PARAM_STR);
        $statement->bindValue(':email', $client->getEmail(), PDO::PARAM_STR);
        $statement->bindValue(':tel', $client->getTel(), PDO::PARAM_STR);
        $statement->bindValue(':adresse', $client->getAdresse(), PDO::PARAM_STR);
        $statement->bindValue(':cp', $client->getCp(), PDO::PARAM_STR);
        $statement->bindValue(':ville', $client->getVille(), PDO::PARAM_STR);
    }

    private function existe(int $id): bool
    {
        $statement = $this->db->prepare('SELECT 1 FROM clients WHERE id_client = :id LIMIT 1');
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchColumn() !== false;
    }

    private function gererErreurIntegrite(PDOException $exception, bool $suppression = false): never
    {
        if ($exception->getCode() === '23000') {
            $codeSql = (int) ($exception->errorInfo[1] ?? 0);
            if ($suppression && $codeSql === 1451) {
                throw new DomainException(
                    'Ce client est associé à un ou plusieurs équipements et ne peut pas être supprimé.'
                );
            }

            throw new DomainException(
                'Cette opération est impossible car certaines données sont déjà utilisées ou invalides.'
            );
        }

        throw $exception;
    }

    private function hydrater(array $ligne): Client
    {
        require_once Racine . '/../app/modele/Clients.php';
        return new Client(
            (int) $ligne['id_client'],
            (string) $ligne['raison_social'],
            (string) ($ligne['email'] ?? ''),
            (string) ($ligne['tel'] ?? ''),
            (string) ($ligne['adresse'] ?? ''),
            (string) ($ligne['cp'] ?? ''),
            (string) ($ligne['ville'] ?? ''),
        );
    }
    // Faire plus tard les setter si besoin
}