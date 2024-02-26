<?php

namespace App\Traits;


trait GlobalResponseTrait
{
    /**
     * @param string $message
     * @param $records
     * @param $outcomeCode
     * @param $errorStage
     * @return mixed
     * @author Waseem Usman <waseemsunktk@gmail.com>
     */
    public function returnResponse($message = '', $records = [], $outcomeCode = 200, $recordsTotal = 0)
    {
        $numOfRecords = 0;

        if (is_array($records)) {
            $numOfRecords = count($records);
        } elseif (preg_match('/App/i', get_class($records))) {
            $numOfRecords = 1;
        } elseif (preg_match('/Collection/i', get_class($records))) {
            $numOfRecords = $records->count();
        } elseif (preg_match('/Pagination/i', get_class($records))) {
            $data = ($records->toArray());
            $numOfRecords = count($data['data']);
        }

        $outCome = 'SUCCESS';

        if($outcomeCode != 200)
        {
            $outCome = $this->responseCodeMessages()[$outcomeCode];
            $numOfRecords = 0;
        }

        $response['_metadata'] = [
            'outcome' => $outCome,
            'outcomeCode' => $outcomeCode,
            'numOfRecords' => $numOfRecords,
            'totalRecords' => $recordsTotal,
            'message' => $message,
        ];
        
        $response['records'] = $records;

        $response['errors'] = [];

        return $response;
    }

    /**
     * @param $code
     * @param bool $codeInfo
     * @param array $errorsMessage
     * @return mixed
     * @author Sajjad Hanif <sajjadhanif@gmail.com>
     */
    public function returnResponseError($code, $codeInfo = false, $errorsMessage = [])
    {
        $errors = $this->responseCodeMessages();

        if (!isset($errors[$code])) {
            $code = 1000;
        }

        $response['_metadata'] = [
            'outcome' => $errors[$code],
            'outcomeCode' => $code,
            'numOfRecords' => 0,
            'message' => $codeInfo,
        ];

        $response['records'] = [];
        $response['errors'] = $errorsMessage;

        return ($response);
    }

    /**
     * @param $errors
     * @return mixed
     * @author Sajjad Hanif <sajjadhanif@gmail.com>
     */

    public function formatErrorMessages($errors) {
        $errorsArray = array();
        $k = 0;

        foreach($errors as $field=>$code){
            $errorMsg = config('errors.messages.'.$code);

            $errorMsg = str_replace('<FIELD NAME>', $field, $errorMsg);

            $errorsArray[$k] = [
                "status" => 'INVALID_PARAMS',
                "code" => $code,
                "message" => $errorMsg,
                "field" => $field
            ];
            $k++;
        }

        return $errorsArray;
    }

    /**
     * @return mixed
     * @author Sajjad Hanif <sajjadhanif@gmail.com>
     */

    public function responseCodeMessages()
    {

        return [
            100 => 'Continue',
            101 => 'Switching Protocols',
            103 => 'Early Hints',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            307 => 'Temporary Redirect',
            308 => 'Permanent Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Payload Too Large',
            414 => 'URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Range Not Satisfiable',
            417 => 'Expectation Failed',
            418 => 'I\'m a teapot',
            422 => 'Unprocessable Entity',
            425 => 'Too Early',
            426 => 'Upgrade Required',
            428 => 'Precondition Required',
            429 => 'Too Many Requests',
            431 => 'Request Header Fields Too Large',
            451 => 'Unavailable For Legal Reasons',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            508 => 'Loop Detected',
            510 => 'Not Extended',
            511 => 'Network Authentication Required',
            512 => 'Token Expired',
            999 => 'Unkown Error'
        ];
    }

}
