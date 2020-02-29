<?php


namespace TrafficIsobar\Mindbox\app\Http\Classes;

use TrafficIsobar\Mindbox\app\Http\Classes\ResponseAbstract;

class SuccessResponse extends ResponseAbstract
{

    /**
     * SuccessResponse constructor.
     * @param bool $response
     */
    public function __construct($response = false)
    {
        parent::__construct($response);

        $this->responseCode = 200;

        $this->responseMessage = $this->get('status') ?: '';
    }


    /**
     * @return bool
     */
    public function getMindboxId() {
        return $this->get('customer', 'ids', 'mindboxId');
    }
}
