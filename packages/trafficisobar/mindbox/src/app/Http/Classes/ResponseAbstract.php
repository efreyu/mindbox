<?php


namespace TrafficIsobar\Mindbox\app\Http\Classes;


abstract class ResponseAbstract
{
    protected $response;

    /*
     * @var int
     */
    protected $responseCode;
    /*
     * @var string
     */
    protected $responseMessage;

    public function __construct($response = false) {
        $this->response = $response;
    }

    /**
     * get all response as is exist
     * @return bool|mixed
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * @return bool
     */
    public function isSuccess() {
        return $this instanceof SuccessResponse;
    }

    /**
     * Успешная авторизация
     * @return bool
     */
    public function isAuthenticated(): bool {
        return $this->isSuccess() && $this->get('customer', 'processingStatus') == 'AuthenticationSucceeded';
    }

    /**
     * Не верный пароль
     * @return bool
     */
    public function isNotAuthenticated(): bool {
        return $this->isSuccess() && $this->get('customer', 'processingStatus') == 'AuthenticationFailed';
    }

    /**
     * Потребитель не найден
     * @return bool
     */
    public function isNotFound(): bool {
        return $this->isSuccess() && $this->get('customer', 'processingStatus') == 'NotFound';
    }

    /**
     * @return bool
     */
    public function isFound(): bool {
        return $this->isSuccess() && $this->get('customer', 'processingStatus') == 'Found';
    }

    /**
     * @return bool
     */
    public function isCreated(): bool {
        return $this->isSuccess() && $this->get('customer', 'processingStatus') == 'Created';
    }

    /**
     * @return bool
     */
    public function isChanged(): bool {
        return $this->isSuccess() && $this->get('customer', 'processingStatus') == 'Changed';
    }

    /**
     * @return array
     */
    public function getReason(): array {
        return [
            'code' => $this->responseCode,
            'data' => ['message' => $this->responseMessage]
        ];
    }

    /**
     * @return int
     */
    public function getStatusCode(): int {
        return $this->responseCode;
    }

    /**
     * @return string
     */
    public function getStatusMessage(): string {
        return $this->responseMessage;
    }

    /**
     * Method for fast access to data
     * for example: $this->get('customer', 'ids', 'mindboxId')
     * @param mixed ...$args
     * @return bool
     */
    public function get(...$args) {
        $temporaryArray = $this->response;
        foreach ($args as $item) {
            if ($this->checkSignature($item, $temporaryArray)) {
                $temporaryArray = $temporaryArray[$item];
            } else {
                return false;
            }
        }

        return $temporaryArray ?: false;
    }

    /**
     * Safe array access
     * @param $needle string
     * @param $haystack mixed
     * @return bool
     */
    protected function checkSignature($needle, $haystack): bool {
        return is_array($haystack) && array_key_exists($needle, $haystack) && !empty($haystack[$needle]);
    }
}
