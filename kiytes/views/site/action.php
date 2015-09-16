<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Kiytes | Web Application';
?>
    
    <?php if ( !isset($content) || !isset($content['actionType']) ) { ?>
        <header class="panel-heading text-center">
            <strong>Action not found</strong>
        </header>
    <?php } elseif ( 'phoneVerifyRequest' === $content['actionType'] ) { ?>
        <header class="panel-heading text-center">
            <strong>Phone verification</strong>
        </header>

        <div class="panel-body wrapper-lg">
            <div class="form-group" style="text-align: center;">
                Sms with verification link was sent on given phone number<br>
                <span class="label label-info">(<?= $content['phone'] ?>)</span><br>
                <?php if (YII_DEBUG) { ?>
                    <br><br>(DEBUG MODE) activation url : <br>
                    <a class="label label-info" href="<?= $content['verificationUrl'] ?>"><?= $content['verificationUrl'] ?></a>
                    
                    <?php if ($content['twilioException']) { ?>
                    <br><br>(DEBUG MODE) Twilio API exception [<div class="label label-default">code <?= $content['twilioException']->getCode() ?></div>] : 
                    <br><?= $content['twilioException']->getMessage() ?>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    <?php } elseif ( 'phoneVerifyAction' === $content['actionType'] ) { ?>
        <header class="panel-heading text-center">
            <strong>Phone verification</strong>
        </header>

        <div class="panel-body wrapper-lg">
            <div class="form-group" style="text-align: center;">
                <?php if ( 1 === $content['isValidToken']) { ?>
                    <?php if ('1' == $content['phoneVerified']) { ?>
                        Phone number <span class="label label-info"><?= $content['phone'] ?></span> is now verified
                    <?php } else { ?>
                        Failed to verify hone number <span class="label label-info"><?= $content['phone'] ?></span>, try again
                    <?php } ?>
                <?php } else { ?>
                    Verification token <span class="label label-info"><?= $content['givenToken'] ?></span> not found or invalid, please require new verification sms
                <?php } ?>
            </div>
        </div>
    <?php } ?>
