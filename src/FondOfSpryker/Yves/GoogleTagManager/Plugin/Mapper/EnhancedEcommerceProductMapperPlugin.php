<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface;
use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

class EnhancedEcommerceProductMapperPlugin extends AbstractPlugin implements EnhancedEcommerceProductMapperInterface
{
    public const SKU = 'sku';
    public const BRAND = 'brand';
    public const ATTRIBUTES = 'attributes';
    public const QUANTITY = 'quantity';
    public const PRICE = 'price';

    public const ATTR_MODEL = 'model';
    public const ATTR_MODEL_UNTRANSLATED = 'model_untranslated';

    public const ATTR_STYLE = 'style';
    public const ATTR_STYLE_UNTRANSLATED = 'style_untranslated';

    public const ATTR_SIZE = 'size';
    public const ATTR_SIZE_UNTRANSLATED = 'size_untranslated';

    /**
     * @var \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer
     */
    protected $enhancedEcommerceProductTransfer;

    /**
     * @param array $product
     *
     * @return \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer
     */
    public function map(array $product): EnhancedEcommerceProductTransfer
    {
        $this->enhancedEcommerceProductTransfer = new EnhancedEcommerceProductTransfer();

        $this->setId($product);
        $this->setName($product);
        $this->setVariant($product);
        $this->setBrand($product);
        $this->setDimension1($product);
        $this->setQuantity($product);

        return $this->enhancedEcommerceProductTransfer;
    }

    /**
     * @param array $product
     *
     * @return void
     */
    protected function setId(array $product): void
    {
        if (!isset($product[static::SKU])) {
            return;
        }

        $this->enhancedEcommerceProductTransfer->setId($product[static::SKU]);
    }

    /**
     * @param array $product
     *
     * @return void
     */
    protected function setName(array $product): void
    {
        if (isset($product[static::ATTRIBUTES][static::ATTR_MODEL_UNTRANSLATED])) {
            $this->enhancedEcommerceProductTransfer->setName($product[static::ATTRIBUTES][static::ATTR_MODEL_UNTRANSLATED]);

            return;
        }

        if (isset($product[static::ATTRIBUTES][static::ATTR_MODEL])) {
            $this->enhancedEcommerceProductTransfer->setName($product[static::ATTRIBUTES][static::ATTR_MODEL]);

            return;
        }
    }

    /**
     * @param array $product
     *
     * @return void
     */
    protected function setVariant(array $product): void
    {
        if (isset($product[static::ATTRIBUTES][static::ATTR_MODEL_UNTRANSLATED])) {
            $this->enhancedEcommerceProductTransfer->setVariant($product[static::ATTRIBUTES][static::ATTR_STYLE_UNTRANSLATED]);

            return;
        }

        if (isset($product[static::ATTRIBUTES][static::ATTR_MODEL])) {
            $this->enhancedEcommerceProductTransfer->setVariant($product[static::ATTRIBUTES][static::ATTR_STYLE]);

            return;
        }
    }

    /**
     * @param array $product
     *
     * @return void
     */
    protected function setBrand(array $product): void
    {
        if (!isset($product[static::ATTRIBUTES][static::BRAND])) {
            return;
        }

        $this->enhancedEcommerceProductTransfer->setBrand($product[static::ATTRIBUTES][static::BRAND]);
    }

    /**
     * @param array $product
     *
     * @return string
     */
    protected function setDimension1(array $product): void
    {
        if (isset($product[static::ATTRIBUTES][static::ATTR_MODEL_UNTRANSLATED])) {
            $this->enhancedEcommerceProductTransfer->setDimension1($product[static::ATTRIBUTES][static::ATTR_SIZE_UNTRANSLATED]);

            return;
        }

        if (isset($product[static::ATTRIBUTES][static::ATTR_MODEL])) {
            $this->enhancedEcommerceProductTransfer->setDimension1($product[static::ATTRIBUTES][static::ATTR_SIZE]);

            return;
        }
    }

    /**
     * @param array $product
     *
     * @return void
     */
    protected function setQuantity(array $product): void
    {
        if (!array_key_exists(static::QUANTITY, $product) || !$product[static::QUANTITY]) {
            return;
        }

        $this->enhancedEcommerceProductTransfer->setQuantity($product[static::QUANTITY]);
    }

    /**
     * @param array $product
     *
     * @return void
     */
    protected function setPrice(array $product): void
    {
        if (!isset($product[static::PRICE])) {
            return;
        }

        $this->enhancedEcommerceProductTransfer->setPrice($product[static::PRICE]);
    }
}
