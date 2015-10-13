<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
"http://www.w3.org/TR/html4/frameset.dtd">
<html lang="<?php echo Yii::app()->language ?>">
    <head>
        <meta charset="utf-8">
        <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/lhcdipolelogo.gif"/>
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
       <script>
		function mostra_loading_screen()
		{
			setTimeout("document.getElementById('coprente').style.display = 'block'",500);
		}
	 </script> 
        
    </head>
    <body>
    <div onclick="location.reload();" id="coprente"  ><div id=box_popup_che_deve_comparire >Loading <br><img src=<?php echo Yii::app()->request->baseUrl; ?>/images/wait.gif ></div></div>
        <div id="head">
            <h1>
                <a title="CERN" href="http://cern.ch">
                    CERN
                    <span><?php echo Yii::t('default','Accelerating science')?></span>
                </a>
            </h1>
            <div style="float: right;">
            <?php
            if (isset(Yii::app()->user->username)) {
				echo "<div class='log'>".CHtml::link(Yii::t('default', 'Sign out') . ' (' . Yii::app()->user->username . ')', 'https://login.cern.ch/adfs/ls/?wa=wsignout1.0', array('title' => Yii::t('default', 'Sign out of your account')))."</div>";
            } else {
				echo "<div class='log'>".CHtml::link(Yii::t('default', 'Sign out') , 'https://login.cern.ch/adfs/ls/?wa=wsignout1.0', array('title' => Yii::t('default', 'Sign out of your account')))."</div>";
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
                <h2>Vector Traveler System (VTS )</h2>
				<?php echo Warnings::model()->warningSign();?>
				<?php Yii::app()->user->setFlash('construction', 'Warning, Vector down for maintenance'); ?>
                <nav id="mainmenu">
                    <?php
                    $this->widget('zii.widgets.CMenu', array(
                        'items' => array(
                            array('label' => Yii::t('default', 'Home'), 'url' => array('/site/index')),
                            array('label' => Yii::t('default', 'Projects'), 'url' => array('/project/index'), 'visible' => Yii::app()->user->getState('role') > 1),
                            array('label' => Yii::t('default', 'Traveler templates'), 'url' => array('/traveler/index')),
                            array('label' => Yii::t('default', 'Issued travelers'), 'url' => array('/equipment/index')),
                            //array('label' => Yii::t('default', 'Nonconformities'), 'url' => array('/nonconformity/index')),
                            array('label' => Yii::t('default', 'Assembly'), 'url' => array('/equipment/assembly')),
							array('label' => Yii::t('default', 'Users'), 'url' => array('/user'), 'visible' => Yii::app()->user->getState('role') == 4),
							array('label' => Yii::t('default', 'Warnings'), 'url' => array('/warnings/index'), 'visible' => Yii::app()->user->getState('role') == 4),
							array('label' => Yii::t('default', 'Search'), 'url' => array('/site/searching'),'visible' => !Yii::app()->user->isGuest),
							array('label' => Yii::t('default', 'Help'), 'url' => array('/site/help')),
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
                        'homeLink' => false,//CHtml::link(Yii::t('default','Home'), Yii::app()->homeUrl),
						'htmlOptions'=> array('id'=>'breadcrumbs')
                    ));
                    ?><!-- breadcrumbs -->
                <?php endif ?>

                <?php echo $content; ?>

                <div class="clear"></div>


            </div>
        </div><!-- page -->
        <footer id="footer">
            <div class="contenair">
                <a href="http://www.cern.ch"><img src="<?php echo Yii::app()->request->baseUrl;  ?>/images/logo-large.png" alt="cern" style="float: right"/></a>
                <h2><?php echo Yii::t('default','Related sites')?></h2>
                <ul>
                    <li><a href="https://edms.cern.ch/cedar/plsql/cedarw.site_home" target="_blank">EDMS Equipment Data Management Service</a></li>
                    <li><a href="https://edms.cern.ch/asbuilt/plsql/mtf.home" target="_blank">MTF Equipment Management Folder</a></li>
                    <li><a href="http://vector.cern.ch:8000/" target="_blank">Vector FNAL (intern)</a></li>
                    <li><a href="https://vector-offsite.fnal.gov/" target="_blank">Vector FNAL (extern)</a></li>
                </ul>
                Copyright &copy; <?php echo date('Y ') . Yii::t('default', 'by') ?> CERN.<br/>
                <?php echo Yii::t('default', 'All Rights Reserved') ?>.<br/>
                <?php echo Yii::t('default', 'Contact: ') . " <a href='mailto:andrea.romero@cern.ch'>Andrea Romero</a> /  <a href='mailto:per.hagen@cern.ch'>Per Hagen</a></br>"; ?>
				<?php echo Yii::t('default', 'Web design: ') . "<a href='mailto:andrea.romero@cern.ch'>Andrea Romero</a> / Ignacio Asensi / Mattia Bagiella"; ?>
			</div>
        </footer><!-- footer -->

    </body>
</html>
