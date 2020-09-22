<?php

namespace FondOfSpryker\Shared\GoogleTagManager;

interface EnhancedEcommerceConstants
{
    public const SESSION_ADDED_PRODUCTS = 'SESSION_ADDED_PRODUCTS';
    public const SESSION_REMOVED_PRODUCTS = 'SESSION_REMOVED_PRODUCTS';
    public const SESSION_REMOVED_CHANGED_QUANTITY = 'SESSION_REMOVED_CHANGED_QUANTITY';
    public const SESSION_PURCHASE = 'SESSION_PURCHASE';

    public const PRODUCT_FIELD_PRODUCT_ABSTRACT_ID = 'product_abstract_id';
    public const PRODUCT_FIELD_SKU = 'sku';
    public const PRODUCT_FIELD_QUANTITY = 'quantity';
    public const PRODUCT_FIELD_BRAND = 'brand';
    public const PRODUCT_FIELD_PRICE = 'price';
    public const PRODUCT_FIELD_ATTRIBUTE_MODEL = 'model';
    public const PRODUCT_FIELD_ATTRIBUTE_MODEL_UNTRANSLATED = 'model_untranslated';
    public const PRODUCT_FIELD_ATTRIBUTE_STYLE = 'style';
    public const PRODUCT_FIELD_ATTRIBUTE_STYLE_UNTRANSLATED = 'style_untranslated';
    public const PRODUCT_FIELD_ATTRIBUTE_SIZE = 'size';
    public const PRODUCT_FIELD_ATTRIBUTE_SIZE_UNTRANSLATED = 'size_untranslated';

    public const EVENT_CATEGORY = 'ecommerce';
    public const EVENT_GENERIC = 'genericEvent';
    public const EVENT_PRODUCT_DETAIL = 'productDetail';
    public const EVENT_PRODUCT_ADD = 'addToCart';
    public const EVENT_PRODUCT_REMOVE = 'removeFromCart';
    public const EVENT_CHECKOUT = 'checkout';
    public const EVENT_PURCHASE = 'purchase';
    public const EVENT_CHECKOUT_OPTION = 'checkout_option';

    public const CHECKOUT_STEP_BILLING_ADDRESS = 1;
    public const CHECKOUT_STEP_PAYMENT = 2;
    public const CHECKOUT_STEP_SUMMARY = 3;

    public const PAGE_TYPE_CART = 'cart';
    public const PAGE_TYPE_PRODUCT_DETAIL = 'productDetail';
    public const PAGE_TYPE_CHECKOUT_BILLING_ADDRESS = 'checkoutBillingAddress';
    public const PAGE_TYPE_CHECKOUT_PAYMENT = 'checkoutPayment';
    public const PAGE_TYPE_CHECKOUT_SUMMARY = 'checkoutSummary';
    public const PAGE_TYPE_PURCHASE = 'purchase';
    public const PAGE_TYPE_CATEGORY = 'category';
    public const PAGE_TYPE_IMPRESSIONS = 'impressions';

    public const PAYMENT_METHODS = 'PAYMENT_METHODS';
    public const PAYMENT_METHOD_PREPAYMENT_NAME = 'prepayment';
    public const PAYMENT_METHOD_PREPAYMENT_SELECTION = 'prepaymentPrepayment';
    public const PAYMENT_METHOD_PAYPAL_NAME = 'paypal';
    public const PAYMENT_METHOD_PAYPAL_SELECTION = 'payoneEWallet';
    public const PAYMENT_METHOD_CREDITCARD_NAME = 'creditcard';
    public const PAYMENT_METHOD_CREDITCARD_SELECTION = 'payoneCreditCard';

    public const EEC_LOCALE = 'EEC_LOCALE';
}
