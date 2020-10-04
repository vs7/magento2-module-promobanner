<?php
namespace VS7\PromoBanner\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;


class Banner extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'vs7_promobanner';

    protected $_cacheTag = 'vs7_promobanner';

    protected $_eventPrefix = 'vs7_promobanner';

    protected function _construct()
    {
        $this->_init('VS7\PromoBanner\Model\ResourceModel\Banner');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];
        return $values;
    }

    public function getCategoryIds()
    {
        return $this->_getResource()->getCategoryIds($this);
    }
}
