<?php

namespace Tenancy\Misc\Help\Contracts;

interface Package {
    public function isInstalled(): bool;
    public function isRegistered(): bool;
    public function isConfigured(): bool;
    public function getName(): string;
}
