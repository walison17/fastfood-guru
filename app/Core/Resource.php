<?php 

namespace App\Core;

use JsonSerializable;
use Illuminate\Database\Eloquent\Model;

class Resource implements JsonSerializable
{
    /** @var \Illuminate\Database\Eloquent\Model */
    protected $resource;

    /**
     * lista de atributos do recurso que devem ser exibidos na resposta 
     *
     * @var array
     */
    protected $attributes = [];

    public function __construct(Model $resource)
    {
        $this->resource = $resource;
    }

    /**
     * facilita o acesso aos atributos e métodos do recurso
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->resource->{$name};
    }

    /**
     * retorna a lista de atributos que devem ser exibidos 
     *
     * @return array
     */
    protected function getAttributes()
    {
        return $this->getAllAttributes();
    }

    /**
     * retorna todos os atributos do recurso (não inclui os atributos invisíveis)
     *
     * @return array 
     */
    protected function getAllAttributes()
    {
        return $this->resource->toArray(); //pega os atributos do modelo
    }

    /**
     * verifica se a requisição possui campos espeficicos
     *
     * @return boolean
     */
    protected function hasRequestedFields()
    {
        return ! empty($this->getFields());   
    }

    /**
     * retorna os campos solicitadas da requisição
     *
     * @return array
     */
    protected function getFields()
    {
        return explode(',', request('fields'));
    }

    /**
     * retorna todos os relacionamentos do recurso
     *
     * @return array
     */
    protected function getRelationships()
    {
        return $this->resource->getRelations();
    }

    /**
     * verifica se a requisição possui relacionamentos à serem inclusos
     *
     * @return array
     */
    protected function hasIncludes()
    {
        return ! is_null(request('include'));
    }

    /**
     * retorna a lista de relacionamentos incluidos
     *
     * @return array
     */
    protected function getIncludes()
    {
        return ['included' => explode(',', request('include'))];
    }
    
    /**
     * retorna a representação do recurso em forma de array
     *
     * @return array
     */
    public function toArray()
    {
        $data = [
            'id' => $this->resource->getResourceId(),
            'type' => $this->resource->getResourceType(),
            'attributes' => $this->getAttributes(),
        ];

        $data = $this->hasIncludes() ? array_merge($data, $this->getIncludes()) : $data;

        return $data;
    }

    /**
     * serializa o recurso
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}