<?php

declare(strict_types=1);

namespace App\Order;

use Ds\Map;
use Ds\Set;

class Workflow
{
    private Status $status;

    private \DateTimeImmutable $changedAt;

    private Map $transitionMap;

    public function __construct()
    {
        $this->status = Status::created();
        $this->changedAt = new \DateTimeImmutable();
        $this->transitionMap = $this->buildTransitionMap();
    }

    private function buildTransitionMap(): Map
    {
        $map = new Map();
        $map->put(Status::created(), new Set([Status::checked(), Status::canceled()]));
        $map->put(Status::checked(), new Set([Status::delivered(), Status::canceled()]));
        $map->put(Status::delivered(), new Set([Status::completed(), Status::canceled()]));

        return $map;
    }

    public function getChangedAt(): \DateTimeImmutable
    {
        return $this->changedAt;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function isInStatus(Status $status): bool
    {
        return $this->getStatus()->equals($status);
    }

    public function transitTo(Status $status): void
    {
        if (!$this->canTransitTo($status)) {
            throw new \RuntimeException("Can not transit status from {$this->getStatus()->getRawValue()} to {$status->getRawValue()}");
        }
        $this->setStatus($status);
    }

    final protected function setStatus(Status $status)
    {
        $this->status = $status;
        $this->registerChange();
    }

    private function registerChange(): void
    {
        $this->changedAt = new \DateTimeImmutable();
    }

    public function canTransitTo(Status $status): bool
    {
        try {
            /** @var Set $transitionStatusSet */
            $transitionStatusSet = $this->getTransitionMap()->get($this->getStatus());
            return $transitionStatusSet->contains($status);
        } catch (\OutOfBoundsException $e) {
            return false;
        }
    }

    private function getTransitionMap(): Map
    {
        return $this->transitionMap;
    }
}