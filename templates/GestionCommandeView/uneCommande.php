<?php
include_once PATH_VIEW . "header.html";
var_dump($uneCommande);
echo "Id du client : " . $uneCommande->getIdClient();
include_once PATH_VIEW . "footer.html";