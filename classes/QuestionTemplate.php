<?php

interface QuestionTemplate
{

	/* question prompt */
	private $questionText;

	/* Inidcates whether an answer must be provided or not for submission */
	private $isAnswerRequired;

	/* Indicates who created this question template */
	private $creator;

	/* Indicates whether this template should be available for other users to use in templates */
	private $isPrivate;


	/* ======================================= */
	/* = Data Management
	/* ======================================= */

	public static function retrieve($id)
	{

	}

	public static function create()
	{

	}

	public function delete()
	{

	}


}