<?php

class StudentsCore extends ObjectModel
{
    public $id_students;
    public $name;
    public $bday_date;
    public $is_studying = true;
    public $avg_score;

    public static $definition = array(
        'table' => 'students',
        'primary' => 'id_students',
        'multilang' => true,
        'fields' => array(
            'name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName'),
            'bday_date' => array('type' => self::TYPE_DATE),
            'is_studying' => array('type' => self::TYPE_BOOL),
            'avg_score' => array('type' => self::TYPE_FLOAT),
        ),
    );

    public function __construct($idLang = null, $idShop = null)
    {
        parent::__construct($idLang, $idShop);
        // if (Shop::getContext() != Shop::CONTEXT_SHOP) {
        //     $idShop = Shop::getContextListShopID();
        // } else {
        //     $id = Context::getContext()->shop->id;
        //     $idShop = [$id ? $id : Configuration::get('PS_SHOP_DEFAULT')];
        // }
    }

    public function getAll()
    {
        $return = null;
        $return &= Db::getInstance()->execute(
            "SELECT *
            FROM `" . _DB_PREFIX_ . "students`"
            );
        return $return;
    }

    public function geBestScore()
    {
        $return = null;
        $return &= Db::getInstance()->execute("SELECT `MAX(avg_score)` FROM `" . _DB_PREFIX_ . "students`");
        return $return;
    }

    public function getBestStudent()
    {
        $return = null;
        $return &= Db::getInstance()->execute("SELECT `*` FROM `" . _DB_PREFIX_ . "students` st
        INNER JOIN (
            SELECT `id_student`, `MAX(avg_score)` FROM `" . _DB_PREFIX_ . "students`
            GROUP BY `id_students`
            ) st2 ON `st.id_students` = `st2.id_students`");
        return $return;
    }
}
