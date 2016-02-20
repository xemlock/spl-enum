<?php

class PolyfillTest_SplEnumTest extends PHPUnit_Framework_TestCase
{
    public function testConstructorWithDefaultValue()
    {
        $planet = new A();
        $this->assertEquals(A::__default, (string) $planet);
    }

    public function testConstructorWithValidValue()
    {
        $planet = new A(A::A);
        $this->assertEquals(A::A, (string) $planet);

        $planet = new A(A::A, true);
        $this->assertEquals(A::A, (string) $planet);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testConstructorWithInvalidStrictValue()
    {
        new A(1, true);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testConstructorWithInvalidValue()
    {
        new A('c');
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testConstructorWithInvalidValueAndStrict()
    {
        new A('c', true);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testConstructorWithInvalidValueAndNoStrict()
    {
        new A('c', false);
    }

    public function testConstList()
    {
        $a = new A();

        $this->assertEquals(array(
            'A' => A::A,
        ), $a->getConstList());

        $this->assertEquals(array(
            'A' => A::A,
        ), $a->getConstList(false));

        $this->assertEquals(array(
            '__default' => A::__default,
            'A' => A::A,
        ), $a->getConstList(true));
    }

    public function testInheritedConstList()
    {
        $ab = new AB();
        $this->assertEquals(array(
            '__default' => AB::__default,
            'A' => AB::A,
            'B' => AB::B,
        ), $ab->getConstList(true));
    }
}

class A extends Polyfill_SplEnum
{
    const __default = self::A;
    const A = 'a';
}

class AB extends A
{
    const __default = self::B;
    const B = 'b';
}
