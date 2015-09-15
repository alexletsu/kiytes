<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\User;
use yii\helpers\Url;

$this->title = 'Kytes | Web Application';
?>
    <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>css/jquery-ui-1.11.4.min.css" type="text/css" />
    
    <script src="<?= Yii::$app->homeUrl; ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
    <script src="<?= Yii::$app->homeUrl; ?>js/jquery-ui-1.11.4.min.js" type="text/javascript"></script>
    
    <?php if ( !$model->isExists ) { ?>
                    <header class="panel-heading text-center">
                        <strong>Profile not found</strong>
                    </header>
    <?php } elseif ( !$model->isEditable ) { ?>
        <div class="row">
            <header class="panel-heading text-center">
                <strong>Profile : <?= (User::$_TYPE_CUSTOMER == $model->userType ? "CUSTOMER" : "DRIVER") ?> <span class="label <?= ($model->isComplete ? "label-success" : "label-danger" )?>" style="margin-left:20px"><?= ($model->isComplete ? "complete" : "incomplete") ?></span></strong>
            </header>

            <div class="panel-body wrapper-lg">
                <div class="form-group" style="text-align: center;">
                    <img class="profile-photo" src="<?= Yii::$app->homeUrl . "uploads/" . ('' != $model->photo ? $model->photo : 'noavatar.png') ?>" alt="Avatar"/>
                </div>
                
                <div class="row form-group">
                    <div class="col-sm-4">First name</div>
                    <div class="col-sm-4">
                        <span class="label label-info"><?= $model->firstName ?></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-4">Last name</div>
                    <div class="col-sm-4">
                        <span class="label label-info"><?= $model->lastName ?></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-4">Email</div>
                    <div class="col-sm-4">
                        <span class="label label-info"><a href="mailto:<?= $model->email ?>"><?= $model->email ?></a></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-4">Phone</div>
                    <div class="col-sm-3">
                        <span class="label label-info"><?= $model->phone ?></span>
                    </div>
                    <div class="col-sm-3">
                        <span style="margin-left: 20px;" class="label <?= ($model->phoneVerified ? "label-success" : "label-danger") ?>"><?= ($model->phoneVerified ? 'Verified' : 'Not Verified') ?></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-4">Credit Card Information</div>
                    <div class="col-sm-4">
                        <span class="label <?= ($model->creditCardInfo ? "label-success":"label-danger") ?>"><?= ($model->creditCardInfo ? $model->creditCardInfo : "Not Specified") ?></span>
                    </div>
                </div>

                <?php if ( User::$_TYPE_CUSTOMER == $model->userType ) { ?>
                    <?php if ( 0 < count($model->address)) { ?>
                        <div class="line line-dashed"></div>
                        <div class="row form-group">
                            <div class="col-sm-4">Addresses</div>
                            <div class="col-sm-4">
                                <?php foreach($model->address as $_address) { ?>
                                    <div class="row form-group"><div class="col-sm-4">
                                        <span class="label label-success"><?= $_address ?></span><br>
                                    </div></div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="row form-group">
                        <div class="col-sm-4">Address</div>
                        <div class="col-sm-4">
                            <span class="label <?= ($model->address ? "label-success":"label-danger") ?>"><?= ($model->address ? $model->address : "Not Specified") ?></span>
                        </div>
                    </div>
                    
                    <div class="line line-dashed"></div>
                    <div class="form-group" style="text-align: center;">
                        <span class="label <?= ($model->license_photo ? "label-success":"label-danger") ?>">Driver License copy</span><br>
                        <img class="profile-license" src="<?= Yii::$app->homeUrl . "uploads/" . ($model->license_photo ? $model->license_photo : 'ImageNotAvailable.png') ?>" alt="Driver License"/>
                    </div>
                    <div class="form-group" style="text-align: center;">
                        <span class="label <?= ($model->insurance_photo ? "label-success":"label-danger") ?>">Driver Insurance copy</span><br>
                        <img class="profile-insurance" src="<?= Yii::$app->homeUrl . "uploads/" . ($model->insurance_photo ? $model->insurance_photo : 'ImageNotAvailable.png') ?>" alt="Driver Insurance"/>
                    </div>
                    <div class="form-group" style="text-align: center;">
                        <span class="label <?= ($model->car_photo ? "label-success":"label-danger") ?>">Car photo</span><br>
                        <img class="profile-car" src="<?= Yii::$app->homeUrl . "uploads/" . ($model->car_photo ? $model->car_photo : 'ImageNotAvailable.png') ?>" alt="Car Photo"/>
                    </div>

                    <div class="line line-dashed"></div>
                    <div class="row form-group">
                        <div class="col-sm-4">Price/mile</div>
                        <div class="col-sm-4">
                            <span class="label <?= ($model->priceMile ? "label-success":"label-danger") ?>"><?= ($model->priceMile ? $model->priceMile : "Not Specified") ?></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">Car Make</div>
                        <div class="col-sm-4">
                            <span class="label <?= ($model->carMake ? "label-success":"label-danger") ?>"><?= ($model->carMake ? $model->carMake : "Not Specified") ?></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">Car Model</div>
                        <div class="col-sm-4">
                            <span class="label <?= ($model->carModel ? "label-success":"label-danger") ?>"><?= ($model->carModel ? $model->carModel : "Not Specified") ?></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">Car Year</div>
                        <div class="col-sm-4">
                            <span class="label <?= ($model->carYear ? "label-success":"label-danger") ?>"><?= ($model->carYear ? $model->carYear : "Not Specified") ?></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">Car License Plate Number</div>
                        <div class="col-sm-4">
                            <span class="label <?= ($model->licensePlateNumber ? "label-success":"label-danger") ?>"><?= ($model->licensePlateNumber ? $model->licensePlateNumber : "Not Provided") ?></span>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } else { ?>
        <?php $form = ActiveForm::begin([
            'id' => 'profile-form',
            'options' => [
                'enctype'=>'multipart/form-data',
                'class' => 'panel-body wrapper-lg'],
            
            'fieldConfig' => [
                'template' => 
                    "<div class=\"col-sm-4\">{label}</div>\n"
                    . "<div class=\"col-sm-4\">{input}</div>",
                'labelOptions' => ['class' => 'control-label'],
                'inputOptions' => ['class' => 'form-control input-sm']
            ],
        ]); ?>
            <header class="panel-heading text-center">
                <strong>Profile<?php
                    if ( $model->isEditable ) {
                        echo " (" . (0 == $model->isComplete ? "incomplete, " : "") . 'edit)';
                    }            
                ?></strong><br>
                Member since : <?= $model->createdAt->format("d/m Y") ?>
            </header>

            <div class="panel-body wrapper-lg">
                <div class="form-group">
                    Profile type : <?= (User::$_TYPE_CUSTOMER == $model->userType ? "CUSTOMER" : "DRIVER") ?>
                </div>
                <?php if ( $model->phoneVerified ) { ?>
                    <div class="form-group">
                        Phone : <?= $model->phone ?>
                    </div>
                <?php } ?>
                
                <div class="line line-dashed"></div>
                <div class="row">
                    <div class="col-sm-4"><?= Html::activeLabel($model, 'firstName') ?></div>
                    <div class="col-sm-4"><?= Html::activeTextInput($model, 'firstName', ['inputOptions'=>['placeholder' => "eg. Your name or company"]]) ?></div>
                </div>
                <div class="row">
                    <div class="col-sm-4"><?= Html::activeLabel($model, 'lastName') ?></div>
                    <div class="col-sm-4"><?= Html::activeTextInput($model, 'lastName', ['inputOptions'=>['placeholder' => "eg. Your last name"]]) ?></div>
                </div>
                <div class="row">
                    <div class="col-sm-4"><?= Html::activeLabel($model, 'email') ?></div>
                    <div class="col-sm-4"><?= Html::activeTextInput($model, 'email', ['inputOptions'=>['placeholder' => "test@example.com"]]) ?></div>
                    <?php if (Yii::$app->session->hasFlash('email')) { ?>
                        <div class="col-sm-4"><span class='upload_error' style='color:red'><?= Yii::$app->session->getFlash('email', '', true)[0]?></span></div>
                    <?php } ?>
                </div>

                <div class="row">
                    <div class="col-sm-4"><?= Html::activeLabel($model, 'creditCardInfo') ?></div>
                    <div class="col-sm-4"><?= Html::activeTextInput($model, 'creditCardInfo') ?></div>
                </div>

                <?php if ( User::$_TYPE_CUSTOMER === $model->userType ) { ?>
                    <?php if ( 0 < count($model->address)) { ?>
                        <div class="line line-dashed"></div>
                        <div class="row">
                            <div class="col-sm-4">Addresses : </div>
                            <div class="col-sm-4">
                                <?php foreach($model->address as $_address) { ?>
                                    <?= $_address ?><br>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="line line-dashed"></div>
                    <div class="row">
                        <div class="col-sm-4">Add new address : </div>
                        <div class="col-sm-4"><?= Html::activeTextInput($model, 'address', ['value'=>'']) ?></div>
                    </div>
                <?php } else { ?>
                    <div class="row">
                        <div class="col-sm-4"><?= Html::activeLabel($model, 'address') ?></div>
                        <div class="col-sm-4"><?= Html::activeTextInput($model, 'address') ?></div>
                    </div>
                <?php } ?>

                <?php if ( !$model->phoneVerified ) { ?>
                    <div class="line line-dashed"></div>
                    <div class="row">
                        <div class="col-sm-4"><?= Html::activeLabel($model, 'phone') ?></div>
                        <div class="col-sm-4"><?= Html::activeTextInput($model, 'phone') ?></div>
                        <div class="col-sm-4">
                            <?php if (Yii::$app->session->hasFlash('phone')) { ?>
                            <span class='upload_error' style='color:red'><?= Yii::$app->session->getFlash('phone', '', true)[0]?></span><br>
                            <?php } ?>
                            <span style="color:red">(Phone Not Verified)</span>
                            <br><a class="btn btn-primary" href="<?= Url::toRoute('site/verify-phone') ?>">Verify</a>
                        </div>
                    </div>
                <?php } ?>
                
                <div class="line line-dashed"></div>
                <div class="row">
                    <div class="col-sm-4">Photo</div>
                    <div class="col-sm-4"><?= ('' == $model->photo ? "<img class=\"profile-photo\" src=\"" . Yii::$app->homeUrl . "uploads/noavatar.png\"/>" : "<img class=\"profile-photo\" src=\"" . Yii::$app->homeUrl . "uploads/{$model->photo}\"/>") ?></div>
                    <div class="col-sm-4">
                        <span class="btn btn-default btn-file">
                            <?= ( Yii::$app->session->hasFlash('photo') ? "<span class='upload_error' style='color:red'>" . Yii::$app->session->getFlash('photo', '', true) . "</span>" : "" ) ?>
                            <?= Html::activeFileInput($model, 'photo'); ?>
                        </span>
                    </div>
                </div>
                
                <?php if ( User::$_TYPE_DRIVER === $model->userType ) { ?>
                    <div class="line line-dashed"></div>
                    <div class="row">
                        <div class="col-sm-4">Driver license copy</div>
                        <div class="col-sm-4"><?= ('' == $model->license_photo ? "<img class=\"profile-license\" src=\"" . Yii::$app->homeUrl . "uploads/ImageNotAvailable.png\"/>" : "<img class=\"profile-license\" src=\"" . Yii::$app->homeUrl . "uploads/{$model->license_photo}\"/>") ?></div>
                        <div class="col-sm-4">
                            <span class="btn btn-default btn-file">
                                <?= ( Yii::$app->session->hasFlash('license_photo') ? "<span class='upload_error' style='color:red'>" . Yii::$app->session->getFlash('license_photo', '', true) . "</span>" : "" ) ?>
                                <?= Html::activeFileInput($model, 'license_photo'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="line line-dashed"></div>
                    <div class="row">
                        <div class="col-sm-4">Driver Insurance copy</div>
                        <div class="col-sm-4"><?= ('' == $model->insurance_photo ? "<img class=\"profile-insurance\" src=\"" . Yii::$app->homeUrl . "uploads/ImageNotAvailable.png\"/>" : "<img class=\"profile-insurance\" src=\"" . Yii::$app->homeUrl . "uploads/{$model->insurance_photo}\"/>") ?></div>
                        <div class="col-sm-4">
                            <span class="btn btn-default btn-file">
                                <?= ( Yii::$app->session->hasFlash('insurance_photo') ? "<span class='upload_error' style='color:red'>" . Yii::$app->session->getFlash('insurance_photo', '', true) . "</span>" : "" ) ?>
                                <?= Html::activeFileInput($model, 'insurance_photo'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="line line-dashed"></div>
                    <div class="row">
                        <div class="col-sm-4">Car photo</div>
                        <div class="col-sm-4"><?= ('' == $model->car_photo ? "<img class=\"profile-car\" src=\"" . Yii::$app->homeUrl . "uploads/ImageNotAvailable.png\"/>" : "<img class=\"profile-car\" src=\"" . Yii::$app->homeUrl . "uploads/{$model->car_photo}\"/>") ?></div>
                        <div class="col-sm-4">
                            <span class="btn btn-default btn-file">
                                <?= ( Yii::$app->session->hasFlash('car_photo') ? "<span class='upload_error' style='color:red'>" . Yii::$app->session->getFlash('car_photo', '', true) . "</span>" : "" ) ?>
                                <?= Html::activeFileInput($model, 'car_photo'); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-4"><?= Html::activeLabel($model, 'priceMile') ?></div>
                        <div class="col-sm-4"><?= Html::activeTextInput($model, 'priceMile') ?></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><?= Html::activeLabel($model, 'carMake') ?></div>
                        <div class="col-sm-4"><?= Html::activeTextInput($model, 'carMake') ?></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><?= Html::activeLabel($model, 'carModel') ?></div>
                        <div class="col-sm-4"><?= Html::activeTextInput($model, 'carModel') ?></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><?= Html::activeLabel($model, 'carYear') ?></div>
                        <div class="col-sm-4"><?= Html::activeTextInput($model, 'carYear') ?></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><?= Html::activeLabel($model, 'licensePlateNumber') ?></div>
                        <div class="col-sm-4"><?= Html::activeTextInput($model, 'licensePlateNumber') ?></div>
                        <?php if (Yii::$app->session->hasFlash('license_plate')) { ?>
                            <div class="col-sm-4">
                                <span class='upload_error' style='color:red'><?= Yii::$app->session->getFlash('license_plate', '', true)[0]?></span>
                            </div>
                        <?php } ?>
                        
                    </div>
                <?php } ?>
                
                <div class="line line-dashed"></div>
                <div class="row">
                    <?= Html::submitButton('Save Changes', ['class' => 'saveProfile btn btn-primary', 'name' => 'saveprofile-button']) ?>
                </div>
            </div>
        <?php ActiveForm::end(); ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('button#requireVerification').on('click', function(e){
                    e.preventDefault();
                    console.log('require phone verification');
                    /*
                    $.ajax({
                        'url' : '',
                        'type' : 'post',
                        
                    });
                    */
                });
                
                $('.upload_error').fadeOut(10 * 1000);
            });
        </script>
    <?php } ?>
