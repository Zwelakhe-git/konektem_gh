<?php
/**
 * @param url - the url pattern
 * @param method - get, pos
 * @param controller - the callback controller
 * @param name - the name of the url pattern
 */
function path_(string $url, string $method, callable $controller, string $name){
    return [
        "url" => $url,
        "method" => $method,
        'handler' => $controller,
        "name" => $name
    ];
}
?>