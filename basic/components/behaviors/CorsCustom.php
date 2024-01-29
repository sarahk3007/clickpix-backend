<?php

namespace app\components\behaviors;

use Yii;
use yii\filters\Cors;

class CorsCustom extends Cors

{
    public function beforeAction($action)
    {
		Yii::trace("cors beforeAction1");
        if(parent::beforeAction($action)){
			Yii::trace("cors beforeAction2");
			Yii::trace(Yii::$app->getRequest()->getMethod());
	        if (Yii::$app->getRequest()->getMethod() === 'OPTIONS') {
	            //Yii::$app->getResponse()->getHeaders()->set('Allow', 'POST GET OPTIONS');
	            //Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Headers', 'Authorization');
	            Yii::$app->end();
	        }
	
	        return true;
        }
        
        return false;				        
    }
}