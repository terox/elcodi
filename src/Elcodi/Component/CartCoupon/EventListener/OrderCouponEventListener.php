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

namespace Elcodi\Component\CartCoupon\EventListener;

use Doctrine\Common\Persistence\ObjectManager;

use Elcodi\Component\CartCoupon\Event\OrderCouponOnApplyEvent;
use Elcodi\Component\CartCoupon\Factory\OrderCouponFactory;
use Elcodi\Component\Coupon\EventDispatcher\CouponEventDispatcher;

/**
 * Class OrderCouponEventListener
 *
 * This eventListener is subscribed into OpenCoupon events.
 */
class OrderCouponEventListener
{
    /**
     * @var ObjectManager
     *
     * cartCouponObjectManager
     */
    protected $orderCouponObjectManager;

    /**
     * @var CouponEventDispatcher
     *
     * CouponEventDispatcher
     */
    protected $couponEventDispatcher;

    /**
     * @var OrderCouponFactory
     *
     * orderCouponFactory
     */
    protected $orderCouponFactory;

    /**
     * construct method
     *
     * @param ObjectManager         $orderCouponObjectManager OrderCoupon ObjectManager
     * @param CouponEventDispatcher $couponEventDispatcher    CouponEventDispatcher
     * @param OrderCouponFactory    $orderCouponFactory       OrderCoupon factory
     */
    public function __construct(
        ObjectManager $orderCouponObjectManager,
        CouponEventDispatcher $couponEventDispatcher,
        OrderCouponFactory $orderCouponFactory
    )
    {
        $this->orderCouponObjectManager = $orderCouponObjectManager;
        $this->couponEventDispatcher = $couponEventDispatcher;
        $this->orderCouponFactory = $orderCouponFactory;
    }

    /**
     * Event subscribed on OrderCoupon applied into an order.
     *
     * Just should create a new OrderCoupon instance, persist and flush it
     *
     * Also notifies to CouponBundle that a simple coupon has been
     * used by an Order.
     *
     * @param OrderCouponOnApplyEvent $event Event
     */
    public function onOrderCouponApply(OrderCouponOnApplyEvent $event)
    {
        $order = $event->getOrder();
        $coupon = $event->getCoupon();

        $orderCoupon = $this->orderCouponFactory->create();
        $orderCoupon
            ->setOrder($order)
            ->setCoupon($coupon)
            ->setAmount($coupon->getAbsolutePrice())
            ->setName($coupon->getName())
            ->setCode($coupon->getCode());

        $this->orderCouponObjectManager->persist($orderCoupon);
        $this->orderCouponObjectManager->flush($orderCoupon);

        $this
            ->couponEventDispatcher
            ->notifyCouponUsage($coupon);
    }
}
