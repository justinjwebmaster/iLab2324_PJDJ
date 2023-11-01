<?php
session_start();
if(!isset($_SESSION['lang']) || $_SESSION['lang']== ""){
  $_SESSION['lang'] = 'fr';
}
$lang = $_SESSION['lang'];


require_once 'includes/functions.php';

$twig = getTwigInstance();

$projets = connectJson("projets", $_SESSION['lang']);
$students = connectJson('etudiants', $_SESSION['lang']);

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

$tpl_data = [
  'title' => 'Test listing projet',
  'lang' => $lang,
  'projets' => $projets,
  'filteredStudents' => $filteredStudents,
];


echo $template->render($tpl_data);

?>
