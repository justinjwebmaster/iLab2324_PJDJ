<?php
session_start();
if($_SESSION['lang'] == ""){
  $_SESSION['lang'] = 'fr';
}
$lang = $_SESSION['lang'];


require_once 'includes/functions.php';

$twig = getTwigInstance();

$projets = connectJson("projets", $_SESSION['lang']);
$students = connectJson('etudiants', $_SESSION['lang']);

shuffle($projets);

$template = $twig->load('listing.twig');

$tpl_data = [
  'title' => 'Test listing projet',
  'lang' => $lang,
  'projets' => $projets,
  'students' => $students,

];
foreach ($projets as $project) {
  echo "Nom du projet : " . $project["nom"] . "<br>";
}

echo $template->render($tpl_data);

?>

<div class="text-2xl flex">
  <h1>test</h1>
  <h1>Tester</h1>
</div>
