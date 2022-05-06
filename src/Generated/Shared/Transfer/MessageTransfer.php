<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class MessageTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    public const MESSAGE = 'message';

    /**
     * @var string
     */
    public const TYPE = 'type';

    /**
     * @var string
     */
    public const VALUE = 'value';

    /**
     * @var string
     */
    public const PARAMETERS = 'parameters';

    /**
     * @var string|null
     */
    protected $message;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var string|null
     */
    protected $value;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'message' => 'message',
        'Message' => 'message',
        'type' => 'type',
        'Type' => 'type',
        'value' => 'value',
        'Value' => 'value',
        'parameters' => 'parameters',
        'Parameters' => 'parameters',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::MESSAGE => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'message',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::TYPE => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'type',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::VALUE => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'value',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::PARAMETERS => [
            'type' => 'array',
            'type_shim' => null,
            'name_underscore' => 'parameters',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
    ];

    /**
     * @module AopSdk|MerchantOms|MerchantSalesOrder|MerchantSalesReturnMerchantUserGui|MerchantSwitcher|MerchantUser|MerchantUserGui|QuoteRequestAgentsRestApi|QuoteRequestsRestApi|SalesOms|SalesReturnGui|WishlistPage
     *
     * @param string|null $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        $this->modifiedProperties[self::MESSAGE] = true;

        return $this;
    }

    /**
     * @module AopSdk|MerchantOms|MerchantSalesOrder|MerchantSalesReturnMerchantUserGui|MerchantSwitcher|MerchantUser|MerchantUserGui|QuoteRequestAgentsRestApi|QuoteRequestsRestApi|SalesOms|SalesReturnGui|WishlistPage
     *
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @module AopSdk|MerchantOms|MerchantSalesOrder|MerchantSalesReturnMerchantUserGui|MerchantSwitcher|MerchantUser|MerchantUserGui|QuoteRequestAgentsRestApi|QuoteRequestsRestApi|SalesOms|SalesReturnGui|WishlistPage
     *
     * @param string|null $message
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setMessageOrFail($message)
    {
        if ($message === null) {
            $this->throwNullValueException(static::MESSAGE);
        }

        return $this->setMessage($message);
    }

    /**
     * @module AopSdk|MerchantOms|MerchantSalesOrder|MerchantSalesReturnMerchantUserGui|MerchantSwitcher|MerchantUser|MerchantUserGui|QuoteRequestAgentsRestApi|QuoteRequestsRestApi|SalesOms|SalesReturnGui|WishlistPage
     *
     * @return string
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getMessageOrFail()
    {
        if ($this->message === null) {
            $this->throwNullValueException(static::MESSAGE);
        }

        return $this->message;
    }

    /**
     * @module AopSdk|MerchantOms|MerchantSalesOrder|MerchantSalesReturnMerchantUserGui|MerchantSwitcher|MerchantUser|MerchantUserGui|QuoteRequestAgentsRestApi|QuoteRequestsRestApi|SalesOms|SalesReturnGui|WishlistPage
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireMessage()
    {
        $this->assertPropertyIsSet(self::MESSAGE);

        return $this;
    }

    /**
     * @module AopSdk|Cart|CartCode|CartCodesRestApi|ContentProductSetDataImport|Discount|DiscountPromotion|GiftCard|Merchant|MerchantProduct|MerchantProfile|PriceProductStorage|ProductApproval|ProductConfigurationCart|ProductConfigurationStorage|ProductConfigurationWishlist|ProductDiscontinuedStorage|ProductMeasurementUnit|ProductOffer|ProductPackagingUnit|ProductQuantity|ProductQuantityStorage|QuickOrder|Stock|CartCodeWidget|QuickOrderPage
     *
     * @param string|null $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        $this->modifiedProperties[self::TYPE] = true;

        return $this;
    }

    /**
     * @module AopSdk|Cart|CartCode|CartCodesRestApi|ContentProductSetDataImport|Discount|DiscountPromotion|GiftCard|Merchant|MerchantProduct|MerchantProfile|PriceProductStorage|ProductApproval|ProductConfigurationCart|ProductConfigurationStorage|ProductConfigurationWishlist|ProductDiscontinuedStorage|ProductMeasurementUnit|ProductOffer|ProductPackagingUnit|ProductQuantity|ProductQuantityStorage|QuickOrder|Stock|CartCodeWidget|QuickOrderPage
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @module AopSdk|Cart|CartCode|CartCodesRestApi|ContentProductSetDataImport|Discount|DiscountPromotion|GiftCard|Merchant|MerchantProduct|MerchantProfile|PriceProductStorage|ProductApproval|ProductConfigurationCart|ProductConfigurationStorage|ProductConfigurationWishlist|ProductDiscontinuedStorage|ProductMeasurementUnit|ProductOffer|ProductPackagingUnit|ProductQuantity|ProductQuantityStorage|QuickOrder|Stock|CartCodeWidget|QuickOrderPage
     *
     * @param string|null $type
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setTypeOrFail($type)
    {
        if ($type === null) {
            $this->throwNullValueException(static::TYPE);
        }

        return $this->setType($type);
    }

    /**
     * @module AopSdk|Cart|CartCode|CartCodesRestApi|ContentProductSetDataImport|Discount|DiscountPromotion|GiftCard|Merchant|MerchantProduct|MerchantProfile|PriceProductStorage|ProductApproval|ProductConfigurationCart|ProductConfigurationStorage|ProductConfigurationWishlist|ProductDiscontinuedStorage|ProductMeasurementUnit|ProductOffer|ProductPackagingUnit|ProductQuantity|ProductQuantityStorage|QuickOrder|Stock|CartCodeWidget|QuickOrderPage
     *
     * @return string
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getTypeOrFail()
    {
        if ($this->type === null) {
            $this->throwNullValueException(static::TYPE);
        }

        return $this->type;
    }

    /**
     * @module AopSdk|Cart|CartCode|CartCodesRestApi|ContentProductSetDataImport|Discount|DiscountPromotion|GiftCard|Merchant|MerchantProduct|MerchantProfile|PriceProductStorage|ProductApproval|ProductConfigurationCart|ProductConfigurationStorage|ProductConfigurationWishlist|ProductDiscontinuedStorage|ProductMeasurementUnit|ProductOffer|ProductPackagingUnit|ProductQuantity|ProductQuantityStorage|QuickOrder|Stock|CartCodeWidget|QuickOrderPage
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireType()
    {
        $this->assertPropertyIsSet(self::TYPE);

        return $this;
    }

    /**
     * @module AvailabilityCartConnector|Cart|CartCode|CartCodesRestApi|CartPermissionConnector|CategoryGui|Checkout|CmsSlot|CmsSlotBlock|CmsSlotDataImport|Comment|CompanyUser|ConfigurableBundle|ConfigurableBundleCart|ConfigurableBundleGui|Content|ContentBanner|ContentBannerDataImport|ContentBannerGui|ContentFile|ContentFileGui|ContentNavigation|ContentNavigationDataImport|ContentNavigationGui|ContentProduct|ContentProductDataImport|ContentProductGui|ContentProductSetDataImport|Currency|Customer|DataExport|Discount|DiscountPromotion|GiftCard|Glossary|Kernel|ManualOrderEntryGui|Merchant|MerchantProduct|MerchantProductOffer|MerchantProductOption|MerchantProfile|MerchantRelationshipProductList|MerchantSalesReturn|MerchantSalesReturnMerchantUserGui|MerchantSwitcher|MerchantUser|Messenger|MultiCart|OfferGui|OrderCustomReference|OrderCustomReferenceGui|Payment|PaymentGui|PersistentCart|PersistentCartShare|PriceCartConnector|PriceProductStorage|ProductApproval|ProductBundle|ProductBundleProductListConnector|ProductCartConnector|ProductConfiguration|ProductConfigurationCart|ProductConfigurationStorage|ProductConfigurationWishlist|ProductCustomerPermission|ProductDiscontinued|ProductDiscontinuedGui|ProductDiscontinuedProductBundleConnector|ProductDiscontinuedStorage|ProductLabel|ProductLabelGui|ProductList|ProductListGui|ProductMeasurementUnit|ProductMerchantPortalGui|ProductOffer|ProductOfferShoppingList|ProductOptionCartConnector|ProductPackagingUnit|ProductQuantity|ProductQuantityStorage|ProductRelation|ProductRelationGui|QuickOrder|QuoteApproval|QuoteRequest|QuoteRequestAgent|QuoteRequestAgentsRestApi|QuoteRequestsRestApi|ResourceShare|Sales|SalesOrderThreshold|SalesReturn|SalesReturnGui|SalesReturnsRestApi|SecurityGui|SecurityMerchantPortalGui|SecurityOauthUser|SecuritySystemUser|SharedCart|ShipmentCartConnector|ShoppingList|Stock|StockGui|UserMerchantPortalGui|Wishlist|ZedRequest|CartCodeWidget|CartPage|CheckoutPage|CommentWidget|ConfigurableBundlePage|CustomerPage|DiscountWidget|OrderCancelWidget|ProductConfigurationCartWidget|ProductConfiguratorGatewayPage|QuickOrderPage|QuoteApprovalWidget|QuoteRequestAgentPage|QuoteRequestAgentWidget|QuoteRequestPage|QuoteRequestWidget|ResourceSharePage|SalesReturnPage|ShoppingListPage
     *
     * @param string|null $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        $this->modifiedProperties[self::VALUE] = true;

        return $this;
    }

    /**
     * @module AvailabilityCartConnector|Cart|CartCode|CartCodesRestApi|CartPermissionConnector|CategoryGui|Checkout|CmsSlot|CmsSlotBlock|CmsSlotDataImport|Comment|CompanyUser|ConfigurableBundle|ConfigurableBundleCart|ConfigurableBundleGui|Content|ContentBanner|ContentBannerDataImport|ContentBannerGui|ContentFile|ContentFileGui|ContentNavigation|ContentNavigationDataImport|ContentNavigationGui|ContentProduct|ContentProductDataImport|ContentProductGui|ContentProductSetDataImport|Currency|Customer|DataExport|Discount|DiscountPromotion|GiftCard|Glossary|Kernel|ManualOrderEntryGui|Merchant|MerchantProduct|MerchantProductOffer|MerchantProductOption|MerchantProfile|MerchantRelationshipProductList|MerchantSalesReturn|MerchantSalesReturnMerchantUserGui|MerchantSwitcher|MerchantUser|Messenger|MultiCart|OfferGui|OrderCustomReference|OrderCustomReferenceGui|Payment|PaymentGui|PersistentCart|PersistentCartShare|PriceCartConnector|PriceProductStorage|ProductApproval|ProductBundle|ProductBundleProductListConnector|ProductCartConnector|ProductConfiguration|ProductConfigurationCart|ProductConfigurationStorage|ProductConfigurationWishlist|ProductCustomerPermission|ProductDiscontinued|ProductDiscontinuedGui|ProductDiscontinuedProductBundleConnector|ProductDiscontinuedStorage|ProductLabel|ProductLabelGui|ProductList|ProductListGui|ProductMeasurementUnit|ProductMerchantPortalGui|ProductOffer|ProductOfferShoppingList|ProductOptionCartConnector|ProductPackagingUnit|ProductQuantity|ProductQuantityStorage|ProductRelation|ProductRelationGui|QuickOrder|QuoteApproval|QuoteRequest|QuoteRequestAgent|QuoteRequestAgentsRestApi|QuoteRequestsRestApi|ResourceShare|Sales|SalesOrderThreshold|SalesReturn|SalesReturnGui|SalesReturnsRestApi|SecurityGui|SecurityMerchantPortalGui|SecurityOauthUser|SecuritySystemUser|SharedCart|ShipmentCartConnector|ShoppingList|Stock|StockGui|UserMerchantPortalGui|Wishlist|ZedRequest|CartCodeWidget|CartPage|CheckoutPage|CommentWidget|ConfigurableBundlePage|CustomerPage|DiscountWidget|OrderCancelWidget|ProductConfigurationCartWidget|ProductConfiguratorGatewayPage|QuickOrderPage|QuoteApprovalWidget|QuoteRequestAgentPage|QuoteRequestAgentWidget|QuoteRequestPage|QuoteRequestWidget|ResourceSharePage|SalesReturnPage|ShoppingListPage
     *
     * @return string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @module AvailabilityCartConnector|Cart|CartCode|CartCodesRestApi|CartPermissionConnector|CategoryGui|Checkout|CmsSlot|CmsSlotBlock|CmsSlotDataImport|Comment|CompanyUser|ConfigurableBundle|ConfigurableBundleCart|ConfigurableBundleGui|Content|ContentBanner|ContentBannerDataImport|ContentBannerGui|ContentFile|ContentFileGui|ContentNavigation|ContentNavigationDataImport|ContentNavigationGui|ContentProduct|ContentProductDataImport|ContentProductGui|ContentProductSetDataImport|Currency|Customer|DataExport|Discount|DiscountPromotion|GiftCard|Glossary|Kernel|ManualOrderEntryGui|Merchant|MerchantProduct|MerchantProductOffer|MerchantProductOption|MerchantProfile|MerchantRelationshipProductList|MerchantSalesReturn|MerchantSalesReturnMerchantUserGui|MerchantSwitcher|MerchantUser|Messenger|MultiCart|OfferGui|OrderCustomReference|OrderCustomReferenceGui|Payment|PaymentGui|PersistentCart|PersistentCartShare|PriceCartConnector|PriceProductStorage|ProductApproval|ProductBundle|ProductBundleProductListConnector|ProductCartConnector|ProductConfiguration|ProductConfigurationCart|ProductConfigurationStorage|ProductConfigurationWishlist|ProductCustomerPermission|ProductDiscontinued|ProductDiscontinuedGui|ProductDiscontinuedProductBundleConnector|ProductDiscontinuedStorage|ProductLabel|ProductLabelGui|ProductList|ProductListGui|ProductMeasurementUnit|ProductMerchantPortalGui|ProductOffer|ProductOfferShoppingList|ProductOptionCartConnector|ProductPackagingUnit|ProductQuantity|ProductQuantityStorage|ProductRelation|ProductRelationGui|QuickOrder|QuoteApproval|QuoteRequest|QuoteRequestAgent|QuoteRequestAgentsRestApi|QuoteRequestsRestApi|ResourceShare|Sales|SalesOrderThreshold|SalesReturn|SalesReturnGui|SalesReturnsRestApi|SecurityGui|SecurityMerchantPortalGui|SecurityOauthUser|SecuritySystemUser|SharedCart|ShipmentCartConnector|ShoppingList|Stock|StockGui|UserMerchantPortalGui|Wishlist|ZedRequest|CartCodeWidget|CartPage|CheckoutPage|CommentWidget|ConfigurableBundlePage|CustomerPage|DiscountWidget|OrderCancelWidget|ProductConfigurationCartWidget|ProductConfiguratorGatewayPage|QuickOrderPage|QuoteApprovalWidget|QuoteRequestAgentPage|QuoteRequestAgentWidget|QuoteRequestPage|QuoteRequestWidget|ResourceSharePage|SalesReturnPage|ShoppingListPage
     *
     * @param string|null $value
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setValueOrFail($value)
    {
        if ($value === null) {
            $this->throwNullValueException(static::VALUE);
        }

        return $this->setValue($value);
    }

    /**
     * @module AvailabilityCartConnector|Cart|CartCode|CartCodesRestApi|CartPermissionConnector|CategoryGui|Checkout|CmsSlot|CmsSlotBlock|CmsSlotDataImport|Comment|CompanyUser|ConfigurableBundle|ConfigurableBundleCart|ConfigurableBundleGui|Content|ContentBanner|ContentBannerDataImport|ContentBannerGui|ContentFile|ContentFileGui|ContentNavigation|ContentNavigationDataImport|ContentNavigationGui|ContentProduct|ContentProductDataImport|ContentProductGui|ContentProductSetDataImport|Currency|Customer|DataExport|Discount|DiscountPromotion|GiftCard|Glossary|Kernel|ManualOrderEntryGui|Merchant|MerchantProduct|MerchantProductOffer|MerchantProductOption|MerchantProfile|MerchantRelationshipProductList|MerchantSalesReturn|MerchantSalesReturnMerchantUserGui|MerchantSwitcher|MerchantUser|Messenger|MultiCart|OfferGui|OrderCustomReference|OrderCustomReferenceGui|Payment|PaymentGui|PersistentCart|PersistentCartShare|PriceCartConnector|PriceProductStorage|ProductApproval|ProductBundle|ProductBundleProductListConnector|ProductCartConnector|ProductConfiguration|ProductConfigurationCart|ProductConfigurationStorage|ProductConfigurationWishlist|ProductCustomerPermission|ProductDiscontinued|ProductDiscontinuedGui|ProductDiscontinuedProductBundleConnector|ProductDiscontinuedStorage|ProductLabel|ProductLabelGui|ProductList|ProductListGui|ProductMeasurementUnit|ProductMerchantPortalGui|ProductOffer|ProductOfferShoppingList|ProductOptionCartConnector|ProductPackagingUnit|ProductQuantity|ProductQuantityStorage|ProductRelation|ProductRelationGui|QuickOrder|QuoteApproval|QuoteRequest|QuoteRequestAgent|QuoteRequestAgentsRestApi|QuoteRequestsRestApi|ResourceShare|Sales|SalesOrderThreshold|SalesReturn|SalesReturnGui|SalesReturnsRestApi|SecurityGui|SecurityMerchantPortalGui|SecurityOauthUser|SecuritySystemUser|SharedCart|ShipmentCartConnector|ShoppingList|Stock|StockGui|UserMerchantPortalGui|Wishlist|ZedRequest|CartCodeWidget|CartPage|CheckoutPage|CommentWidget|ConfigurableBundlePage|CustomerPage|DiscountWidget|OrderCancelWidget|ProductConfigurationCartWidget|ProductConfiguratorGatewayPage|QuickOrderPage|QuoteApprovalWidget|QuoteRequestAgentPage|QuoteRequestAgentWidget|QuoteRequestPage|QuoteRequestWidget|ResourceSharePage|SalesReturnPage|ShoppingListPage
     *
     * @return string
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getValueOrFail()
    {
        if ($this->value === null) {
            $this->throwNullValueException(static::VALUE);
        }

        return $this->value;
    }

    /**
     * @module AvailabilityCartConnector|Cart|CartCode|CartCodesRestApi|CartPermissionConnector|CategoryGui|Checkout|CmsSlot|CmsSlotBlock|CmsSlotDataImport|Comment|CompanyUser|ConfigurableBundle|ConfigurableBundleCart|ConfigurableBundleGui|Content|ContentBanner|ContentBannerDataImport|ContentBannerGui|ContentFile|ContentFileGui|ContentNavigation|ContentNavigationDataImport|ContentNavigationGui|ContentProduct|ContentProductDataImport|ContentProductGui|ContentProductSetDataImport|Currency|Customer|DataExport|Discount|DiscountPromotion|GiftCard|Glossary|Kernel|ManualOrderEntryGui|Merchant|MerchantProduct|MerchantProductOffer|MerchantProductOption|MerchantProfile|MerchantRelationshipProductList|MerchantSalesReturn|MerchantSalesReturnMerchantUserGui|MerchantSwitcher|MerchantUser|Messenger|MultiCart|OfferGui|OrderCustomReference|OrderCustomReferenceGui|Payment|PaymentGui|PersistentCart|PersistentCartShare|PriceCartConnector|PriceProductStorage|ProductApproval|ProductBundle|ProductBundleProductListConnector|ProductCartConnector|ProductConfiguration|ProductConfigurationCart|ProductConfigurationStorage|ProductConfigurationWishlist|ProductCustomerPermission|ProductDiscontinued|ProductDiscontinuedGui|ProductDiscontinuedProductBundleConnector|ProductDiscontinuedStorage|ProductLabel|ProductLabelGui|ProductList|ProductListGui|ProductMeasurementUnit|ProductMerchantPortalGui|ProductOffer|ProductOfferShoppingList|ProductOptionCartConnector|ProductPackagingUnit|ProductQuantity|ProductQuantityStorage|ProductRelation|ProductRelationGui|QuickOrder|QuoteApproval|QuoteRequest|QuoteRequestAgent|QuoteRequestAgentsRestApi|QuoteRequestsRestApi|ResourceShare|Sales|SalesOrderThreshold|SalesReturn|SalesReturnGui|SalesReturnsRestApi|SecurityGui|SecurityMerchantPortalGui|SecurityOauthUser|SecuritySystemUser|SharedCart|ShipmentCartConnector|ShoppingList|Stock|StockGui|UserMerchantPortalGui|Wishlist|ZedRequest|CartCodeWidget|CartPage|CheckoutPage|CommentWidget|ConfigurableBundlePage|CustomerPage|DiscountWidget|OrderCancelWidget|ProductConfigurationCartWidget|ProductConfiguratorGatewayPage|QuickOrderPage|QuoteApprovalWidget|QuoteRequestAgentPage|QuoteRequestAgentWidget|QuoteRequestPage|QuoteRequestWidget|ResourceSharePage|SalesReturnPage|ShoppingListPage
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireValue()
    {
        $this->assertPropertyIsSet(self::VALUE);

        return $this;
    }

    /**
     * @module AvailabilityCartConnector|Cart|ConfigurableBundle|ConfigurableBundleGui|ContentBannerGui|ContentFile|ContentFileGui|ContentNavigationGui|ContentProduct|ContentProductDataImport|ContentProductGui|ContentProductSetDataImport|Customer|Discount|DiscountPromotion|Kernel|ManualOrderEntryGui|Merchant|MerchantProduct|MerchantProductOption|MerchantProfile|MerchantRelationshipProductList|MerchantSalesReturnMerchantUserGui|MerchantSwitcher|Messenger|MultiCart|PriceCartConnector|ProductApproval|ProductBundle|ProductBundleProductListConnector|ProductCartConnector|ProductConfiguration|ProductConfigurationCart|ProductConfigurationStorage|ProductConfigurationWishlist|ProductDiscontinued|ProductDiscontinuedGui|ProductDiscontinuedProductBundleConnector|ProductLabel|ProductList|ProductListGui|ProductMeasurementUnit|ProductOffer|ProductOfferShoppingList|ProductOptionCartConnector|ProductPackagingUnit|ProductQuantity|ProductQuantityStorage|QuickOrder|QuoteApproval|SalesOrderThreshold|SalesReturn|SalesReturnGui|SharedCart|ShipmentCartConnector|ShoppingList|Stock|ZedRequest|ConfigurableBundlePage|ProductConfiguratorGatewayPage|QuickOrderPage|QuoteApprovalWidget|SalesReturnPage|WishlistPage
     *
     * @param array|null $parameters
     *
     * @return $this
     */
    public function setParameters(array $parameters = null)
    {
        if ($parameters === null) {
            $parameters = [];
        }

        $this->parameters = $parameters;
        $this->modifiedProperties[self::PARAMETERS] = true;

        return $this;
    }

    /**
     * @module AvailabilityCartConnector|Cart|ConfigurableBundle|ConfigurableBundleGui|ContentBannerGui|ContentFile|ContentFileGui|ContentNavigationGui|ContentProduct|ContentProductDataImport|ContentProductGui|ContentProductSetDataImport|Customer|Discount|DiscountPromotion|Kernel|ManualOrderEntryGui|Merchant|MerchantProduct|MerchantProductOption|MerchantProfile|MerchantRelationshipProductList|MerchantSalesReturnMerchantUserGui|MerchantSwitcher|Messenger|MultiCart|PriceCartConnector|ProductApproval|ProductBundle|ProductBundleProductListConnector|ProductCartConnector|ProductConfiguration|ProductConfigurationCart|ProductConfigurationStorage|ProductConfigurationWishlist|ProductDiscontinued|ProductDiscontinuedGui|ProductDiscontinuedProductBundleConnector|ProductLabel|ProductList|ProductListGui|ProductMeasurementUnit|ProductOffer|ProductOfferShoppingList|ProductOptionCartConnector|ProductPackagingUnit|ProductQuantity|ProductQuantityStorage|QuickOrder|QuoteApproval|SalesOrderThreshold|SalesReturn|SalesReturnGui|SharedCart|ShipmentCartConnector|ShoppingList|Stock|ZedRequest|ConfigurableBundlePage|ProductConfiguratorGatewayPage|QuickOrderPage|QuoteApprovalWidget|SalesReturnPage|WishlistPage
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @module AvailabilityCartConnector|Cart|ConfigurableBundle|ConfigurableBundleGui|ContentBannerGui|ContentFile|ContentFileGui|ContentNavigationGui|ContentProduct|ContentProductDataImport|ContentProductGui|ContentProductSetDataImport|Customer|Discount|DiscountPromotion|Kernel|ManualOrderEntryGui|Merchant|MerchantProduct|MerchantProductOption|MerchantProfile|MerchantRelationshipProductList|MerchantSalesReturnMerchantUserGui|MerchantSwitcher|Messenger|MultiCart|PriceCartConnector|ProductApproval|ProductBundle|ProductBundleProductListConnector|ProductCartConnector|ProductConfiguration|ProductConfigurationCart|ProductConfigurationStorage|ProductConfigurationWishlist|ProductDiscontinued|ProductDiscontinuedGui|ProductDiscontinuedProductBundleConnector|ProductLabel|ProductList|ProductListGui|ProductMeasurementUnit|ProductOffer|ProductOfferShoppingList|ProductOptionCartConnector|ProductPackagingUnit|ProductQuantity|ProductQuantityStorage|QuickOrder|QuoteApproval|SalesOrderThreshold|SalesReturn|SalesReturnGui|SharedCart|ShipmentCartConnector|ShoppingList|Stock|ZedRequest|ConfigurableBundlePage|ProductConfiguratorGatewayPage|QuickOrderPage|QuoteApprovalWidget|SalesReturnPage|WishlistPage
     *
     * @param mixed $parameters
     *
     * @return $this
     */
    public function addParameters($parameters)
    {
        $this->parameters[] = $parameters;
        $this->modifiedProperties[self::PARAMETERS] = true;

        return $this;
    }

    /**
     * @module AvailabilityCartConnector|Cart|ConfigurableBundle|ConfigurableBundleGui|ContentBannerGui|ContentFile|ContentFileGui|ContentNavigationGui|ContentProduct|ContentProductDataImport|ContentProductGui|ContentProductSetDataImport|Customer|Discount|DiscountPromotion|Kernel|ManualOrderEntryGui|Merchant|MerchantProduct|MerchantProductOption|MerchantProfile|MerchantRelationshipProductList|MerchantSalesReturnMerchantUserGui|MerchantSwitcher|Messenger|MultiCart|PriceCartConnector|ProductApproval|ProductBundle|ProductBundleProductListConnector|ProductCartConnector|ProductConfiguration|ProductConfigurationCart|ProductConfigurationStorage|ProductConfigurationWishlist|ProductDiscontinued|ProductDiscontinuedGui|ProductDiscontinuedProductBundleConnector|ProductLabel|ProductList|ProductListGui|ProductMeasurementUnit|ProductOffer|ProductOfferShoppingList|ProductOptionCartConnector|ProductPackagingUnit|ProductQuantity|ProductQuantityStorage|QuickOrder|QuoteApproval|SalesOrderThreshold|SalesReturn|SalesReturnGui|SharedCart|ShipmentCartConnector|ShoppingList|Stock|ZedRequest|ConfigurableBundlePage|ProductConfiguratorGatewayPage|QuickOrderPage|QuoteApprovalWidget|SalesReturnPage|WishlistPage
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireParameters()
    {
        $this->assertPropertyIsSet(self::PARAMETERS);

        return $this;
    }

    /**
     * @param array<string, mixed> $data
     * @param bool $ignoreMissingProperty
     *
     * @return $this
     * @throws \InvalidArgumentException
     *
     */
    public function fromArray(array $data, $ignoreMissingProperty = false)
    {
        foreach ($data as $property => $value) {
            $normalizedPropertyName = $this->transferPropertyNameMap[$property] ?? null;

            switch ($normalizedPropertyName) {
                case 'message':
                case 'type':
                case 'value':
                case 'parameters':
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;

                    break;
                default:
                    if (!$ignoreMissingProperty) {
                        throw new \InvalidArgumentException(sprintf('Missing property `%s` in `%s`', $property,
                            static::class));
                    }
            }
        }

        return $this;
    }

    /**
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    public function modifiedToArray($isRecursive = true, $camelCasedKeys = false): array
    {
        if ($isRecursive && !$camelCasedKeys) {
            return $this->modifiedToArrayRecursiveNotCamelCased();
        }
        if ($isRecursive && $camelCasedKeys) {
            return $this->modifiedToArrayRecursiveCamelCased();
        }
        if (!$isRecursive && $camelCasedKeys) {
            return $this->modifiedToArrayNotRecursiveCamelCased();
        }
        if (!$isRecursive && !$camelCasedKeys) {
            return $this->modifiedToArrayNotRecursiveNotCamelCased();
        }
    }

    /**
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    public function toArray($isRecursive = true, $camelCasedKeys = false): array
    {
        if ($isRecursive && !$camelCasedKeys) {
            return $this->toArrayRecursiveNotCamelCased();
        }
        if ($isRecursive && $camelCasedKeys) {
            return $this->toArrayRecursiveCamelCased();
        }
        if (!$isRecursive && !$camelCasedKeys) {
            return $this->toArrayNotRecursiveNotCamelCased();
        }
        if (!$isRecursive && $camelCasedKeys) {
            return $this->toArrayNotRecursiveCamelCased();
        }
    }

    /**
     * @param array<string, mixed>|\ArrayObject<string, mixed> $value
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    protected function addValuesToCollectionModified($value, $isRecursive, $camelCasedKeys): array
    {
        $result = [];
        foreach ($value as $elementKey => $arrayElement) {
            if ($arrayElement instanceof AbstractTransfer) {
                $result[$elementKey] = $arrayElement->modifiedToArray($isRecursive, $camelCasedKeys);

                continue;
            }
            $result[$elementKey] = $arrayElement;
        }

        return $result;
    }

    /**
     * @param array<string, mixed>|\ArrayObject<string, mixed> $value
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    protected function addValuesToCollection($value, $isRecursive, $camelCasedKeys): array
    {
        $result = [];
        foreach ($value as $elementKey => $arrayElement) {
            if ($arrayElement instanceof AbstractTransfer) {
                $result[$elementKey] = $arrayElement->toArray($isRecursive, $camelCasedKeys);

                continue;
            }
            $result[$elementKey] = $arrayElement;
        }

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayRecursiveCamelCased(): array
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $property;

            if ($value instanceof AbstractTransfer) {
                $values[$arrayKey] = $value->modifiedToArray(true, true);

                continue;
            }
            switch ($property) {
                case 'message':
                case 'type':
                case 'value':
                case 'parameters':
                    $values[$arrayKey] = $value;

                    break;
            }
        }

        return $values;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayRecursiveNotCamelCased(): array
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $this->transferMetadata[$property]['name_underscore'];

            if ($value instanceof AbstractTransfer) {
                $values[$arrayKey] = $value->modifiedToArray(true, false);

                continue;
            }
            switch ($property) {
                case 'message':
                case 'type':
                case 'value':
                case 'parameters':
                    $values[$arrayKey] = $value;

                    break;
            }
        }

        return $values;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayNotRecursiveNotCamelCased(): array
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $this->transferMetadata[$property]['name_underscore'];

            $values[$arrayKey] = $value;
        }

        return $values;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayNotRecursiveCamelCased(): array
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $property;

            $values[$arrayKey] = $value;
        }

        return $values;
    }

    /**
     * @return void
     */
    protected function initCollectionProperties(): void
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveCamelCased(): array
    {
        return [
            'message' => $this->message,
            'type' => $this->type,
            'value' => $this->value,
            'parameters' => $this->parameters,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveNotCamelCased(): array
    {
        return [
            'message' => $this->message,
            'type' => $this->type,
            'value' => $this->value,
            'parameters' => $this->parameters,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveNotCamelCased(): array
    {
        return [
            'message' => $this->message instanceof AbstractTransfer ? $this->message->toArray(true,
                false) : $this->message,
            'type' => $this->type instanceof AbstractTransfer ? $this->type->toArray(true, false) : $this->type,
            'value' => $this->value instanceof AbstractTransfer ? $this->value->toArray(true, false) : $this->value,
            'parameters' => $this->parameters instanceof AbstractTransfer ? $this->parameters->toArray(true,
                false) : $this->parameters,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveCamelCased(): array
    {
        return [
            'message' => $this->message instanceof AbstractTransfer ? $this->message->toArray(true,
                true) : $this->message,
            'type' => $this->type instanceof AbstractTransfer ? $this->type->toArray(true, true) : $this->type,
            'value' => $this->value instanceof AbstractTransfer ? $this->value->toArray(true, true) : $this->value,
            'parameters' => $this->parameters instanceof AbstractTransfer ? $this->parameters->toArray(true,
                true) : $this->parameters,
        ];
    }
}
