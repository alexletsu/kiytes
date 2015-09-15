<?php

/* @var $this yii\web\View */

$this->title = 'Kytes | Web Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?= ( isset($content) && (isset($content['header'])) ? $content['header'] : "Home page") ?></h1>

        <p class="lead"><?= ( isset($content) && (isset($content['body'])) ? $content['body'] : "In default layout") ?></p>

    </div>
</div>
