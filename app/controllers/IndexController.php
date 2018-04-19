<?php
/**
 * Created by PhpStorm.
 * User: romeo
 * Date: 17.04.2018
 * Time: 11:21
 */

namespace app\controllers;


use app\models\Category;
use components\web\Controller;

class IndexController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $model = new Category();
        $categoryArray = $model->getArticleList();

        $data = [];
        foreach ($categoryArray as $value){
            $data[$value]=$model->getTitleList($value);
        }
//        var_dump($data);die();
        return $this->getTemplate()->render('index', ['categoryArray' => $data]);
    }
}