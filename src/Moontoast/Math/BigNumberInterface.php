<?php
/**
 * @license See the file LICENSE for copying permission
 */

namespace Moontoast\Math;

/**
 * Represents a number for use with Binary Calculator computations
 *
 * @link http://www.php.net/bcmath
 */
interface BigNumberInterface
{
    /**
     * Returns the string value of this BigNumber
     *
     * @return string String representation of the number in base 10
     */
    public function __toString();

    /**
     * Sets the current number to the absolute value of itself
     *
     * @return BigNumberInterface for fluent interface
     */
    public function abs();

    /**
     * Adds the given number to the current number
     *
     * @param mixed $number May be of any type that can be cast to a string
     *                      representation of a base 10 number
     *
     * @return BigNumberInterface for fluent interface
     * @link http://www.php.net/bcadd
     */
    public function add($number);

    /**
     * Finds the next highest integer value by rounding up the current number
     * if necessary
     *
     * @return BigNumberInterface for fluent interface
     * @link http://www.php.net/ceil
     */
    public function ceil();

    /**
     * Compares the current number with the given number
     *
     * Returns 0 if the two operands are equal, 1 if the current number is
     * larger than the given number, -1 otherwise.
     *
     * @param mixed $number May be of any type that can be cast to a string
     *                      representation of a base 10 number
     *
     * @return int
     * @link http://www.php.net/bccomp
     */
    public function compareTo($number);

    /**
     * Returns the current value converted to an arbitrary base
     *
     * @param int $base The base to convert the current number to
     *
     * @return string String representation of the number in the given base
     */
    public function convertToBase($base);

    /**
     * Decreases the value of the current number by one
     *
     * @return BigNumberInterface for fluent interface
     */
    public function decrement();

    /**
     * Divides the current number by the given number
     *
     * @param mixed $number May be of any type that can be cast to a string
     *                      representation of a base 10 number
     *
     * @return BigNumberInterface for fluent interface
     * @throws Exception\ArithmeticException if $number is zero
     * @link http://www.php.net/bcdiv
     */
    public function divide($number);

    /**
     * Finds the next lowest integer value by rounding down the current number
     * if necessary
     *
     * @return BigNumberInterface for fluent interface
     * @link http://www.php.net/floor
     */
    public function floor();

    /**
     * Returns the scale used for this BigNumber
     *
     * If no scale was set, this will default to the value of bcmath.scale
     * in php.ini.
     *
     * @return int
     */
    public function getScale();

    /**
     * Returns the current raw value of this BigNumber
     *
     * @return string String representation of the number in base 10
     */
    public function getValue();

    /**
     * Increases the value of the current number by one
     *
     * @return BigNumberInterface for fluent interface
     */
    public function increment();

    /**
     * Returns true if the current number equals the given number
     *
     * @param mixed $number May be of any type that can be cast to a string
     *                      representation of a base 10 number
     *
     * @return bool
     */
    public function isEqualTo($number);

    /**
     * Returns true if the current number is greater than the given number
     *
     * @param mixed $number May be of any type that can be cast to a string
     *                      representation of a base 10 number
     *
     * @return bool
     */
    public function isGreaterThan($number);

    /**
     * Returns true if the current number is greater than or equal to the given number
     *
     * @param mixed $number May be of any type that can be cast to a string
     *                      representation of a base 10 number
     *
     * @return bool
     */
    public function isGreaterThanOrEqualTo($number);

    /**
     * Returns true if the current number is less than the given number
     *
     * @param mixed $number May be of any type that can be cast to a string
     *                      representation of a base 10 number
     *
     * @return bool
     */
    public function isLessThan($number);

    /**
     * Returns true if the current number is less than or equal to the given number
     *
     * @param mixed $number May be of any type that can be cast to a string
     *                      representation of a base 10 number
     *
     * @return bool
     */
    public function isLessThanOrEqualTo($number);

    /**
     * Returns true if the current number is a negative number
     *
     * @return bool
     */
    public function isNegative();

    /**
     * Returns true if the current number is a positive number
     *
     * @return bool
     */
    public function isPositive();

    /**
     * Finds the modulus of the current number divided by the given number
     *
     * @param mixed $number May be of any type that can be cast to a string
     *                      representation of a base 10 number
     *
     * @return BigNumberInterface for fluent interface
     * @throws Exception\ArithmeticException if $number is zero
     * @link http://www.php.net/bcmod
     */
    public function mod($number);

    /**
     * Multiplies the current number by the given number
     *
     * @param mixed $number May be of any type that can be cast to a string
     *                      representation of a base 10 number
     *
     * @return BigNumberInterface for fluent interface
     * @link http://www.php.net/bcmul
     */
    public function multiply($number);

    /**
     * Sets the current number to the negative value of itself
     *
     * @return BigNumberInterface for fluent interface
     */
    public function negate();

    /**
     * Raises current number to the given number
     *
     * @param mixed $number May be of any type that can be cast to a string
     *                      representation of a base 10 number
     *
     * @return BigNumberInterface for fluent interface
     * @link http://www.php.net/bcpow
     */
    public function pow($number);

    /**
     * Raises the current number to the $pow, then divides by the $mod
     * to find the modulus
     *
     * This is functionally equivalent to the following code:
     *
     * <code>
     *     $n = new BigNumber(1234);
     *     $n->mod($n->pow(32), 2);
     * </code>
     *
     * However, it uses bcpowmod(), so it is faster and can accept larger
     * parameters.
     *
     * @param mixed $pow May be of any type that can be cast to a string
     *                   representation of a base 10 number
     * @param mixed $mod May be of any type that can be cast to a string
     *                   representation of a base 10 number
     *
     * @return BigNumberInterface for fluent interface
     * @throws Exception\ArithmeticException if $number is zero
     * @link http://www.php.net/bcpowmod
     */
    public function powMod($pow, $mod);

    /**
     * Rounds the current number to the nearest integer
     *
     * @return BigNumberInterface for fluent interface
     * @todo Implement precision digits
     */
    public function round();

    /**
     * Shifts the current number $bits to the left
     *
     * @param int $bits
     *
     * @return BigNumberInterface for fluent interface
     */
    public function shiftLeft($bits);

    /**
     * Shifts the current number $bits to the right
     *
     * @param int $bits
     *
     * @return BigNumberInterface for fluent interface
     */
    public function shiftRight($bits);

    /**
     * Returns the sign (signum) of the current number
     *
     * @return int -1, 0 or 1 as the value of this BigNumber is negative, zero or positive
     */
    public function signum();

    /**
     * Finds the square root of the current number
     *
     * @return BigNumberInterface for fluent interface
     * @link http://www.php.net/bcsqrt
     */
    public function sqrt();

    /**
     * Subtracts the given number from the current number
     *
     * @param mixed $number May be of any type that can be cast to a string
     *                      representation of a base 10 number
     *
     * @return BigNumberInterface for fluent interface
     * @link http://www.php.net/bcsub
     */
    public function subtract($number);
}
