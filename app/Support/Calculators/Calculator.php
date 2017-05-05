<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Support\Calculators;

abstract class Calculator
{
    /**
     * The id of the quote or invoice.
     *
     * @var int
     */
    protected $id;

    /**
     * An array to store items.
     *
     * @var array
     */
    protected $items = [];

    /**
     * An array to store calculated item amounts.
     *
     * @var array
     */
    protected $calculatedItemAmounts = [];

    /**
     * An array to store overall calculated amounts.
     *
     * @var array
     */
    protected $calculatedAmount = [];

    /**
     * Initialize the calculated amount array.
     */
    public function __construct()
    {
        $this->calculatedAmount = [
            'subtotal' => 0,
            'tax'      => 0,
            'total'    => 0
        ];
    }

    /**
     * Sets the id.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Adds a item for calculation.
     *
     * @param int $itemId
     * @param float $quantity
     * @param float $price
     * @param float $taxRatePercent
     * @param float $taxRate2Percent
     * @param bool $taxRate2IsCompound
     */
    public function addItem($itemId, $quantity, $price, $taxRatePercent = 0, $taxRate2Percent = 0, $taxRate2IsCompound = 0)
    {
        $this->items[] = [
            'itemId'             => $itemId,
            'quantity'           => $quantity,
            'price'              => $price,
            'taxRatePercent'     => $taxRatePercent,
            'taxRate2Percent'    => $taxRate2Percent,
            'taxRate2IsCompound' => $taxRate2IsCompound
        ];
    }

    /**
     * Call the calculation methods.
     */
    public function calculate()
    {
        $this->calculateItems();
    }

    /**
     * Returns calculated item amounts.
     *
     * @return array
     */
    public function getCalculatedItemAmounts()
    {
        return $this->calculatedItemAmounts;
    }

    /**
     * Returns overall calculated amount.
     *
     * @return array
     */
    public function getCalculatedAmount()
    {
        return $this->calculatedAmount;
    }

    /**
     * Calculates the items.
     */
    protected function calculateItems()
    {
        foreach ($this->items as $item)
        {
            $subtotal = round($item['quantity'] * $item['price'], 2);

            if ($item['taxRatePercent'])
            {
                $tax1 = round($subtotal * ($item['taxRatePercent'] / 100), 2);
            }
            else
            {
                $tax1 = 0;
            }

            if ($item['taxRate2Percent'])
            {
                if ($item['taxRate2IsCompound'])
                {
                    $tax2 = round(($subtotal + $tax1) * ($item['taxRate2Percent'] / 100), 2);
                }
                else
                {
                    $tax2 = round($subtotal * ($item['taxRate2Percent'] / 100), 2);
                }
            }
            else
            {
                $tax2 = 0;
            }

            $taxTotal = $tax1 + $tax2;
            $total    = $subtotal + $taxTotal;

            $this->calculatedItemAmounts[] = [
                'item_id'  => $item['itemId'],
                'subtotal' => $subtotal,
                'tax_1'    => $tax1,
                'tax_2'    => $tax2,
                'tax'      => $taxTotal,
                'total'    => $total
            ];

            $this->calculatedAmount['subtotal'] += $subtotal;
            $this->calculatedAmount['tax'] += $taxTotal;
            $this->calculatedAmount['total'] += $total;
        }
    }
}