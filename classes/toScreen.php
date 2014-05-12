<?php

/**
* A class to write data directly to the browser with simple HTML markup included.
*/
class toScreen extends writer
{

    /**
     * [writeValues description]
     * @param  array $vouchers An array of voucher codes to be written.
     * @return string A string formatted in simple HTML.
     */
    public function writeValues($values)
    {

        $count = '';
        $string = '';

        foreach ($values as $value) {
                
            $count++;
            $string .= '<p>'.$count.': '.$value.'</p>';

        }

        return $string;
    }
}
