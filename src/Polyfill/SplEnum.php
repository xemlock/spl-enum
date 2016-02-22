<?php

/**
 * SplEnum polyfill
 *
 * SplEnum gives the ability to emulate and create enumeration objects in PHP.
 */
abstract class Polyfill_SplEnum implements Serializable
{
    const __default = NULL;

    /**
     * @var mixed
     */
    protected $__default;

    /**
     * @var bool
     */
    protected $_strict;

    /**
     * @var array
     */
    protected static $_classConstants;

    /**
     * Creates a new value of enumerated type.
     *
     * @param mixed $initial_value
     * @param bool $strict
     */
    public function __construct($initial_value = null, $strict = false)
    {
        // use default value only if no arguments have been passed
        if (func_num_args() < 1) {
            $initial_value = constant(get_class($this) . '::__default');
        }

        $this->_strict = (bool) $strict;
        $this->setValue($initial_value);
    }

    /**
     * Returns all consts (possible values) as an array.
     *
     * @param bool $include_default
     * @return array
     */
    public function getConstList($include_default = false)
    {
        $constList = self::_getClassConstants($this);

        if (!$include_default) {
            unset($constList['__default']);
        }

        return $constList;
    }

    /**
     * Returns enum value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->__default;
    }

    /**
     * Sets new enum value
     *
     * @param $value
     */
    public function setValue($value)
    {
        $constList = self::_getClassConstants($this);

        if (false === ($const = array_search($value, $constList, $this->_strict))) {
            if (__CLASS__ === ($class = get_class($this))) {
                $class = 'SplEnum';
            }
            throw new UnexpectedValueException(
                sprintf('Value not a const in enum %s', $class)
            );
        }

        $this->__default = $constList[$const];
    }

    public function serialize()
    {
        // Only __default property is exported
        return serialize(array('__default' => $this->__default));
    }

    public function unserialize($serialized)
    {
        // When unserializing, SplEnum value is set to __default, see:
        // http://stackoverflow.com/a/25124558
        $this->__default = constant(get_class($this) . '::__default');

        // and the strict checking is disabled
        $this->_strict = false;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->__default;
    }

    /**
     * Returns const list defined for the given enumeration instance.
     *
     * @param object $object
     * @return array
     * @internal
     */
    protected static function _getClassConstants($object)
    {
        $class = get_class($object);

        if (!isset(self::$_classConstants[$class])) {
            $constList = array();
            $refClass = new ReflectionClass($class);

            while ($refClass) {
                $constList = array_merge($refClass->getConstants(), $constList);
                $refClass = $refClass->getParentClass();
            }

            self::$_classConstants[$class] = $constList;
        }

        return self::$_classConstants[$class];
    }
}
