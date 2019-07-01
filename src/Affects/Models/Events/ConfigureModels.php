<?php declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Affects\Models\Events;

use Illuminate\Database\ConnectionResolverInterface;
use InvalidArgumentException;
use Tenancy\Affects\Models\Database\ConnectionResolver;
use Tenancy\Environment;
use Tenancy\Identification\Events\Switched;

class ConfigureModels
{
    /**
     * @var Switched
     */
    public $event;

    /**
     * Tenant connection resolver
     * @var ConnectionResolverInterface
     */
    public static $resolver;

    public function __construct(Switched $event)
    {
        $this->event = $event;
    }

    /**
     * Forces specific models to use the tenant connection.
     *
     * @param array $models
     * @param bool  $reset ; reset the connection back to the default if no tenant identified.
     * @return $this
     */
    public function onTenant(array $models, bool $reset = true)
    {
        $resolver = count($models) ? $this->getResolver($reset) : null;

        foreach ($models as $model) {
            if (!class_exists($model)) {
                throw new InvalidArgumentException("$model does not exist");
            }

            forward_static_call([$model, 'setConnectionResolver'], $resolver);
        }

        return $this;
    }

    protected function getResolver(bool $reset = true): ConnectionResolverInterface
    {
        $db = resolve('db');

        if ($reset && ! $this->event->tenant) {
            return $db;
        }

        if (static::$resolver) {
            return static::$resolver;
        }

        return new ConnectionResolver(Environment::getTenantConnectionName(), $db);
    }
}
