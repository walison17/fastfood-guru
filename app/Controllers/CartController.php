<?php

namespace App\Controllers;

use App\Services\Cart;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Services\CartItem;

class CartController 
{
    /** @var Cart */
    private $cart;

    public function __construct()
    {
        $this->cart = app('carrinho');
    }

    /**
     * exibe todos os itens do carrinho
     *
     * @param Request $request
     * @param Response $response
     * @param mixed $args
     * 
     * @return \Slim\Http\Response
     */
    public function index(Request $request, Response $response, $args)
    {
        if ($request->isXhr()) { //verifica se a requisição é ajax
            return json($this->cart->all());
        }

        return json(new Resource($this->$carrinho->all()));
    }

    public function get($request, $response)
    {
        $item = $this->cart->get($args['id']);
    }

    /**
     * adiciona um item ao carrinho
     *
     * @param Request $request
     * @param Response $response
     * @param mixed $args
     * 
     * @return \Slim\Http\Response
     */
    public function add(Request $request, Response $response, $args)
    {
        $this->cart->add(CartItem::createFromRequest($request));
    }

    /**
     * remove um item do carrinho
     *
     * @param Request $request
     * @param Response $response
     * @param mixed $args
     * 
     * @return \Slim\Http\Response
     */
    public function remove(Request $request, Response $response, $args)
    {
        $this->cart->remove($args['id']);
    }

    /**
     * atualiza um item do carrinho
     *
     * @param Request $request
     * @param Response $response
     * @param mixed $args
     * 
     * @return \Slim\Http\Response
     */
    public function update(Request $request, Response $response, $args) 
    {
        $item = $this->cart->get($args['id']);
        $quantity = $request->getParam('quantidade');

        if ($item->hasStockFor($quantity)) {
            $item->setQuantity($quantity);
        }

        $this->cart->update($item);
    }

    /**
     * limpa todos os itens do carrinho
     *
     * @param Request $request
     * @param Response $response
     * @param mixed $args 
     * 
     * @return \Slim\Http\Response
     */
    public function limpar(Request $request, Response $response, $args)
    {
        $this->cart->clear();
    }
}