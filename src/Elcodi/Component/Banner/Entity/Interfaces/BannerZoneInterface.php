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

namespace Elcodi\Component\Banner\Entity\Interfaces;

use Doctrine\Common\Collections\Collection;

use Elcodi\Component\Language\Entity\Interfaces\LanguageInterface;

/**
 * BannerZoneInterfaceInterface
 */
interface BannerZoneInterface
{
    /**
     * Set banner name
     *
     * @param string $name Name of the banner
     *
     * @return $this self Object
     */
    public function setName($name);

    /**
     * Get banner name
     *
     * @return string Name
     */
    public function getName();

    /**
     * Set code
     *
     * @param string $code
     *
     * @return $this self Object
     */
    public function setCode($code);

    /**
     * Get code
     *
     * @return string Code
     */
    public function getCode();

    /**
     * Set Language
     *
     * @param LanguageInterface $language Language to set
     *
     * @return $this self Object
     */
    public function setLanguage(LanguageInterface $language = null);

    /**
     * Get shops
     *
     * @return LanguageInterface Language
     */
    public function getLanguage();

    /**
     * Add banner into banner zone
     *
     * @param BannerInterface $banner Banner
     *
     * @return $this self Object
     */
    public function addBanner(BannerInterface $banner);

    /**
     * Remove banner from banner zone
     *
     * @param BannerInterface $banner Banner
     *
     * @return $this self Object
     */
    public function removeBanner(BannerInterface $banner);

    /**
     * Set banners into banner zone
     *
     * @param Collection $banners Banners
     *
     * @return $this self Object
     */
    public function setBanners(Collection $banners);

    /**
     * Get banners
     *
     * @return Collection Banners
     */
    public function getBanners();

    /**
     * Set the BannerZoneInterface height in pixels
     *
     * @param float $height Height
     *
     * @return $this self Object
     */
    public function setHeight($height);

    /**
     * Get the BannerZoneInterface height in pixels
     *
     * @return float Height
     */
    public function getHeight();

    /**
     * Set the BannerZoneInterface width in pixels
     *
     * @param float $width Width
     *
     * @return $this self Object
     */
    public function setWidth($width);

    /**
     * Get the BannerZoneInterface width in pixels
     *
     * @return float Width
     */
    public function getWidth();
}
