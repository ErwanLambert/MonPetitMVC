<?php
namespace APP\Model;

use \PDO;
use APP\Entity\Client;
use Tools\Connexion;

class GestionClientModel {
    
    public function find($id) {
        $unObjetPdo = Connexion::getConnexion();
        $sql = "select * from CLIENT where id=:id";
        $ligne = $unObjetPdo->prepare($sql);
        $ligne->bindValue(':id', $id, PDO::PARAM_INT);
        $ligne->execute();
        return $ligne->fetchObject(Client::class);
    }
    
    public function findAll() {
        $unObjetPDO = Connexion::getConnexion();
        $sql = "select * from CLIENT";
        $lignes = $unObjetPDO->query($sql);
        return $lignes->fetchAll(PDO::FETCH_CLASS, Client::class);
    }
}