<?php

/**
 * RecentComments is a Yii widget used to display a list of recent
  comments
 */
class MyEquipments extends CWidget {
	
    private $equipments;
    public $displayLimit = 5;
    public $equipmentId = null;

    public function init() {
        $this->equipments = Equipment::model()->findMyEquipments();
    }

    public function getMyEquipments() {
        return $this->equipments;
    }

    public function run() {
// this method is called by CController::endWidget()
        $this->render('MyEquipments');
    }

}