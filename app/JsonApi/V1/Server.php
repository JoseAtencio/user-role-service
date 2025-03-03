<?php

namespace App\JsonApi\V1;

use LaravelJsonApi\Core\Server\Server as BaseServer;

class Server extends BaseServer
{

    /**
     * The base URI namespace for this server.
     *
     * @var string
     */
    protected string $baseUri = '/api/v1';

    /**
     * Bootstrap the server when it is handling an HTTP request.
     *
     * @return void
     */
    public function serving(): void
    {
        // no-op
    }

    /**
     * Get the server's list of schemas.
     *
     * @return array
     */
    protected function allSchemas(): array
    {
        return [
            \App\JsonApi\V1\Users\UserSchema::class,
            \App\JsonApi\V1\Notifies\NotifySchema::class,
            \App\JsonApi\V1\Enterprises\EnterpriseSchema::class,
            \App\JsonApi\V1\Activities\ActivitySchema::class,
        ];
    }
}
