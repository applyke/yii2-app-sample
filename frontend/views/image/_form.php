<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Image */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="image-form ">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'action' => ["/image/create"]]);?>
    <div class="form-group">
        <?= $form->field($model, 'file')->fileInput() ?>
        <?= Html::submitButton( 'Upload', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
