<?php

    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap\ActiveForm */
    /* @var $model app\models\LoginForm */

    use yii\helpers\Url;

    $this->title = 'Kiytes | Web Application';
    
    $invitation_get_params = [];
    if ( $context['address_start'] ) {
        $invitation_get_params['address_start'] = $context['address_start'];
    }
    if ( $context['address_dest'] ) {
        $invitation_get_params['address_dest'] = $context['address_dest'];
    }
    if ( $context['time_start'] ) {
        $invitation_get_params['time_start'] = $context['time_start'];
    }
    if ( $context['message'] ) {
        $invitation_get_params['message'] = $context['message'];
    }
?>
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>css/jquery-ui-1.11.4.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>css/jquery.raty.css" type="text/css" />    
    
    <script src="<?= Yii::$app->homeUrl; ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
    <script src="<?= Yii::$app->homeUrl; ?>js/jquery-ui-1.11.4.min.js" type="text/javascript"></script>
    <script src="<?= Yii::$app->homeUrl; ?>js/jquery.raty.js" type="text/javascript"></script>
    
    <header class="panel-heading text-center">
        <div class="row">
            <strong><?= ($context['is_drivers'] ? "Drivers" : "Users") ?></strong>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <input type="text" class="form-control input-sm" id="address_filter" placeholder="Address (zip code)"<?= ($context['filtered_address'] ? " value=\"{$context['filtered_address']}\"" : "") ?>>
            </div>
            <div class="col-sm-1 text-left" style="padding-top:3px;">
                <a class="label label-info" id="filter_by_address" href="#">Filter</a>
            </div>
        </div>
    </header>
    <div class="panel-body wrapper-lg">
        <?php foreach($drivers as $_ind => $driver) { ?>
            <?= (0 !== $_ind ? "<div class=\"line line-dashed\"></div>" : "") ?>
            <div class="row form-group">
                <div class="col-sm-2">
                    <a href="<?= Url::toRoute("site/profile") . '/' . $driver['id'] ?>">
                        <img class="profile-photo" src="<?= Yii::$app->homeUrl ?>uploads/<?= ($driver['photo'] ? $driver['photo'] : "noavatar.png") ?>" alt="Driver Avatar"/>
                    </a>
                </div>
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-sm-4"><?= ($context['is_drivers'] ? "Driver" : "User" ) ?> Rating</div>
                        <div class="col-sm-5">
                            <div class="rating-system" data-score="<?= $driver['rating'] ?>"></div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-5">
                            <span class="label label-info"><?= ('0.00' !== $driver['rating'] ? $driver['rating'] : '') ?></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">First name</div>
                        <div class="col-sm-5">
                            <span class="label label-info"><?= $driver['first_name'] ?></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">Last name</div>
                        <div class="col-sm-5">
                            <span class="label label-info"><?= $driver['last_name'] ?></span>
                        </div>
                    </div>
                    <?php if ($context['is_drivers']) { ?>
                        <div class="row form-group">
                            <div class="col-sm-4">Price/Mile</div>
                            <div class="col-sm-5">
                                <span class="label label-info"><?= $driver['price_mile'] ?></span>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php if ($context['is_drivers']) { ?>
                    <div class="col-sm-2">
                        <!-- <a href="<?= Url::toRoute("site/car") . '/' . $driver['car_id'] ?>"> -->
                            <img class="profile-car" src="<?= Yii::$app->homeUrl ?>uploads/<?= ($driver['car_photo'] ? $driver['car_photo'] : "ImageNotAvailable.png") ?>" alt="Car Photo"/>
                        <!-- </a> -->
                    </div>
                    <div class="col-sm-4">
                        <div class="row form-group">
                            <div class="col-sm-4">Car Model</div>
                            <div class="col-sm-5">
                                <span class="label label-info"><?= $driver['model'] ?></span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-4">Car Make</div>
                            <div class="col-sm-5">
                                <span class="label label-info"><?= $driver['make'] ?></span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-4">Car Year</div>
                            <div class="col-sm-5">
                                <span class="label label-info"><?= $driver['year'] ?></span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-4">Plate Number</div>
                            <div class="col-sm-5">
                                <span class="label label-info"><?= $driver['license_plate'] ?></span>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php if (false == $context['is_guest'] ) { ?>
                <div class="row form-group">
                    <div class="col-sm-2" style="text-align:center;">
                        <?php if ($driver['is_complete']) { ?>
                            <?php if ($context['is_drivers']) { ?>
                                <a class="btn btn-primary" href="<?= Url::toRoute(array_merge(["site/invitation", "driver"=>$driver['id']], $invitation_get_params)) ?>">Hire Driver</a>
                            <?php } else {?>
                                <span class="label label-success">User Profile Complete</span>
                            <?php } ?>
                        <?php } else { ?>
                            <span class="label label-danger"><?= ($context['is_drivers'] ? "Driver" : "User") ?> Profile Incomplete</span>
                        <?php } ?>
                    </div>
                    <?php if ($context['is_admin']) { ?>
                        <div class="col-sm-3" style="text-align:center;">
                            <a class="btn btn-primary" href="<?= Url::toRoute("site/rides") . "/{$driver['id']}" ?>">Show Ride History (Admin Mode)</a>
                        </div>
                        <?php if (!$driver['is_admin']) { ?>
                            <div class="col-sm-3" style="text-align:center;">
                                <a class="btn btn-primary" href="<?= Url::toRoute([($context['is_drivers'] ? "site/drivers" : "site/users"), "user_id" => $driver['id'], "action" => "markAdmin"]) ?>">Mark As Admin (Admin Mode)</a>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
    <?php if ( 1 < $context['total_pages'] ) { ?>
    <footer class="panel-footer text-center">
        <div class="row form-group">
            <div class="col-sm-5" style="text-align:right;">
                <?php if ( 0 < $context['current_page'] ) { ?>
                    <?php $params = ('1' < $context['current_page'] ? [ "page" => intval($context['current_page'])-1] : []); ?>
                    <?php if ($context['filtered_address']) {$params["address"] = $context['filtered_address'];} ?>
                    <a class="label label-info" href="<?= Url::toRoute(array_merge(["site/drivers"], $params, $invitation_get_params)) ?>">Previous Page</a>
                <?php } ?>
            </div>
            <div class="col-sm-2" style="text-align:center;">
                <span class="label label-default">Page <?= intval($context['current_page'])+1 ?></span>
            </div>
            <div class="col-sm-5" style="text-align:left;">
                <?php if ( ($context['current_page']+1) < $context['total_pages'] ) { ?>
                    <?php $params = [ "page" => intval($context['current_page'])+1 ]; ?>
                    <?php if ($context['filtered_address']) {$params["address"] = $context['filtered_address'];} ?>
                    <a class="label label-info" href="<?= Url::toRoute(array_merge(["site/drivers"], $params, $invitation_get_params)) ?>">Next Page</a>
                <?php } ?>
            </div>
        </div>
    </footer>
    <?php } ?>
    <script type="text/javascript">
        $("div.rating-system").raty({
            path: "<?= Yii::$app->homeUrl ?>",
            noRatedMsg : "Not rated yet",
            number: 5,
            readOnly: function() {
                return true;
            },
            score: function() {
                return $(this).attr('data-score');
            },
            click: function(score, evt) {
                //console.log('rate click, ID: ', this.id, "\nscore: ", score, "\nevent: ", evt);
            }
        });
        
        $('#filter_by_address').click(onFilterByAddress);
        $('#address_filter').change(onFilterByAddress);
        
        function onFilterByAddress(e) {
            e.preventDefault();
            
            var address = $('#address_filter').val();
            if ( address.length > 0 ) {
                location.href = "<?= Url::toRoute(array_merge(['site/drivers'], $invitation_get_params)) . (count($invitation_get_params) > 0 ? '&' : '?') ?>address=" + address;
            } else {
                location.href = "<?= Url::toRoute(array_merge(['site/drivers'], $invitation_get_params)) ?>";
            }
            
            return false;
        }
    </script>
    