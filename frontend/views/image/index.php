<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ImageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model backend\models\Image */

$this->title = 'Images';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<div class="image-create">
    <h2>Add New Image</h2>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
<div class="image-index">
    <h2>Your Images</h2>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'path:image',
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=> '{rotate}',
                'buttons'=> [
                    'rotate' => function($path){
                        $options = array_merge([
                            'title' => Yii::t('yii', 'Rotate'),
                            'aria-label' => Yii::t('yii', 'Rotate'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]);
                        return Html::a('Rotate', $path, $options);
                    }
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=> '{delete}',
            ]
        ],
    ]); ?>
</div>
