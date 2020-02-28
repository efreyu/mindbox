<?php


namespace TrafficIsobar\Mindbox\app\Http\Classes;

use TrafficIsobar\Mindbox\app\Http\Classes\ResponseAbstract;

class ErrorResponse extends ResponseAbstract
{
    /**
     * ErrorResponse constructor.
     * @param bool $response
     */
    public function __construct($response = false)
    {
        parent::__construct($response);
        $this->handle();
    }

    protected function handle(): void {
        $message = [];
        if ($this->checkSignature('status', $this->response)) {
            switch ($this->response['status']) {
                case 'ValidationError':
                    {
                        if ($this->checkSignature('validationMessages', $this->response)) {
                            foreach ($this->response['validationMessages'] as $error) {
                                $message[] = $error['message'];
                            }
                        }
                        $this->responseCode = 400;
                        $this->responseMessage = (!empty($message) && is_array($message)) ? implode(' ', $message) : '';
                    }
                    break;
                case 'ProtocolError':
                    $this->responseCode = 500;
                    $this->responseMessage = !empty($this->response['errorMessage']) ? (string)$this->response['errorMessage'] : '';
                    break;
                case 'InternalServerError':
                    $this->responseCode = 503;
                    $this->responseMessage = 'CRM не доступна';
                    break;
            }
        }
        if (empty($this->responseCode) || empty($this->responseMessage)) {
            $this->responseCode = 503;
            $this->responseMessage = 'CRM не доступна';
        }
    }
}
