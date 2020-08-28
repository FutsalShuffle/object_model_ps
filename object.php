class Students extends ObjectModel
{
public static $definition = array(
  'table' => 'students',
  'primary' => 'id_students',
  'multilang' => true,
  'fields' => array(
    'id_student'   => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
    'name'         => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
    'bday_date'    => array('type' => self::TYPE_DATE),
    'active'       => array('type' => self::TYPE_BOOL),
    'avg_score'    => array('type' => self::TYPE_FLOAT),
    'meta_name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName'),
  ),
);

public function __construct($idLang = null, $idShop = null)
{
    parent::__construct($idLang, $idShop);
    if (Shop::getContext() != Shop::CONTEXT_SHOP) {
        $idShop = Shop::getContextListShopID();
        } else {
            $id = Context::getContext()->shop->id;
            $idShop = [$id ? $id : Configuration::get('PS_SHOP_DEFAULT')];
        }
}

public function getByName(string $name)
{
    $return = null;
    $return &= Db::getInstance()->execute(
        sprintf(
        SELECT `*`
		FROM `' . _DB_PREFIX_ . 'students`
		WHERE `name` = ' . $name
        )
        );
    return $return;
        
}

public function getMaxAvgScore()
{
    $return = null;
    $return &= Db::getInstance()->execute(
        sprintf(
        SELECT `MAX(avg_score)`
		FROM `' . _DB_PREFIX_ . 'students`
        )
        );
    return $return;      
}


public function getBestStudent()
{
    $return = null;
    $return &= Db::getInstance()->execute(
        sprintf(
        SELECT `*`
		FROM `' . _DB_PREFIX_ . 'students` st
        INNER JOIN ( 
            SELECT `id_student`, MAX(avg_score)
            FROM `' . _DB_PREFIX_ . 'students`
            GROUP BY `id`
        ) st2 ON `st.id_student` = `st2.id_student`
        )
        );
    return $return;      
}

}