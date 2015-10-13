<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{

    const ERROR_NO_DOMAIN_CONTROLLER_AVAILABLE = 1001; // could not bind anonymously to any domain controllers
    const ERROR_INVALID_CREDENTIALS = 1002; // could not bind with user's credentials
    const ERROR_NOT_PERMITTED = 1003; //user was not found in search criteria

    private $id;
	private $ccid;

    public function __construct()//$username = null,$password = null
    {

//throw new CHttpException (402, "useridentity1");
        $ccid = $_SERVER["ADFS_PERSONID"];

    }

	public function authenticate()
	{
		$ccid = $_SERVER["ADFS_PERSONID"];
		//throw new CHttpException (402, "useridentity");
		if($ccid===0)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else
		{
	
                $user = User::model()->findByAttributes(array('ccid'=>$ccid));
                //throw new CHttpException (402, $ccid);
				if($user===null)
                {
					$newUser = new User();
					$newUser->ccid = $_SERVER["ADFS_PERSONID"];
					$newUser->login = $_SERVER["ADFS_LOGIN"];
					$newUser->username = $_SERVER["ADFS_FULLNAME"];
					$newUser->email = $_SERVER["ADFS_EMAIL"];
					$newUser->firstName = $_SERVER["ADFS_FIRSTNAME"];
					$newUser->lastName = $_SERVER["ADFS_LASTNAME"];
					$newUser->telephoneNumber = $_SERVER["ADFS_PHONENUMBER"];
					$newUser->department = $_SERVER["ADFS_DEPARTMENT"];
					$newUser->createdTime = new CDbExpression('NOW()');
					$newUser->lastLogin = new CDbExpression('NOW()');
					$newUser->role = 0;
					$newUser->save();
					Yii::app()->user->setState('username',$_SERVER["FULLNAME"]);
					$this->id = $newUser->primaryKey;
					$this->username = $_SERVER["ADFS_FULLNAME"];
					Yii::app()->user->setState('role',0);
                }else{
                    $user->lastLogin = new CDbExpression('NOW()');
                    $user->login = $_SERVER["ADFS_LOGIN"];
                    $user->username = $_SERVER["ADFS_FULLNAME"];
                    $user->email = $_SERVER["ADFS_EMAIL"];
                    $user->firstName = $_SERVER["ADFS_FIRSTNAME"];
                    $user->lastName = $_SERVER["ADFS_LASTNAME"];
                    $user->telephoneNumber = $_SERVER["ADFS_PHONENUMBER"];
                    $user->department = $_SERVER["ADFS_DEPARTMENT"];
                    $user->createdTime = new CDbExpression('NOW()');
                    $user->lastLogin = new CDbExpression('NOW()');
					$user->save();
					Yii::app()->user->setState('username',$user->username);
					Yii::app()->user->setState('role',$user->role);
					$this->id = $user->id;
                }
                !$this->errorCode=self::ERROR_NONE;
        }
		return !$this->errorCode;
	}

    public function getId()
    {
        return $this->id;
    }
}