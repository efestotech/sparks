<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;
use Config\Services;

class ApiAuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during normal execution.
     * However, in the event of a problem, it should return an instance
     * of CodeIgniter\HTTP\Response. If it does, script execution will be
     * halted and that response will be sent back to the client, allowing
     * for error pages, redirects, etc.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (empty($authHeader) || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return Services::response()
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                ->setJSON([
                    'success' => false,
                    'error'   => [
                        'code'    => 'UNAUTHORIZED',
                        'message' => 'Missing or invalid Authorization header'
                    ]
                ]);
        }

        $apiKey    = $matches[1];
        $userModel = new UserModel();
        $user      = $userModel->findByApiKey($apiKey);

        if (!$user) {
            return Services::response()
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                ->setJSON([
                    'success' => false,
                    'error'   => [
                        'code'    => 'INVALID_API_KEY',
                        'message' => 'The provided API Key is invalid or inactive'
                    ]
                ]);
        }

        // Store user in request for controller access
        $request->user = $user;
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
