<?php

declare(strict_types=1);

namespace spec\Sylius\RefundPlugin\Checker;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\RefundPlugin\Checker\OrderFullyRefundedTotalChecker;
use Sylius\RefundPlugin\Checker\OrderFullyRefundedTotalCheckerInterface;
use Sylius\RefundPlugin\Provider\OrderRefundedTotalProviderInterface;

final class OrderFullyRefundedTotalCheckerSpec extends ObjectBehavior
{
    public function let(OrderRefundedTotalProviderInterface $orderRefundedTotalProvider): void
    {
        $this->beConstructedWith($orderRefundedTotalProvider);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(OrderFullyRefundedTotalChecker::class);
    }

    public function it_implements_order_fully_refunded_total_checker_interface(): void
    {
        $this->shouldImplement(OrderFullyRefundedTotalCheckerInterface::class);
    }

    public function it_returns_false_if_order_refunded_total_is_lower_than_order_total(
        OrderInterface $order,
        OrderRefundedTotalProviderInterface $orderRefundedTotalProvider
    ): void {
        $order->getTotal()->willReturn(1000);
        $order->getNumber()->willReturn('0000001');

        $orderRefundedTotalProvider->__invoke('0000001')->willReturn(500);

        $this->isOrderFullyRefunded($order)->shouldReturn(false);
    }

    public function it_returns_true_if_order_refunded_total_is_equal_to_order_total(
        OrderInterface $order,
        OrderRefundedTotalProviderInterface $orderRefundedTotalProvider
    ): void {
        $order->getTotal()->willReturn(1000);
        $order->getNumber()->willReturn('0000001');

        $orderRefundedTotalProvider->__invoke('0000001')->willReturn(1000);

        $this->isOrderFullyRefunded($order)->shouldReturn(true);
    }
}
