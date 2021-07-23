<?php

namespace src\Controller;

abstract class BaseController
{
    /**
     * @param array $errors
     * @return array
     */
    protected function unprocessableEntityResponse(array $errors): array
    {
        return [
            'message' => $errors,
            'code' => 400,
            'data' => []
        ];
    }

    /**
     * @return array
     */
    protected function notFoundResponse(): array
    {
        return [
            'code' => 'HTTP/1.1 404 Not Found',
            'data' => []
        ];
    }
}