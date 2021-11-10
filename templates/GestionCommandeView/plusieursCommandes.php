<?php
include_once PATH_VIEW . "header.html";
echo "<p>Nombre de commandes trouvées : ".count($commandes) ."<p>";

foreach ($commandes as $commande){
    echo $commande->getId() . " - " . $commande->getIdClient() . " - " . $commande->getDateCde() . " - " . $commande->getNoFacture() . "<br>";
}
include_once PATH_VIEW . "footer.html";