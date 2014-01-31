<?php

/**
* A class to write data directly to the browser with simple HTML markup included.
*/
class toScreenWriter extends writer
{
	

	/**
	 * [writeValues description]
	 * @param  array $vouchers An array of voucher codes to be written.
	 */
	public function writeValues($values) {

		$count = '';

		foreach ($values as $value) {
				
			$count++;

			echo '<p>'.$count.': '.$value.'</p>';

		}


	}


}


?>