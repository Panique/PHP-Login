<?php

/**
 * Class Application
 * The heart of the app
 */
class Application
{
    /** @var null The controller part of the URL */
    private $url_controller;
    /** @var null The method part (of the above controller) of the URL */
    private $url_action;
    /** @var null Parameter one of the URL */
    private $url_parameter_1;
    /** @var null Parameter two of the URL */
    private $url_parameter_2;
    /** @var null Parameter three of the URL */
    private $url_parameter_3;

    /**
     * Starts the Application
     * Takes the parts of the URL and loads the according controller & method and passes the parameter arguments to it
     * TODO: get rid of deep if/else nesting
     * TODO: make the hardcoded locations ("error/index", "index.php", new Index()) dynamic, maybe via config.php
     */
    public function __construct()
    {
        $this->splitUrl();
        
        $notFound = false;

        // check for controller: is the url_controller NOT empty ?
        if ($this->url_controller) {
            // check for controller: does such a controller exist ?
            if (file_exists(CONTROLLER_PATH . $this->url_controller . '.php')) {
                // if so, then load this file and create this controller
                // example: if controller would be "car", then this line would translate into: $this->car = new car();
                require CONTROLLER_PATH . $this->url_controller . '.php';
                $this->url_controller = new $this->url_controller();

                // check for method: does such a method exist in the controller ?
                if ($this->url_action) {
                    if (method_exists($this->url_controller, $this->url_action)) {

                        // call the method and pass the arguments to it
                        if (isset($this->url_parameter_3)) {
                            $this->url_controller->{$this->url_action}($this->url_parameter_1, $this->url_parameter_2, $this->url_parameter_3);
                        } elseif (isset($this->url_parameter_2)) {
                            $this->url_controller->{$this->url_action}($this->url_parameter_1, $this->url_parameter_2);
                        } elseif (isset($this->url_parameter_1)) {
                            $this->url_controller->{$this->url_action}($this->url_parameter_1);
                        } else {
                            // if no parameters given, just call the method without arguments
                            $this->url_controller->{$this->url_action}();
                        }
                    } else {
                        // flag for a 404 Not Found response
                        $notFound = true;
                    }
                } else {
                    // default/fallback: call the index() method of a selected controller
                    $this->url_controller->index();
                }
            // obviously mistyped controller name, therefore show 404
            } else {
                // flag for a 404 Not Found response
                $notFound = true;
            }
        // if url_controller is empty, simply show the main page (index/index)
        } else {
            // invalid URL, so simply show home/index
            require CONTROLLER_PATH . 'index.php';
            $controller = new Index();
            $controller->index();
        }
        
        // if a matching controller / method could not be found, send a 404 page without redirection
        if ($notFound) {
            require CONTROLLER_PATH . 'error.php';
            $controller = new Error();
            $controller->index();
        }
    }

    /**
     * Gets and splits the URL
     */
    private function splitUrl()
    {
        if (isset($_GET['url'])) {

            // split URL
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            // Put URL parts into according properties
            // By the way, the syntax here if just a short form of if/else, called "Ternary Operators"
            // http://davidwalsh.name/php-shorthand-if-else-ternary-operators
            $this->url_controller = (isset($url[0]) ? $url[0] : null);
            $this->url_action = (isset($url[1]) ? $url[1] : null);
            $this->url_parameter_1 = (isset($url[2]) ? $url[2] : null);
            $this->url_parameter_2 = (isset($url[3]) ? $url[3] : null);
            $this->url_parameter_3 = (isset($url[4]) ? $url[4] : null);
        }
    }
}
