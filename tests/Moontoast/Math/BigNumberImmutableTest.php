<?php
/**
 * @license See the file LICENSE for copying permission
 */

namespace Moontoast\Math;

use PHPUnit\Framework\TestCase;

class BigNumberImmutableImmutableTest extends TestCase
{
    protected function setUp()
    {
        ini_set('bcmath.scale', 0);
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::__construct
     * @covers \Moontoast\Math\BigNumberImmutable::getValue
     * @covers \Moontoast\Math\BigNumberImmutable::getScale
     * @covers \Moontoast\Math\BigNumberImmutable::getScale
     */
    public function testConstruct()
    {
        $bn1 = new BigNumberImmutable('9,223,372,036,854,775,808');
        $this->assertSame('9223372036854775808', $bn1->getValue());
        $this->assertEquals(0, $bn1->getScale());

        $bn2 = new BigNumberImmutable(2147483647);
        $this->assertSame('2147483647', $bn2->getValue());
        $this->assertEquals(0, $bn2->getScale());

        $bn3 = new BigNumberImmutable($bn1, 4);
        $this->assertSame('9223372036854775808.0000', $bn3->getValue());
        $this->assertEquals(4, $bn3->getScale());

        $bn4 = new BigNumberImmutable('9223372036854775808.12345678901', 5);
        $this->assertSame('9223372036854775808.12345', $bn4->getValue());
        $this->assertEquals(5, $bn4->getScale());

        BigNumberImmutable::setDefaultScale(2);
        $bn5 = new BigNumberImmutable(2147483647);
        $this->assertSame('2147483647.00', $bn5->getValue());
        $this->assertEquals(2, $bn5->getScale());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::withScale
     */
    public function testWithScale()
    {
        ini_set('bcmath.scale', 5);

        $bn = new BigNumberImmutable('9223372036854775808.12345');

        $bn2 = $bn->withScale(2);
        $this->assertSame('9223372036854775808.12', $bn2->getValue());
        $this->assertSame(2, $bn2->getScale());

        $bn3 = $bn->withScale(0);
        $this->assertSame('9223372036854775808', $bn3->getValue());
        $this->assertSame(0, $bn3->getScale());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::withValue
     */
    public function testWithValue()
    {
        $bn = new BigNumberImmutable(1);
        $bn2 = $bn->withValue(1234.657);

        $this->assertNotSame($bn, $bn2);
        $this->assertSame('1234', $bn2->getValue());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::__toString
     * @covers \Moontoast\Math\BigNumberImmutable::getValue
     */
    public function testToString()
    {
        $bn = new BigNumberImmutable(2147483647);
        $this->assertSame('2147483647', (string) $bn);
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::abs
     */
    public function testAbs()
    {
        $bn1 = new BigNumberImmutable('-1234.5678', 4);
        $bn2 = new BigNumberImmutable('5678');
        $bn3 = new BigNumberImmutable('0');

        $this->assertSame('1234.5678', $bn1->abs()->getValue());
        $this->assertSame('5678', $bn2->abs()->getValue());
        $this->assertSame('0', $bn3->abs()->getValue());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::add
     */
    public function testAdd()
    {
        $bn = new BigNumberImmutable(2147483647);
        $bn2 = $bn->add('9223372036854775808');

        $this->assertNotSame($bn, $bn2);
        $this->assertSame('9223372039002259455', $bn2->getValue());

        $bn3 = $bn2->withScale(3);
        $this->assertNotSame($bn3, $bn2);
        $this->assertSame('9223372039002259457.250', $bn3->add(2.25)->getValue());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::ceil
     */
    public function testCeil()
    {
        $bn1 = new BigNumberImmutable('4.3', 1);
        $bn2 = new BigNumberImmutable('9.999', 3);
        $bn3 = new BigNumberImmutable('-3.14', 2);
        $bn4 = new BigNumberImmutable('23.00000000000000999999', 20);
        $bn5 = new BigNumberImmutable('23.00000000000001999999', 20);
        $bn6 = new BigNumberImmutable('-23.00000000000000999999', 20);
        $bn7 = new BigNumberImmutable('-23.00000000000001999999', 20);

        $this->assertSame('5', $bn1->ceil()->getValue());
        $this->assertSame('10', $bn2->ceil()->getValue());
        $this->assertSame('-3', $bn3->ceil()->getValue());
        $this->assertSame('23', $bn4->ceil()->getValue());
        $this->assertSame('24', $bn5->ceil()->getValue());
        $this->assertSame('-23', $bn6->ceil()->getValue());
        $this->assertSame('-23', $bn7->ceil()->getValue());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::compareTo
     */
    public function testCompareTo()
    {
        $bn1 = new BigNumberImmutable('9223372036854775808');
        $bn2 = new BigNumberImmutable(2147483647);

        $this->assertEquals(0, $bn1->compareTo('9223372036854775808'));
        $this->assertEquals(0, $bn2->compareTo(2147483647));
        $this->assertEquals(1, $bn1->compareTo($bn2));
        $this->assertEquals(-1, $bn2->compareTo($bn1));
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::convertToBase
     */
    public function testConvertToBase()
    {
        $bn1 = new BigNumberImmutable('9223372036854775807');
        $bn2 = new BigNumberImmutable('9223372036854775810');
        $bn3 = new BigNumberImmutable('2');

        $this->assertEquals('7fffffffffffffff', $bn1->convertToBase(16));
        $this->assertEquals('8000000000000002', $bn2->convertToBase(16));
        $this->assertEquals('10', $bn3->convertToBase(2));
        $this->assertEquals('1y2p0ij32e8e7', $bn1->convertToBase(36));
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::decrement
     */
    public function testDecrement()
    {
        $bn = new BigNumberImmutable('9223372036854775808');
        $bn2 = $bn->decrement();

        $this->assertNotSame($bn, $bn2);
        $this->assertSame('9223372036854775807', $bn2->getValue());

        $bn3 = $bn2->withScale(4);
        $this->assertSame('9223372036854775806.0000', $bn3->decrement()->getValue());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::divide
     */
    public function testDivide()
    {
        $bn = new BigNumberImmutable('9223372036854775808');
        $bn2 = $bn->divide(2);

        $this->assertNotSame($bn, $bn2);
        $this->assertSame('4611686018427387904', $bn2->getValue());

        $bn3 = $bn2->withScale(5);
        $this->assertSame('1537228672809129301.33333', $bn3->divide(3)->getValue());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::divide
     * @expectedException Moontoast\Math\Exception\ArithmeticException
     * @expectedExceptionMessage Division by zero
     */
    public function testDivideByZero()
    {
        $bn = new BigNumberImmutable('9223372036854775808');
        $bn->divide(0);
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::floor
     */
    public function testFloor()
    {
        $bn1 = new BigNumberImmutable('4.3', 1);
        $bn2 = new BigNumberImmutable('9.999', 3);
        $bn3 = new BigNumberImmutable('-3.14', 2);
        $bn4 = new BigNumberImmutable('23.00000000000000999999', 20);
        $bn5 = new BigNumberImmutable('23.00000000000001999999', 20);
        $bn6 = new BigNumberImmutable('-23.00000000000000999999', 20);
        $bn7 = new BigNumberImmutable('-23.00000000000001999999', 20);

        $this->assertSame('4', $bn1->floor()->getValue());
        $this->assertSame('9', $bn2->floor()->getValue());
        $this->assertSame('-4', $bn3->floor()->getValue());
        $this->assertSame('23', $bn4->floor()->getValue());
        $this->assertSame('23', $bn5->floor()->getValue());
        $this->assertSame('-23', $bn6->floor()->getValue());
        $this->assertSame('-24', $bn7->floor()->getValue());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::getScale
     */
    public function testGetScale()
    {
        $bn1 = new BigNumberImmutable('9223372036854775808');
        $bn2 = new BigNumberImmutable(2147483647, 20);

        $this->assertEquals(0, $bn1->getScale());
        $this->assertEquals(20, $bn2->getScale());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::getValue
     */
    public function testGetValue()
    {
        $bn1 = new BigNumberImmutable('9223372036854775808');
        $bn2 = new BigNumberImmutable(2147483647, 20);

        $this->assertSame('9223372036854775808', $bn1->getValue());
        $this->assertSame('2147483647.00000000000000000000', $bn2->getValue());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::increment
     */
    public function testIncrement()
    {
        $bn = new BigNumberImmutable('9223372036854775808');
        $bn2 = $bn->increment();

        $this->assertNotSame($bn, $bn2);
        $this->assertSame('9223372036854775809', $bn2->getValue());

        $bn3 = $bn2->withScale(3);
        $this->assertSame('9223372036854775810.000', $bn3->increment()->getValue());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::isEqualTo
     */
    public function testIsEqualTo()
    {
        $bn1 = new BigNumberImmutable('9223372036854775808.123456', 6);
        $bn2 = new BigNumberImmutable('9223372036854775808.123461', 6);

        $this->assertFalse($bn1->isEqualTo($bn2));

        $bn3 = $bn1->withScale(4);
        $bn4 = $bn2->withScale(4);
        $this->assertTrue($bn3->isEqualTo($bn4));
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::isGreaterThan
     */
    public function testIsGreaterThan()
    {
        $bn1 = new BigNumberImmutable('9223372036854775808.123456', 6);
        $bn2 = new BigNumberImmutable('9223372036854775808.123461', 6);

        $this->assertTrue($bn2->isGreaterThan($bn1));
        $this->assertFalse($bn1->isGreaterThan($bn2));

        $bn3 = $bn2->withScale(4);
        $this->assertFalse($bn3->isGreaterThan($bn1));

        $bn4 = $bn1->withScale(4);
        $this->assertFalse($bn4->isGreaterThan($bn2));
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::isGreaterThanOrEqualTo
     */
    public function testIsGreaterThanOrEqualTo()
    {
        $bn1 = new BigNumberImmutable('9223372036854775808.123456', 6);
        $bn2 = new BigNumberImmutable('9223372036854775808.123461', 6);

        $this->assertTrue($bn2->isGreaterThanOrEqualTo($bn1));
        $this->assertFalse($bn1->isGreaterThanOrEqualTo($bn2));

        $bn3 = $bn2->withScale(4);
        $this->assertTrue($bn3->isGreaterThanOrEqualTo($bn1));

        $bn4 = $bn1->withScale(4);
        $this->assertTrue($bn4->isGreaterThanOrEqualTo($bn2));
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::isLessThan
     */
    public function testIsLessThan()
    {
        $bn1 = new BigNumberImmutable('9223372036854775808.123456', 6);
        $bn2 = new BigNumberImmutable('9223372036854775808.123461', 6);

        $this->assertTrue($bn1->isLessThan($bn2));
        $this->assertFalse($bn2->isLessThan($bn1));

        $bn3 = $bn1->withScale(4);
        $this->assertFalse($bn3->isLessThan($bn2));

        $bn4 = $bn2->withScale(4);
        $this->assertFalse($bn4->isLessThan($bn1));
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::isLessThanOrEqualTo
     */
    public function testIsLessThanOrEqualTo()
    {
        $bn1 = new BigNumberImmutable('9223372036854775808.123456', 6);
        $bn2 = new BigNumberImmutable('9223372036854775808.123461', 6);

        $this->assertTrue($bn1->isLessThanOrEqualTo($bn2));
        $this->assertFalse($bn2->isLessThanOrEqualTo($bn1));

        $bn3 = $bn1->withScale(4);
        $this->assertTrue($bn3->isLessThanOrEqualTo($bn2));

        $bn4 = $bn2->withScale(4);
        $this->assertTrue($bn4->isLessThanOrEqualTo($bn1));
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::isNegative
     */
    public function testIsNegative()
    {
        $bn1 = new BigNumberImmutable(1234);
        $bn2 = new BigNumberImmutable(-1234);
        $bn3 = new BigNumberImmutable(0);
        $bn4 = new BigNumberImmutable('-0.0000', 3);

        $this->assertFalse($bn1->isNegative());
        $this->assertTrue($bn2->isNegative());
        $this->assertFalse($bn3->isNegative());
        $this->assertFalse($bn4->isNegative());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::isPositive
     */
    public function testIsPositive()
    {
        $bn1 = new BigNumberImmutable(1234);
        $bn2 = new BigNumberImmutable(-1234);
        $bn3 = new BigNumberImmutable(0);
        $bn4 = new BigNumberImmutable('-0.0000', 3);

        $this->assertTrue($bn1->isPositive());
        $this->assertFalse($bn2->isPositive());
        $this->assertFalse($bn3->isPositive());
        $this->assertFalse($bn4->isPositive());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::mod
     */
    public function testMod()
    {
        $bn = new BigNumberImmutable('9223372036854775808');
        $mod = $bn->mod(3);

        $this->assertNotSame($bn, $mod);
        $this->assertSame('2', $mod->getValue());
        $this->assertSame('0', $mod->mod(2)->getValue());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::mod
     * @expectedException Moontoast\Math\Exception\ArithmeticException
     * @expectedExceptionMessage Division by zero
     */
    public function testModDivisionByZero()
    {
        $bn = new BigNumberImmutable('9223372036854775808');
        $bn->mod(0);
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::multiply
     */
    public function testMultiply()
    {
        $bn1 = new BigNumberImmutable('9223372036854775808.34747474747474747', 17);
        $bn2 = new BigNumberImmutable('9223372036854775808.34747474747474747', 17);
        $bn3 = new BigNumberImmutable('9223372036854775808.34747474747474747', 17);

        $this->assertNotSame($bn1, $bn1->multiply(35));
        $this->assertSame('322818021289917153292', $bn1->multiply(35)->withScale(0)->getValue());

        $this->assertNotSame($bn2, $bn2->multiply(35));
        $this->assertSame('322818021289917153292.161', $bn2->multiply(35)->withScale(3)->getValue());

        $this->assertNotSame($bn3, $bn3->multiply(35));
        $this->assertSame('322818021289917153292.16161616161616145', $bn3->multiply(35)->getValue());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::negate
     */
    public function testNegate()
    {
        $bn1 = new BigNumberImmutable(1234);
        $bn2 = new BigNumberImmutable('0.000000567', 9);

        $negative = $bn1->negate();
        $this->assertSame('-1234', $negative->getValue());
        $this->assertSame('1234', $negative->negate()->getValue());

        $bn3 = $bn2->withScale(7);
        $this->assertSame('-0.0000005', $bn3->negate()->getValue());

        $bn4 = $bn2->withScale(6);
        $this->assertSame('0.000000', $bn4->negate()->getValue());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::pow
     */
    public function testPow()
    {
        $bn1 = new BigNumberImmutable(16);
        $bn2 = new BigNumberImmutable('4294967296.5352423424523', 13);

        $this->assertNotSame($bn1, $bn1->pow(8));
        $this->assertSame('4294967296', $bn1->pow(8)->getValue());

        $bn3 = $bn2->pow(2)->withScale(6);
        $this->assertSame('18446744078307248328.820606', $bn3->getValue());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::powMod
     */
    public function testPowMod()
    {
        $bn1 = new BigNumberImmutable(16);

        $this->assertNotSame($bn1, $bn1->powMod(8, 2));
        $this->assertSame('0', $bn1->powMod(8, 2)->getValue());

        $bn2 = $bn1->powMod(8, 2)->withScale(3);
        $this->assertSame('0.000', $bn2->getValue());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::powMod
     * @expectedException Moontoast\Math\Exception\ArithmeticException
     * @expectedExceptionMessage Division by zero
     */
    public function testPowModDivisionByZero()
    {
        $bn = new BigNumberImmutable(16);
        $bn->powMod(8, 0);
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::round
     */
    public function testRound()
    {
        $bn1 = new BigNumberImmutable('3.4', 1);
        $bn2 = new BigNumberImmutable('3.5', 1);
        $bn3 = new BigNumberImmutable('3.6', 1);
        $bn4 = new BigNumberImmutable('1.95583', 5);
        $bn5 = new BigNumberImmutable('1241757');
        $bn6 = new BigNumberImmutable('-3.4', 1);
        $bn7 = new BigNumberImmutable('-3.5', 1);
        $bn8 = new BigNumberImmutable('-3.6', 1);

        $this->assertSame('3', $bn1->round()->getValue());
        $this->assertSame('4', $bn2->round()->getValue());
        $this->assertSame('4', $bn3->round()->getValue());
        $this->assertSame('2', $bn4->round()->getValue());
        $this->assertSame('1241757', $bn5->round()->getValue());
        $this->assertSame('-3', $bn6->round()->getValue());
        $this->assertSame('-4', $bn7->round()->getValue());
        $this->assertSame('-4', $bn8->round()->getValue());
    }

    public function testWithScaleWithValue()
    {
        $bn = new BigNumberImmutable(1);
        $bn2 = $bn->withScale(2)->withValue(1234.657);

        $this->assertSame('1234.65', $bn2->getValue());
        $this->assertEquals(2, $bn2->getScale());
    }

    public function testWithValueWithScale()
    {
        $bn = new BigNumberImmutable(1);
        $bn2 = $bn->withValue(1234.657)->withScale(2);
        $this->assertSame('1234.00', $bn2->getValue());
        $this->assertEquals(2, $bn2->getScale());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::signum
     */
    public function testSignum()
    {
        $bn1 = new BigNumberImmutable(1234);
        $bn2 = new BigNumberImmutable(-1234);
        $bn3 = new BigNumberImmutable(0);
        $bn4 = new BigNumberImmutable('0.0000005', 7);
        $bn5 = new BigNumberImmutable('-0.0000005', 7);

        $this->assertEquals(1, $bn1->signum());
        $this->assertEquals(-1, $bn2->signum());
        $this->assertEquals(0, $bn3->signum());

        $this->assertEquals(0, $bn4->withScale(0)->signum());

        $this->assertEquals(1, $bn4->withScale(7)->signum());

        $this->assertEquals(-1, $bn5->withScale(7)->signum());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::shiftLeft
     */
    public function testShiftLeft()
    {
        $bn = new BigNumberImmutable(1);

        $bn2 = $bn->shiftLeft(30);
        $this->assertNotSame($bn, $bn2);
        $this->assertSame('1073741824', $bn2->getValue());
        $bn3 = $bn2->shiftLeft(32);
        $this->assertSame('4611686018427387904', $bn3->getValue());

        $bn4 = $bn3->shiftLeft(63);
        $this->assertSame('42535295865117307932921825928971026432', $bn4->getValue());

        $bn5 = $bn4->shiftLeft(64);
        $this->assertSame('784637716923335095479473677900958302012794430558004314112', $bn5->getValue());

        $this->assertSame(
            '3369993333393829974333376885877453834204643052817571560137951281152',
            $bn5->shiftLeft(32)->getValue()
        );
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::shiftRight
     */
    public function testShiftRight()
    {
        $bn = new BigNumberImmutable('3369993333393829974333376885877453834204643052817571560137951281152');

        $bn2 = $bn->shiftRight(32);
        $this->assertNotSame($bn, $bn2);
        $this->assertSame('784637716923335095479473677900958302012794430558004314112', $bn2->getValue());
        $this->assertSame('42535295865117307932921825928971026432', $bn2->shiftRight(64)->getValue());
        $this->assertSame('4611686018427387904', $bn2->shiftRight(64)->shiftRight(63)->getValue());
        $this->assertSame('1073741824', $bn2->shiftRight(64)->shiftRight(63)->shiftRight(32)->getValue());
        $this->assertSame('1', $bn2->shiftRight(64)->shiftRight(63)->shiftRight(32)->shiftRight(30)->getValue());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::sqrt
     */
    public function testSqrt()
    {
        $bn1 = new BigNumberImmutable(16);
        $bn2 = new BigNumberImmutable(17);

        $this->assertNotSame($bn1, $bn1->sqrt());
        $this->assertSame('4', $bn1->sqrt()->getValue());
        $this->assertSame('4', $bn2->sqrt()->getValue());

        $bn3 = $bn2->withScale(8);
        $this->assertSame('4.12310562', $bn3->sqrt()->getValue());
    }

    /**
     * @covers \Moontoast\Math\BigNumberImmutable::subtract
     */
    public function testSubtract()
    {
        $bn = new BigNumberImmutable(2147483647);

        $bn2 = $bn->subtract('9223372036854775808');
        $this->assertNotSame($bn, $bn2);
        $this->assertSame('-9223372034707292161', $bn2->getValue());

        $bn3 = $bn2->withScale(3);
        $this->assertSame('-9223372034707292163.250', $bn3->subtract(2.25)->getValue());
    }

    public function testConstructorDoesntDirectlyWrapPassedMutableInstance()
    {
        $wrapped = new BigNumber(1);
        $SUT = new BigNumberImmutable($wrapped);
        $wrapped->setValue(2);
        static::assertNotEquals($wrapped->getValue(), $SUT->getValue());
        static::assertSame("2", $wrapped->getValue());
        static::assertSame("1", $SUT->getValue());
    }

    public function testConstructorDoesUpdate0ScaleWithPassedMutableInstance()
    {
        $wrapped = new BigNumber(1, 2);
        $SUT = new BigNumberImmutable($wrapped, 0);
        static::assertNotSame($wrapped->getValue(), $SUT->getValue());
        static::assertSame(0, $SUT->getScale());
        static::assertSame("1", $SUT->getValue());
    }
}
