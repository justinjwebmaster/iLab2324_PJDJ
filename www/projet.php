<?php
session_start();
if($_SESSION['lang'] == ""){
  $_SESSION['lang'] = 'fr';
}
$lang = $_SESSION['lang'];

$projetId = $_GET['id'];

require_once 'includes/functions.php';

$twig = getTwigInstance();

$projets = connectJson("projets", $_SESSION['lang']);
// recuper le projet à afficher et faire un tableau avec ses infos
foreach ($projets as $projet) {
  if ($projet['id'] == $projetId) {
    $display = $projet;
  }
}

$projetsSim = [];
// Récupérer les projets similaires (même option)
foreach($projets as $projetSim) {
  if($projetSim['option'] == $display['option'] && $projetSim['id'] != $projetId) {
    $projetsSim[] = $projetSim;
  }
}

$students = connectJson('etudiants', $_SESSION['lang']);

$curentStudent = null;
foreach($students as $student) {
  if ($student['id'] == $display['etudiants']) {
    $studentName = $student['nom'];
    $curentStudent = $student;
    break;  // quitte la boucle après avoir trouvé le premier étudiant correspondant
  }
}

$url = $curentStudent['site'];
$qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($url);


$template = $twig->load('projets.twig');

$tpl_data = [
  'title' => 'Test listing projet',
  'lang' => $lang,
  'projet' => $display,
  'studentName' => $studentName,
  'student' => $curentStudent,
  'projetId' => $projetId,
  'botMessage' => [
    'description' => $projet['description'],
    'infoSup' => $projet['brief'],
  ],
  'qrImg' => $qrImageUrl,
  'projetsSim' => $projetsSim,
  'students' => $students,
];


echo $template->render($tpl_data);
