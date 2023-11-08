<?php
session_start();
if (!isset($_SESSION['lang']) || $_SESSION['lang'] == "") {
  $_SESSION['lang'] = 'fr';
}
$lang = $_SESSION['lang'];

if (isset($_GET['type'])) {
  $selectedTypes = $_GET['type'];
} else {
  $selectedTypes = [];
}

require_once 'includes/functions.php';

$twig = getTwigInstance();
$template = $twig->load('listing.twig');

$projets = connectJson("projets", $_SESSION['lang']);
$students = connectJson('etudiants', $_SESSION['lang']);
$options = connectJson('options', $_SESSION['lang']);

shuffle($projets);

$prev_pattern = ''; // Pour suivre le motif précédent

function getNextPattern($prev) {
  $patterns = ($prev == '13') ? ['31', '33'] : (($prev == '31') ? ['13', '33'] : ['13', '31']);
  return $patterns[array_rand($patterns)];
}

foreach ($projets as $key => $projet) {
  $desiredIds = explode(',', $projet['etudiants']);
  $filteredStudents = [];

  foreach ($students as $student) {
    if (in_array($student['id'], $desiredIds)) {
      $filteredStudents[] = $student;
    }
  }
  $projets[$key]['filteredStudents'] = $filteredStudents;
}

$filteredProjets = [];
foreach ($projets as $projet) {
  if (empty($selectedTypes) || in_array($projet['option'], $selectedTypes)) {
    $filteredProjets[] = $projet;
  }
}

$count = count($filteredProjets);
$index = 0;
while ($index < $count) {
  $pattern = getNextPattern($prev_pattern);
  $prev_pattern = $pattern;

  if ($pattern == '13' && $index + 1 < $count) {
    $filteredProjets[$index]['pattern'] = 'one-col';
    $index++;
    $filteredProjets[$index]['pattern'] = 'two-cols';
    $index++;
  } elseif ($pattern == '31' && $index + 1 < $count) {
    $filteredProjets[$index]['pattern'] = 'two-cols';
    $index++;
    $filteredProjets[$index]['pattern'] = 'one-col';
    $index++;
  } else {
    $filteredProjets[$index]['pattern'] = 'three-cols';
    $index++;
  }
}

$message = "Salut, moi c’est <span class='nom__bot title'>NO-ID</span> Touche un projet et je t’apprendrai tout ce dont tu as besoin de savoir !";


$tpl_data = [
  'title' => 'listing projet',
  'lang' => $lang,
  'projets' => $filteredProjets,
  'filteredStudents' => $filteredStudents,
  'options' => $options,
  'selectedTypes' => $selectedTypes,
  "messageHeader" => $message,
];

echo $template->render($tpl_data);
?>
