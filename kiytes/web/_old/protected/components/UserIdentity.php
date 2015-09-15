<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	
	private $_id;
 
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
	
   $this->password=md5($this->password);
    // Create an instance from model User and search
    $users = Users::model()->findByAttributes(array('Email'=>$this->username));
 //print_r($users->UserName);
    //Swap original if(!isset($users[$this->username]))
    if(!isset($users->UserName))
        $this->errorCode=self::ERROR_USERNAME_INVALID;
    //Swap original elseif($users[$this->password]!==$this->password)
    elseif($users->Password!=$this->password)
        $this->errorCode=self::ERROR_PASSWORD_INVALID;
		  else
        {
			foreach($users as $k=>$v){
				$_SESSION['user'][$k]=$v;
			}
            $this->_id=$users->UserId;
            $this->setState('lastLoginTime',time());
			
            $this->errorCode=self::ERROR_NONE;
        }
    return !$this->errorCode;
	}
	
	public function getId()
    {
        return $this->_id;
    }
}