<?php

namespace VS7\PromoBanner\Plugin;

class DataProvider
{
    protected $_bannerFactory;
    protected $_bannerHelper;

    public function __construct(
        \VS7\PromoBanner\Model\BannerFactory $bannerFactory,
        \VS7\PromoBanner\Helper\Data $bannerHelper
    )
    {
        $this->_bannerFactory = $bannerFactory;
        $this->_bannerHelper = $bannerHelper;
    }

    public function afterGetData(
        \Magento\SalesRule\Model\Rule\DataProvider $subject,
        $data
    )
    {
        $banner = $this->_bannerFactory->create();
        $ruleId = key($data);
        $banner->load($ruleId, 'rule_id');
        $image = $this->_bannerHelper->prepareImage($banner->getImage());
        $data[$ruleId]['vs7_promobanner'] = array(
            'name' => $banner->getName(),
            'url_key' => $banner->getUrlKey(),
            'image' => array($image),
            'position' => $banner->getPosition(),
            'text' => $banner->getText()
        );
        if (empty($image)) unset($data[$ruleId]['vs7_promobanner']['image']);
        return $data;
    }
}
