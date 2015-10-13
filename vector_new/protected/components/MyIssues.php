<?php

/**
 * RecentComments is a Yii widget used to display a list of recent
  comments
 */
class myIssues extends CWidget {
	
    private $issues;
    public $displayLimit = 5;
    public $travelerId = null;

    public function init() {
        $this->issues = Issue::model()->findmyIssues();
    }

    public function getmyIssues() {
        return $this->issues;
    }

    public function run() {
// this method is called by CController::endWidget()
        $this->render('MyIssues');
    }

}