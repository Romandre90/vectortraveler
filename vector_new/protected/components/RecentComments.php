<?php

/**
 * RecentComments is a Yii widget used to display a list of recent
  comments
 */
class RecentComments extends CWidget {

    private $comments;
    public $displayLimit = 10;
    public $travelerId = null;

    public function init() {
        $this->comments = Comment::model()->findRecentComments($this->displayLimit, $this->travelerId);
    }

    public function getRecentComments() {
        return $this->comments;
    }

    public function run() {
// this method is called by CController::endWidget()
        $this->render('recentComments');
    }

}