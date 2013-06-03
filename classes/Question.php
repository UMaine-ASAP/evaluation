<?php


abstract Class Question
{

	/* question prompt */
	private $questionText;

	/* Inidcates whether an answer must be provided or not for submission */
	private $isAnswerRequired;

	/* The category of the question. Categories group similiar questions in the evaluation */
	private $category;

	/* The display precendence of this question. Higher values place the question lower in the display */
	private $weight;

	/* The raw user generated response to the question */
	private $answer;


	public static function retrieve();
	public static function create();
	public function delete();
	public function save();

	/**
	 * Validates input data
	 * 
	 * @param  The provided answer
	 * 
	 * @return   True if succcessful, false otherwise
	 */
	public function validate($answer);
	
	/**
	 * An answer may be complicated
	 */
	public function setAnswer($answer);
}