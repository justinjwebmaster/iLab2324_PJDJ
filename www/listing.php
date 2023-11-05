<?php
session_start();
if(!isset($_SESSION['lang']) || $_SESSION['lang']== ""){
  $_SESSION['lang'] = 'fr';
}
$lang = $_SESSION['lang'];

if(isset($_GET['type'])){
  $selectedTypes = $_GET['type'];
} else {
  $selectedTypes = [];
}


require_once 'includes/functions.php';

$twig = getTwigInstance();

$projets = connectJson("projets", $_SESSION['lang']);
$students = connectJson('etudiants', $_SESSION['lang']);
$options = connectJson('options', $_SESSION['lang']);

shuffle($projets);

$template = $twig->load('listing.twig');


foreach ($projets as $key => $projet) {
  $desiredIds = explode(',', $projet['etudiants']);
  $filteredStudents = [];

  foreach ($students as $student) {
    if (in_array($student['id'], $desiredIds)) {
      $filteredStudents[] = $student;
    }
  }

  // Associez les étudiants filtrés au projet correspondant.
  $projets[$key]['filteredStudents'] = $filteredStudents;
}

$filteredProjets = [];

foreach ($projets as $projet) {
  if (empty($selectedTypes) || in_array($projet['option'], $selectedTypes)) {
    $filteredProjets[] = $projet;
  }
}

$tpl_data = [
  'title' => 'Liste des Projets de la HEAJ',
  'lang' => $lang,
  'projets' => $filteredProjets,  // Changez cette ligne pour utiliser $filteredProjets
  'filteredStudents' => $filteredStudents,
  'options' => $options,
  'selectedTypes' => $selectedTypes
];


echo $template->render($tpl_data);

?>
