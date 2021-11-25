<?php
namespace APP\Controller;

use APP\Model\GestionCommandeModel;
use APP\Model\GestionClientModel;
use APP\Repository\CommandeRepository;
use APP\Repository\ClientRepository;
use ReflectionClass;
use \Exception;
use Tools\MyTwig;
use Tools\Repository;

class GestionCommandeController {
    
    public function chercheUne($params) {
        //appel de la méthode find($id) de la classe Model adéquate
        $repository = Repository::getRepository("APP\Entity\Commande");
        $ids = $repository->findIds();
        $params['lesId'] = $ids;
        if(array_key_exists('id', $params)){
            $id = filter_var(intval($params['id']), FILTER_VALIDATE_INT);
            $uneCommande = $repository->find($id);
            $params['uneCommande'] = $uneCommande;
        }
        $r = new ReflectionClass($this);
        $vue = str_replace('Controller', 'View', $r->getShortName()) . "/uneCommande.html.twig";
        MyTwig::afficheVue($vue, $params);
    }
    public function chercheToutes() {
        //instanciation du repository
        $modele = new GestionCommandeModel();
        $commandes = $modele->findAll();
        if ($commandes) {
            $r = new ReflectionClass($this);
            include_once PATH_VIEW . str_replace('Controller', 'View', $r->getShortName()) . "/plusieursCommande.php";
        } else {
            throw new Exception("Aucune Commande à afficher");
        }
    }
    
    public function commandesUnClient($idClient) {
        $idClient = filter_var(intval($idClient['id']), FILTER_VALIDATE_INT);
        //$modele = new GestionCommandeModel();
        $repository = new CommandeRepository("APP\Entity\Commande");
        //$modeleClient = new GestionClientModel();
        $repositoryClient = new ClientRepository("APP\Entity\Client");
        //$commandes = $modele->findCommandesClient($idClient['id']);
        $commandes = $repository->findCommandesClient($idClient);
        //$client = $modeleClient->find($idClient['id']);
        $client = $repositoryClient->find($idClient);
        $r = new ReflectionClass($this);
        $vue = str_replace('Controller', 'View', $r->getShortName()) . "/commandesClient.html.twig";
        $params = array(
            'commandes' => $commandes,
            'client' => $client
        );
        MyTwig::afficheVue($vue, $params);
    }
}