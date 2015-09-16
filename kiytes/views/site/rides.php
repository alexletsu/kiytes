<?php

    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap\ActiveForm */
    /* @var $model app\models\LoginForm */

    use yii\helpers\Url;
    
    use app\models\User;
    use app\models\Address;
    use app\models\Ride;

    $this->title = 'Kiytes | Web Application';
    //Yii::$app->session->setFlash('ride_error', 'ride error');
?>
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>css/jquery.raty.css" type="text/css" />    
    <script src="<?= Yii::$app->homeUrl; ?>js/jquery.raty.js" type="text/javascript"></script>
    
    <header class="panel-heading text-center">
        <div class="row">
            <strong>Rides</strong>
            <?= ($context['is_admin'] ? "<br><div class=\"label label-success\">(admin mode, rides for user " . $context['user_email'] . ")</div>" : "") ?>
        </div>
    </header>
    <div class="panel-body wrapper-lg">
        <?php if ( Yii::$app->session->hasFlash('ride_error') ) { ?>
            <div class="row form-group">
                <div class="col-sm-12 alert alert-danger text-center">
                    <?= Yii::$app->session->getFlash('ride_error') ?>
                </div>
            </div>
        <?php } ?>
        
        <!-- <rides pending> --> 
        <?php if ( $ridesPending && ( 0 < count($ridesPending)) ) {  ?>
            <div class="row form-group" style="margin-top:30px;">
                <div class="col-lg-3">
                    <div class="label label-success">Pending Rides</div>
                </div>
            </div>
            <div class="rides-block">
            <?php foreach($ridesPending as $ind => $pendingRide) { ?>
                <div class="line line-dashed"></div>
                <?php if ( User::$_TYPE_CUSTOMER === $context['user_type']) { ?>
                    <?php 
                        $_driver = User::findOne(['id' => $pendingRide->driver_id]);
                        $_startAddress = Address::findOne(['id'=>$pendingRide->address_start]);
                        $_endAddress = Address::findOne(['id'=>$pendingRide->address_end]);
                        
                        $_dtStart = new \DateTime();
                        $_dtStart->setTimestamp( $pendingRide->time_start );
                    ?>
                    <div class="row form-group">
                        <div class="col-lg-3"></div>
                        <div class="col-sm-2">
                            <a href="<?= Url::toRoute('site/profile') . "/{$_driver->id}" ?>">
                                <img class="profile-photo" src="<?= Yii::$app->homeUrl . "uploads/" . ($_driver->photo ? $_driver->photo : "noavatar.png") ?>" alt="Driver Avatar">
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-3">Driver Rating</div>
                                <div class="col-sm-4">
                                    <div class="rating-system" data-score="<?= $_driver->getRate() ?>"></div>
                                </div>
                                <div class="col-sm-2">Start At</div>
                                <div class="col-sm-3">
                                    <span class="label label-info"><?= $_dtStart->format("d/m/Y H:i") ?></span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-4">
                                    <span class="label label-info"><?= ('0.00' !== $_driver->getRate() ? $_driver->getRate() : '') ?></span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-3">From</div>
                                <div class="col-sm-4">
                                    <span class="label label-info"><?= $_startAddress->address ?></span>
                                </div>
                                <div class="col-sm-2">Status</div>
                                <div class="col-sm-3">
                                    <span class="label label-info">Pending</span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-3">To</div>
                                <div class="col-sm-4">
                                    <span class="label label-info"><?= $_endAddress->address ?></span>
                                </div>
                            </div>
                            <?php if ($pendingRide->message) { ?>
                                <div class="row form-group">
                                    <div class="col-sm-3">Message</div>
                                    <div class="col-sm-4">
                                        <span class="label label-info"><?= $pendingRide->message ?></span>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <?php 
                        $_client = User::findOne(['id' => $pendingRide->client_id]);
                        $_startAddress = Address::findOne(['id'=>$pendingRide->address_start]);
                        $_endAddress = Address::findOne(['id'=>$pendingRide->address_end]);
                        
                        $_dtStart = new \DateTime();
                        $_dtStart->setTimestamp( $pendingRide->time_start );
                    ?>
                    <div class="row form-group">
                        <div class="col-lg-2"></div>
                        <div class="col-sm-2">
                            <a href="<?= Url::toRoute('site/profile') . "/{$_client->id}" ?>">
                                <img class="profile-photo" src="<?= Yii::$app->homeUrl . "uploads/" . ($_client->photo ? $_client->photo : "noavatar.png") ?>" alt="Customer Avatar">
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-3">Client Rating</div>
                                <div class="col-sm-4">
                                    <div class="rating-system" data-score="<?= $_client->getRate() ?>"></div>
                                </div>
                                <div class="col-sm-2">Start At</div>
                                <div class="col-sm-3">
                                    <span class="label label-info"><?= $_dtStart->format("d/m/Y H:i") ?></span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-4">
                                    <span class="label label-info"><?= ('0.00' !== $_client->getRate() ? $_client->getRate() : '') ?></span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-3">From</div>
                                <div class="col-sm-4">
                                    <span class="label label-info"><?= $_startAddress->address ?></span>
                                </div>
                                <div class="col-sm-2">Status</div>
                                <div class="col-sm-3">
                                    <span class="label label-info">Pending</span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-3">To</div>
                                <div class="col-sm-4">
                                    <span class="label label-info"><?= $_endAddress->address ?></span>
                                </div>
                                <?php if (!$context['is_admin']) { ?>
                                    <div class="col-sm-2">
                                        <a class="label label-success" href="<?= Url::toRoute(['site/rides', 'ride_id' => $pendingRide->id, 'action' => 'accept']) ?>">Accept</a>
                                    </div>
                                    <div class="col-sm-3">
                                        <a class="label label-danger" href="<?= Url::toRoute(['site/rides', 'ride_id' => $pendingRide->id, 'action' => 'decline']) ?>">Decline</a>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php if ($pendingRide->message) { ?>
                                <div class="row form-group">
                                    <div class="col-sm-3">Message</div>
                                    <div class="col-sm-4">
                                        <span class="label label-info"><?= $pendingRide->message ?></span>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
            </div>
        <?php } ?>
        <!-- </rides pending> --> 
        
        <!-- <rides active> --> 
        <?php if ( $ridesActive && ( 0 < count($ridesActive)) ) {  ?>
            <div class="row form-group" style="margin-top:30px;">
                <div class="col-lg-3">
                    <div class="label label-success">Active Rides</div>
                </div>
            </div>
            <div class="rides-block">
            <?php foreach($ridesActive as $ind => $activeRide) { ?>
                <?php if ( User::$_TYPE_CUSTOMER === $context['user_type']) { ?>
                    <?php 
                        $_driver = User::findOne(['id' => $activeRide->driver_id]);
                        $_startAddress = Address::findOne(['id' => $activeRide->address_start]);
                        $_endAddress = Address::findOne(['id' => $activeRide->address_end]);
                        
                        $_dtStart = new \DateTime();
                        $_dtStart->setTimestamp( $activeRide->time_start );
                    ?>
                    <div class="line line-dashed"></div>
                    <div class="row form-group">
                        <div class="col-lg-3"></div>
                        <div class="col-sm-2">
                            <a href="<?= Url::toRoute('site/profile') . "/{$_driver->id}" ?>">
                                <img class="profile-photo" src="<?= Yii::$app->homeUrl . "uploads/" . ($_driver->photo ? $_driver->photo : "noavatar.png") ?>" alt="Driver Avatar">
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-3">Driver Rating</div>
                                <div class="col-sm-4">
                                    <div class="rating-system" data-score="<?= $_driver->getRate() ?>"></div>
                                </div>
                                <div class="col-sm-2">Start At</div>
                                <div class="col-sm-3">
                                    <span class="label label-info"><?= $_dtStart->format("d/m/Y H:i") ?></span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-4">
                                    <span class="label label-info"><?= ('0.00' !== $_driver->getRate() ? $_driver->getRate() : '') ?></span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-3">From</div>
                                <div class="col-sm-4">
                                    <span class="label label-info"><?= $_startAddress->address ?></span>
                                </div>
                                <div class="col-sm-2">Status</div>
                                <div class="col-sm-3">
                                    <span class="label label-success">Accepted</span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-3">To</div>
                                <div class="col-sm-4">
                                    <span class="label label-info"><?= $_endAddress->address ?></span>
                                </div>
                                <?php if (!$context['is_admin']) { ?>
                                    <div class="col-sm-4">
                                        <a class="label label-success" href="<?= Url::toRoute(['site/rides', 'ride_id' => $activeRide->id, 'action' => 'markComplete']) ?>">Mark Ride As Complete</a>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php if ($activeRide->message) { ?>
                                <div class="row form-group">
                                    <div class="col-sm-3">Message</div>
                                    <div class="col-sm-4">
                                        <span class="label label-info"><?= $activeRide->message ?></span>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <?php 
                        $_client = User::findOne(['id' => $activeRide->client_id]);
                        $_startAddress = Address::findOne(['id' => $activeRide->address_start]);
                        $_endAddress = Address::findOne(['id' => $activeRide->address_end]);
                        
                        $_dtStart = new \DateTime();
                        $_dtStart->setTimestamp( $activeRide->time_start );
                    ?>
                    <div class="line line-dashed"></div>
                    <div class="row form-group">
                        <div class="col-lg-3"></div>
                        <div class="col-sm-2">
                            <a href="<?= Url::toRoute('site/profile') . "/{$_client->id}" ?>">
                                <img class="profile-photo" src="<?= Yii::$app->homeUrl . "uploads/" . ($_client->photo ? $_client->photo : "noavatar.png") ?>" alt="Customer Avatar">
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-3">Client Rating</div>
                                <div class="col-sm-4">
                                    <div class="rating-system" data-score="<?= $_client->getRate() ?>"></div>
                                </div>
                                <div class="col-sm-2">Start At</div>
                                <div class="col-sm-3">
                                    <span class="label label-info"><?= $_dtStart->format("d/m/Y H:i") ?></span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-4">
                                    <span class="label label-info"><?= ('0.00' !== $_client->getRate() ? $_client->getRate() : '') ?></span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-3">From</div>
                                <div class="col-sm-4">
                                    <span class="label label-info"><?= $_startAddress->address ?></span>
                                </div>
                                <div class="col-sm-2">Status</div>
                                <div class="col-sm-3">
                                    <span class="label label-success">Accepted</span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-3">To</div>
                                <div class="col-sm-4">
                                    <span class="label label-info"><?= $_endAddress->address ?></span>
                                </div>
                            </div>
                            <?php if ($activeRide->message) { ?>
                                <div class="row form-group">
                                    <div class="col-sm-3">Message</div>
                                    <div class="col-sm-4">
                                        <span class="label label-info"><?= $activeRide->message ?></span>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
            </div>
        <?php } ?>
        <!-- </rides active> --> 
        
        <!-- <rides history> --> 
        <?php if ( $ridesHistory && ( 0 < count($ridesHistory)) ) {  ?>
            <div class="row form-group" style="margin-top:30px;">
                <div class="col-lg-3">
                    <div class="label label-default">Rides History</div>
                </div>
            </div>
            <div class="rides-block">
            <?php foreach($ridesHistory as $ind => $rideHistory) { ?>
                <?php if ( User::$_TYPE_CUSTOMER === $context['user_type']) { ?>
                    <?php 
                        $_driver = User::findOne(['id' => $rideHistory->driver_id]);
                        $_startAddress = Address::findOne(['id' => $rideHistory->address_start]);
                        $_endAddress = Address::findOne(['id' => $rideHistory->address_end]);
                        
                        $_dtStart = new \DateTime();
                        $_dtStart->setTimestamp( $rideHistory->time_start );
                    ?>
                    <div class="line line-dashed"></div>
                    <div class="row form-group">
                        <div class="col-lg-3"></div>
                        <div class="col-sm-2">
                            <a href="<?= Url::toRoute('site/profile') . "/{$_driver->id}" ?>">
                                <img class="profile-photo" src="<?= Yii::$app->homeUrl . "uploads/" . ($_driver->photo ? $_driver->photo : "noavatar.png") ?>" alt="Driver Avatar">
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-3">Driver Rating</div>
                                <div class="col-sm-4">
                                    <div class="rating-system" data-score="<?= $_driver->getRate() ?>"></div>
                                </div>
                                <div class="col-sm-2">Started At</div>
                                <div class="col-sm-3">
                                    <span class="label label-default"><?= $_dtStart->format("d/m/Y H:i") ?></span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-4">
                                    <span class="label label-default"><?= ('0.00' !== $_driver->getRate() ? $_driver->getRate() : '') ?></span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-3">From</div>
                                <div class="col-sm-4">
                                    <span class="label label-default"><?= $_startAddress->address ?></span>
                                </div>
                                <div class="col-sm-2">Status</div>
                                <div class="col-sm-3">
                                    <?php if ( Ride::$_STATUS_COMPLETE === $rideHistory->status ) { ?>
                                        <span class="label label-success">Complete</span>
                                    <?php } elseif ( Ride::$_STATUS_DECLINED === $rideHistory->status ) { ?>
                                        <span class="label label-danger">Declined</span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-3">To</div>
                                <div class="col-sm-4">
                                    <span class="label label-default"><?= $_endAddress->address ?></span>
                                </div>
                                <?php if ( !$context['is_admin'] && (Ride::$_STATUS_COMPLETE === $rideHistory->status) && !$_driver->isRatedBy($context['user_id'])) { ?>
                                    <div class="col-sm-2">
                                        <a class="label label-default ride-complete" data-ride="<?= $rideHistory->id ?>" href="#">Rate Driver</a>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="rating-system ride-complete" data-ride="<?= $rideHistory->id ?>"></div>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php if ($rideHistory->message) { ?>
                                <div class="row form-group">
                                    <div class="col-sm-3">Message</div>
                                    <div class="col-sm-4">
                                        <span class="label label-default"><?= $rideHistory->message ?></span>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <?php 
                        $_client = User::findOne(['id' => $rideHistory->client_id]);
                        $_startAddress = Address::findOne(['id' => $rideHistory->address_start]);
                        $_endAddress = Address::findOne(['id' => $rideHistory->address_end]);
                        
                        $_dtStart = new \DateTime();
                        $_dtStart->setTimestamp( $rideHistory->time_start );
                    ?>
                    <div class="line line-dashed"></div>
                    <div class="row form-group">
                        <div class="col-lg-3"></div>
                        <div class="col-sm-2">
                            <a href="<?= Url::toRoute('site/profile') . "/{$_client->id}" ?>">
                                <img class="profile-photo" src="<?= Yii::$app->homeUrl . "uploads/" . ($_client->photo ? $_client->photo : "noavatar.png") ?>" alt="Customer Avatar">
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-3">Client Rating</div>
                                <div class="col-sm-4">
                                    <div class="rating-system" data-score="<?= $_client->getRate() ?>"></div>
                                </div>
                                <div class="col-sm-2">Started At</div>
                                <div class="col-sm-3">
                                    <span class="label label-default"><?= $_dtStart->format("d/m/Y H:i") ?></span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-4">
                                    <span class="label label-info"><?= ('0.00' !== $_client->getRate() ? $_client->getRate() : '') ?></span>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-3">From</div>
                                <div class="col-sm-4">
                                    <span class="label label-default"><?= $_startAddress->address ?></span>
                                </div>
                                <div class="col-sm-2">Status</div>
                                <div class="col-sm-3">
                                    <?php if ( Ride::$_STATUS_COMPLETE === $rideHistory->status ) { ?>
                                        <span class="label label-success">Complete</span>
                                    <?php } elseif ( Ride::$_STATUS_DECLINED === $rideHistory->status ) { ?>
                                        <span class="label label-danger">Declined</span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-3">To</div>
                                <div class="col-sm-4">
                                    <span class="label label-default"><?= $_endAddress->address ?></span>
                                </div>
                                <?php if ( !$context['is_admin'] && (Ride::$_STATUS_COMPLETE === $rideHistory->status) && !$_client->isRatedBy($context['user_id'])) { ?>
                                    <div class="col-sm-2">
                                        <a class="label label-default ride-complete" data-ride="<?= $rideHistory->id ?>" href="#">Rate Client</a>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="rating-system ride-complete" data-ride="<?= $rideHistory->id ?>"></div>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php if ($rideHistory->message) { ?>
                                <div class="row form-group">
                                    <div class="col-sm-3">Message</div>
                                    <div class="col-sm-4">
                                        <span class="label label-default"><?= $rideHistory->message ?></span>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
            </div>
        <?php } ?>
        <!-- </rides history> -->
    </div>
    
    <script type="text/javascript">
        $(document).ready(function(){
            $('div.alert').fadeOut(10 * 1000);
        });
        
        $("div.rating-system").raty({
            path: "<?= Yii::$app->homeUrl ?>",
            noRatedMsg : "Not rated yet",
            number: 5,
            readOnly: function() {
                return !this.classList.contains('ride-complete');
            },
            score: function() {
                return $(this).attr('data-score');
            },
            click: function(score, evt) {
                this.dataset['score'] = score;
                $('a.ride-complete[data-ride=' + this.dataset['ride'] + ']').addClass('label-success').removeClass('label-default');
                //console.log('rate click, ID: ', this.id, "\nscore: ", score, "\nevent: ", evt);
            }
        });
        
        $("a.ride-complete").click(function(ev){
            ev.preventDefault();
            
            completeRide(this.dataset['ride']);
        });
        
        function completeRide(rideId) {
            var 
                $rateRideEl = $('div.rating-system.ride-complete[data-ride=' + rideId + ']'),
                _score = ( 'undefined' !== typeof($rateRideEl.data('score')) ? $rateRideEl.data('score') : null )
                _url = "<?= Url::toRoute(['site/rides', 'action' => (User::$_TYPE_CUSTOMER === $context['user_type'] ? 'rateDriver' : 'rateClient')]) ?>&ride_id=" + rideId + (_score ? "&rate=" + _score : "");
                
            //console.log("completeRide, ride id : ", rideId, ", rate element : ", $rateRideEl[0], ", rate score : ", _score , ", url : ", _url);
            if ( _score ) {
                location.href = _url;
            }
        }
    </script>
    