<?php

class EvaluationState
{
	const NOT_STARTED = 0;
	const IN_PROGRESS = 1;
	const SUBMITTED   = 2;
}

class Evaluation
{

	private $title;
	private $purpose;
	private $isPrivate;

	private $geode;

	private $creator;

	/* The questions */
	private $questions;

	/* Indicates the `EvaluationState` of the evaluation: NOT_STARTED, IN_PROGRESS. SUBMITTED. See EvaluationStatus */
	private $state;

	public static function retrieve($id)
	{

	}

	public static function create($evaluationTemplate)
	{
		// C
	}

	public function delete()
	{
		// Remove all questions
		
		// Remove self
	}

	/**
	 * Gathers all required questions not filled out
	 * 
	 * @return  Array of required questions that do not have data.
	 */
	public function QuestionsMissingRequiredAnswers()
	{
		$problemQuestions = array();
		foreach ($questions as $question) {
			if($question->isAnswerRequired and ($question->answer == '' or $question->answer == null ) )
			{
				$problemQuestions = $question;
			}
		}
		return $problemQuestions;
	}


	/**
	 * Checks whether every required question is filled out
	 * 
	 * @return    Boolean    True if every required question has data, false otherwise
	 */
	public function validateEvaluationComplete()
	{
		return count(QuestionsMissingRequiredAnswers()) == 0;
	}

	/**
	 * Submits the evaluation
	 * 
	 * @return    Boolean    Whether the submission is successful or not
	 */
	public function submit()
	{
		if ($this->state == EvaluationState::SUBMITTED) {
			return false;
		}

		if( ! $this->validateEvaluationComplete() )
		{
			return false;
		}
		
		$this->checkRequiredFields();

		// Save question answers set
		foreach ($this->questions as &$question) {
			$question->save();
		}

		// Set is submitted
		$this->state = EvaluationState::SUBMITTED;
		return $this->save();
	}

	/**
	 * Sets the answers for the specified questions
	 * 
	 * Note that this function does NOT save the question response. You must `submit` the evaluation to save the changes
	 * 
	 * @param    array    Associative array in the format: questionId => json_answer for saving/decoding by the question class
	 * 
	 * @return    Boolean    True if successful, false otherwise
	 */
	public function setQuestionAnswers($answers)
	{
		foreach ($answers as $questionId => $answer)
		{
			if ( ! $this->setQuestionAnswer($questionId, $answer) )
			{
				return false;
			}
		}
		return true;
	}

	/**
	 * Sets the answer for the specified question
	 * 
	 * Note that this function does NOT save the question answer. You must `submit` the evaluation to save the changes
	 * 
	 * @param  The question id to set
	 * @param  The json answer for saving/decoding by the question class
	 * 
	 * @return  True if successful, false otherwise
	 */
	public function setQuestionAnswer($questionId, $answer)
	{
		// make sure question belongs to this evaluation
		$question = null;
		foreach ($this->questions as &$q)
		{
			if($questionId == $q->id )
			{
				$question = $q;
				break;
			}
		}
		if ( is_null($question) ) { return false; }


		// Validate result
		if ( ! $question->validate($answer) ) {
			return false;
		}

		// set result
		return $question->setAnswer($answer);
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