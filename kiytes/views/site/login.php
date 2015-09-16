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
        <strong>Sign in</strong>
    </header>

	<div class="alert" style="display:none">
        <button data-dismiss="alert" class="close" type="button">×</button>
        <i class="fa fa-ok-sign"></i> <span id="messages"> </span>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'panel-body wrapper-lg'],
        'enableAjaxValidation'=>true,
        'fieldConfig' => [
            'template' => "{label}\n {input}\n {error}",
            'labelOptions' => ['class' => 'control-label'],
            'inputOptions' => ['class' => 'form-control input-lg']
        ],
    ]); ?>
        <div class="form-group">
            <?= $form->field($model, 'email', ['inputOptions'=>['placeholder' => "test@example.com"]]) ?>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'password')->passwordInput(['inputOptions'=>['placeholder' => "Password"]]) ?>
        </div>
        <div class="checkbox">
            <?= $form->field($model, 'rememberMe')->checkbox(['label' => "Keep me logged in"]) ?>
        </div>
        <a href="#" class="pull-right m-t-xs"><small>Forgot password?</small></a>
        <?= Html::submitButton('Sign in', ['class' => 'login btn btn-primary', 'name' => 'login-button']) ?>
        <div class="line line-dashed"></div>
        <a href="#" class="btn btn-facebook btn-block m-b-sm"><i class="fa fa-facebook pull-left"></i>Sign in with Facebook</a>
        <a href="#" class="btn btn-twitter btn-block"><i class="fa fa-twitter pull-left"></i>Sign in with Twitter</a>
        <div class="line line-dashed"></div>
        <p class="text-muted text-center"><small>Do not have an account? &nbsp;<a href="<?= Url::toRoute("site/signup"); ?>">Sign Up</a></small></p>
    <?php ActiveForm::end(); ?>
