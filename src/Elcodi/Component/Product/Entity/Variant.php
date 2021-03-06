<?php

/**
 * This file is part of the Elcodi package.
 *
 * Copyright (c) 2014 Elcodi.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @author Aldo Chiecchia <zimage@tiscali.it>
 */

namespace Elcodi\Component\Product\Entity;

use Doctrine\Common\Collections\Collection;

use Elcodi\Component\Attribute\Entity\Interfaces\ValueInterface;
use Elcodi\Component\Core\Entity\Abstracts\AbstractEntity;
use Elcodi\Component\Core\Entity\Traits\DateTimeTrait;
use Elcodi\Component\Core\Entity\Traits\EnabledTrait;
use Elcodi\Component\Media\Entity\Traits\ImagesContainerTrait;
use Elcodi\Component\Media\Entity\Traits\PrincipalImageTrait;
use Elcodi\Component\Product\Entity\Interfaces\ProductInterface;
use Elcodi\Component\Product\Entity\Interfaces\VariantInterface;
use Elcodi\Component\Product\Entity\Traits\ProductPriceTrait;

/**
 * Class Variant
 *
 * A Product variant is a specific combination of finite options
 * for a given Product. The multiplicity of attribute/options define
 * a "tuple" of a product and its related options such that a specific
 * combination is univocally determined.
 *
 * A Variant will normally have a different SKU than its parent product,
 * so it can have independent stock and pricing informations.
 */
class Variant extends AbstractEntity implements VariantInterface
{
    use
        ProductPriceTrait,
        EnabledTrait,
        DateTimeTrait,
        ImagesContainerTrait,
        PrincipalImageTrait;

    /**
     * @var string
     *
     * Product SKU
     */
    protected $sku;

    /**
     * @var integer
     *
     * Stock available
     */
    protected $stock;

    /**
     * @var ProductInterface
     *
     * Parent product
     */
    protected $product;

    /**
     * @var Collection
     *
     * Collection of possible options for this product
     */
    protected $options;

    /**
     * Gets the variant SKU
     *
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Sets the variant SKU
     *
     * @param string $sku
     *
     * @return VariantInterface
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Gets the variant stock
     *
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Sets the variant stock
     *
     * @param int $stock
     *
     * @return $this self Object
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Gets this variant option values
     *
     * @return Collection
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Sets this variant option values
     *
     * @param Collection $options
     *
     * @return VariantInterface
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Adds an option to this variant
     *
     * Passed option Attribute is also added to the attribute collection
     * of the parent Product.
     *
     * If Variant::product is not set or does not implement ProductInterface
     * a LogicException is thrown: presence of the parent product is mandatory
     * since adding an Option to a Variant also updates the Parent product
     * Attribute collection. This way Variant::options and Product::attributes
     * are synchronized
     *
     * @param ValueInterface $option
     *
     * @throws \LogicException
     *
     * @return VariantInterface
     */
    public function addOption(ValueInterface $option)
    {
        if (!$this->product instanceof ProductInterface) {
            throw new \LogicException('Cannot add options to a Variant before setting a parent Product');
        }

        $this->options->add($option);
        $this->product->addAttribute($option->getAttribute());

        return $this;
    }

    /**
     * Removes an option from this variant
     *
     * @param ValueInterface $option
     *
     * @return VariantInterface
     */
    public function removeOption(ValueInterface $option)
    {
        $this->options->removeElement($option);

        return $this;
    }

    /**
     * Gets parent product
     *
     * @return ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Sets parent product
     *
     * @param ProductInterface $product
     *
     * @return VariantInterface
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }
}
