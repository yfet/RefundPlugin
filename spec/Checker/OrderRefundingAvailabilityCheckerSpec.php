<?php

declare(strict_types=1);

namespace spec\Sylius\RefundPlugin\Checker;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\RefundPlugin\Checker\OrderRefundingAvailabilityCheckerInterface;

final class OrderRefundingAvailabilityCheckerSpec extends ObjectBehavior
{
    public function let(OrderRepositoryInterface $orderRepository): void
    {
        $this->beConstructedWith($orderRepository);
    }

    public function it_implements_order_refunding_availability_checker_interface(): void
    {
        $this->shouldImplement(OrderRefundingAvailabilityCheckerInterface::class);
    }

    public function it_returns_true_if_order_is_paid_and_not_free(
        OrderRepositoryInterface $orderRepository,
        OrderInterface $order
    ): void {
        $orderRepository->findOneByNumber('00000007')->willReturn($order);
        $order->getPaymentState()->willReturn(OrderPaymentStates::STATE_PAID);
        $order->getTotal()->willReturn(100);

        $this('00000007')->shouldReturn(true);
    }

    public function it_returns_true_if_order_is_partially_refunded_and_not_free(
        OrderRepositoryInterface $orderRepository,
        OrderInterface $order
    ): void {
        $orderRepository->findOneByNumber('00000007')->willReturn($order);
        $order->getPaymentState()->willReturn(OrderPaymentStates::STATE_PARTIALLY_REFUNDED);
        $order->getTotal()->willReturn(100);

        $this('00000007')->shouldReturn(true);
    }

    public function it_returns_false_if_order_is_in_other_state_and_not_free(
        OrderRepositoryInterface $orderRepository,
        OrderInterface $order
    ): void {
        $orderRepository->findOneByNumber('00000007')->willReturn($order);
        $order->getPaymentState()->willReturn(OrderPaymentStates::STATE_AWAITING_PAYMENT);
        $order->getTotal()->willReturn(100);

        $this('00000007')->shouldReturn(false);
    }

    public function it_returns_false_if_order_is_free(
        OrderRepositoryInterface $orderRepository,
        OrderInterface $order
    ): void {
        $orderRepository->findOneByNumber('00000007')->willReturn($order);
        $order->getPaymentState()->willReturn(OrderPaymentStates::STATE_PAID);
        $order->getTotal()->willReturn(0);

        $this('00000007')->shouldReturn(false);
    }

    public function it_returns_false_if_order_is_partially_refunded_and_free(
        OrderRepositoryInterface $orderRepository,
        OrderInterface $order
    ): void {
        $orderRepository->findOneByNumber('00000007')->willReturn($order);
        $order->getPaymentState()->willReturn(OrderPaymentStates::STATE_PARTIALLY_REFUNDED);
        $order->getTotal()->willReturn(0);

        $this('00000007')->shouldReturn(false);
    }

    public function it_returns_false_if_order_is_in_other_state_and_free(
        OrderRepositoryInterface $orderRepository,
        OrderInterface $order
    ): void {
        $orderRepository->findOneByNumber('00000007')->willReturn($order);
        $order->getPaymentState()->willReturn(OrderPaymentStates::STATE_AWAITING_PAYMENT);
        $order->getTotal()->willReturn(0);

        $this('00000007')->shouldReturn(false);
    }
}
