<?php

include 'Seeker/Base.php';

class Seeker extends Base
{

    public $html;
    public $js;
    public $elements;

    private $sz_elements = "a,abbr,address,area,article,aside,audio,b,base,bdi,bdo,blockquote,br,button,canvas,caption,cite,code,col,colgroup,command,datalist,dd,del,details,dfn,div,dl,dt,em,embed,fieldset,figcaption,figure,footer,form,h1,h2,h3,h4,h5,h6,header,hgroup,hr,i,iframe,img,input,ins,kbd,keygen,label,legend,li,link,map,mark,menu,meter,nav,noscript,object,ol,optgroup,option,output,p,param,pre,progress,q,rp,rt,ruby,s,samp,section,select,small,source,span,strong,sub,summary,sup,table,tbody,td,textarea,tfoot,th,thead,time,title,tr,track,u,ul,var,video,wbr";

    public function __construct()
    {
        parent::__construct();
        $dom = new DOMDocument();

        $this->html = $dom;
        $this->elements = explode(',', $this->sz_elements);
    }

    /**
     * Loads the specified HTML into the HTML object
     *
     * Loads the specified HTML into the classes $html variable (DOMDocument),
     * which is required for all functionality
     * (Suppresses errors)
     *
     * @param mixed $html The HTML specified to be loaded
     */
    public function load($html)
    {
        @$this->html->loadHTML($html);
    }

    /**
     * Seeks all present elements in the HTML
     *
     * Uses private list of nested HTML elements to determine which elements
     * are present in the HTML
     *
     * @return array
     */
    public function seekPresentElements()
    {
        $pt_elements = array();

        foreach ($this->elements as $sk_element) {
            if ($this->html->getElementsByTagName($sk_element)->length > 0) {
                $pt_elements[] = $sk_element;
            }
        }

        return $pt_elements;
    }

    /**
     * Seeks classes based on the element source provided
     *
     * Based on what element source is specified, this will return an
     * array for each of the classes from the element source
     *
     * @param string $element The element type to seek (Overrides $use_element_list if not null)
     * @param bool $use_element_list Use the element list or not
     * @return array
     */
    public function seekClasses($element = null, $use_element_list = true)
    {
        ////////////////////////////////////////////////
        // Logic Cases for what element source to use //
        ////////////////////////////////////////////////
        $classes = array();

        if (!$element && $use_element_list) {
            foreach ($this->elements as $element_name) {
                foreach ($this->html->getElementsByTagName($element_name) as $use_element) {
                    if ($use_element->getAttribute('class')) {
                        $classes[$use_element->tagName] = explode(' ', $use_element->getAttribute('class'));
                    }
                }
            }
        }
        if (( $element && $use_element_list ) || ( $element && !$use_element_list )) {
            foreach ($this->html->getElementsByTagName($use) as $use_element) {
                $classes[$use_element->tagName] = explode(' ', $use_element->getAttribute('class'));
            }
        }
        if (!$element && !$use_element_list) {
            throw new Exception("No input source provided");
        }

        return $classes;
    }
}

$seek = new Seeker();
$seek->load('<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
        <p>Hello world! This is HTML5 Boilerplate.</p>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    </body>
</html>');

var_dump($seek->seekClasses(null, true));
