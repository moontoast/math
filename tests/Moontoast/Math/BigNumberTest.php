<?php
namespace Moontoast\Math;

class BigNumberTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        ini_set('bcmath.scale', 0);
    }

    /**
     * @covers Moontoast\Math\BigNumber::__construct
     * @covers Moontoast\Math\BigNumber::getValue
     * @covers Moontoast\Math\BigNumber::getScale
     * @covers Moontoast\Math\BigNumber::setValue
     * @covers Moontoast\Math\BigNumber::filterNumber
     * @covers Moontoast\Math\BigNumber::setDefaultScale
     */
    public function testConstruct()
    {
        $bn1 = new BigNumber('9,223,372,036,854,775,808');
        $this->assertSame('9223372036854775808', $bn1->getValue());
        $this->assertEquals(0, $bn1->getScale());

        $bn2 = new BigNumber(2147483647);
        $this->assertSame('2147483647', $bn2->getValue());
        $this->assertEquals(0, $bn2->getScale());

        $bn3 = new BigNumber($bn1, 4);
        $this->assertSame('9223372036854775808.0000', $bn3->getValue());
        $this->assertEquals(4, $bn3->getScale());

        $bn4 = new BigNumber('9223372036854775808.12345678901', 5);
        $this->assertSame('9223372036854775808.12345', $bn4->getValue());
        $this->assertEquals(5, $bn4->getScale());

        BigNumber::setDefaultScale(2);
        $bn5 = new BigNumber(2147483647);
        $this->assertSame('2147483647.00', $bn5->getValue());
        $this->assertEquals(2, $bn5->getScale());
    }

    /**
     * @covers Moontoast\Math\BigNumber::__toString
     * @covers Moontoast\Math\BigNumber::getValue
     */
    public function testToString()
    {
        $bn = new BigNumber(2147483647);
        $this->assertSame('2147483647', (string) $bn);
    }

    /**
     * @covers Moontoast\Math\BigNumber::abs
     */
    public function testAbs()
    {
        $bn1 = new BigNumber('-1234.5678', 4);
        $bn2 = new BigNumber('5678');
        $bn3 = new BigNumber('0');

        $this->assertSame('1234.5678', $bn1->abs()->getValue());
        $this->assertSame('5678', $bn2->abs()->getValue());
        $this->assertSame('0', $bn3->abs()->getValue());
    }

    /**
     * @covers Moontoast\Math\BigNumber::add
     */
    public function testAdd()
    {
        $bn = new BigNumber(2147483647);

        $this->assertSame($bn, $bn->add('9223372036854775808'));
        $this->assertSame('9223372039002259455', $bn->getValue());

        $bn->setScale(3);
        $this->assertSame('9223372039002259457.250', $bn->add(2.25)->getValue());
    }

    /**
     * @covers Moontoast\Math\BigNumber::ceil
     */
    public function testCeil()
    {
        $bn1 = new BigNumber('4.3', 1);
        $bn2 = new BigNumber('9.999', 3);
        $bn3 = new BigNumber('-3.14', 2);
        $bn4 = new BigNumber('23.00000000000000999999', 20);
        $bn5 = new BigNumber('23.00000000000001999999', 20);
        $bn6 = new BigNumber('-23.00000000000000999999', 20);
        $bn7 = new BigNumber('-23.00000000000001999999', 20);

        $this->assertSame('5', $bn1->ceil()->getValue());
        $this->assertSame('10', $bn2->ceil()->getValue());
        $this->assertSame('-3', $bn3->ceil()->getValue());
        $this->assertSame('23', $bn4->ceil()->getValue());
        $this->assertSame('24', $bn5->ceil()->getValue());
        $this->assertSame('-23', $bn6->ceil()->getValue());
        $this->assertSame('-23', $bn7->ceil()->getValue());
    }

    /**
     * @covers Moontoast\Math\BigNumber::compareTo
     */
    public function testCompareTo()
    {
        $bn1 = new BigNumber('9223372036854775808');
        $bn2 = new BigNumber(2147483647);

        $this->assertEquals(0, $bn1->compareTo('9223372036854775808'));
        $this->assertEquals(0, $bn2->compareTo(2147483647));
        $this->assertEquals(1, $bn1->compareTo($bn2));
        $this->assertEquals(-1, $bn2->compareTo($bn1));
    }

    /**
     * @covers Moontoast\Math\BigNumber::convertToBase
     */
    public function testConvertToBase()
    {
        $bn1 = new BigNumber('9223372036854775807');
        $bn2 = new BigNumber('9223372036854775810');
        $bn3 = new BigNumber('2');

        $this->assertEquals('7fffffffffffffff', $bn1->convertToBase(16));
        $this->assertEquals('8000000000000002', $bn2->convertToBase(16));
        $this->assertEquals('10', $bn3->convertToBase(2));
        $this->assertEquals('1y2p0ij32e8e7', $bn1->convertToBase(36));
    }

    /**
     * @covers Moontoast\Math\BigNumber::decrement
     */
    public function testDecrement()
    {
        $bn = new BigNumber('9223372036854775808');

        $this->assertSame($bn, $bn->decrement());
        $this->assertSame('9223372036854775807', $bn->getValue());

        $bn->setScale(4);
        $this->assertSame('9223372036854775806.0000', $bn->decrement()->getValue());
    }

    /**
     * @covers Moontoast\Math\BigNumber::divide
     */
    public function testDivide()
    {
        $bn = new BigNumber('9223372036854775808');

        $this->assertSame($bn, $bn->divide(2));
        $this->assertSame('4611686018427387904', $bn->getValue());

        $bn->setScale(5);
        $this->assertSame('1537228672809129301.33333', $bn->divide(3)->getValue());
    }

    /**
     * @covers Moontoast\Math\BigNumber::divide
     * @expectedException Moontoast\Math\Exception\ArithmeticException
     * @expectedExceptionMessage Division by zero
     */
    public function testDivideByZero()
    {
        $bn = new BigNumber('9223372036854775808');
        $bn->divide(0);
    }

    /**
     * @covers Moontoast\Math\BigNumber::floor
     */
    public function testFloor()
    {
        $bn1 = new BigNumber('4.3', 1);
        $bn2 = new BigNumber('9.999', 3);
        $bn3 = new BigNumber('-3.14', 2);
        $bn4 = new BigNumber('23.00000000000000999999', 20);
        $bn5 = new BigNumber('23.00000000000001999999', 20);
        $bn6 = new BigNumber('-23.00000000000000999999', 20);
        $bn7 = new BigNumber('-23.00000000000001999999', 20);

        $this->assertSame('4', $bn1->floor()->getValue());
        $this->assertSame('9', $bn2->floor()->getValue());
        $this->assertSame('-4', $bn3->floor()->getValue());
        $this->assertSame('23', $bn4->floor()->getValue());
        $this->assertSame('23', $bn5->floor()->getValue());
        $this->assertSame('-23', $bn6->floor()->getValue());
        $this->assertSame('-24', $bn7->floor()->getValue());
    }

    /**
     * @covers Moontoast\Math\BigNumber::getScale
     */
    public function testGetScale()
    {
        $bn1 = new BigNumber('9223372036854775808');
        $bn2 = new BigNumber(2147483647, 20);

        $this->assertEquals(0, $bn1->getScale());
        $this->assertEquals(20, $bn2->getScale());
    }

    /**
     * @covers Moontoast\Math\BigNumber::getValue
     */
    public function testGetValue()
    {
        $bn1 = new BigNumber('9223372036854775808');
        $bn2 = new BigNumber(2147483647, 20);

        $this->assertSame('9223372036854775808', $bn1->getValue());
        $this->assertSame('2147483647.00000000000000000000', $bn2->getValue());
    }

    /**
     * @covers Moontoast\Math\BigNumber::increment
     */
    public function testIncrement()
    {
        $bn = new BigNumber('9223372036854775808');

        $this->assertSame($bn, $bn->increment());
        $this->assertSame('9223372036854775809', $bn->getValue());

        $bn->setScale(3);
        $this->assertSame('9223372036854775810.000', $bn->increment()->getValue());
    }

    /**
     * @covers Moontoast\Math\BigNumber::isEqualTo
     */
    public function testIsEqualTo()
    {
        $bn1 = new BigNumber('9223372036854775808.123456', 6);
        $bn2 = new BigNumber('9223372036854775808.123461', 6);

        $this->assertFalse($bn1->isEqualTo($bn2));

        $bn1->setScale(4);
        $this->assertTrue($bn1->isEqualTo($bn2));
    }

    /**
     * @covers Moontoast\Math\BigNumber::isGreaterThan
     */
    public function testIsGreaterThan()
    {
        $bn1 = new BigNumber('9223372036854775808.123456', 6);
        $bn2 = new BigNumber('9223372036854775808.123461', 6);

        $this->assertTrue($bn2->isGreaterThan($bn1));
        $this->assertFalse($bn1->isGreaterThan($bn2));

        $bn2->setScale(4);
        $this->assertFalse($bn2->isGreaterThan($bn1));

        $bn1->setScale(4);
        $this->assertFalse($bn1->isGreaterThan($bn2));
    }

    /**
     * @covers Moontoast\Math\BigNumber::isGreaterThanOrEqualTo
     */
    public function testIsGreaterThanOrEqualTo()
    {
        $bn1 = new BigNumber('9223372036854775808.123456', 6);
        $bn2 = new BigNumber('9223372036854775808.123461', 6);

        $this->assertTrue($bn2->isGreaterThanOrEqualTo($bn1));
        $this->assertFalse($bn1->isGreaterThanOrEqualTo($bn2));

        $bn2->setScale(4);
        $this->assertTrue($bn2->isGreaterThanOrEqualTo($bn1));

        $bn1->setScale(4);
        $this->assertTrue($bn1->isGreaterThanOrEqualTo($bn2));
    }

    /**
     * @covers Moontoast\Math\BigNumber::isLessThan
     */
    public function testIsLessThan()
    {
        $bn1 = new BigNumber('9223372036854775808.123456', 6);
        $bn2 = new BigNumber('9223372036854775808.123461', 6);

        $this->assertTrue($bn1->isLessThan($bn2));
        $this->assertFalse($bn2->isLessThan($bn1));

        $bn1->setScale(4);
        $this->assertFalse($bn1->isLessThan($bn2));

        $bn2->setScale(4);
        $this->assertFalse($bn2->isLessThan($bn1));
    }

    /**
     * @covers Moontoast\Math\BigNumber::isLessThanOrEqualTo
     */
    public function testIsLessThanOrEqualTo()
    {
        $bn1 = new BigNumber('9223372036854775808.123456', 6);
        $bn2 = new BigNumber('9223372036854775808.123461', 6);

        $this->assertTrue($bn1->isLessThanOrEqualTo($bn2));
        $this->assertFalse($bn2->isLessThanOrEqualTo($bn1));

        $bn1->setScale(4);
        $this->assertTrue($bn1->isLessThanOrEqualTo($bn2));

        $bn2->setScale(4);
        $this->assertTrue($bn2->isLessThanOrEqualTo($bn1));
    }

    /**
     * @covers Moontoast\Math\BigNumber::isNegative
     */
    public function testIsNegative()
    {
        $bn1 = new BigNumber(1234);
        $bn2 = new BigNumber(-1234);
        $bn3 = new BigNumber(0);
        $bn4 = new BigNumber('-0.0000', 3);

        $this->assertFalse($bn1->isNegative());
        $this->assertTrue($bn2->isNegative());
        $this->assertFalse($bn3->isNegative());
        $this->assertFalse($bn4->isNegative());
    }

    /**
     * @covers Moontoast\Math\BigNumber::isPositive
     */
    public function testIsPositive()
    {
        $bn1 = new BigNumber(1234);
        $bn2 = new BigNumber(-1234);
        $bn3 = new BigNumber(0);
        $bn4 = new BigNumber('-0.0000', 3);

        $this->assertTrue($bn1->isPositive());
        $this->assertFalse($bn2->isPositive());
        $this->assertFalse($bn3->isPositive());
        $this->assertFalse($bn4->isPositive());
    }

    /**
     * @covers Moontoast\Math\BigNumber::mod
     */
    public function testMod()
    {
        $bn = new BigNumber('9223372036854775808');

        $this->assertSame($bn, $bn->mod(3));
        $this->assertSame('2', $bn->getValue());
        $this->assertSame('0', $bn->mod(2)->getValue());
    }

    /**
     * @covers Moontoast\Math\BigNumber::mod
     * @expectedException Moontoast\Math\Exception\ArithmeticException
     * @expectedExceptionMessage Division by zero
     */
    public function testModDivisionByZero()
    {
        $bn = new BigNumber('9223372036854775808');
        $bn->mod(0);
    }

    /**
     * @covers Moontoast\Math\BigNumber::multiply
     */
    public function testMultiply()
    {
        $bn1 = new BigNumber('9223372036854775808.34747474747474747', 17);
        $bn2 = new BigNumber('9223372036854775808.34747474747474747', 17);
        $bn3 = new BigNumber('9223372036854775808.34747474747474747', 17);

        $bn1->setScale(0);
        $this->assertSame($bn1, $bn1->multiply(35));
        $this->assertSame('322818021289917153292', $bn1->getValue());

        $bn2->setScale(3);
        $this->assertSame($bn2, $bn2->multiply(35));
        $this->assertSame('322818021289917153292.161', $bn2->getValue());

        $this->assertSame($bn3, $bn3->multiply(35));
        $this->assertSame('322818021289917153292.16161616161616145', $bn3->getValue());
    }

    /**
     * @covers Moontoast\Math\BigNumber::negate
     */
    public function testNegate()
    {
        $bn1 = new BigNumber(1234);
        $bn2 = new BigNumber('0.000000567', 9);

        $this->assertSame('-1234', $bn1->negate()->getValue());
        $this->assertSame('1234', $bn1->negate()->getValue());

        $bn2->setScale(7);
        $this->assertSame('-0.0000005', $bn2->negate()->getValue());

        $bn2->setScale(6);
        $this->assertSame('0.000000', $bn2->negate()->getValue());
    }

    /**
     * @covers Moontoast\Math\BigNumber::pow
     */
    public function testPow()
    {
        $bn1 = new BigNumber(16);
        $bn2 = new BigNumber('4294967296.5352423424523', 13);

        $this->assertSame($bn1, $bn1->pow(8));
        $this->assertSame('4294967296', $bn1->getValue());

        $bn2->setScale(6);
        $this->assertSame('18446744078307248328.820606', $bn2->pow(2)->getValue());
    }

    /**
     * @covers Moontoast\Math\BigNumber::powMod
     */
    public function testPowMod()
    {
        $bn1 = new BigNumber(16);
        $bn2 = clone $bn1;

        $this->assertSame($bn1, $bn1->powMod(8, 2));
        $this->assertSame('0', $bn1->getValue());

        $bn1->setScale(3);
        $this->assertSame('0.000', $bn1->powMod(8, 2)->getValue());
    }

    /**
     * @covers Moontoast\Math\BigNumber::powMod
     * @expectedException Moontoast\Math\Exception\ArithmeticException
     * @expectedExceptionMessage Division by zero
     */
    public function testPowModDivisionByZero()
    {
        $bn = new BigNumber(16);
        $bn->powMod(8, 0);
    }

    /**
     * @covers Moontoast\Math\BigNumber::round
     */
    public function testRound()
    {
        $bn1 = new BigNumber('3.4', 1);
        $bn2 = new BigNumber('3.5', 1);
        $bn3 = new BigNumber('3.6', 1);
        $bn4 = new BigNumber('1.95583', 5);
        $bn5 = new BigNumber('1241757');
        $bn6 = new BigNumber('-3.4', 1);
        $bn7 = new BigNumber('-3.5', 1);
        $bn8 = new BigNumber('-3.6', 1);

        $this->assertSame('3', $bn1->round()->getValue());
        $this->assertSame('4', $bn2->round()->getValue());
        $this->assertSame('4', $bn3->round()->getValue());
        $this->assertSame('2', $bn4->round()->getValue());
        $this->assertSame('1241757', $bn5->round()->getValue());
        $this->assertSame('-3', $bn6->round()->getValue());
        $this->assertSame('-4', $bn7->round()->getValue());
        $this->assertSame('-4', $bn8->round()->getValue());
    }

    /**
     * @covers Moontoast\Math\BigNumber::setScale
     */
    public function testSetScale()
    {
        $bn = new BigNumber('9223372036854775808');

        $this->assertSame($bn, $bn->setScale(6));
        $this->assertEquals(6, $bn->getScale());
    }

    /**
     * @covers Moontoast\Math\BigNumber::setValue
     */
    public function testSetValue()
    {
        $bn = new BigNumber(1);

        $this->assertSame($bn, $bn->setValue(1234.657));
        $this->assertSame('1234', $bn->getValue());
    }

    /**
     * @covers Moontoast\Math\BigNumber::setValue
     */
    public function testSetValueWithScale()
    {
        $bn = new BigNumber(1);
        $bn->setScale(2);

        $this->assertSame($bn, $bn->setValue(1234.657));
        $this->assertSame('1234.65', $bn->getValue());
        $this->assertEquals(2, $bn->getScale());
    }

    /**
     * @covers Moontoast\Math\BigNumber::signum
     */
    public function testSignum()
    {
        $bn1 = new BigNumber(1234);
        $bn2 = new BigNumber(-1234);
        $bn3 = new BigNumber(0);
        $bn4 = new BigNumber('0.0000005', 7);
        $bn5 = new BigNumber('-0.0000005', 7);

        $this->assertEquals(1, $bn1->signum());
        $this->assertEquals(-1, $bn2->signum());
        $this->assertEquals(0, $bn3->signum());

        $bn4->setScale(0);
        $this->assertEquals(0, $bn4->signum(0));

        $bn4->setScale(7);
        $this->assertEquals(1, $bn4->signum());

        $bn4->setScale(7);
        $this->assertEquals(-1, $bn5->signum());
    }

    /**
     * @covers Moontoast\Math\BigNumber::shiftLeft
     */
    public function testShiftLeft()
    {
        $bn = new BigNumber(1);

        $this->assertSame($bn, $bn->shiftLeft(30));
        $this->assertSame('1073741824', $bn->getValue());
        $this->assertSame('4611686018427387904', $bn->shiftLeft(32)->getValue());
        $this->assertSame('42535295865117307932921825928971026432', $bn->shiftLeft(63)->getValue());
        $this->assertSame('784637716923335095479473677900958302012794430558004314112', $bn->shiftLeft(64)->getValue());
        $this->assertSame('3369993333393829974333376885877453834204643052817571560137951281152', $bn->shiftLeft(32)->getValue());
    }

    /**
     * @covers Moontoast\Math\BigNumber::shiftRight
     */
    public function testShiftRight()
    {
        $bn = new BigNumber('3369993333393829974333376885877453834204643052817571560137951281152');

        $this->assertSame($bn, $bn->shiftRight(32));
        $this->assertSame('784637716923335095479473677900958302012794430558004314112', $bn->getValue());
        $this->assertSame('42535295865117307932921825928971026432', $bn->shiftRight(64)->getValue());
        $this->assertSame('4611686018427387904', $bn->shiftRight(63)->getValue());
        $this->assertSame('1073741824', $bn->shiftRight(32)->getValue());
        $this->assertSame('1', $bn->shiftRight(30)->getValue());
    }

    /**
     * @covers Moontoast\Math\BigNumber::sqrt
     */
    public function testSqrt()
    {
        $bn1 = new BigNumber(16);
        $bn2 = new BigNumber(17);
        $bn3 = clone $bn2;

        $this->assertSame($bn1, $bn1->sqrt());
        $this->assertSame('4', $bn1->getValue());
        $this->assertSame('4', $bn2->sqrt()->getValue());

        $bn3->setScale(8);
        $this->assertSame('4.12310562', $bn3->sqrt()->getValue());
    }

    /**
     * @covers Moontoast\Math\BigNumber::subtract
     */
    public function testSubtract()
    {
        $bn = new BigNumber(2147483647);

        $this->assertSame($bn, $bn->subtract('9223372036854775808'));
        $this->assertSame('-9223372034707292161', $bn->getValue());

        $bn->setScale(3);
        $this->assertSame('-9223372034707292163.250', $bn->subtract(2.25)->getValue());
    }

    /**
     * @covers Moontoast\Math\BigNumber::filterNumber
     */
    public function testFilterNumber()
    {
        $filterNumber = new \ReflectionMethod('Moontoast\Math\BigNumber', 'filterNumber');
        $filterNumber->setAccessible(true);

        $bn1 = new BigNumber(0);
        $bn2 = new BigNumber(2147483647);

        $this->assertSame('1234', $filterNumber->invoke($bn1, 1234));
        $this->assertSame('1234567890.1234', $filterNumber->invoke($bn1, 1234567890.1234));
        $this->assertSame('9223372036854775808', $filterNumber->invoke($bn1, '9,223,372,036,854,775,808'));
        $this->assertSame('9223372036854775808.432', $filterNumber->invoke($bn1, '9,223,372,036,854,775,808.432'));
        $this->assertSame('2147483647', $filterNumber->invoke($bn1, $bn2));
    }

    /**
     * @covers Moontoast\Math\BigNumber::baseConvert
     */
    public function testBaseConvert()
    {
        $fromBase = array(2, 8, 10, 16, 36);
        $toBase = array(2, 8, 10, 16, 36);
        $convertValues = array(10, 27, 39, 039, 0x5F, '10', '27', '39', '5F', '5f', '3XYZ', '3xyz', '5f$@');

        foreach ($fromBase as $from) {
            foreach ($toBase as $to) {
                foreach ($convertValues as $val) {
                    // Test that our baseConvert matches PHP's base_convert
                    $phpRes = base_convert($val, $from, $to);
                    $bnRes = BigNumber::baseConvert($val, $from, $to);
                    $this->assertEquals(
                        $phpRes,
                        $bnRes,
                        "from base is {$from}, to base is {$to}, value is {$val}, baseConvert result is {$bnRes}"
                    );
                }
            }
        }
    }

    /**
     * @covers Moontoast\Math\BigNumber::convertFromBase10
     */
    public function testConvertFromBase10()
    {
        $toBase = array(2, 8, 10, 16, 36);
        $convertValues = array(10, 27, 39, 039, 0x5F, '10', '27', '39');

        foreach ($toBase as $to) {
            foreach ($convertValues as $val) {
                // Test that our baseConvert matches PHP's base_convert
                $phpRes = base_convert($val, 10, $to);
                $bnRes = BigNumber::convertFromBase10($val, $to);
                $this->assertEquals(
                    $phpRes,
                    $bnRes,
                    "from base is 10, to base is {$to}, value is {$val}, convertFromBase10 result is {$bnRes}"
                );
            }
        }
    }

    /**
     * @covers Moontoast\Math\BigNumber::convertFromBase10
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid `to base' (1)
     */
    public function testConvertFromBase10ExceptionBaseLessThan2()
    {
        $n = BigNumber::convertFromBase10('1234', 1);
    }

    /**
     * @covers Moontoast\Math\BigNumber::convertFromBase10
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid `to base' (37)
     */
    public function testConvertFromBase10ExceptionBaseGreaterThan36()
    {
        $n = BigNumber::convertFromBase10('1234', 37);
    }

    /**
     * @covers Moontoast\Math\BigNumber::convertFromBase10
     */
    public function testConvertFromBase10NegativeNumbers()
    {
        $toBase = array(2, 8, 10, 16, 36);
        $convertValues = array(-10, -27, -39, -039, -0x5F, '-10', '-27', '-39');

        foreach ($toBase as $to) {
            foreach ($convertValues as $val) {
                // Test that our baseConvert matches PHP's base_convert
                $phpRes = base_convert($val, 10, $to);
                $bnRes = BigNumber::convertFromBase10($val, $to);
                $this->assertEquals(
                    $phpRes,
                    $bnRes,
                    "from base is 10, to base is {$to}, value is {$val}, convertFromBase10 result is {$bnRes}"
                );
            }
        }
    }

    /**
     * @covers Moontoast\Math\BigNumber::convertToBase10
     */
    public function testConvertToBase10()
    {
        $fromBase = array(2, 8, 10, 16, 36);
        $convertValues = array(10, 27, 39, 039, 0x5F, '10', '27', '39', '5F', '5f', '3XYZ', '3xyz', '5f$@');

        foreach ($fromBase as $from) {
            foreach ($convertValues as $val) {
                // Test that our baseConvert matches PHP's base_convert
                $phpRes = base_convert($val, $from, 10);
                $bnRes = BigNumber::convertToBase10($val, $from);
                $this->assertEquals(
                    $phpRes,
                    $bnRes,
                    "from base is {$from}, to base is 10, value is {$val}, convertToBase10 result is {$bnRes}"
                );
            }
        }
    }

    /**
     * @covers Moontoast\Math\BigNumber::convertToBase10
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid `from base' (1)
     */
    public function testConvertToBase10ExceptionBaseLessThan2()
    {
        $n = BigNumber::convertToBase10('5F', 1);
    }

    /**
     * @covers Moontoast\Math\BigNumber::convertToBase10
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid `from base' (37)
     */
    public function testConvertToBase10ExceptionBaseGreaterThan36()
    {
        $n = BigNumber::convertToBase10('5F', 37);
    }

    /**
     * @covers Moontoast\Math\BigNumber::setDefaultScale
     */
    public function testSetDefaultScale()
    {
        BigNumber::setDefaultScale(23);

        $this->assertEquals(23, ini_get('bcmath.scale'));
    }

    /**
     * Tests the possibility of a "negative" string zero, i.e. "-0.000"
     *
     * The sign of -0 is still a negative sign. This is ultimately calculated
     * by bccomp(), according to which, when -0.000 is compared to 0.000, it
     * will return a -1, meaning -0.000 is less than 0.000, but -0 compared to
     * 0 will return a 0, meaning the two are equal. This is odd, but it is the
     * expected behavior.
     */
    public function testNegativeZero()
    {
        $bn = new BigNumber('-0.0000005', 3);

        $this->assertSame('-0.000', $bn->getValue());
        $this->assertEquals(-1, $bn->signum());
        $this->assertTrue($bn->isNegative());
    }
}
