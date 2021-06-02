<?php

declare(strict_types=1);

namespace spec\Sylius\RefundPlugin\ProcessManager;

use PhpSpec\ObjectBehavior;
use Sylius\RefundPlugin\Command\GenerateCreditMemo;
use Sylius\RefundPlugin\Event\UnitsRefunded;
use Sylius\RefundPlugin\Model\OrderItemUnitRefund;
use Sylius\RefundPlugin\Model\ShipmentRefund;
use Sylius\RefundPlugin\ProcessManager\UnitsRefundedProcessStepInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class CreditMemoProcessManagerSpec extends ObjectBehavior
{
    public function let(MessageBusInterface $commandBus): void
    {
        $this->beConstructedWith($commandBus);
    }

    public function it_implements_units_refunded_process_step_interface(): void
    {
        $this->shouldImplement(UnitsRefundedProcessStepInterface::class);
    }

    public function it_reacts_on_units_generated_event_and_dispatch_generate_credit_memo_command(MessageBusInterface $commandBus)
    {
        $unitRefunds = [new OrderItemUnitRefund(1, 1000), new OrderItemUnitRefund(3, 2000), new OrderItemUnitRefund(5, 3000)];
        $shipmentRefunds = [new ShipmentRefund(1, 500), new ShipmentRefund(2, 1000)];

        $command = new GenerateCreditMemo('000222', 3000, $unitRefunds, $shipmentRefunds, 'Comment');
        $commandBus->dispatch($command)->willReturn(new Envelope($command))->shouldBeCalled();

        $this->next(new UnitsRefunded('000222', $unitRefunds, $shipmentRefunds, 1, 3000, 'USD', 'Comment'));
    }
}
