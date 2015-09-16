<?php
    /* @var $this yii\web\View */

    $this->title = 'Kytes | Web Application';
?>

    <script src="<?= Yii::$app->homeUrl; ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
    <script src="<?= Yii::$app->homeUrl; ?>js/jquery-ui-1.11.4.min.js" type="text/javascript"></script>
    
    <header class="panel-heading text-center">
        <strong><?= ( isset($content) && (isset($content['header'])) ? $content['header'] : "Home page") ?></span></strong>
    </header>
    <div class="panel-body wrapper-lg">
        <div class="form-group" style="text-align: center;">
            <?= ( isset($content) && (isset($content['body'])) ? $content['body'] : "In default layout") ?>
        </div>
        <?php if ( Yii::$app->session->hasFlash('home_notification') ) { ?>
            <div class="row form-group">
                <div class="col-sm-12 alert alert-info text-center">
                    <?= Yii::$app->session->getFlash('home_notification')[0] ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('div.alert').fadeOut(10 * 1000);
        });
    </script>
    