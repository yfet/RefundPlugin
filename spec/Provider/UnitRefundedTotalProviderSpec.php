<?php

declare(strict_types=1);

namespace spec\Sylius\RefundPlugin\Provider;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\RefundPlugin\Entity\RefundInterface;
use Sylius\RefundPlugin\Model\RefundType;
use Sylius\RefundPlugin\Provider\UnitRefundedTotalProviderInterface;

final class UnitRefundedTotalProviderSpec extends ObjectBehavior
{
    public function let(RepositoryInterface $refundRepository): void
    {
        $this->beConstructedWith($refundRepository);
    }

    public function it_implements_unit_refunded_total_provider_interface(): void
    {
        $this->shouldImplement(UnitRefundedTotalProviderInterface::class);
    }

    public function it_returns_refunded_total_of_unit_with_id(
        RepositoryInterface $refundRepository,
        RefundInterface $firstRefund,
        RefundInterface $secondRefund
    ): void {
        $refundType = RefundType::orderItemUnit();

        $refundRepository
            ->findBy(['refundedUnitId' => 1, 'type' => $refundType->__toString()])
            ->willReturn([$firstRefund, $secondRefund])
        ;

        $firstRefund->getAmount()->willReturn(1000);
        $secondRefund->getAmount()->willReturn(3000);

        $this->__invoke(1, $refundType)->shouldReturn(4000);
    }
}
