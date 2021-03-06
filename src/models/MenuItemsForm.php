<?php
namespace jakharbek\menu\models;

use jakharbek\core\language\behaviors\ModelBehavior;
use jakharbek\core\language\validators\LangValidator;
use jakharbek\menu\Bootstrap;
use Yii;
use yii\base\Model;
use jakharbek\core\language\behaviors\LangBehavior;

class MenuItemsForm extends Model{

    public $id;
    public $menu_id;
    public $title;
    public $url;
    public $extends;
    public $sort;
    public $icon;
    public $image;
    public $imageFile;
    public function init(){
        if($this->scenario == MenuItems::SCENARIO_UPDATE):
            if($this->id > 0):
                $menuItem = MenuItems::findOne(['id' => $this->id]);
                $this->attributes = $menuItem->attributes;
            endif;
        endif;
    }
    public function rules()
    {
        return [
           [['title','url','id'],'required','on' => [MenuItems::SCENARIO_UPDATE,MenuItems::SCENARIO_CREATE]],
           [['imageFile'],'image','skipOnEmpty' => true,'extensions' => 'png, jpg','minWidth' => 10,'maxWidth' => 1000,'minHeight' => 10,'maxHeight' => 1000],
        ];
    }

    public function scenarios()
    {
         $scenarious = parent::scenarios();
         $scenarious[MenuItems::SCENARIO_UPDATE] = ['title','url','id','icon','imageFile','image'];
         $scenarious[MenuItems::SCENARIO_CREATE] = ['title','url','icon','imageFile','image'];
         return $scenarious;
    }
    public function formName()
    {
        if($this->scenario == MenuItems::SCENARIO_UPDATE):
            return parent::formName()."Update";
        endif;
        return parent::formName(); // TODO: Change the autogenerated stub
    }
    public function upload(){
        if($this->validate()):
            if(!$this->imageFile){return true;}
            if(!is_dir(Bootstrap::$upload_folder)) {
                mkdir(Bootstrap::$upload_folder);
                return $this->upload();
            }
            $this->image =  Yii::$app->security->generateRandomString(). '.' . $this->imageFile->extension;
            $this->imageFile->saveAs(Bootstrap::$upload_folder.$this->image);
            return true;
        else:
            return false;
        endif;
    }
    public function attributeLabels(){
        return [
            'id' => Yii::t('jakhar-menu','Unique ID'),
            'menu_id' => Yii::t('jakhar-menu','Menu ID'),
            'title' => Yii::t('jakhar-menu','Title'),
            'url' => Yii::t('jakhar-menu','Url'),
            'extends' => Yii::t('jakhar-menu','Extends'),
            'sort' => Yii::t('jakhar-menu','Sort'),
            'icon' => Yii::t('jakhar-menu','Icon'),
            'image' => Yii::t('jakhar-menu','Image'),
            'imageFile' => Yii::t('jakhar-menu','imageFile'),
        ];
    }
}