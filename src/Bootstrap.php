<?php

namespace jakharbek\menu;

use jakharbek\menu\assets\MenuAssets;
use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface{

    public static $modules = ['test/menu' => 'jakharbek\menu\modules\menu\Module'];
    const EXT_ALIAS = '@vendor/jakharbek/jakharbek-menu/src';
    public static $upload_folder = "";
    public static $upload_folder_source = "";
    public static $pages = [
        'search' => '/test/menu/menu/search',
        'manager' => '/test/menu/menu/manager',
        'delete' => '/test/menu/menu/delete'
        ];

    /*
     * Разрешение для админки
     */
    public static $premission_admin_panel = "controlPanel";

    public function bootstrap($app)
    {
        /*
         * Set alias
         */
        Yii::setAlias('@jakhar/menu', Bootstrap::EXT_ALIAS);
        $this->registerTranslations();

        /*
         * Menu
         */
        $this->setModule(self::$modules);
        /*
         * Register asset
         */
        MenuAssets::register(Yii::$app->view);

        /*
         * Upload folder
         */
        self::$upload_folder = Yii::getAlias("@webroot")."/uploads/menu/";

        self::$upload_folder_source = Yii::getAlias("@web")."/uploads/menu/";

    }

    /*
     * @method @private set controller in engine
     */
    private function setController($controller_set_to = [],$controller_path = ""){
        if(count($controller_set_to) > 0):
            foreach ($controller_set_to as $controller):
                if(preg_match("#/+#",$controller)):
                    $module = explode("/",$controller)[0];
                    $controller = explode("/",$controller)[1];
                    if(!Yii::$app->hasModule($module)){continue;}
                    Yii::$app->getModule($module)->controllerMap = array_merge(Yii::$app->getModule($module)->controllerMap, [$controller => $controller_path]);
                else:
                    Yii::$app->controllerMap = array_merge(Yii::$app->controllerMap,[$controller => $controller_path]);
                endif;
            endforeach;
        endif;
    }
    /*
      * @method @private set module in engine
      */
    private function setModule($modules = null){
        if($modules == null){return;}
        if(count(Bootstrap::$modules) > 0):
            foreach (Bootstrap::$modules as $module_key => $module_path):
                if(preg_match("#/+#",$module_key)):
                    $module_parent = explode("/",$module_key)[0];
                    $module_data = explode("/",$module_key)[1];
                    if(!Yii::$app->hasModule($module_parent)){continue;}
                    Yii::$app->getModule($module_parent)->setModule($module_data,$module_path);
                else:
                    Yii::$app->setModule($module_key,$module_path);
                endif;
            endforeach;
        endif;
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['jakhar-menu'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => self::EXT_ALIAS.'/messages',
            'fileMap' => [
                'jakhar-menu' => 'main.php',
            ],
        ];
    }
}