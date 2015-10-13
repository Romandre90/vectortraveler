<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    private $id;
    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
    */
    public function authenticate() {
        $auth_user= explode('\\', $_SERVER['AUTH_USER']);
		$auth_name=$auth_user[1];
		
       $userInfo = $client->GetUserInfo(array("UserName" => $this->username, "Password" => $this->password));
       $userInfoResult = $userInfo->GetUserInfoResult;
        $ccid = $userInfoResult->ccid;
        if($auth_name== null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        
            $user=User::model()->findByAttributes(array('login'=>$auth_name));
            if($user===null){
                $newUser = new User();
                $newUser->ccid = $ccid;
                $newUser->login = $userInfoResult->login;
                $newUser->username = $userInfoResult->name;
                $newUser->email = $userInfoResult->email;
                $newUser->firstName = $userInfoResult->firstname;
                $newUser->lastName = $userInfoResult->lastname;
                $newUser->telephoneNumber = $userInfoResult->telephonenumber;
                $newUser->department = $userInfoResult->department;
                $newUser->createdTime = new CDbExpression('GETDATE()');
                $newUser->lastLogin = new CDbExpression('GETDATE()');
                $newUser->role = 0;
                $newUser->save();
                Yii::app()->user->setState('username',$userInfoResult->name);
                $this->id = $newUser->primaryKey;
                $this->username = $userInfoResult->name;
                Yii::app()->user->setState('role',0);
            }else{
                $user->lastLogin = new CDbExpression('GETDATE()');
                $user->save();
                Yii::app()->user->setState('username',$user->username);
                Yii::app()->user->setState('role',$user->role);
                $this->id = $user->id;
            }
            //$this->setState('name', $name);
            $this->errorCode=self::ERROR_NONE;
        
        return !$this->errorCode;
    }
    
    public function getId()
    {
        return $this->id;
    }

}