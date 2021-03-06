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

namespace Elcodi\Component\Cart\Tests\UnitTest\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit_Framework_TestCase;

use Elcodi\Component\Cart\Entity\Cart;
use Elcodi\Component\Cart\Entity\Interfaces\OrderInterface;
use Elcodi\Component\Cart\EventDispatcher\OrderEventDispatcher;
use Elcodi\Component\Cart\Factory\OrderFactory;
use Elcodi\Component\Cart\Services\OrderLineManager;
use Elcodi\Component\Cart\Services\OrderManager;
use Elcodi\Component\Cart\Transformer\CartLineOrderLineTransformer;
use Elcodi\Component\Cart\Transformer\CartOrderTransformer;
use Elcodi\Component\Currency\Entity\Interfaces\CurrencyInterface;
use Elcodi\Component\Currency\Entity\Money;
use Elcodi\Component\User\Entity\Customer;

/**
 * Class CartOrderTransformerTest
 */
class CartOrderTransformerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var CartOrderTransformer
     *
     * CartOrderTransformer
     */
    protected $cartOrderTransformer;

    /**
     * @var OrderFactory
     *
     * Order factory
     */
    protected $orderFactory;

    /**
     * @var CartLineOrderLineTransformer
     *
     * CartLineOrderLineTransformer
     */
    protected $cartLineOrderLineTransformer;

    /**
     * Setup
     */
    public function setUp()
    {
        parent::setUp();

        /**
         * @var OrderEventDispatcher         $orderEventDispatcher
         * @var CartLineOrderLineTransformer $cartLineOrderLineTransformer
         * @var OrderLineManager             $orderLineManager
         * @var OrderFactory                 $orderFactory
         * @var OrderManager                 $orderManager
         */
        $orderEventDispatcher = $this
            ->getMock(
                'Elcodi\Component\Cart\EventDispatcher\OrderEventDispatcher',
                [], [], '', false
            );

        $orderFactory = $this->getMock('Elcodi\Component\Cart\Factory\OrderFactory');
        $cartLineOrderLineTransformer = $this->getMock(
            'Elcodi\Component\Cart\Transformer\CartLineOrderLineTransformer',
            [], [], '', false
        );

        $cartOrderTransformer = new CartOrderTransformer(
            $orderEventDispatcher,
            $cartLineOrderLineTransformer,
            $orderFactory
        );

        $this->orderFactory = $orderFactory;
        $this->cartOrderTransformer = $cartOrderTransformer;
        $this->cartLineOrderLineTransformer = $cartLineOrderLineTransformer;
    }

    /**
     * test Create Order from Cart
     *
     * @group order
     */
    public function testCreateOrderFromCartNewOrder()
    {
        /**
         * @var OrderInterface    $order
         * @var CurrencyInterface $currency
         */
        $order = $this->getMock('Elcodi\Component\Cart\Entity\Order', null);
        $currency = $this->getMock('Elcodi\Component\Currency\Entity\Currency', null);
        $currency->setIso('EUR');

        $this
            ->orderFactory
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue($order));

        $this
            ->cartLineOrderLineTransformer
            ->expects($this->any())
            ->method('createOrderLinesByCartLines')
            ->will($this->returnValue(new ArrayCollection));

        $customer = new Customer();
        $cart = new Cart();
        $cart
            ->setCustomer($customer)
            ->setQuantity(10)
            ->setProductAmount(Money::create(20, $currency))
            ->setAmount(Money::create(20, $currency))
            ->setCartLines(new ArrayCollection);

        $this
            ->cartOrderTransformer
            ->createOrderFromCart($cart);
    }

    /**
     * test Create Order from Cart
     *
     * @group order
     */
    public function testCreateOrderFromCartNewOrderExistingOrder()
    {
        /**
         * @var OrderInterface    $order
         * @var CurrencyInterface $currency
         */
        $order = $this->getMock('Elcodi\Component\Cart\Entity\Order', null);
        $currency = $this->getMock('Elcodi\Component\Currency\Entity\Currency', null);
        $currency->setIso('EUR');

        $this
            ->orderFactory
            ->expects($this->never())
            ->method('create')
            ->will($this->returnValue($order));

        $this
            ->cartLineOrderLineTransformer
            ->expects($this->any())
            ->method('createOrderLinesByCartLines')
            ->will($this->returnValue(new ArrayCollection));

        $customer = new Customer();
        $cart = new Cart();
        $cart
            ->setCustomer($customer)
            ->setQuantity(10)
            ->setProductAmount(Money::create(20, $currency))
            ->setOrder($order)
            ->setAmount(Money::create(20, $currency))
            ->setCartLines(new ArrayCollection);

        $this
            ->cartOrderTransformer
            ->createOrderFromCart($cart);
    }
}
