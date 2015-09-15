<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="bg-dark">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
    <?= Html::csrfMetaTags() ?>
    
    <title><?= Html::encode($this->title) ?></title>
    
    <?php $this->head() ?>
    
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>css/animate.css" type="text/css" />
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>css/font.css" type="text/css" />
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>css/app.css" type="text/css" />
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>css/site.css" type="text/css" />
</head>
<body>
    <section id="content" class="m-t-lg wrapper-md animated fadeInUp">
        <div class="container aside-xxl">
            <a class="navbar-brand block logo" href="<?= Yii::$app->homeUrl ?>">KYTES</a>
            <section class="panel panel-default m-t-lg bg-white">
                <?= $content; ?>
            </section>
        </div>
    </section>
    <!-- footer -->
    <footer id="footer">
        <div class="text-center padder clearfix">
            <p>
                <small> &copy; 2013</small>
            </p>
        </div>
    </footer>
    <!-- / footer -->
</body>
</html>



