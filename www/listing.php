<?php
session_start();
if($_SESSION['lang'] == ""){
  $_SESSION['lang'] = 'fr';
}
$lang = $_SESSION['lang'];

var_dump($lang);

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

echo $template->render($tpl_data);
