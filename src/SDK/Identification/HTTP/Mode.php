<?php

namespace Tenancy\SDK\Identification\HTTP;

use Tenancy\SDK\Identification\BaseMode;

class Mode extends BaseMode{
    /** @var string */
    const TENANTHOST  = 'tenantHost';

    /** @var string */
    const TENANTSUBDOMAIN = 'tenantSubdomain';
}
