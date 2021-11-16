<?php
namespace APP\Controller;

use APP\Model\GestionClientModel;
use Tools\MyTwig;
use ReflectionClass;
use \Exception;

class GestionClientController {
    
    public function chercheUn($params) {
        //appel de la méthode find($id) de la classe Model adequate
        $modele = new GestionClientModel();
        $id = filter_var(intval($params["id"]), FILTER_VALIDATE_INT);
        $unClient = $modele->find($id);
        if ($unClient) {
            $r = new ReflectionClass($this);
            $vue = str_replace('Controller', 'View', $r->getShortName()) . "/unClient.html.twig";
            MyTwig::afficheVue($vue, array('unClient' => $unClient));
        } else {
            throw new Exception("Client " . $id . " inconnu");
        }
    }
    
    public function chercheTous() {
        //appel de la méthode findAll() de la classe Model adequate
        $modele = new GestionClientModel();
        $clients = $modele->findAll();
        if ($clients) {
            $r = new ReflectionClass($this);
            $vue = str_replace('Controller', 'View', $r->getShortName()) . "/tousClients.html.twig";
            MyTwig::afficheVue($vue, array('tousClients' => $clients));
        } else {
            throw new Exception("Aucun Client à afficher");
        }
    }
}