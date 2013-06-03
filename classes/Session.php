<?php

require_once __DIR__ . '/Topic.php';
require_once __DIR__ . '/Member.php';

Class SessionModel extends Model
{
	public static $_table = 'Session';
}

/**
 * Sessions are collections of topics for evaluation
 */
Class Session
{
	/* Id of session in database */
	private $id;

	/* Title of the session */
	private $title;

	/* Indicates whether anyone can see this session and fill out forms */
	private $allowAnonymousMembership;

	/* Time created */
	private $created;

	/* Array of Users who are managers of the session */
	private $managers;

	/* Array of Users who are administrators of the session */
	private $admins;

	public static function retrieveAll()
	{
		$sessionIds = ORM::for_table('Session')->select('id')->find_many();

		$sessions = array();
		foreach ($sessionIds as $sid) {
			$sessions[] = self::retrieve($sid['id']);
		}
		return $sessions;
	}	

	public static function retrieve($id)
	{
		// get the specified session id
		$data = Model::factory('SessionModel')->find_one($id);

		if($data == null)
			return null;

		return new Session($data);
	}

	public static function create($title, $admin=-1)
	{
		$newSession = Model::factory('SessionModel')->create();
		$newSession->title = $title;
		$newSession->set_expr('created', 'NOW()');
		// @TODO: Set admin
		$newSession->save();

		return self::retrieve($newSession->id);
	}

	public function Session($data)
	{		
		// set values
		$this->id = $data->id;
		$this->title = $data->title;
		$this->created = $data->created;
		
		// Set managers
		
		// Set admins
		
		// Set topics
	}

	/**
	 * Provides readonly access to all private variables
	 */
	private $availableProperties = array('topics', 'memberCount', 'members', 'usersNotMembers');

	public function __get($name)
	{
		switch($name)
		{
			/* Array of session Topics. See Topic class */
			case 'topics':
				return Model::factory('Topic')->where('session_id', $this->id)->find_many();
				break;

			/* Array of Users who are members */
			case 'members':
				return Model::factory('Member')->where('session_id', $this->id)->find_many();
			break;
			case 'usersNotMembers':
				// @TODO: strip out users not members of the session
				return Model::factory('User')->find_many();
			case 'memberCount':
				return count($this->members);
			break;
			default:
				return ($name != "instance" && isset($this->$name)) ? $this->$name : false;	
		}


	}
	public function __isset($name)
	{
		return $name != "instance" && ( isset($this->$name) || in_array($name, $this->availableProperties));
	}

	/* =============================== */
	/* Session Details
	/* =============================== */

	public function setTitle($newTitle)
	{
		$this->title = $newTitle;
	}

	/* =============================== */
	/* Session Access
	/* =============================== */	

	public function allowAnonymousMembership($bool)
	{
		$this->allowAnonymousMembership = $bool;
	}

	/* =============================== */
	/* Topics Management
	/* =============================== */	
	public function topicExists($topic)
	{
		return in_array($topic, $this->topics);
	}


	public function createTopic($name)
	{
		$topic = Model::factory('Topic')->create();
		$topic->name = $name;
		$topic->status = '';
		$topic->set_expr('created', 'NOW()');
		$topic->session_id = $this->id;
		$topic->save();
	}

	public function removeTopic($topic)
	{
		if ( ! $topic instanceOf Topic )
		{
			throw new Exception('Session removeTopic expects a `Topic` instance');
		}
		$topic->destroy();
	}

	/* =============================== */
	/* Membership Management
	/* =============================== */

	public function addMember($user)
	{
		if( ! $user instanceOf User) return false;

		// @TODO: make sure user is not already a member

		$newMember = Model::factory('Member')->create();
		$newMember->user_id = $user->id;
		$newMember->session_id = $this->id;
		$newMember->save();
	}

	public function removeMember($member)
	{
		throw new Exception(__METHOD__ . ' Not Implemented');
	}


	public function addRole($member, $role)
	{
		if ($role instanceOf Role && $member instanceOf Member) {
			// @TODO: make sure member is a member of session first

			// If role already exists, return
			if($member->hasRole($role)) {
				return true;
			}

			$memberRole = Model::factory('Member_Role')->create();
			$memberRole->Member_id = $member->id;
			$memberRole->Role_id = $role->id;
			$memberRole->save();

		}

	}	

	public function removeRole($member, $role)
	{
		if ($role instanceOf Role && $member instanceOf Member) {
			// @TODO: make sure member is a member of session first

			// Make sure role exists
			if($member->hasRole($role)) {
				$memberRole = Model::factory('Member_Role')->where('Member_id', $member->id)->where('Role_id', $role->id)->find_one();				
				$memberRole->delete();
			}
		}

	}

	public function addEvaluatorRole($member)
	{
		throw new Exception(__METHOD__ . ' Not Implemented');
	}

	public function removeEvaluatorRole($evaluator)
	{
		throw new Exception(__METHOD__ . ' Not Implemented');
	}

	public function addManagerRole($member)
	{
		if ( ! $user instanceOf User )
		{
			throw new Exception('addManager expects a user object');
		}
		throw new Exception(__METHOD__ . ' Not Implemented');
	}

	public function removeManagerRole($manager)
	{
		throw new Exception(__METHOD__ . ' Not Implemented');
	}

	public function addAdministrativeRole($member)
	{
		throw new Exception(__METHOD__ . ' Not Implemented');
	}

	public function removeAdministrativeRole($administrator)
	{
		throw new Exception(__METHOD__ . ' Not Implemented');
	}

}