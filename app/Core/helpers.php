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
    /**
     * retorna a classe de autenticação do containter
     *
     * @return \App\Core\Auth
     */
    function auth()
    {
        return app('auth');
    }
}

if (! function_exists('paginate')) 
{
    /**
     * faz a paginação dos itens passados
     *
     * @param mixed $data
     * @return \App\Core\Paginator
     */
    function paginate($data)
    {
        return new App\Core\Paginator($data);
    }
}


if (! function_exists('request')) 
{
    /**
     * pega um paramêtro da requisição
     *
     * @param string $param
     * @param mixed $default quando o parâmetro não exitir esse valor é retornado
     * @return mixed
     */
    function request(string $param, mixed $default = null)
    {
        return app('request')->getParam($param, $default);
    }
}


if (! function_exists('url'))
{
    /**
     * constroi a url para rota informada, quando nada for passado a url da home é retornada
     *
     * @param string $route
     * @param array $data
     * @param array $queryParams
     * @return string
     */
    function url(string $route = null, array $data = [], array $queryParams = [])
    {
        if ($route) {
            return app('router')->pathFor($route, $data, $queryParams);
        }

        return app('router')->pathFor('home');
    }
}

if (! function_exists('current_url'))
{   
    /**
     * retorna a url da página atual
     *
     * @return string
     */
    function current_url()
    {
        $base = app('request')->getUri()->getBaseUrl();
        $path = app('request')->getUri()->getPath();
    
        return "{$base}{$path}";
    }
}

if (! function_exists('view')) 
{
    /**
     * renderiza uam view 
     *
     * @param string $template caminho do template (relativo a pasta configurada)
     * @param array $context variáveis enviadas para view
     * @return \Psr\Http\Message\ResponseInterface
     */
    function view(string $template, $context = [])
    {
        return app('view')->render($template, $context);
    }
}

if (! function_exists('redirect')) 
{
    /**
     * redireciona para a rota informada 
     *
     * @param string $route nome da rota 
     * @param int $statusCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    function redirect(string $route, int $statusCode = 302)
    {
        $response = app('response');

        return $response->withRedirect(url($route), $statusCode);
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
     * @return string
     */
    function asset(string $filename)
    {   
        $webfiles = basename(config('webfiles'));

        return "{$webfiles}/{$filename}";
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

function return_if($condition, $return)
{
    if ($condition) return $return;
}

