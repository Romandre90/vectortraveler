<?php
/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_fields = array("samaccountname","mail","memberof","department","displayname","telephonenumber","primarygroupid","objectsid");
	
	/**
	 * Authenticates a user using Active Directory.
	 * 
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
			// alter search fields if needed
	        if(isset(Yii::app()->params['adFields']))
				  $this->_fields = Yii::app()->params['adFields'];
	        
	        // authenticate
			if (Yii::app()->ldap->authenticate($this->username, $this->password)){
				$this->errorCode = self::ERROR_NONE;
				
				// collect AD info about users
				$info = Yii::app()->ldap->user()->infoCollection($this->username, $this->_fields);
				$groups = Yii::app()->ldap->user()->groups($this->username);
				
				// export ad info to user session
				foreach($this->_fields as $field)
					$this->setState($field, $info->$field);	
				
				// export groups array
				$this->setState('groups', $groups);
			}
			else {
				 $this->errorCode = self::ERROR_PASSWORD_INVALID;
			}	        
	     
	        return !$this->errorCode;
	}
}