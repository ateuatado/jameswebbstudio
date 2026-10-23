<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class DevelopmentToolbar extends \CodeIgniter\Filters\DebugToolbar
{
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Only local development hosts may receive debugging assets.
        $host = strtolower($request->getUri()->getHost());
        $local = in_array($host, ['localhost', '127.0.0.1', '::1'], true) || str_ends_with($host, '.test');
        if (ENVIRONMENT === 'development' && $local) {
            return parent::after($request, $response, $arguments);
        }
        return null;
    }
}
