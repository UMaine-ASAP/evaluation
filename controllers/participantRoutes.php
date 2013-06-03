<?php

require_once __DIR__ . '/../classes/Session.php';


$app->get('/view', function() {
	$sessions = Session::retrieveAll();
	render('participate/all-sessions.twig', array('sessions'=>$sessions));	

});
