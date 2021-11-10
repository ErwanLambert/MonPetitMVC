<?php
namespace APP\Controller;

use APP\Model\GestionCommandeModel;
use ReflectionClass;
use \Exception;

class GestionCommandeController {
    
    public function chercheUne($params) {
        //appel de la méthode find($id) de la classe Model adéquate
        $modele = new GestionCommandeController();
        $id = filter_var(intval($params["id"]), FILTER_VALIDATE_INT);
        $uneCommande = $modele->find($id);
        if ($uneCommande) {
            $r = new ReflectionClass($this);
            include_once PATH_VIEW . str_replace('Controller', 'View', $r->getShortName()) . "/uneCommande.php";
        } else {
            throw new Exception("Commande " . $id . "inconnue");
        }
    }
    public function chercheTous() {
        //appel de la méthode findAll() de la classe Model adequate
        $modele = new GestionCommandeController();
        $commandes = $modele->findAll();
        if ($commandes) {
            $r = new ReflectionClass($this);
            include_once PATH_VIEW . str_replace('Controller', 'View', $r->getShortName()) . "/plusieursCommandes.php";
        } else {
            throw new Exception("Aucune Commande à afficher");
        }
    }
}