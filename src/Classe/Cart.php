<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function add($id, array $data)
    {
        $request = $this->requestStack->getCurrentRequest();
        $session = $request->getSession();
        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
        } else {
            $cart[$id] = ['quantity' => 1];
        }
        $session->set('cart', $cart);
    }

    public function get()
    {
        $request = $this->requestStack->getCurrentRequest();
        $session = $request->getSession();

        return $session->get('cart', []);
    }

    public function remove()
    {
        $request = $this->requestStack->getCurrentRequest();
        $session = $request->getSession();

        return $session->remove('cart', []);
    }
    public function delete($id)
    {
        $request = $this->requestStack->getCurrentRequest();
        $session = $request->getSession();

        $cart = $session->get('cart', []);

        if(isset($cart[$id])){
            unset($cart[$id]);
            $session->set('cart', $cart);
        }
        return $cart;
    }
}
