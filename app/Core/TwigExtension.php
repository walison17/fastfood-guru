<?php

namespace App\Core;

use App\Core\Container;

class TwigExtension extends \Twig_Extension
{
    /** @var Container */
    private $container;

    public function __construct()
    {
        $this->container = Container::instance();
    }

    public function getName()
    {
        return 'slim';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('path_for', array($this, 'pathFor')),
            new \Twig_SimpleFunction('base_url', array($this, 'baseUrl')),
            new \Twig_SimpleFunction('is_current_path', array($this, 'isCurrentPath')),
            new \Twig_SimpleFunction('asset', array($this, 'asset')),
            new \Twig_SimpleFunction('si_img', array($this, 'cacheImage')),
            new \Twig_SimpleFunction('date_format', array($this, 'dateFormat')),
            new \Twig_SimpleFunction('month_abbr', array($this, 'monthAbbr')),
            new \Twig_SimpleFunction('randomize', array($this, 'randomize')),
        ];
    }

    public function pathFor($name, $data = [], $queryParams = [], $appName = 'default')
    {
        return $this->container->router->pathFor($name, $data, $queryParams);
    }

    public function baseUrl()
    {
        return $this->container->request->getUri()->getBaseUrl();
    }

    public function asset($path)
    {
        $asset = asset($path);

        return "/{$asset}";
    }

    public function isCurrentPath($name, $data = [])
    {
        return $this->container->router->pathFor($name, $data) === $this->container->request->getUri()->getPath();
    }

    public function cacheImage($url)
    {
        $path = si_img($url);

        return "/{$path}";
    }

    public function dateFormat($date)
    {
        return format_date($date);
    }

    public function monthAbbr(string $date)
    {
        return month_abbr($date);
    }

    public function randomize($array, int $quantity)
    {
        return collect($array)->random($quantity)->all();
    }
}
