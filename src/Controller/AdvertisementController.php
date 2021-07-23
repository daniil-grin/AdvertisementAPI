<?php

namespace src\Controller;

use src\Repository\AdvertisementRepository;

class AdvertisementController extends BaseController
{
    private $personGateway;

    public function __construct($db)
    {
        $this->personGateway = new AdvertisementRepository($db);
    }

    public function processRequest($requestMethod, $uriArray)
    {
        switch ($requestMethod) {
            case 'POST':
                if (isset($uriArray[2])) {
                    $response = $this->updateAdvertisementFromRequest($uriArray[2]);
                } else {
                    $response = $this->createAdvertisementFromRequest();
                }
                break;
            case 'GET':
                if (isset($uriArray[2]) && $uriArray[2] === 'relevant') {
                    $response = $this->getRelevant();
                }
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        echo json_encode(array(
            'message' => $response['message'],
            'code' => $response['code'],
            'data' => $response['data']
        ), JSON_UNESCAPED_UNICODE);
    }

    private function getRelevant(): array
    {
        $result = $this->personGateway->getRelevant();
        if (!$result) {
            return $this->notFoundResponse();
        } else {
            $this->personGateway->addImpressions($result['id']);
        }

        return [
            'code' => 200,
            'message' => 'OK',
            'data' => $result
        ];
    }

    private function createAdvertisementFromRequest(): array
    {
        $errors = $this->validateAdvertisement();
        if (!empty($errors)) {
            return $this->unprocessableEntityResponse($errors);
        }
        $result = $this->personGateway->insert();

        return [
            'message' => 'OK',
            'code' => 200,
            'data' => $result
        ];
    }

    private function updateAdvertisementFromRequest($id): array
    {
        $result = $this->personGateway->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $errors = $this->validateAdvertisement();
        if (!empty($errors)) {
            return $this->unprocessableEntityResponse($errors);
        }
        $result = $this->personGateway->update($id);

        return [
            'code' => 200,
            'message' => 'OK',
            'data' => $result
        ];
    }

    /**
     * @return array
     */
    private function validateAdvertisement(): array
    {
        $errors = [];
        $bannerHeaders = get_headers($_POST['banner'] ?? '', 1);
        if (strpos($bannerHeaders['Content-Type'], 'image/') === false) {
            $errors[] = 'Invalid banner link';
        }
        if (!isset($_POST['text'])) {
            $errors[] = 'Invalid title';
        }
        if (!isset($_POST['limit'])) {
            $errors[] = 'Invalid limit';
        }
        if (!isset($_POST['price'])) {
            $errors[] = 'Invalid price';
        }
        return $errors;
    }
}