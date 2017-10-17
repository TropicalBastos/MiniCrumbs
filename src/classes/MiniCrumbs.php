<?php

/**
 * Author: Ian Bastos
 * Date: 16/10/2017
 */

require("Crumb.php");

class MiniCrumbs{

    protected $uri;
    protected $origin;
    protected $slugs;
    protected $fm;
    protected $breadcrumbs = null;
    protected $home = null;
    protected $words_pattern = "/([+-_$%{}()])|(%20)/";
    protected $trailing_slash = "/\/$/";

    /*
       @param = format specifier:
           uppercase - lowercase - standard (uppercase on every first letter)
       @param = homepage crumb - defaults to 'Home', pass false in order to omit it
       @param options array
   */

    public function __construct($fm, $home = 'home', $options = array())
    {

        if(in_array('test', $options)){
            if($options['test'])
                $uri = "/home/test/testing";
        }else{
            $uri = $_SERVER['REQUEST_URI'];
        }

        if(gettype($home) == "string"){
            $this->home = $home;
        }

        $uri = preg_replace($this->trailing_slash, "", $uri);

        //cache the original slugs
        $this->origin = explode("/", $uri);
        $this->slugs = explode("/", $uri);
        $this->uri = $uri;

        if($fm != 'upper'
            && $fm != 'lower'
            && $fm != 'standard')
            throw new Exception('Format Exception: Please specify the correct format');
        else
            $this->fm = $fm;
    }


    /*
        @param array of strings
        formats all crumbs based on the constructed arguments
        return @array of new words
    */

    protected function formatCrumbs($words)
    {
        $result = [];

        switch($this->fm){

            case 'upper': {
                for($i = 0; $i < count($words); $i++){
                    $result[] = strtoupper($words[$i]);
                }

                  break;
            }

            case 'lower': {
                for($i = 0; $i < count($words); $i++){
                    $result[] = strtolower($words[$i]);
                }

                break;
            }

            default: {
                for($i = 0; $i < count($words); $i++){
                    $result[] = ucfirst($words[$i]);
                }

                break;
            }

        }

        return $result;
    }

    /*
       Delimits each special character in the uri with a space for breadcrumb readability
       return @void
   */

    protected function formatSpecials()
    {
        for($i = 0; $i < count($this->slugs); $i++){
            $words = preg_split($this->words_pattern, $this->slugs[$i]);
            $words = $this->formatCrumbs($words);
            $this->slugs[$i] = implode(" ", $words);
        }
    }

    /*
       Builds the links ready to be matched with their respective breadcrumb names
       return @array
   */

    protected function buildLinks()
    {
        $currentUri = "";
        $result = [];

        foreach($this->origin as $uri){
            if($uri == "") continue;
            $currentUri .= ($uri . "/");
            $result[] = $currentUri;
        }

        return $result;
    }

    /*
       clean empty strings in slugs array
       return @void
   */

    protected function cleanSlugs()
    {
        $cleanSlugs = [];
        foreach($this->slugs as $slug){
            if($slug == "") continue;
            $cleanSlugs[] = $slug;
        }

        $this->slugs = $cleanSlugs;
    }

    /*
        Returns an indexed array of crumb objects
        indexed in the order of the uri
        return @array
    */

    public function parse()
    {
        $breadcrumbs = [];
        $this->cleanSlugs();
        $this->formatSpecials();
        $links = $this->buildLinks();

        if(gettype($this->home) == "string"){
            $homeStr = $this->formatCrumbs([$this->home]);
            $breadcrumbs[] = new Crumb($homeStr[0], "/");
        }

        for($i = 0; $i < count($this->slugs); $i++){
            $breadcrumbs[] = new Crumb($this->slugs[$i], $links[$i]);
        }

        $this->breadcrumbs = $breadcrumbs;

        return $breadcrumbs;
    }

    /*
        Renders pre made breadcrumbs
        return @void
    */

    public function render()
    {
        if($this->breadcrumbs == null)
            throw new Exception("Breadcrumb error: No breadcrumb instantiated with the 'parse()' method");

        $html = '<div id="bread">' . '<ul>';

        for($i = 0; $i < count($this->breadcrumbs)-1; $i++){
            $html .= '<li><a href="' . $this->breadcrumbs[$i]->getUri() . '">'
                . $this->breadcrumbs[$i]->getName() . '</a></li>';
        }

        $html .= '<li>' . $this->breadcrumbs[count($this->breadcrumbs)-1]->getName() . '</li>'
              . '</ul>'
              . '</div>';

        echo $html;
    }

}