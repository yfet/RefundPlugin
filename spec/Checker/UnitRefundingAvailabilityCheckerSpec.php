<?php

declare(strict_types=1);

namespace spec\Sylius\RefundPlugin\Checker;

use PhpSpec\ObjectBehavior;
use Sylius\RefundPlugin\Checker\UnitRefundingAvailabilityCheckerInterface;
use Sylius\RefundPlugin\Model\RefundType;
use Sylius\RefundPlugin\Provider\RemainingTotalProviderInterface;

final class UnitRefundingAvailabilityCheckerSpec extends ObjectBehavior
{
    public function let(
        RemainingTotalProviderInterface $remainingTotalProvider
    ): void {
        $this->beConstructedWith($remainingTotalProvider);
    }

    public function it_implements_unit_refunding_availability_checker_interface(): void
    {
        $this->shouldImplement(UnitRefundingAvailabilityCheckerInterface::class);
    }

    public function it_returns_false_if_remaining_unit_total_is_0(
        RemainingTotalProviderInterface $remainingTotalProvider
    ): void {
        $type = RefundType::orderItemUnit();

        $remainingTotalProvider->getTotalLeftToRefund(1, $type)->willReturn(0);

        $this->__invoke(1, $type)->shouldReturn(false);
    }

    public function it_returns_true_if_remaining_unit_total_is_more_than_0(
        RemainingTotalProviderInterface $remainingTotalProvider
    ): void {
        $type = RefundType::shipment();

        $remainingTotalProvider->getTotalLeftToRefund(1, $type)->willReturn(100);

        $this->__invoke(1, $type)->shouldReturn(true);
    }
}
