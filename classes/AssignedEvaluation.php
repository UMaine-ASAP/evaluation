<?php

/**
 * A specfic evaluation set for the Geodes in the topic 
 * Holds evaluators and filled out evaluations and aggregates evaluations
 */
class AssignedEvaluation
{
	/* The evaluation items */
	private $geodes;

	/* Array of the completed/in-progress Evaluations */
	private $GeodeEvaluationGroups;

	/* Indicates whether the evaluation process is still in progress or completed */
	private $isCompleted;

	/* Indicates whether evaluators can see the results or not */
	private $allowEvaluatorsToViewResults;

	/* The number of evaluations an evaluator can submit */
	private $evaluationsPermittedPerEvaluator;

	/* Indicates whether all evaluators in the system can evaluate or not */
	private $allowAnyEvaluatorToEvaluate;
	
	/* The users who are evaluating the geodes */
	private $evaluators;

	/* Indicates whether non-registered users can evaluate geodes */
	private $allowAnonymousUsersToEvaluate;


	public static function retrieve()
	{

	}

	public static function create($geodes, $evaluator, $evaluationsPermittedPerEvaluator)
	{

	}


	public function fullEvaluationResults()
	{
		return array_map('geodeEvaluationResults', $this->geodes);
	}

	public function geodeEvaluationResults($geode)
	{
		// Run query to aggregate scores grouped by geode type
	}

	public function setIsCompleted($bool)
	{
		$this->isCompleted = $bool;
	}

	public function setAllowEvaluatorsToViewResults($bool)
	{
		$this->allowEvaluatorsToViewResults = $bool;
	}
}