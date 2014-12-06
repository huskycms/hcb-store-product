<?php
namespace HcbStoreProduct\Stdlib\Extractor\Localized;

use HcBackend\Service\Alias\DetectAlias;
use Zf2Libs\Stdlib\Extractor\ExtractorInterface;
use Zf2Libs\Stdlib\Extractor\Exception\InvalidArgumentException;
use HcbStoreProduct\Entity\Product\Localized as ProductLocalizedEntity;
use HcBackend\Stdlib\Extractor\Page\Extractor as PageExtractor;

class Resource implements ExtractorInterface
{
    /**
     * @var PageExtractor
     */
    protected  $pageExtractor;

    /**
     * @var DetectAlias
     */
    protected $detectAlias;

    /**
     * @param PageExtractor $pageExtractor
     */
    public function __construct(PageExtractor $pageExtractor,
                                DetectAlias $detectAlias)
    {
        $this->pageExtractor = $pageExtractor;
        $this->detectAlias = $detectAlias;
    }

    /**
     * Extract values from an object
     *
     * @param  ProductLocalizedEntity $productLocalized
     * @throws InvalidArgumentException
     * @return array
     */
    public function extract($productLocalized)
    {
        if (!$productLocalized instanceof ProductLocalizedEntity) {
            throw new InvalidArgumentException("Expected HcbStoreProduct\\Entity\\Product\\Localized
                                                object, invalid object given");
        }

        $createdTimestamp = $productLocalized->getProduct()->getCreatedTimestamp();
        if ($createdTimestamp) {
            $createdTimestamp = $createdTimestamp->format('Y-m-d H:i:s');
        }

        $updatedTimestamp = $productLocalized->getProduct()->getUpdatedTimestamp();
        if ($updatedTimestamp) {
            $updatedTimestamp = $updatedTimestamp->format('Y-m-d H:i:s');
        }

        $aliasWireEntity = $this->detectAlias->detect($productLocalized->getProduct());

        $localData = array('id'=>$productLocalized->getId(),
                           'locale'=>$productLocalized->getLocale()->getLocale(),
                           'alias'=>(is_null($aliasWireEntity) ? '' :
                                     $aliasWireEntity->getAlias()->getName()),
                           'title'=>$productLocalized->getTitle(),
                           'description'=>$productLocalized->getDescription(),
                           'shortDescription'=>$productLocalized->getShortDescription(),
                           'extraDescription'=>$productLocalized->getExtraDescription(),
                           'status' => $productLocalized->getProduct()->getStatus(),
                           'price' => $productLocalized->getProduct()->getPrice(),
                           'replaceProduct' => $productLocalized->getProduct()
                                                                ->getProduct()->getId(),
                           'priceDeal' => $productLocalized->getProduct()->getPriceDeal(),
                           'createdTimestamp'=>$createdTimestamp,
                           'updatedTimestamp'=>$updatedTimestamp);

        if (($pageEntity = $productLocalized->getPage())) {
            $localData = array_merge($localData, $this->pageExtractor->extract($pageEntity));
        }

        return $localData;
    }
}
