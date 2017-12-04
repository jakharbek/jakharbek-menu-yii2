<?php
namespace jakharbek\menu\models;

use jakharbek\core\language\behaviors\ModelBehavior;
use jakharbek\core\language\validators\LangValidator;
use Yii;
use yii\base\Model;
use jakharbek\core\language\behaviors\LangBehavior;

class MenuForm extends Model{

    public $title;
    public $alias;
    public $lang;

    public function init(){
       if($menu = Menu::getByAlias($this->alias,$this->lang)){
           $this->attributes = $menu->attributes;
       }
    }

    public function behaviors()
    {
        return [
            [
                'class' => ModelBehavior::className(),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['title','alias'],'required','on' => [Menu::SCENARIO_CREATE,Menu::SCENARIO_UPDATE]],
            [['alias'],'unique','targetClass' => Menu::className(),'targetAttribute' => ['alias','lang'],'on' => Menu::SCENARIO_CREATE],
            [['title','alias'],'safe','on' => Menu::SCENARIO_SEARCH],
        ];
    }

    public function scenarios()
    {
         $scenarious = parent::scenarios();
         $scenarious[Menu::SCENARIO_CREATE] = ['title','alias','lang'];
         $scenarious[Menu::SCENARIO_UPDATE] = ['title','alias','lang'];
        $scenarious[Menu::SCENARIO_SEARCH] = ['title','alias'];
         return $scenarious;
    }
    public function has(){
        if(Menu::getByAlias($this->alias,$this->lang)):
            return true;
        else:
            return false;
        endif;
    }
    public function getId(){
        if($this->has())
        {
            $menu = Menu::getByAlias($this->alias,$this->lang);
            return $menu->id;
        }else{
            return false;
        }
    }
    public function attributeLabels(){
        return [
            'id' => Yii::t('jakhar-menu','Unique ID'),
            'title' => Yii::t('jakhar-menu','Title'),
            'alias' => Yii::t('jakhar-menu','Alias'),
            'lang' => Yii::t('jakhar-menu','Lang'),
        ];
    }
}