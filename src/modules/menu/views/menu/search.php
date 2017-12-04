<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use jakharbek\core\Bootstrap;
use yii\grid\GridView;
use \jakharbek\user\models\UserForm;
use \jakharbek\user\models\User;
use kartik\daterange\DateRangePicker;
use \yii\widgets\Pjax;
use yii\helpers\Url;
use jakharbek\core\assets\CoreAssets;
?>

<?php
Pjax::begin();
?>
<h1><?=Yii::t('jakhar-menu','Menu')?> <?=Html::a(Yii::t('jakhar-menu','Menu Create'),[\jakharbek\menu\Bootstrap::$pages['manager']],['class' => 'btn btn-success'])?></h1>
<?php
echo GridView::widget([
    'dataProvider' => $adprovider,
    'filterModel' => $model,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'title',
            'format' => 'raw',
            'value' => function($data){
                $url = Url::to([\jakharbek\menu\Bootstrap::$pages['manager'],'alias' => $data->alias]);
                return Html::a($data->title,$url);
            }
        ],
        'alias',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    $url = yii\helpers\Url::to([\jakharbek\menu\Bootstrap::$pages['delete']]);
                    return Html::a('<span class="delete-grid-element glyphicon glyphicon-trash"></span>', $url, [
                        'title'        => 'delete',
                        'data-query' => 'delete',
                        'data-query-delete-selector' => '[data-key='.$model->id.']',
                        'data-query-method' => 'POST',
                        'data-query-url' => $url,
                        'data-query-confirm' => Yii::t('jakhar-menu','Are you sure?'),
                        'data-query-params' => 'id='.$model->id,
                    ]);
                },
            ],
        ],
    ],
]);


$this->registerJs('query_handler();');
Pjax::end();
?>
