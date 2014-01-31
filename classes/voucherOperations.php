<?php

/**
* This class and its functions make all the decisions concerning what objects to instantiate 
* for the type of vouchers to be processed.
*/
class voucherOperations
{
	

	//	Display type keys 	
	const CSV = 0;
	const TXT = 1;
	const SCREEN = 2;

	//	Voucher type keys
	const A = 0;
	const B = 1;


	/**
	 * This function must be called in order to carry out any voucher operation.  
	 * Depending on the information supplied to it the UI it decides whether vouchers are to be
	 * generated or redeemed, what type of vouchers to generate and the format which is used 
	 * to supply them to the user.  It brings all other decision making methods together to provide
	 * a simple interface.
	 * 
	 * @param  array $data Supplied data from UI
	 * @return mixed       The method will provide a return value when redeeming an existing voucher
	 *                     however not when generating new ones.
	 */
	public function processRequest(array $data)
	{
		
		if(array_key_exists('redeem', $data) && !empty($data['redeem']))
		{

			return $this->redeemVoucher($data['redeem']);

		} else {

			//	Throw an error if all necessary fields are not supplied.
			$this->checkSuppliedData($data);

			//	Instantiate and return display type object
			$display = $this->displayType($data['writeto']);

			//	Instantiate and return the required voucher type
			$generate = $this->voucherType($data);

			//	Supply the required info to create the new vouchers
			$generate->setIdentifiers($display, $data);

			//	Generate an array of new voucher codes
			$generate->createVouchers();

			//	Write them using the chosen method
			$generate->writeValues();

			return;

		}
	}


	/**
	 * Use the data supplied to instatiate the correct writer object to output the generated voucher array.
	 * 
	 * @param  int $type An integer which corresponds to a constant value (flag) from this class
	 * @return object       The correct writer object.
	 */
	private function displayType($type)
	{
		switch($type) {
			case self::CSV:
				return new toCSV();
				break;

			case self::TXT:
				return new toText();
				break;

			case self::SCREEN:
				return new toScreenWriter();
				break;

			default:
				throw new Exception("Unsupported display type.  Please check the documentation and try again.", 1);
				return;
				break;
		}
	}

	/**
	 * Chooses the correct voucher type class to instantiate and return based on supplied info.
	 * If new voucher type classes are added this method should be updated to include them.
	 * 
	 * @param  array $data An array supplied by the user which contains all the info required for voucher generation
	 * @return object       An object of type 'newVoucher' which can be used to generate voucher codes of a specific type
	 */
	private function voucherType(array $data)
	{

		switch($data['vouchertype']) {

			case self::A:

				return new typeA();
				break;

			case self::B:

				return new typeB();
				break;

			default:

				throw new Exception("Unsupported voucher type.  Please check the documentation and try again.", 1);
				return;
				break;
		}
	}


	/**
	 * Accepts a voucher code and instantiates the correct onject to properly decode it and return
	 * the data held within.  If new voucher type classes are added, the switch written here must
	 * be updated to inlude the new functionality.
	 * 
	 * @param  string $voucher A voucher code to be redeemed
	 * @return string          A sting of information obtained from the voucher to be wrapped in 
	 *                         HTML and displayed to the user.
	 */
	public function redeemVoucher($voucher)
	{
		//	Decide which voucher type to instantiate
		switch(substr(trim($voucher), 0, 1))
		{
			case 'a':
			$redeem = new typeA();
			break;

			case 'b':
			$redeem = new typeB();
			break;
			
			default:
			throw new Exception("No voucher type identified.  Voucher is invalid", 1);
			break;
		}

		//	Using the newly instantiated object retrieve an array of information to be processed
		//	for display.
		$result = $redeem->checkValidity($voucher);

		$data_string  = 'Employee Identifier : '.$result['employee']."<br>";
		$data_string .= 'Promotion Identifier : '.$result['offer_ref']."<br>";
		$data_string .= 'Date Generated : '.$result['date']."<br>";
		$data_string .= 'Voucher Number : '.$result['quantity'];

		return $data_string;

	}


	/**
	 * Check that data supplied by the user is of the correct format for the voucher generators
	 * to use and make sense of later when decoding.
	 * 	
	 * @param  array $data Data received from the UI
	 */
	private function checkSuppliedData(array $data) 
	{

		//	Check that the number of vouchers ot be generated has been supplied by the user.
		if(empty($data['quantity'])) {
			throw new Exception("You must specify the number of voucher codes to generate.  Currently the system can generate a maximum of ".newVoucher::MAXVOUCHERS." in one go.", 1);
		}

		$this->chkEmployee($data['employee']);
		$this->chkoffer($data['offer']);
		$this->chkDate($data['date']);

		return;
	}


	/**
	 * Check a date has been supplied and is six characters long	 
	 * @param  string $date  The date received from the UI
	 */
	private function chkDate($date)
	{

		if(!empty($date))
		{
			if(strlen($date) != 6)
			{ throw new Exception("The date can only be six characters long, eg. Todays date is ".date('dmy'), 1); }

		} else {
			throw new Exception("You must specify a date when generating vouchers", 1);
		}

		return;

	}


	/**
	 * Check that an employee identifier has been received from the UI and is three characters long	  
	 * @param  string $employee The employee identifier received from the UI
	 */
	private function chkEmployee($employee)
	{

		if(!empty($employee))
		{
			if(strlen($employee) != 3)
			{ throw new Exception("The employee identifier can only be three characters long.", 1); }

		} else {
			throw new Exception("You must specify an employee identifier when generating vouchers", 1);
		}

		return;

	}


	/**
	 * Check that an offer identifier has been received from the UI and is three characters long	  
	 * @param  string $offer The offer identifier received from the UI
	 */
	private function chkOffer($offer)
	{

		if(!empty($offer))
		{
			if(strlen($offer) != 3)
			{ throw new Exception("The offer identifier can only be three characters long.", 1); }

		} else {
			throw new Exception("You must specify an offer identifier when generating vouchers", 1);
		}

		return;

	}

}

?>