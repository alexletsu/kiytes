<?php

    /* @var $this \yii\web\View */
    /* @var $content string */

    use yii\helpers\Html;
    use yii\bootstrap\Nav;
    use yii\bootstrap\NavBar;
    use yii\widgets\Breadcrumbs;

    use app\assets\AppAsset;
    use app\models\User;
    use app\models\Ride;

    AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>css/animate.css" type="text/css" />
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>css/font.css" type="text/css" />
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>css/app.css" type="text/css" />
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>css/site.css" type="text/css" />
    
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>css/jquery-ui-1.11.4.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>css/bootstrap-datetimepicker.min.css" type="text/css" />
    
    <script src="<?= Yii::$app->homeUrl; ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
    <script src="<?= Yii::$app->homeUrl; ?>js/jquery-ui-1.11.4.min.js" type="text/javascript"></script>
    
    <script src="<?= Yii::$app->homeUrl; ?>js/bootstrap.js" type="text/javascript"></script>
    <script src="<?= Yii::$app->homeUrl; ?>js/moment-with-locales.min.js" type="text/javascript"></script>
    <script src="<?= Yii::$app->homeUrl; ?>js/bootstrap-datetimepicker.js" type="text/javascript"></script>
    
</head>
<body>
<?php $this->beginBody() ?>

    <?php
        $items = [
                ['label' => 'Home', 'url' => ['site/home']],            
            ];

        $_currUser = ( Yii::$app->user->isGuest ? null : User::findOne(['id' => Yii::$app->user->id]) );
        
        $pendingRides = null;
        if ( $_currUser ) {
            if ( User::$_TYPE_CUSTOMER === $_currUser->user_type ) {
                $pendingRides = Ride::find()->where(
                        '(client_id = :client_id) AND (status IN (:status_invitation, :status_pending))', 
                        [
                            ':client_id' => $_currUser->id,
                            ':status_invitation' => Ride::$_STATUS_INVITATION,
                            ':status_pending' => Ride::$_STATUS_PENDING,
                        ]
                    )->count();
            } else {
                $pendingRides = Ride::find()->where(
                        '(driver_id = :driver_id) AND (status IN (:status_invitation, :status_pending))', 
                        [
                            ':driver_id' => $_currUser->id,
                            ':status_invitation' => Ride::$_STATUS_INVITATION,
                            ':status_pending' => Ride::$_STATUS_PENDING,
                        ]
                    )->count();
            }
        }

        if ( !$_currUser || (User::$_TYPE_CUSTOMER === $_currUser->user_type) ) {
            $items[] = ['label' => 'Drivers' . ($_currUser && $_currUser->is_admin ? ' (admin mode)' : ''), 'url' => ['site/drivers']];
        }
        
        if ( $_currUser && $_currUser->is_admin ) {
            $items[] = ['label' => 'Users (admin mode)', 'url' => ['site/users']];
        }

        if ( !$_currUser ) {
            $items[] = ['label' => 'Sign In', 'url' => ['/site/login']];
            $items[] = ['label' => 'Sign Up', 'url' => ['/site/signup']];
        } else {
            if ( User::$_TYPE_CUSTOMER === $_currUser->user_type ) {
                $items[] = ['label' => 'Create Ride Invitation', 'url' => ['/site/invitation']];
            }
            
            $items[] = [
                    'label' => 'Rides', 
                    'url' => ['/site/rides'],
                    'linkOptions' => ['class' => 'rides-item'],
                ];
            
            $items[] = [
                    'label' => 'Profile (' . Yii::$app->user->identity->email . ')',
                    'url' => ['/site/profile']
                ];
            $items[] = [
                    'label' => 'Logout (' . Yii::$app->user->identity->email . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
        }
    ?>
    
    <?php 
        NavBar::begin([
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-default navbar-inverse',
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => $items,
        ]);

        echo Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]);
        NavBar::end();
    ?>
    <section id="content" class="m-t-lg wrapper-md animated fadeInUp">
        <div class="container">
            <a class="navbar-brand block logo" href="<?= Yii::$app->homeUrl ?>">KIYTES</a>
            <section class="panel panel-default m-t-lg bg-white">
                <?= $content ?>
            </section>
        </div>
    </section>
    
    <!-- footer -->
    <footer id="footer">
        <div class="text-center padder clearfix">
            <p>
                <small> &copy; 2015</small>
            </p>
        </div>
    </footer>
    <!-- / footer -->
    <script type="text/javascript">
        $(document).ready(function() {
            <?php if ( $pendingRides ) { ?>
                $ridesItem = $('.nav li a.rides-item');
                $ridesItem.html($ridesItem.html() + "<span class=\"badge\" style=\"background-color:#31b0d5; margin-left:10px;\"><?= intval($pendingRides) ?></span>")
            <?php } ?>
        });
    </script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
