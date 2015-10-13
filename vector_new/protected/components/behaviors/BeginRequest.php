<?php
class BeginRequest extends CBehavior {
    // The attachEventHandler() mathod attaches an event handler to an event. 
    // So: onBeginRequest, the handleBeginRequest() method will be called.
    public function attach($owner) {
        $owner->attachEventHandler('onBeginRequest', array($this, 'handleBeginRequest'));
    }
 
    public function handleBeginRequest($event) {        
        
		if(Yii::app()->user->id == null){
			$identity= new UserIdentity();
				if($identity->authenticate()){
					Yii::app()->user->login($identity);
			}
		}
		
		
		
		$app = Yii::app();
        if (isset($_POST['lang']))
        {
            $app->language = $_POST['lang'];
            $app->user->setState('lang', $_POST['lang']);
            $cookie = new CHttpCookie('lang', $_POST['lang']);
            $cookie->expire = time() + (60*60*24*365); // (1 year)
            Yii::app()->request->cookies['lang'] = $cookie;
        }
        else if ($app->user->hasState('lang'))
            $app->language = $app->user->getState('lang');
        else if(isset(Yii::app()->request->cookies['lang']))
            $app->language = Yii::app()->request->cookies['lang']->value;
    }
}