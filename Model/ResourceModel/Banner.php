<?php

namespace VS7\PromoBanner\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class Banner extends AbstractDb
{
    protected $_categoryBannerTable;

    public function __construct(
        Context $context
    )
    {
        parent::__construct($context);
    }

    public function getCategoryIds($banner)
    {
        if (empty($banner)) {
            return [];
        }

        $select = $this->getConnection()->select()->from(
            $this->getCategoryBannerTable(),
            'category_id'
        )->where(
            'banner_id=?',
            $banner->getId()
        );

        return $this->getConnection()->fetchCol($select);
    }

    protected function _construct()
    {
        $this->_init('vs7_promobanner', 'banner_id');
    }

    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->_saveCategories($object);
        return parent::_afterSave($object);
    }

    protected function _saveCategories($object)
    {
        $categoryIds = $object->getData('category_ids');
        if (!is_array($categoryIds)) {
            $categoryIds = explode(',', $categoryIds);
        }
        if (count($categoryIds) == 0) return false;

        $bannerId = $object->getId();
        $connection = $this->getConnection();
        $cond = ['banner_id=?' => $bannerId];
        $connection->delete($this->getCategoryBannerTable(), $cond);

        $data = [];
        foreach ($categoryIds as $categoryId) {
            $data[] = [
                'category_id' => (int)$categoryId,
                'banner_id' => (int)$bannerId
            ];
        }
        $connection->insertMultiple($this->getCategoryBannerTable(), $data);
    }

    public function getCategoryBannerTable()
    {
        if (!$this->_categoryBannerTable) {
            $this->_categoryBannerTable = $this->getTable('vs7_promobanner_category');
        }
        return $this->_categoryBannerTable;
    }
}
