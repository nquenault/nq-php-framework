<?php if(!defined('BASEPATH')) die('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Paramètres de connection à la base de données
|--------------------------------------------------------------------------
|
| timeout :
| Temps alloué à la tentative de connection à la base de données
| avant de tomber en echec (en secondes).
|
*/
$config['db']['hostname'] = 'localhost';
$config['db']['database'] = '';
$config['db']['username'] = '';
$config['db']['password'] = '';
$config['db']['timeout'] = 5;


/*
|--------------------------------------------------------------------------
| Titre du site web
|--------------------------------------------------------------------------
|
| Permet de définir le titre du site web qui sera affiché
| dans chaque titre de page
|
*/
$config['site_title'] = 'Welcome !';

/*
|--------------------------------------------------------------------------
| JQuery
|--------------------------------------------------------------------------
|
| Permet de gérer l'intégration de JQuery ainsi que la version utilisée
|
| Plugins : core, widget, mouse, draggable
|
*/
$config['jquery']['enabled'] = true;
$config['jquery']['version'] = '1.7';
$config['jquery']['plugins'] = array();

/*
|--------------------------------------------------------------------------
| KineticJS
|--------------------------------------------------------------------------
|
| KineticJS is an HTML5 Canvas JavaScript framework that enables
| high performance animations, transitions, node nesting, layering, filtering,
| caching, event handling for desktop and mobile applications, and much more.
|
*/
$config['kineticjs']['enabled'] = false;
$config['kineticjs']['version'] = '4.3.0';

/*
|--------------------------------------------------------------------------
| Paramètrage de la page par défaut
|--------------------------------------------------------------------------
*/
$config['default_page'] = 'welcome';

/*
|--------------------------------------------------------------------------
| Jeu de caractère par défaut
|--------------------------------------------------------------------------
*/
$config['charset'] = 'UTF-8';

/*
|--------------------------------------------------------------------------
| Suffix URL
|--------------------------------------------------------------------------
|
| Cette option permet de modifier le suffix de toutes les URLs générées
|
*/
$config['url_suffix'] = '.html';

/*
|--------------------------------------------------------------------------
| SESSION
|--------------------------------------------------------------------------
|
| session_expire : temps d'expiration de la session
|
*/
$config['session_name'] = 'PHPSESSID';
$config['session_expire'] = array(
	'secondes'	=> 0,
	'minutes'	=> 0,
	'hours'		=> 3,
	'days'		=> 0
);

/*
|--------------------------------------------------------------------------
| Comportements divers
|--------------------------------------------------------------------------
|
| HOME_ON_NOTFOUND :
| Si à true, l'utilisateur sera redirigé vers la page d'accueil
| lorsqu'une page n'est pas trouvée. Sinon, affichera une page d'erreur 404.
|
*/
$config['behavior']['HOME_ON_NOTFOUND'] = true;

/*
|--------------------------------------------------------------------------
| Langue(s)
|--------------------------------------------------------------------------
|
| Définie la ou les langue(s) primaires du site web,
| ainsi que la langue de traitement du contenu.
|
| Dans le cas ou plusieurs langues sont définies (eg: fr,en),
| la langue de traitement du contenu sera la 1ère définie dans la chaîne.
|
*/
$config['lang'] = 'fr,en';

/*
|--------------------------------------------------------------------------
| Navigation AJAX basée sur les ancres (BETA)
|--------------------------------------------------------------------------
|
| Défini si la navigation se faire page par page (monsite.com/mapage.html?param=1),
| ou si la navigation se fait par les ancres (monsite.com/#mapage.html?param=1).
|
| Si la navigation ajax est active seulement une partie de la page principale
| est changée.
|
*/
$config['anchors_ajax_navigation'] = false;

/*
|--------------------------------------------------------------------------
| Filtrage de vulnérabilités XSS (Cross Site Scripting)
|--------------------------------------------------------------------------
|
| Permet de définir le filtrage XSS sur les variables globales
|
*/
$config['xss_filtering']['REQUEST'] = false;
$config['xss_filtering']['GET'] = true;
$config['xss_filtering']['POST'] = true;
$config['xss_filtering']['COOKIE'] = true;

?>
