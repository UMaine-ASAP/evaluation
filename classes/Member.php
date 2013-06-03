<?php

Class Member_Role extends Model
{

}


Class Role extends Model
{
	public static $_table = 'Role';

	public static function allRoles()
	{
		return Model::Factory('Role')->find_many();
	}

	public static function manager()
	{
		return self::getRole('manager');
	}

	public static function admin()
	{
		return self::getRole('admin');
	}

	public static function evaluator()
	{
		return self::getRole('evaluator');
	}

	private static function getRole($roleName)
	{
		return Model::Factory('Role')->where('name', $roleName)->find_one();
	}

	public static function retrieve($id)
	{
		return Model::factory('Role')->find_one($id);
	}

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

}

Class User extends Model
{
	public static $_table = 'User';


	private $availableProperties = array('name', 'fullName');
	public function __get($name)
	{
		switch($name)
		{
			case 'fullName':
				return $this->first . ' ' . $this->last;
				break;
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
		return Model::factory('User')->find_one($id);
	}

	public function User()
	{
	}

}


/**
 * Sessions are collections of topics for evaluation
 */
Class Member extends Model
{
	public static $_table = 'Member';


	private $availableProperties = array('fullName', 'roles');
	public function __get($name)
	{
		switch($name)
		{
			case 'fullName':
				return $this->user->fullName;
				break;
			case 'user':
				return Model::factory('User')->find_one($this->user_id);
				break;
			case 'roles':
				return $this->has_many_through('Role')->find_many();
			break;
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
		return Model::factory('Member')->find_one($id);
	}

	public function hasRole($checkRole)
	{
		if( ! $checkRole instanceOf Role) return false;

		foreach ($this->roles as $myRole) {
			if($myRole->id == $checkRole->id)
			{
				return true;
			}
		}
		return false;
	}

}