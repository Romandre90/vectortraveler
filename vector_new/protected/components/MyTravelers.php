<?php

/**
 * RecentComments is a Yii widget used to display a list of recent
  comments
 */
class MyTravelers extends CWidget {
	
    private $travelers;
    public $displayLimit = 5;
    public $travelerId = null;

    public function init() {
        $this->travelers = Traveler::model()->findMyTravelers();
    }

    public function getMyTravelers() {
        return $this->travelers;
    }

    public function run() {
// this method is called by CController::endWidget()
        $this->render('MyTravelers');
    }

}