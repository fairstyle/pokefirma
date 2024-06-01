<?php

namespace App\Libraries;

use CodeIgniter\HTTP\ResponseInterface;

class FirmaAPI
{

    protected $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    protected function getCurrentTime(): string
    {
        return date('Y-m-d H:i:s');
    }

    public function isValidOrResponse(mixed $param, array $data = []): bool|ResponseInterface
    {
        $isValid = true;

        if(isset($param)) {
            if(is_array($param)) {
                foreach ($param as $value) {
                    if(!isset($value) || $value === "")
                        $isValid = false;
                }
            } else
                $isValid = !empty($param);
        }

        if($isValid)
            return true;

        return $this->response->setJSON([
            'code' => $this->response::HTTP_BAD_REQUEST,
            'message' => "Faltan Parametros",
            'data' => $data,
            'current_time' => $this->getCurrentTime()
        ], $this->response::HTTP_BAD_REQUEST);
    }

    public function defaultResponseBadRequest(array $data = []): ResponseInterface
    {
        return $this->response->setJSON([
            'code' => $this->response::HTTP_BAD_REQUEST,
            'message' => "Bad Request",
            'data' => $data,
            'current_time' => $this->getCurrentTime()
        ], $this->response::HTTP_BAD_REQUEST);
    }

    public function defaultResponseOk(array|\stdClass $data = []): ResponseInterface
    {
        return $this->response->setJSON([
            'code' => $this->response::HTTP_OK,
            'message' => "Ok",
            'data' => $data,
            'current_time' => $this->getCurrentTime()
        ], $this->response::HTTP_OK);
    }

    public function defaultResponseNoContent(array $data = []): ResponseInterface
    {
        return $this->response->setJSON([
            'code' => $this->response::HTTP_NO_CONTENT,
            'message' => "No Content",
            'data' => $data,
            'current_time' => $this->getCurrentTime()
        ], $this->response::HTTP_NO_CONTENT);
    }

    public function doRequest(string $method = "GET", string $url = "", array $data = []): bool|string
    {
        $options = [
            'http' => [
                'method'  => $method,
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($data)
            ],
        ];

        return file_get_contents($url, false, stream_context_create($options));
    }

    public function getPost(string $name): string|array|null
    {
        $data = json_decode(file_get_contents('php://input'));
        return $data->$name ?? null;
    }

    public function getGet(string|null $name = null): string|array|null
    {
        return $name !== null ? ($_GET[$name] ?? null) : $_GET;
    }

}