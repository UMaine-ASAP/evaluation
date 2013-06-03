<?php

/**
 * A specific collection of items to evaluate (Geodes) and assigned evaluations
 */
class Topic extends Model
{
	private $geodes;

	/* Array of assigned evaluations */
	private $assignedEvaluations;

	private $evaluationTemplates;

	private $allowPublicToView;

	/* The name of the Topic - available in database*/
	/* The current state of the topic */

	/* DateTime topic was created */


	private $availableProperties = array();

	public function __get($name)
	{
		switch($name)
		{
			default:
				return parent::__get($name);
		}
	}

	public function __isset($name)
	{
		return isset($this->$name) || in_array($name, $this->availableProperties) || parent::__isset($name);
	}

	public static function retrieve($id)
	{
		return Model::factory('Topic')->find_one($id);
	}

	public static function create($name, $sessionId)
	{

	}

	public function assignEvaluationToAllEvaluators()
	{

	}

	public function assignEvaluationToEvaluators($evaluators)
	{
		
	}

	/* =============================== */
	/* Geode Management
	/* =============================== */

	public function addGeode($geode)
	{

	}

	public function removeGeode()
	{

	}

	public function getNumberOfGeodes()
	{
		return count($geodes);
	}

}