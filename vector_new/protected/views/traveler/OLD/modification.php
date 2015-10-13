<?php
/* @var $this TravelerController */
/* @var $model Traveler */
/* @var $model Step */

$this->breadcrumbs = array(
    Yii::t('default','Travelers') => array('index'),
    $model->id => array('view','id'=>$model->id),
    Yii::t('default','Modification')
);

$this->menu = array(
    array('label' => Yii::t('default','List Traveler'), 'url' => array('index')),
    array('label'=>Yii::t('default','Print'), 'url'=>'#', 'linkOptions'=>array('onclick'=>'window.print()')),
   );
?>

<div class="traveler">
    <div class="travelerTitle"><?php echo CHtml::encode($model->title); ?></div>
    <div class="travelerSubTitle"><?php echo " Rev. ". $model->revision?></div>
    <div class="travelerAuthor"> <?php echo Yii::t('default','by')." <b>".CHtml::encode($model->user->username); ?></b></div>
</div>
<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        array(
            'name'=>'createTime',
            'value'=>$model->dateCreated
        ),
        array(
            'name' => 'status',
            'value' => CHtml::encode($model->statusText)
        ),
    ),
));
?>
<div class="view">
<?php
echo $model->modification;

?>
</div>