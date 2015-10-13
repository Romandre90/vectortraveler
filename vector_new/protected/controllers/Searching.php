<?php echo Yii::t('default', ''); ?>

//FUNCIONA PERFECtAmenTe:
public function actionSearching()
	{
		if(Yii::app()->user->isGuest){
			throw new CHttpException (401, "You're not authorized to perform this action");
		}
		//falta comprobar que Post projects no esté puesto
		/*if(!isset($_POST['selection'])){
			throw new CHttpException (401, "traveler");
		}*/
		//searching in issued travelers
		$reg_ex="/^([0-9a-zA-Z\s]{1,20})$/";//only alphabetic characters, numbers and spaces including tab, nothing else
		if($_POST['selection']=='Issue'){
			if(isset($_POST['word'])){
				if(preg_match($reg_ex, $_POST['word'])){
					$projects=(int)$_POST['Projects'];
					$condition=(string)$_POST['word'];
					
					if($projects==''){  //when no project is selected
						$sql = "SELECT	 [vector_new].[dbo].[result].[id], 
									[vector_new].[dbo].[result].[elementId], 
									[vector_new].[dbo].[result].[value], 
									[vector_new].[dbo].[result].[issueId], 
									[vector_new].[dbo].[result].[fileId], 
									[vector_new].[dbo].[result].[createTime], 
									[vector_new].[dbo].[result].[userId], 
									[vector_new].[dbo].[result].[colonne], 
									[vector_new].[dbo].[result].[ligne]     
								FROM [vector_new].[dbo].[result] 
								JOIN [vector_new].[dbo].[issue] ON [vector_new].[dbo].[result].[issueId]=  [vector_new].[dbo].[issue].[id] 
								JOIN [vector_new].[dbo].[traveler] ON  [vector_new].[dbo].[issue].[travelerId] = [vector_new].[dbo].[traveler].[id]
								JOIN [vector_new].[dbo].[components] ON [vector_new].[dbo].[traveler].[componentId] =  [vector_new].[dbo].[components].[id] 
								WHERE [vector_new].[dbo].[result].[value] LIKE '%$condition%' ORDER BY [vector_new].[dbo].[result].[id]";
					
					} else $sql = "SELECT	 [vector_new].[dbo].[result].[id], [vector_new].[dbo].[result].[elementId], [vector_new].[dbo].[result].[value], [vector_new].[dbo].[result].[issueId], [vector_new].[dbo].[result].[fileId], [vector_new].[dbo].[result].[createTime], [vector_new].[dbo].[result].[userId], [vector_new].[dbo].[result].[colonne], [vector_new].[dbo].[result].[ligne]     
								FROM [vector_new].[dbo].[result] 
								JOIN [vector_new].[dbo].[issue] ON [vector_new].[dbo].[result].[issueId]=  [vector_new].[dbo].[issue].[id] 
								JOIN [vector_new].[dbo].[traveler] ON  [vector_new].[dbo].[issue].[travelerId] = [vector_new].[dbo].[traveler].[id]
								JOIN [vector_new].[dbo].[components] ON [vector_new].[dbo].[traveler].[componentId] =  [vector_new].[dbo].[components].[id] 
								WHERE [vector_new].[dbo].[result].[value] LIKE '%$condition%' and [vector_new].[dbo].[components].[projectId] = $projects ORDER BY [vector_new].[dbo].[result].[id]";
					
					$items = Yii::app()->db->createCommand($sql)->queryAll();
					
					$this->render('find', array('items'=>$items, 'word'=>$condition, 'projects'=>$projects, 'travelerVsIssue'=> 'Issue'));
				} else throw new CHttpException(403, Yii::t('default', 'Only alphabetic characters and numbers are allowed. 20 characters maximum'));
			} else $this->render('search');
		}else if($_POST['selection']=='Traveler'){ //searching in traveler templates
				if(isset($_POST['word'])){
						if(preg_match($reg_ex, $_POST['word'])){
						$projects=(int)$_POST['Projects'];
						$condition=(string)$_POST['word'];
						
						if($projects==''){
							$sql = "SELECT	 [vector_new].[dbo].[element].[id], 
										[vector_new].[dbo].[element].[label], 
										[vector_new].[dbo].[element].[stepId], 
										[vector_new].[dbo].[components].[projectId],
										[vector_new].[dbo].[traveler].[name], 
										[vector_new].[dbo].[step].[name] stepName
									FROM    [vector_new].[dbo].[element] 
									JOIN [vector_new].[dbo].[step] ON [vector_new].[dbo].[element].[stepId]=  [vector_new].[dbo].[step].[id] 
									JOIN [vector_new].[dbo].[traveler] 	ON [vector_new].[dbo].[step].[travelerId]=  [vector_new].[dbo].[traveler].[id]
									JOIN [vector_new].[dbo].[components] 	ON [vector_new].[dbo].[traveler].[componentId]=  [vector_new].[dbo].[components].[id]
									WHERE [vector_new].[dbo].[element].[label] like '%$condition%'  ORDER BY [vector_new].[dbo].[traveler].[id]";
						
						} else $sql = "SELECT	 [vector_new].[dbo].[element].[id], 
										[vector_new].[dbo].[element].[label], 
										[vector_new].[dbo].[element].[stepId], 
										[vector_new].[dbo].[components].[projectId],
										[vector_new].[dbo].[traveler].[name], 
										[vector_new].[dbo].[step].[name] stepName
									FROM    [vector_new].[dbo].[element] 
									JOIN [vector_new].[dbo].[step] ON [vector_new].[dbo].[element].[stepId]=  [vector_new].[dbo].[step].[id] 
									JOIN [vector_new].[dbo].[traveler] 	ON [vector_new].[dbo].[step].[travelerId]=  [vector_new].[dbo].[traveler].[id]
									JOIN [vector_new].[dbo].[components] 	ON [vector_new].[dbo].[traveler].[componentId]=  [vector_new].[dbo].[components].[id]
									WHERE [vector_new].[dbo].[element].[label] like '%$condition%' and [vector_new].[dbo].[components].[projectId] = $projects  ORDER BY [vector_new].[dbo].[traveler].[id]";
						
						$items = Yii::app()->db->createCommand($sql)->queryAll();
						
						$this->render('find', array('items'=>$items, 'word'=>$condition, 'projects'=>$projects, 'travelerVsIssue'=> 'Traveler'));
					} else throw new CHttpException(403, Yii::t('default', 'Only alphabetic characters and numbers are allowed. 20 characters maximum'));
				} else $this->render('search');
			} else if($_POST['selection']=='File'){



			} else throw new CHttpException(403, Yii::t('default', 'Error'));
		
	}
	

SELECT	[vector_new].[dbo].[result].[id],
		[vector_new].[dbo].[result].[value], 
		[vector_new].[dbo].[issue].[id]  

FROM    [vector_new].[dbo].[result] 
 
JOIN [vector_new].[dbo].[issue] 
	ON [vector_new].[dbo].[result].[issueId]=  [vector_new].[dbo].[issue].[id] 
JOIN  [vector_new].[dbo].[traveler]
	ON  [vector_new].[dbo].[issue].[travelerId] = [vector_new].[dbo].[traveler].[id]
JOIN  [vector_new].[dbo].[components]
	ON [vector_new].[dbo].[traveler].[componentId] =  [vector_new].[dbo].[components].[id] 
	

WHERE [vector_new].[dbo].[result].[value] like '%cote%' and [vector_new].[dbo].[components].[projectId] =33 ORDER BY [vector_new].[dbo].[result].[id]

























//(script)|(&lt;)|(&gt;)|(%3c)|(%3e)|(SELECT) |(UPDATE) |(INSERT) |(DELETE)|(GRANT) |(REVOKE)|(UNION)|(&amp;lt;)|(&amp;gt;)
		///(\%27)|(\')|(\-\-)|(\%23)|(#)/ix
		//FUNCIONA: $reg_ex="/^[a-z]+[a-z0-9]*[.-_]*$/i";
		//FUNCIONA MAL: $reg_ex=preg_quote('/(?:^\s*[;>"]\s*(?:union|select|create|rename|truncate|load|alter|delete|updateiinsert|desc))|(?:(?:select|create|rename|truncate|load|alter|delete|update|insert|desc)\s+(?:concat|char|load_file)\s?\(?)|(?:end\s*\);)|("\s+regexp\W)/');
		

public function actionSearching()
	{
		if(isset($_POST['word'])){
			$criteria = new CDbCriteria;
			$condition=(string)$_POST['word'];
			$criteria->condition="value LIKE '%$condition%'";
			$items=Result::model()->findAll($criteria);
			$this->render('find', array('items'=>$items));
			

		}else throw new CHttpException(403, "Not working");
	}
	
	
	//este funciona, excepto la expresion regular. solo permite una palabra
	por lo demás funciona bien
	public function actionSearching()
	{
		if(Yii::app()->user->getState('role')<3){
			throw new CHttpException (401, "You're not authorized to perform this action");
		}
		//(script)|(&lt;)|(&gt;)|(%3c)|(%3e)|(SELECT) |(UPDATE) |(INSERT) |(DELETE)|(GRANT) |(REVOKE)|(UNION)|(&amp;lt;)|(&amp;gt;)
		///(\%27)|(\')|(\-\-)|(\%23)|(#)/ix
		$reg_ex="/^[a-z]+[a-z0-9]*[.-_]*$/i";
		if(preg_match($reg_ex, $_POST['word'])){
			if(isset($_POST['word'])){
				$criteria = new CDbCriteria;
				$condition=(string)$_POST['word'];
				$criteria->condition="value LIKE '%$condition%'";
				$items=Result::model()->findAll($criteria);
				$this->render('find', array('items'=>$items));
				

			}else throw new CHttpException(403, "Not working");
		} else throw new CHttpException(403, "Search not valid");
	}
	
	
	
	
	//no funciona el query
	{
		if(Yii::app()->user->getState('role')<3){
			throw new CHttpException (401, "You're not authorized to perform this action");
		}
		if(isset($_POST['word'])){
			$words=$_POST['word'];
			$words=strtr($words, array('%'=>'\%', '_'=>'\_'));
			$words="'%".$words."%'";
			$sql=Yii::app()->db->createCommand()->select('value, id')->from('result')->where("value  LIKE $words");
			//$sql->bindParam(":words",$words,PDO::PARAM_STR);
			$sql=$sql->queryAll();
			$this->render('find', array('items'=>$sql));
		} else throw new CHttpException (401, "empty text");
	}