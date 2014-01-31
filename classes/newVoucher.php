<?php

/**
 * Abstract class providing common code and interfaces used by all classes which generate 
 * vouchers and check the validity of existing vouchers.
 */
Abstract class newVoucher
{

	/** @var string Holds a short employee identifier */
	protected $employee;

	/** @var int Number of vouchers to create (may be overridden by MAXVOUCHERS) */
	protected $quantity;

	/** @var string Offer identifier */
	protected $offer_ref;

	/** @var string A simple string provided by the user providing the date of format ddmmyy */
	protected $date;

	/** @var object Object to handle writing voucher result, of type 'writer' */
	protected $writer;

	/** @var array An array to hold the vouchers that are generated by createVouchers() */
	protected $vouchers;

	/** Maximum number of vouchers that can be generated in one operation */
	const MAXVOUCHERS = 10;


	/**
	 * Writes input values to class properties
	 * @param object $writer   Object to handle writing voucher result
	 * @param string $employee Three character employee identifier
	 * @param string $offer    Three character offer identifier
	 * @param integer $quantity Number of vouchers to produce (can be overriden by MAXVOUCHERS)
	 * @param string $date     Date as a user supplied string (ddmmyy)
	 */
	public function setIdentifiers(writer $writer, array $data)
	{
		$this->employee = $data['employee'];
		$this->offer_ref = $data['offer'];
		$this->quantity = $data['quantity'];
		$this->date = $data['date'];
		$this->writer = $writer;
	}


	/**
	 * Create voucher interface used across child objects
	 * @return array Return an array of vouchers codes 
	 *  
	 */
	Abstract public function createVouchers();

	
	/**
	 * Returns voucher codes after they're generated
	 * @return array An array of un written voucher codes.
	 */
	public function getVouchers()
	{
		return $this->vouchers;
	}


	/**
	 * Removes whitespace, the leading voucher type identifier and converts the hexadecimal 
	 * voucher string to decimal.  It then calls returndata() to retrieve information held
	 * within the original data string used when the voucher was created.
	 * 
	 * @param string $testvalue 	A voucher code provided by the user.
	 * @return array Contains all relevent data decoded and held within every voucher.
	 */
	public function checkValidity($testvalue)
	{
		$basic_values = array();

		//	Remove any whitespace
		$testvalue = trim($testvalue);

		//	Remove voucher type identifier and produce original information string
		$testvalue = substr($testvalue, 1);
		$original_string = $this->hextobin($testvalue);

		//	Be sure that the original string is long enough to be processed to return its data.
		if(!(strlen($original_string) >= 13))
		{ throw new Exception("The original string was not long enough and therefore cannot be validated.", 1); }

		//	Cut the $original_string up and supply an array of basic values for the user to check.
		return $this->returndata($original_string);
	}


	/**
	 * Takes a supplied decimal voucher code and retrieves information included upon the
	 * codes generation according to the order specific to each voucher type.
	 * 	 
	 * @return array An array of data holding voucher code data.
	 */
	Abstract protected function returnData($data);


	/**
	 * Use the writeValues() function from the writer object that was passed into the 
	 * constructor upon newVoucher object instantiation.
	 */
	public function writeValues()
	{
		$this->writer->writeValues($this->vouchers);
	}


	/**
	 * A snippet of code from the PHP manual.  In PHP versions that include hex2bin() this 
	 * method is unnecessary.
	 * 
	 * @param  string $hexstr 	A hexadecmal string to be converted to a decimal version 
	 * @return string         	The decimal version of the original hexadecimal string
	 */
	 protected function hextobin($hexstr)
    {
        $n = strlen($hexstr);
        $sbin="";  
        $i=0;
        while($i<$n)
        {      
            $a =substr($hexstr,$i,2);          
            $c = pack("H*",$a);
            if ($i==0){$sbin=$c;}
            else {$sbin.=$c;}
            $i+=2;
        }
        return $sbin;
    } 


}
?>