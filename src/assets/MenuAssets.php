<?php

namespace jakharbek\menu\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class MenuAssets extends AssetBundle
{
    public $sourcePath = '@jakhar/menu/web/';

    public $css = [
        'css/main.css',
    ];

    public $js = [
        'js/main.js',
        'js/jquery-sortable.js',
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_END];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
