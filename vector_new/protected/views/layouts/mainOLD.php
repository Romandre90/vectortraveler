<!DOCTYPE html>
<html lang="<?php echo Yii::app()->language ?>">
    <head>
        <meta charset="utf-8">
        <link rel="shortcut icon" href="/images/lhcdipolelogo.gif"/>
        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/vector.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-te-1.4.0.css" />
        
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>
        <div id="head">
            <h1>
                <a title="CERN" href="http://cern.ch">
                    CERN
                    <span><?php echo Yii::t('default','Accelerating science')?></span>
                </a>
            </h1>
            <div style="float: right;">
            <?php
            if (Yii::app()->user->isGuest) {
                if (Yii::app()->controller->action->id== 'login'){
                    echo "<div class='login'>".Yii::t('default', 'Sign in')."</div>";
                }else{
                    echo "<div class='log'>".CHtml::link(Yii::t('default', 'Sign in'), '#', array('submit'=>array('site/login'),'params'=>array('login'=>true),'title' => Yii::t('default', 'Sign in to your CERN account')))."</div>";
                }

            } else {
                echo "<div class='log'>".CHtml::link(Yii::t('default', 'Sign out') . ' (' . Yii::app()->user->name . ')', array('/site/logout'), array('title' => Yii::t('default', 'Sign out of your account')))."</div>";
            }
            ?>
            </div>

        </div>

        <div id="menu">
            <div class="contenair">
                <div  id="language-selector" style="float: right;">
                    <?php
                    $this->widget('application.components.widgets.LanguageSelector');
                    ?>
                </div>
                <h2>CERN Vector Traveler System (VTS)</h2>
				<?php echo Warnings::model()->warningSign();?>
				
                <nav id="mainmenu">
                    <?php
                    $this->widget('zii.widgets.CMenu', array(
                        'items' => array(
                            array('label' => Yii::t('default', 'Home'), 'url' => array('/site/index')),
                            //array('label'=>Yii::t('default','About'), 'url'=>array('/site/page', 'view'=>'about')),
                            array('label' => Yii::t('default', 'Projects'), 'url' => array('/project/index'), 'visible' => Yii::app()->user->getState('role') > 1),
                            array('label' => Yii::t('default', 'Travelers'), 'url' => array('/traveler/index')),
                            array('label' => Yii::t('default', 'Equipments'), 'url' => array('/equipment/index')),
                            array('label' => Yii::t('default', 'Nonconformities'), 'url' => array('/nonconformity/index')),
                            //array('label'=>Yii::t('default','Contact'), 'url'=>array('/site/contact')),
                            array('label' => Yii::t('default', 'Users'), 'url' => array('/user'), 'visible' => Yii::app()->user->getState('role') == 4),
							array('label' => Yii::t('default', 'Warnings'), 'url' => array('/warnings/index'), 'visible' => Yii::app()->user->getState('role') == 4),
                        ),
                    ));
                    ?>

                </nav>
            </div>

        </div>
        <div id="page">
            <div class="container">
                <?php if (isset($this->breadcrumbs)): ?>
                    <?php
                    $this->widget('zii.widgets.CBreadcrumbs', array(
                        'links' => $this->breadcrumbs,
                        'homeLink' => CHtml::link(Yii::t('default','Home'), Yii::app()->homeUrl),
                    ));
                    ?><!-- breadcrumbs -->
                <?php endif ?>

                <?php echo $content; ?>

                <div class="clear"></div>


            </div>
        </div><!-- page -->
        <footer id="footer">
            <div class="contenair">
                <a href="http://www.cern.ch"><img src="/images/logo-large.png" alt="cern" style="float: right"/></a>
                <h2><?php echo Yii::t('default','Related sites')?></h2>
                <ul>
                    <li><a href="https://edms.cern.ch/cedar/plsql/cedarw.site_home" target="_blank">EDMS Equipment Data Management Service</a></li>
                    <li><a href="https://edms.cern.ch/asbuilt/plsql/mtf.home" target="_blank">MTF Equipment Management Folder</a></li>
                    <li><a href="http://vector.cern.ch:8000/" target="_blank">Vector FNAL (intern)</a></li>
                    <li><a href="https://vector-offsite.fnal.gov/" target="_blank">Vector FNAL (extern)</a></li>
                </ul>
                Copyright &copy; <?php echo date('Y ') . Yii::t('default', 'by') ?> CERN.<br/>
                <?php echo Yii::t('default', 'All Rights Reserved') ?>.<br/>
                <?php echo Yii::t('default', 'Contact') . " <a href='mailto:ignacio.asensi@cern.ch'>Ignacio Asensi</a> / Mattia Bagiella / <a href='mailto:per.hagen@cern.ch'>Per Hagen</a>"; ?>
            </div>
        </footer><!-- footer -->

    </body>
</html>
