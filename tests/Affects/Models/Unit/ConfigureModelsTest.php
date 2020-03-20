<?php

namespace Tenancy\Tests\Affects\Models\Unit;

use InvalidArgumentException;
use Tenancy\Affects\Models\Events\ConfigureModels;
use Tenancy\Affects\Models\Provider;
use Tenancy\Identification\Events\Switched;
use Tenancy\Tests\Affects\AffectsEventUnitTestCase;
use Tenancy\Tests\Mocks\ConnectionResolver;

class ConfigureModelsTest extends AffectsEventUnitTestCase
{
    protected $affectsProvider = Provider::class;

    protected $event = ConfigureModels::class;

    /** @test */
    public function static_calls_detect_not_existing_classes()
    {
        new ConfigureModels(new Switched($this->tenant));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Tenancy\Tests\Affects\Models\Unit\FakeModel does not exist');

        ConfigureModels::setConnectionResolver(
            [FakeModel::class],
            'setConnectionResolver',
            [new ConnectionResolver('sqlite', resolve('db'))]
        );
    }

    /** @test */
    public function forward_calls_detect_not_existing_classes()
    {
        $event = new ConfigureModels(new Switched($this->tenant));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Tenancy\Tests\Affects\Models\Unit\FakeModel does not exist');

        $event->setConnectionResolver(
            [FakeModel::class],
            [new ConnectionResolver('sqlite', resolve('db'))]
        );
    }
}
