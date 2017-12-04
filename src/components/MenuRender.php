<?php
namespace jakharbek\menu\components;
use Yii;
use yii\base\Component;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\IdentityInterface;
use jakharbek\menu\models\MenuItems;
use jakharbek\menu\models\Menu;


/*
 * @class MenuRender для вывода меню на сайт
 * для этого нужно реализовать абстракные методы
 * @example пример
 *
class MenuTop extends MenuRender{

    public function beforeRenderMenu()
    {
        echo ' <div class="nav">
				     <ul>';
    }

    public function afterRenderMenu()
    {
       echo '        </ul>
               </div>';
    }

    public function beginRenderItem()
    {
       if($this->has_child):
            echo ' <li class="has-children">
											 <a href="'.$this->item->url.'">'.$this->item->title.' </a>';
       else:
            echo '<li><a href="'.$this->item->url.'">'.$this->item->title.' </a>';
       endif;
    }

    public function endRenderItem()
    {
        echo "</li>";
    }

    public function beginRenderItemChild()
    {
       if($this->is_active):
           echo '<li class="current"><a href="'.$this->item->url.'">'.$this->item->title.' </a>';
       else:
           echo '<li><a href="'.$this->item->url.'">'.$this->item->title.' </a>';
       endif;
    }

    public function endRenderItemChild()
    {
        echo '</li>';
    }

    public function beforeRenderItemChilds()
    {
       echo ' <ul class="sub-menu">';
    }

    public function afterRenderItemChilds()
    {
        echo '</ul>';
    }
}


@example

$menu = new MmenuTop(['alias' => 'top']);
 */
abstract class MenuRender extends Component{

    //@var @string алиас меню по которому будут искать меню
    public $alias;
    //@var @MenuItems текушей элемент меню использавать при реалицазии абстрактных методов
    public $item;
    //@var @boolean если ли у элемента под элементы использавать при реализации абстрактных методов
    public $has_child;
    //@var @boolean явлаеться ли текушей элемент активном использавать при реализации абстрактных методов
    public $is_active;
    //@var @integer какой сейчас уровен использавать при реализации абстрактных методов
    public $level;
    //@var @integer количество под элементов у элемента использавать при реализации абстрактных методов
    public $childsCount;
    private $query;
    private $query_childs;
    private $items;
    private $childs;

    public function init(){
        $this->run();
    }
    public function run(){
        $this->items();
    }
    private function items(){
        $query = MenuItems::getItems($this->alias);
        if(!$query){return;}
        $this->items = $items = $query->all();
        //beforeMainLoop
        $this->beforeRenderMenu();
        foreach ($items as $item):
            $this->item = $item;
            $this->has_child = $has_child = (boolean)$this->query_childs = $query_childs = MenuItems::getItems($this->alias,$this->item->id);
            $this->level = 1;
            if($this->has_child):
                $childsCount = $this->childsCount = $this->query_childs->count();
            else:
                $childsCount = $this->childsCount = 0;
            endif;
            $is_active = $this->is_active();
            $this->handler();
            $this->beginRenderItem();

            $this->childs();

            $this->item = $item;
            $this->has_child = $has_child;
            $this->is_active = null;
            $this->level = 1;
            $this->query_childs = $query_childs;
            $this->is_active = $is_active;
            $this->childsCount = $childsCount;
            $this->handler();
            $this->endRenderItem();
        endforeach;
        $this->afterRenderMenu();
        //afterMainLoop
    }
    private function childs(){
        $has_child = $this->has_child;
        $query_childs = $this->query_childs;
        $item = $this->item;
        $this->childsCount = $childsCount = 0;
        if($has_child):
            $childsCount = $this->childsCount = $query_childs->count();
            $this->childs = $childs = $query_childs->all();
            $level = $this->level = ($this->level + 1);
            $this->beforeRenderItemChilds();
            foreach ($childs as $child):
                $this->item = $child;
                $has_child_child = $this->has_child = (boolean)$this->query_childs = $query_child_childs = MenuItems::getItems($this->alias,$this->item->id);
                if($has_child_child):
                    $this->childsCount = $child_childsCount = $query_child_childs->count();
                else:
                    $this->childsCount = $child_childsCount = 0;
                endif;
                $is_active = $this->is_active = $this->is_active();
                $this->handler();
                $this->beginRenderItemChild();
                $this->childs();
                $this->query_childs = $query_child_childs;
                $this->has_child = $has_child_child;
                $this->item = $child;
                $this->level = $level;
                $this->is_active = $is_active;
                $this->childsCount = $child_childsCount;
                $this->handler();
                $this->endRenderItemChild();
            endforeach;
            $this->childs = $childs;
            $this->query_childs = $query_childs;
            $this->has_child = $has_child;
            $this->level = $level;
            $this->is_active = $is_active;
            $this->item = $item;
            $this->childsCount = $childsCount;
            $this->handler();
            $this->afterRenderItemChilds();
        endif;
    }

    //abstracts methods;
    /*
     * @method метод выполнаеться перед началом вывода меню
     */
    public abstract function beforeRenderMenu();
    /*
     * @method метод выполнаеться в после вывода меню
     */
    public abstract function afterRenderMenu();
    /*
     * @method метод выполнаеться в начале вывода элемента
     */
    public abstract function beginRenderItem();
    /*
     * @method метод выполнаеться в конце вывода элемента
     */
    public abstract function endRenderItem();
    /*
    * @method метод выполнаеться перед началов вывода под элементов
    */
    public abstract function beforeRenderItemChilds();
    /*
     * @method метод выполнаеться после вывода под элементов
     */
    public abstract function afterRenderItemChilds();
    /*
     * @method метод выполнаеться в начале вывода под элемента
     */
    public abstract function beginRenderItemChild();
    /*
     * @method метод выполнаеться в конце вывода под элемента
     */
    public abstract function endRenderItemChild();

    public function is_active(){
        $pathInfo = Yii::$app->request->getPathInfo();
        $urlarr = $this->getUrlFormat($this->item->url);
        $patharr = $this->getUrlFormat($pathInfo);
        $this->is_active = $urlarr == $patharr;
        return $this->is_active;
    }
    public function handler(){

    }
    public function getUrlFormat($url = null){
        if($url == null){return false;}
        $url = rawurldecode($url);
        $url = mb_strtolower($url);
        $pattern = '~([a-zA-Z0-9_а-яА-Я-\.\,\s]+)~u';
        preg_match_all($pattern,$url,$urls);
        return $urls[1];
    }
}