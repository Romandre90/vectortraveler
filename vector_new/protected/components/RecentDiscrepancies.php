<?php

/**
 * RecentComments is a Yii widget used to display a list of recent
  comments
 */
class RecentDiscrepancies extends CWidget {

    private $discrepancies;
    public $displayLimit = 5;
    public $travelerId = null;

    public function init() {
        $this->discrepancies = Nonconformity::model()->findOpenDiscrepancies();
    }

    public function getOpenDiscrepancies() {
        return $this->discrepancies;
    }

    public function run() {
// this method is called by CController::endWidget()
        $this->render('recentDiscrepancies');
    }

}