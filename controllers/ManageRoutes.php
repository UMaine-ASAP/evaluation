<?php

require_once __DIR__ . '/../classes/Session.php';

/* =============================== */
/* Helper Routes
/* =============================== */

function validateSessionExists($session)
{
	$app = \Slim\Slim::getInstance();
	if( !is_null($session) )
	{
		$app->flash('error', ErrorResponses::NOTFOUND_SESSION);
		$app->redirect('/');
	}	
}

function validateTopicExists($topic)
{
	$app = \Slim\Slim::getInstance();	
	if( !is_null($topic) )
	{
		$app->flash('error', ErrorResponses::NOTFOUND_TOPIC);
		$app->redirect('/');
	}	
}

function validateTopicExistsInSession($session, $topic)
{
	$app = \Slim\Slim::getInstance();
	if( ! $session->topicExists($topic) )
	{
		$app->flash('error', ErrorResponses::NOTFOUND_TOPICINSESSION);
		$app->redirect('/');
	}	
}

function validateUserManagesSession($session)
{
	validateSessionExists($session);

	if( ! in_array($session, User::sessionsManaged() ) )
	{
		$app->flash('error', ErrorResponses::MANAGEMENT_PERMISSIONDENIED);
		$app->redirect('/');
	}
}

function validateUserManagesTopic($session, $topic)
{
	validateSessionExists($session);
	validateUserManagesSession($session);

	validateTopicExists($topic);
	validateTopicExistsInSession($session, $topic);

}


$app->get('/manage', function() {
	redirect('/manage/all-sessions');
});

$app->get('/manage/all-sessions', function() {
	// @TODO: validate user is root admin

	$sessions = Session::retrieveAll();
	render('manager/all-sessions.twig', array('sessions'=>$sessions));	
});

$app->get('/manage/session', function() {
	redirect('/manage/all-sessions');	
});

$app->get('/manage/session/:sid', function($sid) use ($app) {
	$session = Session::retrieve($sid);

	if($session == null) 
	{
		$app->flash('error', 'Session not found!');
		redirect('/manage/all-sessions');
	}
	render('manager/session.twig', array('session'=>$session), array('sessions'=>'manage/all-sessions', $session->title=>''));	
});


$app->get('/manage/session/:sid/edit-member-view/:mid', function($sid, $mid) {
	//@TODO: permission checks
	$session = Session::retrieve($sid);
	$member = Member::retrieve($mid);
	$role = Role::retrieve(1);
	$result = $member->hasRole($role)?'Yes':'No';
	error_log('has role: ' . $role->name . ' user ' . $member->fullName . ': '. $result);
	render('manager/forms/editMemberModal.twig', array('session'=>$session, 'member'=>$member, 'allRoles'=>Role::allRoles()));
});

// View topic in session
$app->get('/manage/session/:sid/topic/:tid', function($sid, $tid) use ($app) {
	$session = Session::retrieve($sid);
	$topic   = Topic::retrieve($tid);

	// @TODO: make sure topic belongs to session

	if($topic == null) 
	{
		$app->flash('error', 'Topic not found!');
		redirect('/manage/session/'.$sid);
	}
	$breadcrumbs = array('sessions'     => 'manage/all-sessions', 
					$session->title => 'manage/session/'.$session->id,
					$topic->name    => '');
	render('manager/topic.twig', array('topic'=>$topic), $breadcrumbs);	
});


/* =============================== */
/*  Member Management
/* =============================== */

/**
 * Add User(s) as members to Session
 * 
 * Requires:
 * - logged in user is a manager of session
 * - Session is a valid session
 * - Users are valid
 */
$app->post('/manage/session/:sessionId/addUsersAsMembers', function($sessionId) use ($app) {
	// @TODO: validate user is manager of session


	// Get and validate Session
	$session = Session::retrieve($sessionId);
	//validateUserManagesSession($session);

	// Get and validate data
	$name = $app->request()->post('name');

	$result = '';
	foreach( $app->request()->post() as $key=>$userId)
	{
		if( strpos($key, 'user-') !== false)
		{
			$user = User::retrieve($userId);

			$session->addMember($user);
			$result += 'Added User ' . $user->fullName . '<br>';
		}
	}
		$app->flash('success', $result);

		redirect("/manage/session/$sessionId");	
});


/**
 * Add Role to Member in Session
 * 
 * Requires:
 * - logged in user is a manager of session
 * - Session is a valid session
 * - Users are valid
 * - User is a member
 */
$app->post('/manage/session/:sessionId/modifyRoles', function($sessionId) use ($app) {
	// @TODO: validate user is manager of session


	// Get and validate Session
	$session = Session::retrieve($sessionId);
	//validateUserManagesSession($session);

	$member = Member::retrieve( $app->request()->post('memberId') );
	$role   = Role::retrieve( $app->request()->post('roleId') );

	foreach (Role::allRoles() as $role) {
		// Since we're using a checkbox, if the value is set, we're adding (or keeping) the role
		if ( $app->request()->post('role-'.$role->id) != null ) {
			// add role
			$session->addRole($member, $role);
		} else {
			// remove role
			$session->removeRole($member, $role);
		}
	}

	// @TODO: validate member is a member of session

	//$session->addRole($member, $role);

	redirect("/manage/session/$sessionId");	
});

/* =============================== */
/* Session Management
/* =============================== */

/**
 * Create a Session
 * 
 * Requires:
 * - logged in user manages session
 * - Session is a valid session
 * - Topic exists
 * - Topic exists in session
 */
$app->post('/manage/session/create', function() use ($app) {
	// @TODO: validate user is root admin

	// Get and validate data
	$name = $app->request()->post('name');

	if( $name != '' )
	{
		$session = Session::create($name);
		redirect('/manage/session/' . $session->id);
	} else {
		flash('error', 'Session name is required!');
		redirect('/manage/all-sessions');
	}
});



/**
 * Remove a Topic
 * 
 * Requires:
 * - logged in user manages session
 * - Session is a valid session
 * - Topic exists
 * - Topic exists in session
 */
$app->post('/manage/session/:sessionId/removetopic/:topicId', function($sessionId, $topicId) {

	// Get and validate data
	$session = Session::retrieve($sessionId);
	$topic = Topic::retrieve($topicId);

	validateUserManagesTopic($session, $topic);

	// Remove topic
	$session->removeTopic($topic);
});


/**
 * Add a new Topic to an existing session
 * 
 * Requires:
 * 	- logged in user manages session
 * 	- Session is a valid session
 * 	- Topic does not already exist
 */
$app->post('/manage/session/:sessionId/addTopic', function($sessionId) use ($app) {

	// Get and validate data
	$session = Session::retrieve($sessionId);
	//validateUserManagesSession($session);

	$newTopicName = $app->request()->post('name');

	// ensure topic name doesn't already exist
	foreach ($session->topics as $topic) {
		if($topic->name == $newTopicName)
		{
			$app->flash('error', ErrorResponses::MANAGEMENT_SESSION_TOPIC_ALREADY_EXISTS);
			$app->redirect('/');
		}
	}
	$session->createTopic($newTopicName);
});


/* =============================== */
/* Topic Management
/* =============================== */

/**
 * Add a Geode
 * 
 * Requires:
 * - logged in user manages session
 * - Session is a valid session
 * - Topic exists
 * - Topic exists in session
 */
$app->post('/manage/topic/:topicId/addGeode/:newGeodeName', function($topicId, $newTopicName) {

	// Get and validate data
	$sessionId = $app->request()->post('sessionId');
	$session   = Session::retrieve($sessionId);
	$topic     = Topic::retrieve($topicId);

	validateUserManagesTopic($session, $topic);

	// Add new Geode to topic
	$geode = Geode::create($newGeodeName);
	$topic->addTopic($topic);
});


