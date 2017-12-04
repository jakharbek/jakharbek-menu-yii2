<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use jakharbek\user\models\User;
use jakharbek\core\Bootstrap;
use jakharbek\core\language\widgets\TabWidget;
use \jakharbek\core\language\widgets\AliasFieldWidget;
use \jakharbek\menu\modules\menu\components\MenuAdmin;
use jakharbek\menu\components\MenuTop;
use yii\widgets\Pjax;
use yii\helpers\Url;
?>
<?php Pjax::begin();?>
<h1><?=Yii::t('jakhar-menu','Menu Manager')?></h1>
<div class="site-login">
    <div class="row">
        <?= TabWidget::widget([
            'model' => $model,
            'db' => $db
        ]);?>
        <div class="col-lg-4">
            <?php if($model->has()):?>

                <div class="panel panel-default" style="margin-top:20px;">
                    <div class="panel-heading"><?=Yii::t('jakhar-menu','Menu Item')?></div>
                    <div class="panel-body">
                        <?php
                        $form = ActiveForm::begin(['action' => Url::to(["/".Yii::$app->request->pathInfo,'alias' => $alias,'lang' => Yii::$app->language]),'id' => 'menu-item-form','options' => ['data-pjax' => true,['enctype' => 'multipart/form-data']]]);
                        echo $form->field($modelItemCreate,'title');
                        echo $form->field($modelItemCreate,'url');
                        echo $form->field($modelItemCreate,'icon');
                        echo $form->field($modelItemCreate,'imageFile')->fileInput();
                        echo $form->field($modelItemCreate,'id')->hiddenInput()->label(false);
                        echo Html::submitButton(Yii::t('jakhar-menu','Menu Create'),['class' => 'btn btn-primary']);
                        ActiveForm::end();
                        ?>
                    </div>
                </div>
            <?php endif;?>
                <div class="panel panel-default" style="margin-top:20px;">
                    <div class="panel-heading"><?=Yii::t('jakhar-menu','Menu')?></div>
                    <div class="panel-body">
                        <?php $form = ActiveForm::begin(['id' => 'menu-form','options' => ['data-pjax' => true]]); ?>
                        <?php echo $form->field($model,'title')?>
                        <?php echo AliasFieldWidget::widget(['form' => $form,'model' => $model]);?>
                        <?php echo Html::submitButton($submit_text,['class' => 'btn btn-primary']);?>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
        </div>
        <div class="col-lg-6">
            <div class="panel panel-default" style="margin-top:20px;">
                <div class="panel-heading"><?=Yii::t('jakhar-menu','Menu Structure')?></div>
                <div class="panel-body">
            <?php $menu = new MenuAdmin(['alias' => $alias]);?>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="panel panel-default" style="margin-top:20px;">
                <div class="panel-heading"><?=Html::a(Yii::t('jakhar-menu','Menus'),[\jakharbek\menu\Bootstrap::$pages['search']])?></div>
                <div class="panel-body">
                    <?php
                        if(count($all_menus)):
                            foreach ($all_menus as $menu):
                                echo "<li>";
                                    echo Html::a($menu->title,["/".Yii::$app->request->pathInfo,'alias' => $menu->alias,'lang' => Yii::$app->language]);
                                echo "</li>";
                            endforeach;
                        endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

$this->registerJs('query_handler();');
?>
<?php Pjax::end();?>