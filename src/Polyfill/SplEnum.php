<?php

/**
 * SplEnum polyfill
 *
 * SplEnum gives the ability to emulate and create enumeration objects in PHP.
 */
abstract class Polyfill_SplEnum
{
    const __default = NULL;

    /**
     * @var mixed
     */
    protected $__default;

    /**
     * @var array
     */
    protected static $__constList;

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

        $constList = self::_getConstList($this);

        if (false === ($const = array_search($initial_value, $constList, $strict))) {
            throw new UnexpectedValueException(
                sprintf('Value not a const in enum %s', $this->_getEnumName())
            );
        }

        $this->__default = $constList[$const];
    }

    /**
     * Returns all consts (possible values) as an array.
     *
     * @param bool $include_default
     * @return array
     */
    public function getConstList($include_default = false)
    {
        $constList = self::_getConstList($this);

        if (!$include_default) {
            unset($constList['__default']);
        }

        return $constList;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->__default;
    }

    /**
     * Returns enum name for reporting purposes.
     *
     * @return string
     * @internal
     */
    protected function _getEnumName()
    {
        if (__CLASS__ === ($name = get_class($this))) {
            $name = 'SplEnum';
        }
        return $name;
    }

    /**
     * Returns const list defined for the given enumeration instance.
     *
     * @param object $object
     * @return array
     * @internal
     */
    protected static function _getConstList($object)
    {
        $class = get_class($object);

        if (!isset(self::$__constList[$class])) {
            $constList = array();
            $refClass = new ReflectionClass($class);

            while ($refClass) {
                $constList = array_merge($refClass->getConstants(), $constList);
                $refClass = $refClass->getParentClass();
            }

            self::$__constList[$class] = $constList;
        }

        return self::$__constList[$class];
    }
}
