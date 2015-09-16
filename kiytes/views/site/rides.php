<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Url;

$this->title = 'Kytes | Web Application';
?>
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>css/jquery-ui-1.11.4.min.css" type="text/css" />
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>css/bootstrap-datetimepicker.min.css" type="text/css" />
    
    <script src="<?= Yii::$app->homeUrl; ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
    <script src="<?= Yii::$app->homeUrl; ?>js/jquery-ui-1.11.4.min.js" type="text/javascript"></script>
    
    <script src="<?= Yii::$app->homeUrl; ?>js/bootstrap.js" type="text/javascript"></script>
    <script src="<?= Yii::$app->homeUrl; ?>js/moment-with-locales.min.js" type="text/javascript"></script>
    <script src="<?= Yii::$app->homeUrl; ?>js/bootstrap-datetimepicker.js" type="text/javascript"></script>
    
    
    <header class="panel-heading text-center">
        <div class="row">
            <strong>Rides</strong>
        </div>
    </header>
    <div class="panel-body wrapper-lg">
        <?php if ( Yii::$app->session->hasFlash('invite_error') ) { ?>
            <div class="row form-group">
                <div class="col-sm-12 alert alert-danger text-center">
                    <?= Yii::$app->session->getFlash('invite_error')[0] ?>
                </div>
            </div>
        <?php } ?>
        <div class="row form-group">
            <div class="col-sm-1">
                Driver
            </div>
            <?php if ( $context['driver']) { ?>
                <div class="col-sm-2">
                    <div class="row" style="margin-top:10px;">
                        <div class="col text-center">
                            <a class="label label-default" href="<?= Url::toRoute("site/profile") . "/{$context['driver']->id}"?>"><?= "{$context['driver']->first_name} {$context['driver']->last_name}" ?></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col text-center">
                            <a href="<?= Url::toRoute("site/profile") . "/{$context['driver']->id}"?>">
                                <img class="profile-photo" src="<?= Yii::$app->homeUrl . "/uploads/{$context['driver']->photo}"?>" alt="Driver Avatar">
                            </a>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px;">
                        <div class="col text-center">
                            <a class="label label-info choose_driver" href="#">Choose Another Driver</a>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="col-sm-2 text-left">
                    <a class="label label-info choose_driver" href="#">Choose Driver</a>
                </div>
            <?php } ?>
            
            <div class="col-sm-9">
                <div class="row form-group">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-3" style="margin-top: 9px;">
                        From
                    </div>
                    <div class="col-sm-6">
                        <select id="from_address" class="form-control address-selector"></select>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-3" style="margin-top: 9px;">
                        To
                    </div>
                    <div class="col-sm-6">
                        <select id="to_address" class="form-control address-selector"></select>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-3" style="margin-top: 9px;">
                        Ride Date And Time
                    </div>
                    <div class="col-sm-6">
                        <input type="text" id="time_start" class="form-control"/>
                    </div>
                </div>
                <?php if ( $context['customer'] ) { ?>
                <div class="row form-group">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6 text-right">
                        <a id="send_invite" class="btn btn-primary" href="#">Send Invite</a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
        var addresses = <?= $context['addresses'] ?>;
        
        $(document).ready(function(){
            for( var ind in addresses ) {
                $('#from_address').append("<option id='" + ind + "'>" + addresses[ind] + "</option>");
                $('#to_address').append("<option id='" + ind + "'>" + addresses[ind] + "</option>");
            }
            
            <?php if ( $context['address_start']) { ?>
                    $('#from_address option[id=<?=$context['address_start']?>]').prop('selected', true);
            <?php } ?>
            <?php if ( $context['address_dest']) { ?>
                $('#to_address option[id=<?=$context['address_dest']?>]').prop('selected', true);
            <?php } ?>
            
            var dt_start = new moment();
            <?php if ( $context['time_start']) { ?>
                dt_start = moment(unescape("<?= $context['time_start'] ?>"), "DD/MM/YYYY HH:mm");
            <?php } ?>
            
            dt_start.format('DD/MM/YYYY HH:mm')
            
            $('#time_start').datetimepicker({
                'sideBySide' : true,
                'useSeconds' : false,
                'format' : "DD/MM/YYYY HH:mm",
                'defaultDate' : dt_start,
            }).change(function(e) {
                /*
                console.log('time_start change');
                
                console.log('date time : ', $('#time_start').data("DateTimePicker").date.format("DD/MM/YYYY HH:mm"));
                console.log('date time unix timestamp : ', $('#time_start').data("DateTimePicker").date.format("X"));
                console.log('date time unix ms timestamp : ', $('#time_start').data("DateTimePicker").date.format("x"));
                */
            });
            
            $('#send_invite').click(function(e){
                e.preventDefault();
                
                var fromEl = document.getElementById('from_address'),
                    toEl  = document.getElementById('to_address'),
                    _url = "<?= Url::toRoute('site/rides') ?>?" 
                        + "address_start=" + fromEl.options[fromEl.selectedIndex].id
                        + "&address_dest=" + toEl.options[toEl.selectedIndex].id
                        + "&time_start=" + escape($('#time_start').data("DateTimePicker").date.format("DD/MM/YYYY HH:mm"))
                        + "&send_invite=1";
                
                <?php if ( $context['driver']) { ?>
                    _url += '&driver=<?= $context['driver']->id ?>';
                    location.href = _url;
                <?php } else { ?>
                    alert('Driver not choosen');
                <?php } ?>
            });
            
            $('div.alert').fadeOut(10 * 1000);
        });
        
        $('a.choose_driver').click(function(e) {
            e.preventDefault();
            var 
                fromEl = document.getElementById('from_address'),
                toEl  = document.getElementById('to_address'),
                _url = "<?= Url::toRoute('site/drivers') ?>?" 
                    + "address_start=" + fromEl.options[fromEl.selectedIndex].id
                    + "&address_dest=" + toEl.options[toEl.selectedIndex].id
                    + "&time_start=" + escape($('#time_start').data("DateTimePicker").date.format("DD/MM/YYYY HH:mm"));
            
            location.href = _url;
        });
    </script>
    