<?php
/**
 * @license See the file LICENSE for copying permission
 */

namespace Moontoast\Math;

abstract class AbstractBigNumber implements BigNumberInterface
{
    /**
     * {@inheritdoc}
     */
    public function isEqualTo($number)
    {
        return ($this->compareTo($number) === 0);
    }

    /**
     * {@inheritdoc}
     */
    public function isGreaterThan($number)
    {
        return ($this->compareTo($number) === 1);
    }

    /**
     * {@inheritdoc}
     */
    public function isGreaterThanOrEqualTo($number)
    {
        return ($this->compareTo($number) >= 0);
    }

    /**
     * {@inheritdoc}
     */
    public function isLessThan($number)
    {
        return ($this->compareTo($number) === -1);
    }

    /**
     * {@inheritdoc}
     */
    public function isLessThanOrEqualTo($number)
    {
        return ($this->compareTo($number) <= 0);
    }

    /**
     * {@inheritdoc}
     */
    public function isNegative()
    {
        return ($this->signum() === -1);
    }

    /**
     * {@inheritdoc}
     */
    public function isPositive()
    {
        return ($this->signum() === 1);
    }

    /**
     * {@inheritdoc}
     */
    public function signum()
    {
        if ($this->isGreaterThan(0)) {
            return 1;
        } elseif ($this->isLessThan(0)) {
            return -1;
        }

        return 0;
    }

    /**
     * Converts a number between arbitrary bases (from 2 to 36)
     *
     * @param string|int $number The number to convert
     * @param int $fromBase (optional) The base $number is in; defaults to 10
     * @param int $toBase (optional) The base to convert $number to; defaults to 16
     * @return string
     */
    public static function baseConvert($number, $fromBase = 10, $toBase = 16)
    {
        $number = self::convertToBase10($number, $fromBase);

        return self::convertFromBase10($number, $toBase);
    }

    /**
     * Converts a base-10 number to an arbitrary base (from 2 to 36)
     *
     * @param string|int $number The number to convert
     * @param int $toBase The base to convert $number to
     * @return string
     * @throws \InvalidArgumentException if $toBase is outside the range 2 to 36
     */
    public static function convertFromBase10($number, $toBase)
    {
        if ($toBase < 2 || $toBase > 36) {
            throw new \InvalidArgumentException("Invalid `to base' ({$toBase})");
        }

        $bn = new static($number);
        $number = $bn->abs()->getValue();
        $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $outNumber = '';

        while (bccomp($number, $toBase) >= 0) {
            $remainder = bcmod($number, $toBase);
            $number = bcdiv($number, $toBase, 0);
            $outNumber = $chars[(int) $remainder] . $outNumber;
        }

        return $chars[(int) $number] . $outNumber;
    }

    /**
     * Converts a number from an arbitrary base (from 2 to 36) to base 10
     *
     * @param string|int $number The number to convert
     * @param int $fromBase The base $number is in
     * @return string
     * @throws \InvalidArgumentException if $fromBase is outside the range 2 to 36
     */
    public static function convertToBase10($number, $fromBase)
    {
        if ($fromBase < 2 || $fromBase > 36) {
            throw new \InvalidArgumentException("Invalid `from base' ({$fromBase})");
        }

        $number = (string) $number;
        $len = strlen($number);
        $base10Num = '0';

        for ($i = $len; $i > 0; $i--) {
            $c = ord($number[$len - $i]);

            if ($c >= ord('0') && $c <= ord('9')) {
                $c -= ord('0');
            } elseif ($c >= ord('A') && $c <= ord('Z')) {
                $c -= ord('A') - 10;
            } elseif ($c >= ord('a') && $c <= ord('z')) {
                $c -= ord('a') - 10;
            } else {
                continue;
            }

            if ($c >= $fromBase) {
                continue;
            }

            $base10Num = bcadd(bcmul($base10Num, $fromBase, 0), (string) $c, 0);
        }

        return $base10Num;
    }

    /**
     * Changes the default scale used by all Binary Calculator functions
     *
     * @param int $scale
     * @return void
     */
    public static function setDefaultScale($scale)
    {
        ini_set('bcmath.scale', $scale);
    }
}
