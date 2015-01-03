<?php
namespace HcbStoreProduct\Service\Localized;

use HcbStoreProduct\Service\LocaleBinderService;
use HcBackend\Service\PageBinderServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use HcbStoreProduct\Data\LocalizedInterface;
use HcbStoreProduct\Entity\Product;
use HcbStoreProduct\Stdlib\Service\Response\CreateResponse;

class CreateService
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var CreateResponse
     */
    protected $createResponse;

    /**
     * @var PageBinderServiceInterface
     */
    protected $pageBinderService;

    /**
     * @var LocaleBinderService
     */
    protected $localeBinderService;

    /**
     * @var UpdateService
     */
    protected $updateService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param PageBinderServiceInterface $pageBinderService
     * @param LocaleBinderService $localeService
     * @param UpdateService $updateService
     * @param CreateResponse $saveResponse
     */
    public function __construct(EntityManagerInterface $entityManager,
                                PageBinderServiceInterface $pageBinderService,
                                LocaleBinderService $localeBinderService,
                                UpdateService $updateService,
                                CreateResponse $saveResponse)
    {
        $this->pageBinderService = $pageBinderService;
        $this->localeBinderService = $localeBinderService;
        $this->updateService = $updateService;
        $this->entityManager = $entityManager;
        $this->createResponse = $saveResponse;
    }

    /**
     * @param Product $productEntity
     * @param LocalizedInterface $localizedData
     * @return CreateResponse
     */
    public function save(Product $productEntity, LocalizedInterface $localizedData)
    {
        try {
            $this->entityManager->beginTransaction();

            $localizedEntity = new Product\Localized();
            $localizedEntity->setProduct($productEntity);

            $response = $this->localeBinderService
                             ->bind($localizedData, $localizedEntity);

            if ($response->isFailed()) {
                return $response;
            }

            $response = $this->updateService->update($localizedEntity, $localizedData);

            if ($response->isFailed()) {
                return $response;
            }

            $this->entityManager->flush();

            $this->createResponse->setResource($localizedEntity->getId());
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            $this->createResponse->error($e->getMessage())->failed();
            return $this->createResponse;
        }

        $this->createResponse->success();
        return $this->createResponse;
    }
}
