<?php echo CHtml::form(); ?>
    <div id="language-select">
        <?php 
        if(sizeof($languages) < 4) {
            $lastElement = end($languages);
            foreach($languages as $key=>$lang) {
                if($key =='fr')$lang = "Français";
                if($key != $currentLang) {
                    echo CHtml::ajaxLink($lang,'',
                        array(
                            'type'=>'post',
                            'data'=>'lang='.$key.'&YII_CSRF_TOKEN='.Yii::app()->request->csrfToken,
                            'success' => 'function(data) {window.location.reload();}'
                        ),
                        array()
                    );
                } else echo $lang;
                if($lang != $lastElement) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
            }
        }
        else {
            echo CHtml::dropDownList('lang', $currentLang, $languages,
                array(
                    'submit' => '',
                    'csrf'=>true,
                )
            ); 
        }
        ?>
    </div>
<?php echo CHtml::endForm(); ?>
