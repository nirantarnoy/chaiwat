<?php
use yii\helpers\Html;
use yii\web\Session;

$session = new Session();
$session->open();

$_SESSION['picklist']= [];


/* @var $this \yii\web\View */
/* @var $content string */


if (Yii::$app->controller->action->id === 'login') {
/**
 * Do not use this code in your template. Remove it.
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    <?php

$js = '
            $(function () {
                "use strict";

                $.AdminLTESidebarTweak = {};

            $.AdminLTESidebarTweak.options = {
                EnableRemember: true,
                NoTransitionAfterReload: false
                //Removes the transition after page reload.
            };

                $("body").on("collapsed.pushMenu", function(){
                    if($.AdminLTESidebarTweak.options.EnableRemember){
                        document.cookie = "toggleState=closed";
                    } 
                });
                $("body").on("expanded.pushMenu", function(){
                    if($.AdminLTESidebarTweak.options.EnableRemember){
                        document.cookie = "toggleState=opened";
                    } 
                });

                if($.AdminLTESidebarTweak.options.EnableRemember){
                    var re = new RegExp("toggleState" + "=([^;]+)");
                    var value = re.exec(document.cookie);
                    var toggleState = (value != null) ? unescape(value[1]) : null;
                    if(toggleState == "closed"){
                        if($.AdminLTESidebarTweak.options.NoTransitionAfterReload){
                            $("body").addClass("sidebar-collapse hold-transition").delay(100).queue(function(){
                                $(this).removeClass("hold-transition"); 
                            });
                        }else{
                            $("body").addClass("sidebar-collapse");
                        }
                    }
                } 
            });
';

$this->registerJs($js,static::POS_END);
     ?>

    </head>
    <body class="hold-transition skin-green-light sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>
 
    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
