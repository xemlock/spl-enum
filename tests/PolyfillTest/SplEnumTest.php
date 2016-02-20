<?php

class PolyfillTest_SplEnumTest extends PHPUnit_Framework_TestCase
{
    public function testConstructorWithDefaultValue()
    {
        $a = new A();
        $this->assertEquals(A::__default, (string) $a);
    }

    public function testConstructorWithValidValue()
    {
        $a = new A(A::A);
        $this->assertEquals(A::A, (string) $a);

        $a = new A(A::A, true);
        $this->assertEquals(A::A, (string) $a);
    }

    public function testConstructorWithCoercion()
    {
        $i = new I('I');
        $this->assertEquals((string) I::ZERO, (string) $i);
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

    public function testSerialize()
    {
        $ab = new AB(AB::A);
        $serialized = serialize($ab);

        $ab1 = unserialize($serialized);
        $this->assertInstanceOf('AB', $ab1);
        $this->assertEquals(AB::A, (string)$ab1);

        $serialized2 = str_replace(';s:1:"a";', ';s:1:"c";', $serialized);
        $ab2 = unserialize($serialized2);
        $this->assertInstanceOf('AB', $ab2);
        $this->assertEquals(AB::__default, (string)$ab2);
    }

    public function testSerializeWithCoercion()
    {
        $i = new I('X');
        $iSerialized = serialize($i);

        $i1 = unserialize($iSerialized);
        $this->assertEquals((string) I::ZERO, (string) $i1);
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

class I extends Polyfill_SplEnum
{
    const __default = 0;
    const ZERO = 0;
    const ONE  = 1;
    const TWO  = 2;
}
