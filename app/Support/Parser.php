<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Support;

class Parser
{
    public static function parse($object, $string)
    {
        if (preg_match_all('/{{([^{|}]*)}}/', $string, $vars))
        {
            foreach ($vars[1] as $var)
            {
                $varMap = explode('->', trim($var));
                array_shift($varMap);

                switch (count($varMap))
                {
                    case 1:
                        $replace = $object->{$varMap[0]};
                        break;
                    case 2:
                        $replace = $object->{$varMap[0]}->{$varMap[1]};
                        break;
                    case 3:
                        $replace = $object->{$varMap[0]}->{$varMap[1]}->{$varMap[2]};
                        break;
                    case 4:
                        $replace = $object->{$varMap[0]}->{$varMap[1]}->{$varMap[2]}->{$varMap[3]};
                        break;
                }
                $string = str_replace('{{' . $var . '}}', $replace, $string);
            }
        }

        return $string;
    }
}