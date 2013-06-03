<?php

Class QuestionTemplateInEvaluationTemplate extends Model
{
	/* The question template */
	private $questionTemplate;

	/* The category of the question. Categories group similiar questions in the evaluation */
	private $category;

	/* The visual priority this question has relative to other questions in the evaluation template */
	private $weight;
}

Class EvaluationTemplate extends Model
{

	/* Title of the evaluation */
	private $title;


	/* Question instances of QuestionTemplateInEvaluationTemplate */
	private $questions;

	// Optional
	private $purpose;
	private $isPrivate;
	private $creator;


	/**
	 * Gets an evaluation template from the database
	 * 
	 * @param Unique id of the evaluation template to retrieve
	 * 
	 * @return EvaluationTemplate or null if not found
	 */
	public static function retrieve($id)
	{
		return Model::factory('EvaluationTemplate')->find_one($id);
	}

	/**
	 * Creates a new Evaluation Template with the provided information
	 * 
	 * @param  string  The title of the template
	 * @param  array   Array of question Templates
	 * @param  string  Description of the purpose of the template
	 * @param  bool    Indicate whether this template should be shared with other sessions
	 * @param  User    The user who created this evaluation  
	 * 
	 * @return Boolean indicating whether operation was succesful or not
	 */
	public static function create($title, $questions=array(), $purpose='', $isPrivate=false, $creator=null)
	{
		// Verify data
		
		// Create new template
		$et = Model::factory('EvaluationTemplate')->create();
		$et->
		$et->save();

		return true;
	}

	public function delete($doDestroyAttachedQuestions)
	{
		// Don't delete sub questions unless they don't belong to other evaluations
	}

	/**
	 * Add a question to the evaluation template
	 * 
	 * @param Question Template object to add to the template
	 * 
	 * @return Boolean indicating whether operation was successful or not
	 */
	public function addQuestion($question, $category='', $weight=0)
	{
		if ( ! ($question instanceOf QuestionTemplate) )
		{
			return false;
		}

		// Add to data object

		return true;
	}

	public function changeQuestionWeight($questionId, $weight)
	{

	}

	public function changeQuestionCategory($questionId, $category)
	{

	}


	/**
	 * Get the number of questions
	 * 
	 * @return    The number of questions in the evaluation
	 */
	public function getNumberOfQuestions()
	{
		return count($this->questions);
	}	

}