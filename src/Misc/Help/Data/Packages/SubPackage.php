<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Misc\Help\Data\Packages;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

abstract class SubPackage extends ComposerPackage
{
    protected $events = [];
    protected $requiredEvents = [];
    protected $recommendedEvents = [];

    abstract public function getSubsection(): string;

    public function getName(): string
    {
        return 'tenancy/'.Str::kebab($this->getSubsection().$this->getPurpose());
    }

    public function getPurpose()
    {
        return basename(get_class($this));
    }

    public function getNamespace()
    {
        return parent::getNamespace().'\\'.$this->getSubsection().'\\'.$this->getPurpose();
    }

    public function getEvents()
    {
        return $this->formatEvents($this->events);
    }

    public function formatEvents(array $events)
    {
        return array_map(function (string $event) {
            return $this->getNamespace().'\\'.$event;
        }, $events);
    }

    public function getRecommendedEvents()
    {
        $events = $this->getRequiredEvents();

        if (!empty($this->recommendedEvents)) {
            $events = $this->recommendedEvents;
        }

        return $this->formatEvents($events);
    }

    public function getRequiredEvents()
    {
        $events = $this->events;

        if (!empty($this->requiredEvents)) {
            $events = $this->requiredEvents;
        }

        return $this->formatEvents($events);
    }

    public function getProviders()
    {
        return [
            $this->getNamespace().'\\Provider',
        ];
    }

    public function isConfigured(): bool
    {
        $dispatcher = App::make(Dispatcher::class);

        foreach ($this->getRequiredEvents() as $event) {
            if (!$dispatcher->hasListeners($event)) {
                return false;
            }
        }

        return true;
    }
}
