<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


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
$options = connectJson('options', $_SESSION['lang']);

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

$images = [];
for ($i = 1; $i <= 6; $i++) {
  if (!empty($display['img' . $i])) {
    $images[] = ['url' => $display['img' . $i], 'class' => ''];
  }
}

$count = count($images);
$index = 0;
$prev_pattern = null;

function getNextPattern($prev) {
  $patterns = ($prev == '13') ? ['31', '33'] : (($prev == '31') ? ['13', '33'] : ['13', '31']);
  return $patterns[array_rand($patterns)];
}

while ($index < $count) {
  $imagesLeft = $count - $index;
  if ($imagesLeft >= 3) {
    $pattern = getNextPattern($prev_pattern);
  } elseif ($imagesLeft == 2) {
    $pattern = '31';
  } else {
    $pattern = '33';
  }

  $prev_pattern = $pattern;

  if ($pattern == '13' && $imagesLeft > 1) {
    $images[$index]['class'] = 'one-col';
    $index++;
    $images[$index]['class'] = 'two-cols';
    $index++;
  } elseif ($pattern == '31' && $imagesLeft > 1) {
    $images[$index]['class'] = 'two-cols';
    $index++;
    $images[$index]['class'] = 'one-col';
    $index++;
  } else {
    $images[$index]['class'] = 'three-cols';
    $index++;
  }
}



$template = $twig->load('projets.twig');

$tpl_data = [
  'title' => $display['nom'] . ' – ' . $display['option'],
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
  'allCurrentStudents' => $studentDisplay,
  'options' => $options,
  'images' => $images,
];

//var_dump($studentDisplay);
echo $template->render($tpl_data);

