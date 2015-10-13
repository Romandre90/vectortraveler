<?php

class SiteController extends Controller
{

    public $user = null;

    public $layout = '//layouts/column1';
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
            if(!Yii::app()->user->isGuest){
                $this->user = User::model()->findByPk(Yii::app()->user->id);
                $this->layout = '//layouts/column2_role';
            } 		
            $script = null;

            if(isset($_GET['login'])){
                $script = "$('#menu').hide(); $('#menu').slideToggle({ direction: 'up' }, 500);";
            }
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index',array('script'=>$script));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}


	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		

		// collect user input data
		//throw new CHttpException(403, "fuera");
			//$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()){
				//$this->redirect(array('index', 'login' => true));
				
				$this->redirect(Yii::app()->user->getReturnUrl());
		}
		// display the login form
		$this->render('login',array('model'=>$model,'script'=>$script));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	public function actionMyVector()
	{
		$this->render('myvector');
	}
	public function actionAssembly()
	{
		$this->render('assembly');
	}

	public function actionHelp()
	{
		$this->render('help');
	}
	
	public function actionSearch()
	{
		if(Yii::app()->user->isGuest)
			throw new CHttpException (401, "You're not authorized to perform this action");
		$this->render('search');
	}
	
    public function actionSearching()
	{
		//throw new CHttpException (401, "searching");
		if(Yii::app()->user->isGuest){
			throw new CHttpException (401, "You're not authorized to perform this action");
		}
		//falta comprobar que Post projects no esté puesto
		if(!isset($_POST['word'])){
			$this->render('search');
		} else {
			if($_POST['word']==' ') throw new CHttpException(403, "hola");
			if(!isset($_POST['selection'])) {
				$this->render('search');
			} else {
				$reg_ex="/^([0-9a-zA-Z\s]{1,20})$/";//only alphabetic characters, numbers and spaces including tab, nothing else
				if(!preg_match($reg_ex, $_POST['word'])) {
					throw new CHttpException(403, Yii::t('default', 'Only alphabetic characters and numbers are allowed. 20 characters maximum'));
				} else {
						if($_POST['selection']=='Issue'){
							$projects=(int)$_POST['Projects'];
							$condition=(string)$_POST['word'];
							$length=strlen($condition);
							if($projects==''){  //when no project is selected
								$sql = "SELECT	 result.id, result.elementId, result.value, result.issueId, result.fileId,  result.createTime, result.userId, result.colonne, result.ligne, LOCATE('$condition', value) as position  FROM result JOIN issue ON result.issueId=  issue.id JOIN traveler ON  issue.travelerId = traveler.id JOIN components ON traveler.componentId =  components.id WHERE result.value LIKE '%$condition%' ORDER BY result.id";
							} else $sql = "SELECT	 result.id, result.elementId, result.value, result.issueId, result.fileId, result.createTime, result.userId, result.colonne, result.ligne FROM result JOIN issue ON result.issueId=  issue.id  JOIN traveler ON  issue.travelerId = traveler.id JOIN components ON traveler.componentId =  components.id WHERE result.value LIKE '%$condition%' and components.projectId = $projects ORDER BY result.id";
								$items = Yii::app()->db->createCommand($sql)->queryAll();
								$this->render('find', array('items'=>$items, 'length'=>$length, 'word'=>$condition, 'projects'=>$projects, 'travelerVsIssue'=> 'Issue'));
						} else if($_POST['selection']=='Traveler'){ //searching in traveler templates
							$projects=(int)$_POST['Projects'];
							$condition=(string)$_POST['word'];
							if($projects==''){
								$sql = "SELECT	 element.id, element.label, element.stepId, components.projectId, traveler.name, step.name stepName FROM    element JOIN step ON element.stepId=  step.id JOIN traveler 	ON step.travelerId=  traveler.id JOIN components 	ON traveler.componentId=  components.id WHERE element.label like '%$condition%'  ORDER BY traveler.id";
							} else $sql = "SELECT	 element.id, element.label, element.stepId, components.projectId,traveler.name, step.name stepName FROM    element JOIN step ON stepId=  step.id JOIN traveler 	ON step.travelerId=  traveler.id JOIN components 	ON traveler.componentId=  components.id WHERE label like '%$condition%' and components.projectId = $projects  ORDER BY traveler.id";
								$items = Yii::app()->db->createCommand($sql)->queryAll();
								$this->render('find', array('items'=>$items, 'word'=>$condition, 'projects'=>$projects, 'travelerVsIssue'=> 'Traveler'));
						}
						else if($_POST['selection']=='File'){
							$projects=(int)$_POST['Projects'];
							$condition=(string)$_POST['word'];
							if($projects==''){
								$sql = "SELECT file.id fileId, file.stepId, file.fileSelected, traveler.id, traveler.name FROM file JOIN step ON file.stepId= step.id JOIN traveler ON step.travelerId= traveler.id JOIN components ON traveler.componentId= components.id WHERE file.fileSelected like '%$condition%' and file.stepId is NOT null UNION SELECT file.id fileId, file.stepId, file.fileSelected, issue.id, traveler.name FROM file JOIN result ON  file.id =  result.value JOIN issue ON result.issueId=  issue.id JOIN traveler ON traveler.id= issue.travelerId JOIN equipment ON issue.equipmentId=  equipment.id JOIN components	ON equipment.componentId=  components.id WHERE file.fileSelected like '%$condition%' and file.stepId is null";
							} else $sql = "SELECT file.id fileId, file.stepId, file.fileSelected, traveler.id, traveler.name FROM file  JOIN step ON file.stepId=  step.id  JOIN traveler 	ON step.travelerId=  traveler.id JOIN components 	ON traveler.componentId=  components.id WHERE file.fileSelected like '%$condition%' and file.stepId is NOT null and components.projectId=$projects UNION SELECT	file.id fileId, file.stepId, file.fileSelected, issue.id, traveler.name FROM file JOIN result ON file.id =result.value JOIN issue 	ON result.issueId=  issue.id JOIN traveler 	ON traveler.id=  issue.travelerId JOIN equipment 	ON issue.equipmentId=  equipment.id JOIN components	ON equipment.componentId=  components.id WHERE file.fileSelected like '%$condition%' and file.stepId is null and components.projectId=$projects";
							$items = Yii::app()->db->createCommand($sql)->queryAll();
							$this->render('find', array('items'=>$items, 'word'=>$condition, 'projects'=>$projects, 'travelerVsIssue'=> 'File'));
					} else throw new CHttpException(403, Yii::t('default', 'Error'));

				}
			}
		}
	}
	public function getElement($id)
	{
		return Element::model()->findByPk($id);
	}
	
	public function getStep($id)
	{
		return Step::model()->findByPk($id);
	}
	
	public function getTravelerName($id)//$id=->issueId
	{
		$id=(int)$id;
		$criteria = new CDbCriteria;
		$criteria->condition="id=$id";
		$issue=Issue::model()->find($criteria);
		//$issue= Issue::model()->findByPk($id); doesnt work!why?
		$traveler= Traveler::model()->findByPk($issue->travelerId);
		return $traveler->name; 
	}
	
	public function getProjects (){
	
		$projectsArray=CHtml::listData(Project::model()->findAll(), 'id', 'name');
		return $projectsArray;
	
	}

}