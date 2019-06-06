<?php

namespace App\Middleware;

class CsrfViewMiddleware extends Middleware {

  public function __invoke($request, $response, $next) {
    $nameKey = $this->container->csrf->getTokenNameKey();
    $name = $this->container->csrf->getTokenName();
    $valueKey = $this->container->csrf->getTokenValueKey();
    $value = $this->container->csrf->getTokenValue();

    $this->container->view->getEnvironment()->addGlobal('csrf', [
      'field' => '
        <input type="hidden" name="'.$nameKey.'" value="'.$name.'">
        <input type="hidden" name="'.$valueKey.'" value="'.$value.'">
      '
    ]);

    $response = $next($request, $response);
    return $response;
  }

}
