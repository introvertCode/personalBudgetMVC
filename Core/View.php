<?php

namespace Core;

//Render a view file @param string $view The view file @return void

class View{
    public static function render ($view, $args=[]){
        
        extract($args, EXTR_SKIP);

        $file = "../App/Views/$view"; // relative to Core directory

        if (is_readable($file)){
            require $file;
        } else{
            //echo "$file not found";
            // \-potrzebne by nie szukał ścieżki
            throw new \Exception("$file not found");
        }
    }

    /*
Render a view template using Twig

*/

public static function renderTemplate(string $template, array $args = [])
{
   echo static::getTemplate($template, $args);
}

/**
 * Get the contents of a view template using Twig
 * 
 * @param string $template The template file
 * @param array $args  Associative array of adata to display in the view 9optional)
 * 
 * @return string
 */
public static function getTemplate(string $template, array $args = [])
{
    static $twig = null;

    if ($twig === null)
    {
        $loader = new \Twig\Loader\FilesystemLoader('../App/Views');
        $twig = new \Twig\Environment($loader);
        
        //BY DZIAŁAŁA ZMIENNA SESYJNA $_SESSION W TWIG
        //$twig->addGlobal('session', $_SESSION);
        
        //by funkcja była dostępna w modelu twig
        //$twig->addGlobal('is_logged_in', \App\Auth::isLoggedIn());
        $twig->addGlobal('current_user', \App\Auth::getUser());
        $twig->addGlobal('flash_messages', \App\Flash::getMessages());
    }

    return $twig->render($template, $args);
}
}

