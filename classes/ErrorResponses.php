<?php

class ErrorResponses
{


	// Management Errors : 2XXX
	const MANAGEMENT_PERMISSION_DENIED            = 2001;
	const MANAGEMENT_SESSION_TOPIC_ALREADY_EXISTS = 2002;

	// Item not Found : 3XXX
	const NOTFOUND_TOPIC                  = 3001;
	const NOTFOUND_SESSION                = 3002;
	const NOTFOUND_TOPIC_IN_SESSION       = 3003;
	const NOTFOUND_QUESTION_IN_EVALUATION = 3004;

	// InValid Data : 4XXX
	const INVALID_DATA_QUESTION = 4001;

	public $errorResponseMessages = array(

		MANAGEMENT_PERMISSIONDENIED             => 'permission denied',
		MANAGEMENT_SESSION_TOPIC_ALREADY_EXISTS => 'Topic already exists',
		
		NOTFOUND_TOPICINSESSION => 'Topic not found in session',
		NOTFOUND_TOPIC          => 'Topic does not exist',
		NOTFOUND_SESSION        => 'Session does not exist',
	); 
	
	public static function message($responseCode)
	{
		if( array_key_exists($responseCode, ErrorResponses::$errorResponseMessages))
		{
			return ErrorResponses::$errorResponseMessages[$responseCode];
		} else {
			return "an error occurred";
		}	
	}
}