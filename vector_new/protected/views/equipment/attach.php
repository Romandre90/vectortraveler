<?php
/* @var $this EquipmentController */
/* @var $model Equipment */
/* @var $form CActiveForm */
?>

<?php
$this->breadcrumbs = array(
            Yii::t('default', 'Equipments') => array('equipment/index'),
            $model->identifier => array('equipment/view','id'=>$model->id),
            Yii::t('default','Attached Equipments') => array('equipment/attached','id'=>$model->id),
            Yii::t('default','Attach Equipments'),
        
    );?>
<?php
$this->menu=array(
	array('label'=>Yii::t('default','List Equipment'), 'url'=>array('index')),
         array('label'=>Yii::t('default','List Assembly'), 'url'=>array('assembly')),
);

Yii::app()->clientScript->registerScript('attach', "
    $(':checkbox:checked').each(function(){
        $(this).closest('tr').addClass('selected');
    });
$(':checkbox').change(function(){
    id = $(this).val();
    $.ajax({
        url: '$model->id',
        type: 'POST',
        data: 'equipmentId='+id,
    });
    $(this).closest('tr').toggleClass('selected');
});
");

?>

<h1><?php echo Yii::t('default','Attach Equipments')?></h1>

    
    <?php
    $projectId = $model->component->projectId;
    $data = $search->search(array('condition'=>"t.id NOT IN ($model->notIn) AND componentId IN ($model->inProject) and (parentId = $model->id OR parentId IS NULL)"));
    $data->pagination->pageSize = $model->count();
       $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$data,
           'id'=>'equipment-grid',
           'selectableRows'=>0,
           'enablePagination' => false,
           'filter'=>$search,
           'columns'=>array(
               array(
                   'class'=>'CCheckBoxColumn',
                   'id'=>'chk',
                   'checked'=>'$data->parentId',
                   'headerTemplate'=>'',
           'selectableRows'=>2,
               ),
               array(
                 'name' => 'componentId',
                'filter' => CHtml::listData(Components::model()->findAll("projectId = $projectId"), 'id', 'name'),
                'value' => 'Components::Model()->FindByPk($data->componentId)->name',  
               ),
               'identifier',
               'description',
           )
    ));?>
