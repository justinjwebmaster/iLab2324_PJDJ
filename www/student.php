<?php
session_start();
if($_SESSION['lang'] == ""){
  $_SESSION['lang'] = 'fr';
}
$lang = $_SESSION['lang'];

$studentId = $_GET['id'];

require_once 'includes/functions.php';

//get twig instance
$twig = getTwigInstance();

// connexion json
$projets = connectJson("projets", $_SESSION['lang']);
$students = connectJson('etudiants', $_SESSION['lang']);
$option = connectJson('options', $_SESSION['lang']);

$display =[];
// récuperer l'étudiant à afficher et faire un tableau avec ses infos
foreach ($students as $student) {
  if ($student['id'] == $studentId) {
    $display = $student;
  }
}

// Récuperer les projets de cet étudiant
$studentProject = [];
foreach($projets as $projet) {
  $allStudentInProject = explode(',', $projet['etudiants']);
  if (in_array($studentId, $allStudentInProject)) {
    $studentProject[] = $projet;
  }
}

// Récupérer l'option de l'étudiant
$studentOption = [];
foreach($option as $opt) {
  if ($opt['id'] == $display['option']) {
    $studentOption = $opt;
  }
}


$template = $twig->load('student.twig');

$tpl_data = [
  'title' => $display['nom'],
  'lang' => $lang,
  'studentId' => $studentId,
  'student' => $display,
  'studentProject' => $studentProject,
  'studentOption' => $studentOption
];


echo $template->render($tpl_data);
