<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Kiytes | Web Application';
?>
    <header class="panel-heading text-center">
        <strong>Sign up</strong>
    </header>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'panel-body wrapper-lg'],
        //'enableAjaxValidation'=>true,
        'fieldConfig' => [
            'template' => "{label}\n {input}\n {error}",
            'labelOptions' => ['class' => 'control-label'],
            'inputOptions' => ['class' => 'form-control input-lg']
        ],
    ]); ?>
        <div class="form-group">
            <?= $form->field($model, 'firstName', ['inputOptions'=>['placeholder' => "eg. Your name or company"]]) ?>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'lastName', ['inputOptions'=>['placeholder' => "eg. Your last name"]]) ?>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'email', ['inputOptions'=>['placeholder' => "test@example.com"]]) ?>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'userType')->dropDownList([0 => 'Customer', 1 => 'Driver'], []) ?>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'phone', ['inputOptions'=>['placeholder' => "Phone number, e.g. +16175551212"]]) ?>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'password')->passwordInput(['inputOptions'=>['placeholder' => "Type a password"]]) ?>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'password2')->passwordInput(['inputOptions'=>['placeholder' => "Re-Type a password"]]) ?>
        </div>

        <div class="checkbox">
            <?= $form->field($model, 'termsAgreement')->checkbox()->label("Agree the <a href=\"#\">terms and policy</a>") ?>
        </div>

        <?= Html::submitButton('Sign up', ['class' => 'login btn btn-primary', 'name' => 'signin-button']) ?>
        <div class="line line-dashed"></div>
        <p class="text-muted text-center"><small>Already have an account? &nbsp;</small> <a href="<?= Url::toRoute("site/login"); ?>">Sign in</a></p>

    <?php ActiveForm::end(); ?>
     