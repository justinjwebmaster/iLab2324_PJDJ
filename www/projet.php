<?php
session_start();
if(!isset($_SESSION['lang']) || $_SESSION['lang']== ""){
  $_SESSION['lang'] = 'fr';
}
$lang = $_SESSION['lang'];

$projetId = $_GET['id'];

require_once 'includes/functions.php';

//get twig instance
$twig = getTwigInstance();

// connexion json
$projets = connectJson("projets", $_SESSION['lang']);
$students = connectJson('etudiants', $_SESSION['lang']);

$display =[];
// recuper le projet à afficher et faire un tableau avec ses infos
foreach ($projets as $projet) {
  if ($projet['id'] == $projetId) {
    $display = $projet;
  }
}

// Récupérer les projets similaires (même option)
$projetsSim = [];
foreach($projets as $projetSim) {
  if($projetSim['option'] == $display['option'] && $projetSim['id'] != $projetId) {
    $projetsSim[] = $projetSim;
  }
}

// Récupérer les étudiants du projet présent dans le json
$allStudentProjet = explode(',', $display['etudiants']);

// faire un tableau avec les infos de ces étudiants
$studentDisplay = [];
foreach($students as $student) {
  if (in_array($student['id'], $allStudentProjet)) {
    $url = "https://justin.willemet.be/projets/ilab/student.php?id=" . $student['id'];
    $student['qrImage'] = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($url);
    $studentDisplay[] = $student;
  }
}
/* Si un seul étudiant est associé au projet, on peut faire comme ça :
$curentStudent = null;
foreach($students as $student) {
  if ($student['id'] == $display['etudiants']) {
    $studentName = $student['nom'];
    $curentStudent = $student;
    break;  // quitte la boucle après avoir trouvé le premier étudiant correspondant
  }
}
*/

/* // ajouter url pour qr code dasn le tbleau à l'étudiant correspondant
$url = $curentStudent['site'];
$qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($url); */


$template = $twig->load('projets.twig');

$tpl_data = [
  'title' => 'Test listing projet',
  'lang' => $lang,
  'projet' => $display,
  // 'studentName' => $studentName,
  // 'student' => $curentStudent,
  'projetId' => $projetId,
  'botMessage' => [
    'description' => $display['description'],
    'infoSup' => $display['brief'],
  ],
  'projetsSim' => $projetsSim,
  'students' => $students,
  'allCurrentStudents' => $studentDisplay
];

//var_dump($studentDisplay);
echo $template->render($tpl_data);

