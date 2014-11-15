<?php
return array(
    // Product
    'HcbStoreProduct-Controller-Collection-List' =>
        'HcCore\Controller\Common\Rest\Collection\ListController',

    'HcbStoreProduct-Controller-Create' =>
        'HcCore\Controller\Common\Rest\Collection\DataController',

    'HcbStoreProduct-Controller-Collection-Delete' =>
        'HcCore\Controller\Common\Rest\Collection\DataController',

    // Localized
    'HcbStoreProduct-Controller-Localized-Collection-List' =>
        'HcCore\Controller\Common\Rest\Collection\ResourceListController',

    'HcbStoreProduct-Controller-Localized-Update' =>
        'HcCore\Controller\Common\Rest\Collection\ResourceDataController',

    'HcbStoreProduct-Controller-Localized-Create' =>
        'HcCore\Controller\Common\Rest\Collection\ResourceDataController',

    // Product Images
    'HcbStoreProduct-Controller-Image-Create' =>
        'Zf2FileUploader\Controller\Images\CreateController',

    'HcbStoreProduct-Controller-Image-List' =>
        'HcbStoreProduct\Controller\Images\ListController',

    // Product Thumbnail Images
    'HcbStoreProduct-Controller-Thumbnail-List' =>
        'HcbStoreProduct\Controller\Thumbnail\ListController',

    // Product 3D Image
    'HcbStoreProduct-Controller-Image3d-List' =>
        'HcbStoreProduct\Controller\Image3d\ListController'
);
