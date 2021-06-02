<?php

declare(strict_types=1);

namespace spec\Sylius\RefundPlugin\Provider;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\RefundPlugin\Provider\RefundedShipmentFeeProviderInterface;

final class RefundedShipmentFeeProviderSpec extends ObjectBehavior
{
    public function let(RepositoryInterface $adjustmentRepository): void
    {
        $this->beConstructedWith($adjustmentRepository);
    }

    public function it_implements_refunded_shipment_fee_provider_interface()
    {
        $this->shouldImplement(RefundedShipmentFeeProviderInterface::class);
    }

    public function it_returns_fee_from_shipping_adjustment(
        RepositoryInterface $adjustmentRepository,
        AdjustmentInterface $shippingAdjustment
    ): void {
        $adjustmentRepository->find(1)->willReturn($shippingAdjustment);
        $shippingAdjustment->getType()->willReturn(AdjustmentInterface::SHIPPING_ADJUSTMENT);
        $shippingAdjustment->getAmount()->willReturn(1000);

        $this->getFeeOfShipment(1)->shouldReturn(1000);
    }

    public function it_throws_exception_if_there_is_no_adjustment_with_given_id(
        RepositoryInterface $adjustmentRepository
    ): void {
        $adjustmentRepository->find(1)->willReturn(null);

        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during('getFeeOfShipment', [1])
        ;
    }

    public function it_throws_exception_if_adjustment_is_not_shipping_adjustment(
        RepositoryInterface $adjustmentRepository,
        AdjustmentInterface $adjustment
    ): void {
        $adjustmentRepository->find(1)->willReturn($adjustment);
        $adjustment->getType()->willReturn('some_other_type');

        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during('getFeeOfShipment', [1])
        ;
    }
}
