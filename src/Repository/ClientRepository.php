<?php

namespace APP\Repository;

use Tools\Repository;
use APP\Entity\Client;
use PDO;
use Tools\Connexion;

class ClientRepository extends Repository {
    public function __construct($entity) {
        parent::__construct($entity);
    }
    
    public function find($id) {
        $unObjetPdo = $this->connexion;
        $sql = "select * from CLIENT where id=:id";
        $ligne = $unObjetPdo->prepare($sql);
        $ligne->bindValue(':id', $id, PDO::PARAM_INT);
        $ligne->execute();
        return $ligne->fetchObject(Client::class);
    }
    
    public function statistiquesTousClients(): array {
        $sql = "select CLIENT.id, nomCli, prenomCli, villeCli, count(C.id) as nbCommande 
                from CLIENT 
                left join COMMANDE C on CLIENT.id = C.idClient";
        $resultat = $this->executeSQL($sql);
        return $resultat;
    }
}