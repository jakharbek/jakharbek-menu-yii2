<?php
namespace jakharbek\menu\components;

use Yii;

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