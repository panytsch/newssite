<?php
/**
 * Created by PhpStorm.
 * User: romeo
 * Date: 19.04.2018
 * Time: 18:16
 */

namespace app\models;


use components\web\Model;
use PDO;

class Article extends Model
{

    /**
     * Category constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'articles';
    }

    /**
     * @param $id
     */
    public function setView($id)
    {
        $stm = $this->db->prepare("UPDATE `{$this->tableName}` SET `views` = `views` + 1 WHERE `id` = {$id}");
        $stm->execute();
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getArticle($name)
    {
        $stm = $this->db->prepare("SELECT * FROM `{$this->tableName}` WHERE `id` = {$name}");
        $stm->execute();
        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     * @return mixed
     */

    public function getArticlebyID(int $id)
    {
        $stm = $this->db->prepare("SELECT * FROM `{$this->tableName}` WHERE `id` = {$id}");
        $stm->execute();
        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $minId
     * @param int $maxId
     * @return array
     */
    public function getSliceArticle(int $minId, int $maxId)
    {
        $stm = $this->db->prepare("SELECT `{$this->tableName}`.id, `{$this->tableName}`.title, `{$this->tableName}`.small_description, `{$this->tableName}`.description, `{$this->tableName}`.tag1,  `{$this->tableName}`.img, categories.name FROM `{$this->tableName}` JOIN categories ON `{$this->tableName}`.category_id = categories.id LIMIT {$minId}, {$maxId}");
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $tag
     * @return array
     */
    public function getArticleListByTag(string $tag)
    {
        $stm = $this->db->prepare("SELECT * FROM `{$this->tableName}` WHERE tag1 LIKE '%{$tag}%'");
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $minId
     * @param int $maxId
     * @param string $category
     * @return array
     */
    public function getSliceArticleByCategory(int $minId, int $maxId,string $category)
    {
        $stm = $this->db->prepare("SELECT `{$this->tableName}`.id, `{$this->tableName}`.title, `{$this->tableName}`.img, categories.name FROM `{$this->tableName}` JOIN categories ON `{$this->tableName}`.category_id = categories.id AND categories.name = '{$category}' LIMIT {$minId}, {$maxId}");
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $minId
     * @param int $maxId
     * @return array
     */
    public function getSliceAnalitic(int $minId, int $maxId)
    {
        $stm = $this->db->prepare("SELECT `{$this->tableName}`.id, `{$this->tableName}`.title, `{$this->tableName}`.img, categories.name FROM `{$this->tableName}` JOIN categories ON `{$this->tableName}`.category_id = categories.id AND articles.analitic = 1 LIMIT {$minId}, {$maxId}");
        $stm->execute();
//        echo "SELECT `{$this->tableName}`.id, `{$this->tableName}`.title, `{$this->tableName}`.img, categories.name FROM `{$this->tableName}` JOIN categories ON `{$this->tableName}`.category_id = categories.id AND article.analitic = 1 LIMIT {$minId}, {$maxId}";
//        var_dump($stm->fetchAll(PDO::FETCH_ASSOC));
//        die();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return int|null
     */
    public function getCountAnalitic()
    {
        $stm = $this->db->prepare("SELECT COUNT(articles.analitic) FROM articles WHERE articles.analitic = 1");
        $stm->execute();
        $data = $stm->fetchAll(PDO::FETCH_NUM);
        if (empty($data)){
            return null;
        }
        return (int)$data[0][0];
    }

    /**
     * @param string $id
     * @param string $title
     * @param string $minDesc
     * @param string $desc
     * @param string $category
     * @param string $tags
     * @param $img
     */
    public function setNewArticle(string $id,string $title,string $minDesc,string $desc,string $category,string $tags,$img)
    {
//        $date = date("Y-m-d H:i:s");
//        var_dump($date);
        if (empty($img)){
            $stm = $this->db->prepare("INSERT INTO `{$this->tableName}` (title, small_description, description, category_id, tag1, img, `date`) VALUES ('{$title}', '{$minDesc}', '{$desc}', '{$category}', '{$tags}', 'image-not-found.jpg',NOW())");
        } else {
            $stm = $this->db->prepare("INSERT INTO `{$this->tableName}` (title, small_description, description, category_id, tag1, img, `date`) VALUES ('{$title}', '{$minDesc}', '{$desc}', '{$category}', '{$tags}', '{$img}', NOW())");
        }
        if (!$stm->execute()){
            echo "INSERT INTO `{$this->tableName}` (title, small_description, description, category_id, tag1, img, `date`) VALUES ('{$title}', '{$minDesc}', '{$desc}', '{$category}', '{$tags}', '{$img}', NOW())";
        }

    }

    /**
     * @return array
     */
    public function getLastThreeArticle()
    {
        $stm = $this->db->prepare("SELECT * FROM `articles` ORDER BY date DESC LIMIT 3");
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
}