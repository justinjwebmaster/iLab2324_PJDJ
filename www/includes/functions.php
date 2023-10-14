<?php

require_once __DIR__ . '/../../vendor/autoload.php';

function getTwigInstance() {
  // le dossier ou on trouve les templates
  $loader = new Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
  // initialiser l'environnement Twig (avec debug)
  $twig = new Twig\Environment($loader, ['debug' => true]);

  return $twig;
}

function connectJson($file, $lang){

  $json = file_get_contents('datas/' . $lang . '/' . $file . '.json');

  $data = json_decode($json, true);  // true pour obtenir un tableau associatif

  // Vérifier si le décodage a fonctionné
  if (json_last_error() !== JSON_ERROR_NONE) {
    die('Erreur de décodage JSON ETUDIANTS : ' . json_last_error_msg());
  }else{
    return $data;
  }

}
