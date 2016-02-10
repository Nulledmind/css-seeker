<?php

include 'Seeker/Base.php';

class Seeker extends Base {

	public $html;
	public $js;
    public $elements;

    private $sz_elements = "a,abbr,address,area,article,aside,audio,b,base,bdi,bdo,blockquote,br,button,canvas,caption,cite,code,col,colgroup,command,datalist,dd,del,details,dfn,div,dl,dt,em,embed,fieldset,figcaption,figure,footer,form,h1,h2,h3,h4,h5,h6,header,hgroup,hr,i,iframe,img,input,ins,kbd,keygen,label,legend,li,link,map,mark,menu,meter,nav,noscript,object,ol,optgroup,option,output,p,param,pre,progress,q,rp,rt,ruby,s,samp,section,select,small,source,span,strong,sub,summary,sup,table,tbody,td,textarea,tfoot,th,thead,time,title,tr,track,u,ul,var,video,wbr";

	function __construct() {
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
     *
     * @param mixed $html The HTML specified to be loaded
     */
	public function load($html) {
		$this->html->loadHTML($html);
	}

    /**
     * Seeks all present elements in the HTML
     *
     * Uses private list of nested HTML elements to determine which elements
     * are present in the HTML
     *
     * @return array
     */
    public function seekPresentElements() {
        $pt_elements = array();

        foreach ($this->elements as $sk_element) {
            if ( $this->html->getElementsByTagName($sk_element)->length > 0 ) {
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
	public function seekClasses($element = null, $use_element_list = true) {

        ////////////////////////////////////////////////
        // Logic Cases for what element source to use //
        ////////////////////////////////////////////////

        if (!$element && $use_element_list) {
            $use = $this->elements;
        }
        if (($element && $use_element_list) || ($element && !$use_element_list)) {
            $use = $element;
        }
        if (!$element && !$use_element_list) {
            goto fail;
        }

		foreach ($this->html->getElementsByTagName( $use ) as $link) {
			$classes = explode(' ', $link->getAttribute('class'));
            return $classes;
		}

        fail:
            throw new Exception("No input source provided");
	}

}

$seek = new Seeker();
$seek->load('<html><head></head><body><a class="maple stork">Lol</a><div class="moop"><thead class="mop"><td class="meep"></td></thead></div></body></html>');

var_dump($seek->seekClasses("a", false));
