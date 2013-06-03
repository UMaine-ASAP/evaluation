<?php

session_start();

require_once 'libraries/helpers.php';


// Libraries
require_once 'libraries/idiorm.php';
require_once 'libraries/paris.php';

/* Slim Setup */
require 'libraries/Slim/Slim.php';
Slim\Slim::registerAutoloader();
require 'libraries/Slim/Extras/Views/Twig.php';


$app = new Slim\Slim(array(
    'view' => new Slim\Extras\Views\Twig
));



/* Configuration file */
require_once 'configuration.php';

require_once 'classes/ErrorResponses.php';

// Database Setup
ORM::configure("mysql:host=localhost;dbname=$DB_NAME");
ORM::configure('username', $DB_USER);
ORM::configure('password', $DB_PASS);


/**
 * Renders a webpage
 * 
 * Wraps the default $app->render() function to provide global variables to every template
 * 
 * @param    String    templateName         The path to the template file relative to the templates directory
 * @param    Array     templateVariables    Variables available in the templates
 */
// 
function render($templateName, $templateVariables = array(), $breadcrumbs=array()) {
	$app = \Slim\Slim::getInstance();

	$templateVariables['WEBROOT']     = $GLOBALS['WEBROOT'];
	$templateVariables['breadcrumbs'] = $breadcrumbs;

	// Set path variables
	$templateVariables['CSSPATH']   = $GLOBALS['WEBROOT'] . '/views/css';
	$templateVariables['JSPATH']    = $GLOBALS['WEBROOT'] . '/views/javascript';
	$templateVariables['IMAGEPATH'] = $GLOBALS['WEBROOT'] . '/views/images';

	// Get the name of the rendered template
	$fileNameArray = explode('.', basename($templateName));
	$templateVariables['TEMPLATENAME'] = $fileNameArray[0];

	
	// $templateVariables['ISLOGGEDIN'] = ApplicantController::applicantIsLoggedIn();
	// if( $templateVariables['ISLOGGEDIN'] )
	// {
	// 	$applicant = ApplicantController::getActiveApplicant();
	// 	$templateVariables['EMAIL'] = $applicant->loginEmail;
	// }

	return $app->render($templateName, $templateVariables);
}

function redirect($url)
{
	$app = \Slim\Slim::getInstance();

	return $app->redirect($GLOBALS['WEBROOT'] . $url);
}

/* Routes manage url responses and user permissions to system features */

/* Get Member routes */
//require_once "controllers/MemberRoutes.php";


/* Get Manager routes */
require_once "controllers/ManageRoutes.php";



$app->run();