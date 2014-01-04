<?php

$installer = new Mage_Catalog_Model_Resource_Setup('core_setup');
$installer->startSetup();
$catalog_product           = (int)$installer->getEntityTypeId('catalog_product');
$attributeIds       = array();
$select = $installer->getConnection()->select()
    ->from(
        array('ea' => $installer->getTable('eav/attribute')),
        array('entity_type_id', 'attribute_code', 'attribute_id'))
        ->where('ea.entity_type_id IN(?)', array($catalog_product));
foreach ($installer->getConnection()->fetchAll($select) as $row) {
    $attributeIds[$row['entity_type_id']][$row['attribute_code']] = $row['attribute_id'];
}
foreach($attributeIds as $attributeid){
    foreach($attributeid as $attribute_code=>$attribute_id){
        if($attribute_code=="group_price" ){
            $attribute = Mage::getModel('eav/entity_attribute')->load($attribute_id);
            $attribute->setData('frontend_label','¿¿¿¿¿')->save();
        }
        if($attribute_code=="tier_price" ){
            $attribute = Mage::getModel('eav/entity_attribute')->load($attribute_id);
            $attribute->setData('frontend_label','¿¿¿¿')->save();
        }
        if( $attribute_code=="thumbnail"){
            $attribute = Mage::getModel('eav/entity_attribute')->load($attribute_id);
            $attribute->setData('frontend_label','¿¿¿')->save();
        }
        if( $attribute_code=="small_image" ){
            $attribute = Mage::getModel('eav/entity_attribute')->load($attribute_id);
            $attribute->setData('frontend_label','¿¿¿¿¿¿')->save();
        }
        if( $attribute_code=="image"){
            $attribute = Mage::getModel('eav/entity_attribute')->load($attribute_id);
            $attribute->setData('frontend_label','¿¿¿¿¿')->save();
        }

        if( $attribute_code=="options_container"){
            $attribute = Mage::getModel('eav/entity_attribute')->load($attribute_id);
            $attribute->setData('default_value','container1')->save();
        }
    }
}
$installer->endSetup();

$installer = new Mage_Customer_Model_Entity_Setup('core_setup');
$installer->startSetup();

$customer           = (int)$installer->getEntityTypeId('customer');
$customerAddress    = (int)$installer->getEntityTypeId('customer_address');
$attributeIds       = array();
$customerAttrIds       = array();
$select = $installer->getConnection()->select()
    ->from(
        array('ea' => $installer->getTable('eav/attribute')),
        array('entity_type_id', 'attribute_code', 'attribute_id'))
        ->where('ea.entity_type_id IN(?)', array($customer, $customerAddress));

foreach ($installer->getConnection()->fetchAll($select) as $row) {
    $attributeIds[$row['entity_type_id']][$row['attribute_code']] = $row['attribute_id'];
}
foreach($attributeIds as $attributeid){
    foreach($attributeid as $attribute_code=>$attribute_id){
        if($attribute_code=="firstname"){
            $customerAttrIds[]= $attribute_id;
        }
        if($attribute_code=="lastname"){

            $customerAttrIds[]= $attribute_id;
        }

    }
}
$customer_eav_attributeTable = $installer->getTable('customer_eav_attribute');
foreach($customerAttrIds as $_attribute_id){
    $installer->run("
        UPDATE `{$customer_eav_attributeTable}` SET
        `validate_rules`= Null
        WHERE `attribute_id`='{$_attribute_id}'
        ");
}

$directory_country_region = $installer->getTable('directory_country_region');
$directory_country_region_name = $installer->getTable('directory_country_region_name');

$installer->run("
    TRUNCATE table `{$directory_country_region}`;
");
$installer->run("
    TRUNCATE table `{$directory_country_region_name}`;
");

$regions=array(
    'BJ'=>'¿¿',
    'SH'=>'¿¿',
    'GD'=>'¿¿',
    'JS'=>'¿¿',
    'SD'=>'¿¿',
    'SC'=>'¿¿',
    'TW'=>'¿¿',
    'ZJ'=>'¿¿',
    'LN'=>'¿¿',
    'HN1'=>'¿¿',
    'HB'=>'¿¿',
    'FJ'=>'¿¿',
    'HB1'=>'¿¿',
    'HN'=>'¿¿',
    'HK'=>'¿¿',
    'HLJ'=>'¿¿¿',
    'TJ'=>'¿¿',
    'CQ'=>'¿¿',
    'JX'=>'¿¿',
    'SX1'=>'¿¿',
    'AH'=>'¿¿',
    'SX'=>'¿¿',
    'HN2'=>'¿¿',
    'YN'=>'¿¿',
    'GS'=>'¿¿',
    'NMG'=>'¿¿¿',
    'GZ'=>'¿¿',
    'XJ'=>'¿¿',
    'XZ'=>'¿¿',
    'QH'=>'¿¿',
    'GX'=>'¿¿',
    'AM'=>'¿¿',
    'NX'=>'¿¿',
    'JL'=>'¿¿'
);
$country_code = 'CN';
$locale = 'zh_CN';

foreach ($regions as $region_code => $region_name) {

    //$region_name = iconv("gbk","UTF-8",$region_name); //
    // insert region
    $sql = "INSERT INTO `{$directory_country_region}` (`region_id`,`country_id`,`code`,`default_name`) VALUES (NULL,'{$country_code}','{$region_code}','{$region_name}')";
    $installer->run($sql);

    // get new region id for next query
    $region_id = $installer->getConnection()->lastInsertId();
    // insert region name
    $sql = "INSERT INTO `{$directory_country_region_name}` (`locale`,`region_id`,`name`) VALUES ('{$locale}','{$region_id}','{$region_name}')";
    $installer->run($sql);
}

$installer->endSetup();
