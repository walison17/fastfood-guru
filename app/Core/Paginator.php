<?php 

namespace App\Core;

use JsonSerializable;
use Psr\Http\Message\RequestInterface;

class Paginator implements JsonSerializable
{
    /**
     * items da paginação
     *
     * @var array
     */
    protected $items;
    
    /**
     * total de itens à serem páginados
     *
     * @var int 
     */
    protected $total;
    
    /**
     * quantidade de itens por página
     *
     * @var int
     */
    protected $perPage;

    /**
     * número da página atual
     *
     * @var int
     */
    protected $currentPage;
    
    /**
     * parâmetro de paginação a ser exibido na url ex.: exemplo.com?page=1
     *
     * @var string
     */
    protected $queryParam;
    
    /**
     * opções extras para extensões
     *
     * @var array
     */
    protected $options;

    public function __construct
    (
        $items, 
        int $total, 
        int $perPage, 
        string $queryParam = 'page', 
        int $currentPage = null, 
        array $options = []
    )
    {
        $this->items = $items;
        $this->total = $total;
        $this->perPage = $perPage;
        $this->queryParam = $queryParam;
        $this->currentPage = $this->resolveCurrentPage($currentPage);
        $this->options = $options;
    }

    /**
     * resolve o número da página atual através do parâmetro de paginação na url
     *
     * @return int
     */
    protected function resolveCurrentPage(int $page = null)
    {
        $page =  $page ?? request($this->queryParam) ?? 1;
        
        return $this->isValidPage($page) ? $page : $this->getLastPage();
    }  

    /**
     * verifica se o número da página é válido
     *
     * @param int $page
     * @return boolean
     */
    protected function isValidPage(int $page)
    {
        return $page > 0 && $page <= $this->getLastPage();
    }

    /**
     * retorna o número da página atual à nível de consulta (iniciando de 0)
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage - 1;
    }

    /**
     * retorna a url da página atual
     *
     * @return string
     */
    public function currentPageUrl()
    {
        return $this->buildUrl($this->currentPage);
    }

    /**
     * retorna a url da próxima página, se não existir null é retornado
     *
     * @return string|null
     */
    public function nextPageUrl()
    {
        return $this->hasNextPage() 
            ? $this->buildUrl($this->getNextPage())
            : null;
    }

    /**
     * retorna o número da próxima página
     *
     * @return int
     */
    public function getNextPage()
    {
        return $this->hasNextPage()
            ? $this->currentPage + 1
            : null;
    }

    /**
     * verifica se tem próxima página
     *
     * @return boolean
     */
    public function hasNextPage()
    {
        $page = $this->getCurrentPage() > 0 ? $this->getCurrentPage() : 1;

        return ($page + 1) * $this->perPage < $this->total; 
    }

    /**
     * retorna a url da página anterior, se não existir null é retornado
     *
     * @return void
     */
    public function previousPageUrl()
    {
        return $this->hasPreviousPage()
            ? $this->buildUrl($this->getPreviousPage())
            : null;
    }

    /**
     * retorna a url da página anterior, se não existir null é retornado
     *
     * @return int|null
     */
    public function getPreviousPage()
    {
        return $this->hasPreviousPage() 
            ? $this->currentPage - 1 
            : null;
    }

    /**
     * verifica se tem página anterior
     *
     * @return boolean
     */
    public function hasPreviousPage()
    {
        return ($this->getCurrentPage() - 1) >= 0;
    }

    /**
     * constroi a url para página informada 
     *
     * @param int $page
     * @return string
     */
    public function buildUrl(int $page)
    {
        $query = [$this->queryParam => $page];

        return current_url() . '?' . http_build_query($query);
    }

    /**
     * retorna o total de itens
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * retorna a quantidade de itens exibidos por página
     *
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * retorna a url da prímeira página 
     *
     * @return string
     */
    public function firstPageUrl()
    {
        return $this->buildUrl(1);
    }

    /**
     * retorna a url da última página 
     *
     * @return string
     */
    public function lastPageUrl()
    {
        return $this->buildUrl($this->getLastPage());
    }

    /**
     * retorna o número da última página
     *
     * @return int
     */
    public function getLastPage()
    {
        return ceil($this->total / $this->perPage);
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * define o parâmetro de paginação 
     *
     * @param string $queryParam
     * @return void
     */
    public function setQueryParam(string $queryParam)
    {
        $this->queryParam = $queryParam;
    }

    /**
     * retorna os itens páginados
     *
     * @return \Illuminate\Support\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * retorna a representação da paginação em array
     *
     * @return array
     */
    protected function toArray()
    {
        return [
            'current_page' => (int) $this->currentPage,
            'next_page' => $this->getNextPage(),
            'previous_page' => $this->getPreviousPage(),
            'current_page_url' => $this->currentPageUrl(),
            'next_page_url' => $this->nextPageUrl(),
            'previous_page_url' => $this->previousPageUrl(),
            'first_page_url' => $this->firstPageUrl(),
            'last_page_url' => $this->lastPageUrl(),
            'total' => $this->getTotal(),
            'per_page' => $this->getPerPage(),
            'data' => $this->getItems()->toArray()
        ];
    }
}