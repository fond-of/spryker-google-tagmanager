<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Business\Mapper;

class EnhancedEcommerceProductMapper
{
    public const SKU = 'sku';
    public const ATTR = 'attributes';
    public const MODEL = 'model';
    public const MODEL_UNTRANSLATED = 'model_untranslated';
    public const STYLE = 'style';
    public const STYLE_UNTRANSLATED = 'style_untranslated';
    public const BRAND = 'brand';
    public const SIZE = 'size';
    public const SIZE_UNTRANSLATED = 'size_untranslated';
    public const QUANTITY = 'quantity';

    /**
     * @param array $product
     *
     * @return array
     */
    public function map(array $product): array
    {
        return array_merge(
            $this->mapSkuAsId($product),
            $this->mapModelAsName($product),
            $this->mapStyleAsVariant($product),
            $this->mapBrand($product),
            $this->mapSizeAsDimension1($product),
            $this->mapQuantity($product)
        );
    }

    /**
     * @param array $product
     *
     * @return array
     */
    protected function mapSkuAsId(array $product): array
    {
        if (!array_key_exists(static::SKU, $product)) {
            return [];
        }

        return ['id' => $product[static::SKU]];
    }

    /**
     * @param array $product
     *
     * @return array
     */
    protected function mapModelAsName(array $product): array
    {
        if (!array_key_exists(static::ATTR, $product)) {
            return [];
        }

        if (array_key_exists(static::MODEL_UNTRANSLATED, $product[static::ATTR])) {
            return ['name' => $product[static::ATTR][static::MODEL_UNTRANSLATED]];
        }

        return ['name' => $product[static::ATTR][static::MODEL]];
    }

    /**
     * @param array $product
     *
     * @return array
     */
    protected function mapStyleAsVariant(array $product): array
    {
        if (!array_key_exists(static::ATTR, $product)) {
            return [];
        }

        if (array_key_exists(static::STYLE_UNTRANSLATED, $product[static::ATTR])) {
            return ['variant' => $product[static::ATTR][static::STYLE_UNTRANSLATED]];
        }

        return ['variant' => $product[static::ATTR][static::STYLE]];
    }

    /**
     * @param array $product
     *
     * @return array
     */
    protected function mapBrand(array $product): array
    {
        if (!array_key_exists(static::ATTR, $product)) {
            return [];
        }

        if (!array_key_exists(static::BRAND, $product[static::ATTR])) {
            return[];
        }

        return [static::BRAND => $product[static::ATTR][static::BRAND]];
    }

    /**
     * @param array $product
     *
     * @return array
     */
    protected function mapSizeAsDimension1(array $product): array
    {
        if (!array_key_exists(static::ATTR, $product)) {
            return [];
        }

        if (array_key_exists(static::STYLE_UNTRANSLATED, $product[static::ATTR])) {
            return ['dimension1' => $product[static::ATTR][static::SIZE_UNTRANSLATED]];
        }

        return ['dimension1' => $product[static::ATTR][static::SIZE]];
    }

    /**
     * @param array $product
     *
     * @return array
     */
    protected function mapQuantity(array $product): array
    {
        if (!array_key_exists(static::QUANTITY, $product)) {
            return [];
        }

        if (!$product[static::QUANTITY]) {
            return [];
        }

        return [static::QUANTITY => $product[static::QUANTITY]];
    }
}
