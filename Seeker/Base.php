<?php

class Base
{
	public function uncamelize($input) {
	    preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
	    $ret = $matches[0];
	    foreach ($ret as &$match) {
	        $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
	    }
	    return implode('_', $ret);
	}

	public function __construct()
	{

	}

	public static function create()
	{
		$class = get_called_class();
		return new $class;
	}

	public function __get($key)
	{
		//throw new RuntimeException("Illegal __get($key)");
	}

	public function __set($key, $val)
	{
		//throw new RuntimeException("Illegal __set($key, $val)");
	}

	/*
	 * Magic getXXX(), setXXX($val) and isXXX() methods
	 *
	 * Note that setXXXId() is an extra special case, if it receives an object, it will automatically
	 * extract the 'id' property; see unit tests for details
	 *
	 * Note that magic allows faster prototyping, however, any individual method can be seamlessly
	 * replaced by simply adding a concrete implementation (which will get called instead of the magic method)
	 *
	 */
	public function __call($method, $args)
	{
		if('get' == substr($method, 0, 3)) {
			$attr = $this->uncamelize(substr($method, 3));
			return $this->$attr;
		}
		else if('set' == substr($method, 0, 3)) {
			$attr = $this->uncamelize(substr($method, 3));
			$val = $args[0];
			if(substr($method, -2) == 'Id' && is_object($val)) {
				$val = $val->id;
			}
			$this->$attr = $val;
			return $this;
		}
		else if('is' == substr($method, 0, 2)) {
			$attr = $this->uncamelize(substr($method, 2));
			return $this->$attr;
		}

		// otherwise, method does not exist
		// TODO - ugh, this still allows a getXXX() method to avoid failure, even if the
		// XXX property does not exist
		throw new RuntimeException('__call not implemented: ' . $method);
	}

	public static function __callStatic($method, $args)
	{
		throw new RuntimeException('__callStatic not implemented: ' . $method);
	}

}

