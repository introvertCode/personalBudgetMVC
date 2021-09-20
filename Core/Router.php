<?php
namespace Core;

class Router
{
    // złożony array- routing table
    protected $routes = [];
    // parametry route
    protected $params = [];

    // dodawanie do routng table
    public function add($route, $params = []){
        //robimy to żeby adresy nie musiały być zawsze sztywne np. abc.com/admin/home/new wybierze home jako controller i new jako action przy odpowiednio dodanej ścieżce w index.php.
        //convert route to regex: escape forword slash

         // Convert the route to a regular expression: escape forward slashes
         $route = preg_replace('/\//', '\\/', $route);

         // Convert variables e.g. {controller}
         $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
 
         // Convert variables with custom regular expressions e.g. {id:\d+}
         $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
 
         // Add start and end delimiters, and case insensitive flag
         $route = '/^' . $route . '$/i';
 
         $this->routes[$route] = $params;
    }

    public function getRoutes(){
        return $this->routes;
    }

    public function getParams(){
        return $this->params;
    }

    public function match($url){
        // foreach ($this->routes as $route => $params){
        //     if ($url == $route){
        //         $this->params = $params;
        //         return true;
        //     }
        // }
        // return false;

        // match to fixed URL format /controller/action - URL zawsze musiałby tak wyglądać, nie może być np. abc.com/admin/home/new
        //$reg_exp = "/^(?P<controller>[a-z-]+)\/(?P<action>[a-z-]+)$/";

        // if(preg_match($reg_exp, $url, $matches)){
        //     // Get named capture groups values
        //     $params = [];

        //     foreach ($matches as $key => $match){
        //         if (is_string ($key)){
        //             $params[$key] = $match;
        //         }
        //     }

        //     $this->params = $params;
        //     return true;
        // }

        foreach ($this->routes as $route => $params){
            if (preg_match ($route, $url, $matches)){  
                // If matches is provided, then it is filled with the results of search. $matches[0] will contain the text that matched the full pattern, $matches[1] will have the text that matched the first captured parenthesized subpattern, and so on. 
                foreach ($matches as $key => $match) {
                    
                    //dla abc.com/user/add pierwszy match będzie dla całości, user/add i klucz dla tego będzie 0, a nie chcemy tego w naszej tablicy params (chcemy tylko controller i action). Dla kolejnych będzie to controller, id, action. Wieć sprawdzamy czy klucz to jest łańcuch (string). Nazwy controller i action są nadawane w regex ?P<controller> itd.
                    //tak wygląda tablica bez sprawdzenia czy to łańcuch:
                    // array(5) {
                    //     [0]=>
                    //     string(8) "user/add"
                    //     ["controller"]=>
                    //     string(4) "user"
                    //     [1]=>
                    //     string(4) "user"
                    //     ["action"]=>
                    //     string(3) "add"
                    //     [2]=>
                    //     string(3) "add"
                    //   }

                    if (is_string($key)){
                        $params[$key] = $match;
                     }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;

    }

    public function dispatch($url){
        
        $url = $this->removeQueryStringVariables($url);

        if ($this->match($url)){
            //zczytanie jaki controller np. Home, Posts (nazwa klasy)
            $controller = $this->params['controller'];
            //konwersja by wyrazy zaczynały się z wielkiej litery
            $controller = $this->convertToStudlyCaps($controller);
            /*ścieżka do pliku klasy 
                So when we insert the controller variable into the string, we need to prefix it with a backslash, but doing this:

                $controller = "App\Controllers\$controller";     ->     "App\Controllers$controller"

                escapes the dollar sign, so the dollar sign is inserted instead of the variable. So we need to add another backslash to escape the first backslash:

                $controller = "App\Controllers\\$controller";     ->    "App\Controllers\Home"      

            */
            //$controller = "App\Controllers\\$controller";

            $controller = $this->getNamespace().$controller;

            if (class_exists($controller)){
                //tworzenie obiektu z konstruktora abstrakcyjnej klasy controller, z której dziedziczą inne klasy
                $controller_object = new $controller($this->params);
                
                //akcja czyli metoda, zczytujemy z arraya z klucza action
                $action = $this->params['action'];
                //konwersja na camelCase, bo tak sobie założyliśmy że będą wyglądać metody
                $action = $this->convertToCamelCase($action);

                //if (is_callable([$controller_object, $action])){ - nie potrzeba tego ponieważ metoda __call i tak zostanie wywołana

                // by nie można było obejść funkcji before() za pomocą wpisanie od razu metody z Action na końcu np. indexAction - to pominie wywołanie metody __call.    
                if (preg_match('/action$/i', $action) == 0) {
                    //wywołanie metody (akcji)
                    $controller_object->$action();

                }else{
                    //echo "Method $action (in controller $controller) not found";
                    throw new \Exception("Method $action (in controller $controller) not found");
                }
            } else{
                //echo "Controller class $controller not found";
                throw new \Exception("Controller class $controller not found");
            }
        } else {
            //echo 'No route matched.';
            throw new \Exception('No route matched.', 404);
        }
    }

    protected function convertToStudlyCaps($string){
        return str_replace(' ','', ucwords(str_replace('-', ' ',$string)));
    }

    protected function convertToCamelCase($string){
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
    * Remove the query string variables from the URL (if any). As the full
    * query string is used for the route, any variables at the end will need
    * to be removed before the route is matched to the routing table. For
    * example:
    *
    *   URL                           $_SERVER['QUERY_STRING']  Route
    *   -------------------------------------------------------------------
    *   localhost                     ''                        ''
    *   localhost/?                   ''                        ''
    *   localhost/?page=1             page=1                    ''
    *   localhost/posts?page=1        posts&page=1              posts
    *   localhost/posts/index         posts/index               posts/index
    *   localhost/posts/index?page=1  posts/index&page=1        posts/index
    *
    * A URL of the format localhost/?page (one variable name, no value) won't
    * work however. (NB. The .htaccess file converts the first ? to a & when
    * it's passed through to the $_SERVER variable).
    *
    * @param string $url The full URL
    *
    * @return string The URL with the query string variables removed
    */

    protected function removeQueryStringVariables($url){
        if ($url !=''){
            $parts = explode('&',$url, 2);
            if (strpos($parts[0], '=') === false){
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return $url;
    }


    protected function getNamespace(){
        $namespace = 'App\Controllers\\'; 

        if (array_key_exists('namespace', $this->params)){

            //.= tak jak +=
            $namespace .= $this->params['namespace']. '\\';
            //echo $this->params['namespace'];
        }
        return $namespace;
    }


}


