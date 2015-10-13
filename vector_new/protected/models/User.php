<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property integer $ccid
 * @property string $login
 * @property string $username
 * @property string $email
 * @property string $firstName
 * @property string $lastName
 * @property string $telephoneNumber
 * @property string $department
 * @property string $createdTime
 * @property string $lastLogin
 * @property integer $role
 *
 * The followings are the available model relations:
 * @property Discrepancy[] $discrepancies
 * @property Discrepancy[] $discrepancies1
 * @property Discrepancy[] $discrepancies2
 * @property Discrepancy[] $discrepancies3
 * @property Discrepancy[] $discrepancies4
 * @property Discrepancy[] $discrepancies5

 * @property Traveler[] $travelers
 * @property Step[] $steps
 * @property Comment[] $comments
 */
class User extends CActiveRecord
{
    const ROLE_GUEST = 0;
    const ROLE_FILLER = 1;
    const ROLE_CREATOR = 2;
    const ROLE_SUPERVISOR = 3;
    const ROLE_ADMIN = 4;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ccid, role','numerical', 'integerOnly'=>true),
			array('login, username, email, firstName, lastName, telephoneNumber, department', 'length', 'max'=>50),
			array('createdTime, lastLogin', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, ccid, login, username, email, firstName, lastName, telephoneNumber, department, createdTime, lastLogin, role', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'travelers' => array(self::HAS_MANY, 'Traveler', 'userId'),
			'steps' => array(self::HAS_MANY, 'Step', 'userId'),
			'comments' => array(self::HAS_MANY, 'Comment', 'userId'),
                        'discrepancies' => array(self::HAS_MANY, 'Discrepancy', 'causeOfNonconformanceBy'),
			'discrepancies1' => array(self::HAS_MANY, 'Discrepancy', 'correctiveActionToPreventRecurrenceBy'),
			'discrepancies2' => array(self::HAS_MANY, 'Discrepancy', 'discrepancyDescriptionBy'),
			'discrepancies3' => array(self::HAS_MANY, 'Discrepancy', 'dispositionBy'),
			'discrepancies4' => array(self::HAS_MANY, 'Discrepancy', 'dispositionVerifyNoteBy'),
			'discrepancies5' => array(self::HAS_MANY, 'Discrepancy', 'reviewedBy'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ccid' => 'Ccid',
			'login' => Yii::t('default','LoginU'),
			'username' => Yii::t('default',"Username"),
			'email' => 'Email',
			'firstName' => Yii::t('default','First Name'),
			'lastName' => Yii::t('default','Last Name'),
			'telephoneNumber' => Yii::t('default','Telephone Number'),
			'department' => Yii::t('default','Department'),
			'createdTime' => Yii::t('default','Created Time'),
			'lastLogin' => Yii::t('default','Last Login'),
			'role' => Yii::t('default','Role'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('ccid',$this->ccid);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('firstName',$this->firstName,true);
		$criteria->compare('lastName',$this->lastName,true);
		$criteria->compare('telephoneNumber',$this->telephoneNumber,true);
		$criteria->compare('department',$this->department,true);
		$criteria->compare('createdTime',$this->createdTime,true);
		$criteria->compare('lastLogin',$this->lastLogin,true);
		$criteria->compare('role',$this->role);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
            /**
     * Retrieves a list of role
     * @return array an array of available roles.
     */
    public function getRoleOptions() {
        return array(
            self::ROLE_GUEST => Yii::t('default','Guest'),
            self::ROLE_FILLER => Yii::t('default','Filler'),
            self::ROLE_CREATOR => Yii::t('default','Creator'),
            self::ROLE_SUPERVISOR => Yii::t('default','Supervisor'),
            self::ROLE_ADMIN => Yii::t('default','Administrator'),
        );
    }
    
    public function getRoleOptionsDescription() {
        return array(
            self::ROLE_GUEST => '<b>'.Yii::t('default','Guest')."</b><hr/>". Yii::t('default',"You can comment issued traveler and report any nonconformities"),
            self::ROLE_FILLER => '<b>'.Yii::t('default',"Filler")."</b><hr/>" .Yii::t('default',"Your task is to issue traveler, report nonconformities and comment steps"),
            self::ROLE_CREATOR => '<b>'.Yii::t('default',"Creator")."</b><hr/>".Yii::t('default',"Your task is to create project, component, equipment and traveler. You can also issue traveler, report nonconformities and comment steps"),
            self::ROLE_SUPERVISOR => '<b>'.Yii::t('default',"Supervisor")."</b><hr/>".Yii::t('default',"Your task is to control the progress of projects"),
            self::ROLE_ADMIN => '<b>'.Yii::t('default',"Administrator")."</b><hr/>".Yii::t('default',"Your task is to control the integrity of VTS"),
        );
    }

    /**
     * @return string the role text display for the current user
     */
    public function getRoleText() {
        $roleOptions = $this->roleOptions;
        return isset($roleOptions[$this->role]) ?
        $roleOptions[$this->role] : "unknown status ({$this->role})";
    }
    
    /**
     * @return string the role text display for the current user
     */
    public function getRoleDescription() {
        $roleOptionsDescription = $this->roleOptionsDescription;
        return isset($roleOptionsDescription[$this->role]) ?
        $roleOptionsDescription[$this->role] : "unknown status ({$this->role})";
    }
    
    public function getDateCreated() {
        // Format dates based on the locale
        return Yii::app()->dateFormatter->format(Yii::app()->locale->dateFormat,$this->createdTime);
    }
    
    public function getLastLoginElapsed() {
        $etime = time() - strtotime($this->lastLogin);

        if ($etime < 1) {
            return Yii::t('default','now');
        }

        $a = array( 31536000   =>  Yii::t('default','year'),
                    2592000        =>  Yii::t('default','month'),
                    604800        =>  Yii::t('default','week'),
                    86400             =>  Yii::t('default','day'),
                    3600                  =>  Yii::t('default','hour'),
                    60                      =>  Yii::t('default','minute'),
                    1                       =>  Yii::t('default','second')
                    );

        foreach ($a as $secs => $str) {
            $d = $etime / $secs;
            if ($d >= 1) {
                $r = round($d);
                if($str == "mois") return $r . ' ' . $str;
                return $r . ' ' . $str . ($r > 1 ? 's' : '');
            }
        }
    }
    
}