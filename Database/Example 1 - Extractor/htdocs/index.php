<?php

define("PP_NET_CL_PATH", realpath("../../../../Edify"));
require_once PP_NET_CL_PATH . "/Utils/Loader.php";

$db = new \Edify\Database\Server(\Edify\Database\Server::MYSQL);
$db->getConnection("myDBName", "localhost", "root", "root");

$extractor = new \Edify\Database\Extractor($db, realpath("../../../Bloodmoongames/bmg/Models"), "Bmg\\Models\\");
$extractor->run();
print "Finished check the models directory";
exit();
?>
