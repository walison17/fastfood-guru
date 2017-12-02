<?php 

use Slim\Http\Request;
use Slim\Http\Environment;
use PHPUnit\Framework\TestCase;

class Test extends TestCase 
{
    public function post()
    {

    }

    /**
     * faz uma requisição
     *
     * @param string $method 
     * @param string $path
     * @param array $data
     * @param array $optionalHeaders
     * @return void
     */    
    public function makeRequest
    (
        string $method, 
        string $path, 
        array $data = [], 
        array $optionalHeaders = []
    )
    {
        $method = strtoupper($method);

        $options = [
            'REQUEST_METHOD' => $method,
            'REQUEST_URI' => $path
        ];

        if ($method == 'GET') {
            $options['QUERY_STRING'] = http_build_query($data);
        } 

        $env = Environment::mock($options);

        return Request::createFromEnvironment($env);
    }
}