<?php

if (! function_exists('app')) {
    /**
     * acessa a dependencia do container
     *
     * @param string $dependency
     * @throws App\Core\Exceptions\DependencyNotRegistered
     * @return mixed
     */
    function app($dependency)
    {
        /** @var Container */
        $container = App\Core\Container::instance();
        
        if (! $container->has($dependency) ) {
            throw new App\Core\Exceptions\DependencyNotRegistered($dependency);
        }

        return $container->get($dependency);
    }
}

if (! function_exists('auth'))
{
    function auth()
    {
        return app('auth');
    }
}

if (! function_exists('paginate')) 
{
    function paginate($data)
    {
        return new App\Core\Paginator($data);
    }
}


if (! function_exists('request')) 
{
    function request($param)
    {
        return app('request')->getParam($param);
    }
}

if (! function_exists('url'))
{
    function url($route = null, array $data = [], array $queryParams = [])
    {
        if ($route) {
            return app('router')->pathFor($route, $data, $queryParams);
        }

        return app('router')->pathFor('home');
    }
}

if (! function_exists('view')) 
{
    function view($template, $context = [])
    {
        $response = app('response');
        $engine = config('template.engine');

        switch ($engine) {
            case 'twig':
                return app('view')->render($response, "{$template}.twig", $context);

            case 'plates':
                return app('view')->render($template, $context);
                
            default:
                return app('view')->render($response, "{$template}.twig", $context);
        }
    }
}

if (! function_exists('redirect')) 
{
    function redirect($url, $statusCode = 302)
    {
        $response = app('response');

        return $response->withRedirect(url($url), $statusCode);
    }
}


if (! function_exists('json')) 
{
    /**
     * responde a requisição com json
     *
     * @param $data
     * @param int $statusCode
     * @return Psr\Http\Message\ResponseInterface
     */
    function json($data, int $statusCode = 201)
    {
        return app('response')->withJson($data, $statusCode);
    }
}

if (! function_exists('collect')) 
{
    function collect($array = [])
    {
        return new Illuminate\Support\Collection($array);
    }
}

if (! function_exists('api')) 
{
    /**
     * cria uma instância de Service
     *
     * @param string $endpoint
     * @param array $params
     * @param int|null $cacheTime
     * @return void
     */
    function api(string $endpoint, array $params = [], $cacheTime = 25) 
    {
        $isTest = config('test_mode');
        
        if ($isTest) {
            $cacheTime = 1;
        }

        return App\Services\Service::make($endpoint, $params, $cacheTime);    
    }
}

function api_get(string $endpoint, array $params = [], $cacheTime = 25)
{
    $isTest = config('test_mode');

    if ($isTest) {
        $cacheTime = 1;
    }

    return App\Services\Service::make($endpoint, $params, $cacheTime)->get();
}

if (! function_exists('cache_key')) 
{
    /**
     * gera uma chave baseado no endpoint da api, parametros de busca e paginação
     *
     * @param string $enpoint
     * @param array $params
     * @return void
     */
    function cache_key($enpoint, $params = null)
    {
        $key = str_replace('/', '.', $enpoint);

        if ($params) {
            $key .= '.' . hash('sha512', serialize($params));
        }
        
        return $key;
    }
}

if (! function_exists('asset'))
{
    /**
     * retorna o caminho do arquivo estático
     *
     * @param string $filename
     * @return void
     */
    function asset(string $filename)
    {   
        $webfiles = basename(config('webfiles'));

        return "{$webfiles}/{$filename}";
    }
}

if (! function_exists('si_img'))
{
    /**
     * armazena as imagens vindas da api
     *
     * @param string $url
     * @return string
     */
    function si_img(string $url)
    {
        $parsed = parse_url($url); 
        $filename = basename($parsed['path']);
        $urlPath = dirname($parsed['path']);

        $externalFilePattern = '/\/si\/cdn\/img\/[0-9]+\/[0-9]+\/[a-z]+\/[0-9]{1,3}/';
        $filenamePattern = '/\/si\/cdn\/img\/([0-9]+)\/([0-9]+)\/([a-z]+)\/([0-9]+)/';

        if (! preg_match($externalFilePattern, $urlPath)) {
            return $url; 
        }

        $localFilename = preg_replace($filenamePattern, "$1x$2$3$4-", $urlPath) . $filename;
        $webfiles = config('webfiles');

        if (! file_exists(asset("img/{$localFilename}"))) {
            (new GuzzleHttp\Client())->request('GET', $url, ['sink' => "{$webfiles}/img/{$localFilename}"]);
        }
        
        return asset("img/{$localFilename}");
    }
}

if (! function_exists('format_date'))
{
    function format_date(string $date)
    {
        [$year, $monthNumber, $day] = explode('-', $date);
        $months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
        $month = $months[(int) $monthNumber - 1];
    
        return "{$day} de {$month} de {$year}";
    }
}

if (! function_exists('month_abrr'))
{
    function month_abbr(string $date)
    {
        [$year, $monthNumber, $day] = explode('-', $date);
        $months = ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];
    
        return $months[$monthNumber - 1];
    }
}

if (! function_exists('log_info')) 
{
    function log_info(string $message, array $context = [])
    {
        app('logger')->info($message, $context);
    }
}

if (! function_exists('log_error')) 
{
    function log_error(string $message, array $context = [])
    {
        app('logger')->error($message, $context);
    }
}

if (! function_exists('log_debug')) 
{
    /**
     * cria um log com nível com nível debug
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    function log_debug(string $message, array $context = [])
    {
        app('logger')->debug($message, $context);
    }
}

if (! function_exists('config'))
{
    /**
     * acessa as configurações de maneira mais simples ex.: config('cache.directory')
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function config($key, $default = null)
    {
        $keys = explode('.', $key);
        $actual = isset(app('settings')[$keys[0]]) ? app('settings')[$keys[0]] : $default;
        unset($keys[0]);

        $result = sizeof($keys) > 0 ? null : $actual;

        foreach ($keys as $key) {
            if (isset($actual[$key])) {
                is_array($actual[$key]) ? $actual = $actual[$key] : $result = $actual[$key];
            } else {
                break;
            }
        }  

        return $result ?? $default;
    }
}

if (! function_exists('organize'))
{
    /**
     * organiza os resultados de acordo com a chave informada
     *
     * @param array $array
     * @param string $key
     * @return void
     */
    function organize(array $array, $key = 'urlamigavel')
    {
        return collect($array)->keyBy($key)->all();
    }
}
