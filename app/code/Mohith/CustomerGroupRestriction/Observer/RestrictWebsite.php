<?php
/**
 * Created by PhpStorm.
 * User: mohith
 * Date: 3/5/22
 * Time: 3:10 PM
 */

namespace Mohith\CustomerGroupRestriction\Observer;

use Magento\Framework\App\Response\Http;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Mohith\CustomerGroupRestriction\Model\CategoryPage;
use Mohith\CustomerGroupRestriction\Model\CmsPage;
use Mohith\CustomerGroupRestriction\Model\ProductPage;

/**
 * Class RestrictWebsite
 * @package Mohith\CustomerGroupRestriction\Observer
 */
class RestrictWebsite implements ObserverInterface
{
    /**
     * @var Http
     */
    private $response;
    /**
     * @var ProductPage
     */
    private $productPage;
    /**
     * @var CategoryPage
     */
    private $categoryPage;
    /**
     * @var CmsPage
     */
    private $cmsPage;

    /**
     * RestrictWebsite constructor.
     * @param Http $response
     * @param CategoryPage $categoryPage
     * @param CmsPage $cmsPage
     * @param ProductPage $productPage
     */
    public function __construct(
        Http $response,
        CategoryPage $categoryPage,
        CmsPage $cmsPage,
        ProductPage $productPage
    )
    {
        $this->response = $response;
        $this->categoryPage = $categoryPage;
        $this->cmsPage = $cmsPage;
        $this->productPage = $productPage;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $request = $observer->getEvent()->getRequest();
        $actionFullName = strtolower($request->getFullActionName());
        if ($actionFullName == "catalog_product_view") {
            $productRedirectUrl = $this->productPage->getProductPageUrl();
            if (isset($productRedirectUrl)) {
                $this->response->setRedirect($productRedirectUrl);
            }
        } elseif ($actionFullName == "catalog_category_view") {
            $categoryRedirectUrl = $this->categoryPage->getCategoryPageUrl();
            if (isset($categoryRedirectUrl)) {
                $this->response->setRedirect($categoryRedirectUrl);
            }
        } elseif ($actionFullName == "cms_page_view") {
            $categoryRedirectUrl = $this->cmsPage->getCmsPageUrl();
            if (isset($categoryRedirectUrl)) {
                $this->response->setRedirect($categoryRedirectUrl);
            }
        }
    }
}
