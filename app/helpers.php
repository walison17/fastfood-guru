<?php

if (! function_exists('app')) {
    /**
     * acessa a dependencia do container
     *
     * @param string $dependency
     * @return mixed
     */
    function app($dependency)
    {
        /** @var Container */
        $container = App\Core\Container::instance();
        
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

if (! function_exists('request')) 
{
    function request(string $paramName, $default = null)
    {
        return app('request')->getParam($paramName, $default);
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
    /**
     * Renderiza uma view
     *
     * @param string $template caminho do template relativo a pasta resources/templates
     * @param array $context
     * @return \Psr\Http\Message\ResponseInterface
     */
    function view(string $template, $context = [])
    {
        $response = app('response');

        return app('view')->render($template, $context);
    }
}

if (! function_exists('redirect')) 
{
    /**
     * Redireciona para a rota informada 
     *
     * @param string $routeName
     * @param integer $statusCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    function redirect(string $routeName, $statusCode = 302)
    {
        return app('response')->withRedirect(url($routeName), $statusCode);
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

        return "/{$webfiles}/{$filename}";
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

/**
 * Adiciona uma mensagem (flash) para ser exibida apenas na próxima requisição  
 *
 * @param mixed $key
 * @param string $message
 * @return void
 */
function flash($key, string $message)
{
    app('flash')->addMessage($key, $message);
}

/**
 * Retorna uma mensagem flash que foi armazenada
 *
 * @param mixed $key
 * @return string
 */
function get_flash($key, string $default = null)
{
    return app('flash')->getFirstMessage($key) ?? $default;
}

/**
 * Adiciona uma mensagem de erro na sessão
 *
 * @param string $field
 * @param string $message
 * @return void
 */
function flash_error(string $field, string $message)
{
    flash_errors([
        $field => [$message]
    ]);
}

/**
 * Adiciona as mensagens de erro na sessão 
 *
 * @param array $errors
 * @return void
 */
function flash_errors(array $errors) 
{
    app('flash')->addMessage('errors', $errors);
}

/**
 * Retorna as mensagens de erros geradas pela validação
 *
 * @return \App\Core\Validation\ErrorMessages
 */
function errors()
{
    $messages = app('flash')->getFirstMessage('errors');

    return new \App\Core\Validation\ErrorMessages($messages ?? []);
}

/**
 * Recupera o valor de um campo do formulário armazenado na sessão
 *
 * @param string $field campo do formulário
 * @return string
 */
function old(string $field)
{
    $oldInputs = get_flash('old_input');

    return $oldInputs[$field] ?? null;
}

/**
 * Retorna o diretório/caminho de armazenamento
 *
 * @param string $name
 * @return string
 */
function storage_path(string $name = null)
{
    if ($name) {
        $path = config('storage_path') . '/' . $name;
        
        if (! file_exists($path)) mkdir($path, 0777, true);

        return $path;
    }

    return config('storage_path');
}

/**
 * Move um arquivo para o diretório informado
 *
 * @param string $directory
 * @param \Slim\Http\UploadedFile $uploadedFile
 * @return string
 */
function move_file(string $directory, \Slim\Http\UploadedFile $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8));
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}

/**
 * Retorna o caminho de um arquivo armazenado 
 *
 * @param string $filename
 * @param string $folder
 * @return string
 */
function static_file(string $filename, string $folder = null) 
{
    $base = basename(storage_path());

    return $folder ? "{$base}/{$folder}/{$filename}" : "{$base}/{$filename}";
}
