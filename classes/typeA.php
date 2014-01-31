<?php


/**
* Create type A vouchers with a particular arrangement of submitted data 
* presented as a hex value
*/
class typeA extends newVoucher
{
	
	/**
	 * Create type A vouchers with a particular arrangement of submitted data presented 
	 * as a hex value
	 * 
	 * @return array An array containing the unique encoded voucher codes
	 */
	public function createVouchers()
	{

		$code_array = array();
		$count = 0;

		$string = $this->offer_ref.'%d'.$this->employee.$this->date;

			//	Produce unique voucher codes, prepend with type identifier and store in array
			while(($count < $this->quantity) && ($count < newVoucher::MAXVOUCHERS)) {

			$count++;
			//	Substitue in the $count for this loop as a unique voucher number
			$voucher = sprintf($string, $count);
			$voucher = 'a'.bin2hex($voucher);

			$code_array[$count] = $voucher;

			}

		$this->vouchers = $code_array;

		if (empty($this->vouchers)) {
			throw new Exception("Generated vouchers array is empty", 1);
		}

	}


	/**
	 * Receives a decimal voucher code which it deconstructs to the original data used to generate 
	 * the code
	 * 
	 * @return array An array of values which the user can check to determine the validity of the presented voucher code
	 */		
	public function returnData($original_String) 
	{
		//	Fetch basic values according to order of original string used to create the voucher
		$basic_values['offer_ref'] = substr($original_String, 0, 3);
		$basic_values['employee'] = substr($original_String, -9, 3);
		$basic_values['date'] = substr($original_String, -6, 6);
		$basic_values['quantity'] = substr($original_String, 3, -9);

		return $basic_values;
	}



}

?>