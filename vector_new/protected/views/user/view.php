<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs = array(
    Yii::t('default', 'Users') => array('index'),
    $model->ccid,
);

$this->menu = array(
    array('label' => Yii::t('default', 'List User'), 'url' => array('index')),
    array('label' => Yii::t('default', 'Update User'), 'url' => array('update', 'id' => $model->id)),
    array('label' => Yii::t('default', 'Manage User'), 'url' => array('admin')),
);
?>

<h1><?php echo $model->username; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        //'id',
        'ccid',
        'login',
        'username',
        'email',
        'firstName',
        'lastName',
        'telephoneNumber',
        'department',
        'dateCreated',
        array(
            'name'=>'lastLogin',
            'value' => Yii::t('default','{elapsedTime} ago',array('{elapsedTime}'=>$model->lastLoginElapsed)),
        ),
        array(
            'name' => 'role',
            'value' => CHtml::encode($model->getRoleText())
        ),
    ),
));
?>
