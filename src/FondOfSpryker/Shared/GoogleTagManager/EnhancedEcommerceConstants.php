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

    public const EVENT_PRODUCT_DETAIL = 'eec.detail';
    public const EVENT_PRODUCT_ADD = 'eec.add';
    public const EVENT_PRODUCT_REMOVE = 'eec.remove';
    public const EVENT_CHECKOUT = 'eec.checkout';
    public const EVENT_CHECKOUT_OPTION = 'eec.checkout_option';

    public const CHECKOUT_STEP_BILLING_ADDRESS = 1;
    public const CHECKOUT_STEP_PAYMENT = 2;
    public const CHECKOUT_STEP_SUMMARY = 3;

    public const PAGE_TYPE_CART = 'cart';
    public const PAGE_TYPE_PRODUCT_DETAIL = 'productDetail';
    public const PAGE_TYPE_CHECKOUT_BILLING_ADDRESS = 'checkoutBillingAddress';
    public const PAGE_TYPE_CHECKOUT_SHIPPING_ADDRESS = 'checkoutShippingAddress';
    public const PAGE_TYPE_CHECKOUT_PAYMENT = 'checkoutPayment';
    public const PAGE_TYPE_CHECKOUT_SUMMARY = 'checkoutSummary';
    public const PAGE_TYPE_PURCHASE = 'purchase';
}
