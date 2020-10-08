<?php

namespace VS7\PromoBanner\Block;

class BannerList extends \Magento\Framework\View\Element\Template implements \Magento\Framework\DataObject\IdentityInterface
{
    protected $_coreRegistry = null;

    protected $_bannerCollection;

    protected $_banner;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \VS7\PromoBanner\Model\Banner $banner,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_banner = $banner;
        parent::__construct($context, $data);
    }

    public function getIdentities()
    {
        $identities = [];
        foreach ($this->getBannerCollection() as $item) {
            $identities = array_merge($identities, $item->getIdentities());
        }
        return $identities;
    }

    public function getCurrentCategory()
    {
        if (!$this->hasData('current_category')) {
            $this->setData('current_category', $this->_coreRegistry->registry('current_category'));
        }

        return $this->getData('current_category');
    }

    public function getBannerCollection()
    {
        if (empty($this->_bannerCollection)) {
            $currentCategoryId = $this->getCurrentCategory()->getId();

            $this->_bannerCollection = $this->_banner
                ->getCollection()
                ->addCategoryFilter($currentCategoryId)
                ->addActiveRuleFilter();
        }

        return $this->_bannerCollection;
    }
}
