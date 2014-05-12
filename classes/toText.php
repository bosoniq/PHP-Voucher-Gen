<?php

/**
* A class to handle all tasks related to writing data to a TXT file
*/
class toText extends writer
{
    
    /**
     * Takes an array of data and writes it to a simple txt file. One array value per line.
     * @param  array $vouchers An array of values to be written.
     */
    public function writeValues($values)
    {

        $string = '';

        foreach ($values as $value) {
            $string .= $value."\n";
        }

        $tmpName = tempnam(sys_get_temp_dir(), 'data');
        $file = fopen($tmpName, 'w');
        fwrite($file, $string);
        fclose($file);

        header('Content-Description: File Transfer');
        header('Content-Type: text/t');
        header('Content-Disposition: attachment; filename=vouchers.txt');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($tmpName));

        ob_clean();
        flush();
        readfile($tmpName);

        unlink($tmpName);
        return;
    }
}
