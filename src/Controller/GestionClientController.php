<?php
namespace APP\Controller;

use APP\Model\GestionClientModel;
use APP\Entity\Client;
use Tools\MyTwig;
use ReflectionClass;
use \Exception;

class GestionClientController {
    
    public function chercheUn($params) {
        //appel de la méthode find($id) de la classe Model adequate
        $modele = new GestionClientModel();
        // dans tous les cas on récupère les Ids des clients
        $ids = $modele->findIds();
        // on place ces Ids dans le tableau de paramètres que l'on va envoyer à la vue
        $params['lesId']=$ids;
        // on teste si l'id du client à chercher a été passé dans l'URL
        if (array_key_exists('id', $params)) {
            $id = filter_var(intval($params["id"]), FILTER_VALIDATE_INT);
            $unClient = $modele->find($id);
            // on place le client trouvé dans le tableau de paramètres que l'on va envoyer à la vue
            $params['unClient']=$unClient;
        }
        $r = new ReflectionClass($this);
        $vue = str_replace('Controller', 'View', $r->getShortName()) . "/unClient.html.twig";
        MyTwig::afficheVue($vue, $params);
        //} else {
        //    throw new Exception("Client " . $id . " inconnu");
        //}
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
    
    public function creerClient($params) {
        if(empty($params)){
            $vue = "GestionClientView\\creerClient.html.twig";
            MyTwig::afficheVue($vue, array());
        } else {
            $this->enregistreClient($params);
            $this->chercheTous();
        }
    }
    
    public function enregistreClient($params) {
        // création de l'objet client
        $client = new Client($params);
        $modele = new GestionClientModel();
        $modele->enregistreClient($client);
    }
    
    
}