<?php

namespace app\modules\admin\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * Class Menu
 * Theme menu widget.
 */
class Menu extends \yii\widgets\Menu
{


public static function getMenu($menu_items)



{

$html='';
	foreach($menu_items as $main_menu_items){



		$class_active ='';


		if(!empty($main_menu_items['items'])){
			$html.='
			<li class="submenu '.$class_active.'">
			<a href="#"><i class="fas fa-graduation-cap"></i> <span>'.$main_menu_items['label'].'</span> <span class="menu-arrow"></span></a>
			<ul>';
			foreach($main_menu_items['items'] as $submenu_items_data ){


				if(Yii::$app->controller->id==$main_menu_items['controller']){
					$class_active_sub ='active';
				}else{
					$class_active_sub ='';
	
				}



				if(isset($submenu_items_data['visible'])){
					if($submenu_items_data['visible']==true){
						$html.='<li><a class="'.$class_active_sub.'"  href="'.Url::toRoute($submenu_items_data['url']).'">'.$submenu_items_data['label'].'</a></li>';

					}
					}else{
					$html.='<li><a class="'.$class_active_sub.'"  href="'.Url::toRoute($submenu_items_data['url']).'">'.$submenu_items_data['label'].'</a></li>';

				}
	
			}
	
			$html.='</ul>
			</li>';
		
			
		}
		
		
		else{
		
			if(Yii::$app->controller->id==$main_menu_items['controller']){
				$class_active ='active';
			}else{
				$class_active ='';

			}


			$html .= '<li class="'.$class_active.'">
		<a href="'.Url::toRoute($main_menu_items['url']).'"><i class="fas fa-holly-berry"></i> <span>'.$main_menu_items['label'].'</span></a>
		</li>
		';

		}
	}

	

	
return  $html;

}

	

}