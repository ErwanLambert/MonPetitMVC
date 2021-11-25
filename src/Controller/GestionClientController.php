<?php
namespace APP\Controller;

use APP\Model\GestionClientModel;
use APP\Entity\Client;
use Tools\MyTwig;
use ReflectionClass;
use \Exception;
use Tools\Repository;

class GestionClientController {
    
    public function chercheUn($params) {
        //appel de la méthode find($id) de la classe Model adequate
        $repository = Repository::getRepository("APP\Entity\Client");
        // dans tous les cas on récupère les Ids des clients
        $ids = $repository->findIds();
        // on place ces Ids dans le tableau de paramètres que l'on va envoyer à la vue
        $params['lesId']=$ids;
        // on teste si l'id du client à chercher a été passé dans l'URL
        if (array_key_exists('id', $params)) {
            $id = filter_var(intval($params["id"]), FILTER_VALIDATE_INT);
            $unClient = $repository->find($id);
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
        //instanciation du repository
        $repository = Repository::getRepository("APP\Entity\Client");
        $clients = $repository->findAll();
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
            //Création de l'objet client
            $client = new Client($params);
            $repository = Repository::getRepository("APP\Entity\Client");
            $repository->insert($client);
            $this->chercheTous();
        }
    }
    
    public function enregistreClient($params) {
        // création de l'objet client
        $client = new Client($params);
        $modele = new GestionClientModel();
        $modele->enregistreClient($client);
    }
    
    public function nbClients($params){
        $repository = Repository::getRepository("APP\Entity\Client");
        $nbClients = $repository->countRows();
        echo "Nombre de clients : " . $nbClients;
    }
    
    public function statsClients(){
        $repository = Repository::getRepository("APP\Entity\Client");
        $statsClients = $repository->statistiquesTousClients();
        //array_multisort($$repository, $statsClients);
        if ($statsClients){
            $r = new ReflectionClass($this);
            $vue = str_replace('Controller', 'View', $r->getShortName()). "/statsClients.html.twig";
            MyTwig::afficheVue($vue, array('statsClients' => $statsClients));
        } else {
            throw new Exception("Aucune statistiques à afficher");
        }
    }
    
    public function testFindBy($params) {
        $repository = Repository::getRepository("APP\Entity\Client");
        //$params = array("titreCli" => "Monsieur", "villeCli" => "Toulon");
        //$clients = $repository->findBytitreCli_and_villeCli($params);
        $params = array("cpCli" => "14000", "titreCli" => "Madame");
        $clients = $repository->findBycpCli_and_titreCli($params);
        $r = new ReflectionClass($this);
        $vue = str_replace('Controller', 'View', $r->getShortName()) . "/tousClients.html.twig";
        MyTwig::afficheVue($vue, array('tousClients' => $clients));
    }
    
    public function rechercheClients($params){
        $repository = Repository::getRepository("APP\Entity\Client");
        $titres = $repository->findColumnDistinctValues('titreCli');
        $cps = $repository->findColumnDistinctValues('cpCli');
        $villes = $repository->findColumnDistinctValues('villeCli');
        $paramsVue['titres'] = $titres;
        $paramsVue['cps'] = $cps;
        $paramsVue['villes'] = $villes;
        if (isset($params['titreCli']) || isset($params['cpCli']) || isset($params['villeCli'])) {
            // c'est le retour du formulaire de choix de filtre
            $element = "Choisir...";
            while (in_array($element, $params)){
                unset($params[array_search($element, $params)]);
            }
            if (count($params) > 0){
                $clients = $repository->findBy($params);
                $paramsVue['tousClients'] = $clients;
                foreach ($_POST as $valeur) {
                    ($valeur != "Choisir...") ? ($criteres[] = $valeur) : (null);
                }
                $paramsVue['criteres'] = $criteres;
            }
        }
        $vue = "GestionClientView\\filtreClients.html.twig";
        MyTwig::afficheVue($vue, $paramsVue);
    }
    
    public function recupereDesClients($params) {
        $repository = Repository::getRepository("APP\Entity\Client");
        $clients = $repository->findBy($params);
        $r = new ReflectionClass($this);
        $vue = str_replace('Controller', 'View', $r->getShortName()) . "/tousClients.html.twig";
        MyTwig::afficheVue($vue, array('tousClients' => $clients));
    }
}