<?php

/**
* A class to handle all tasks related to writing data to a CSV file
*/
class toCSV extends writer
{
	

	/**
	 * [writeValues description]
	 * @param  array $vouchers An array of voucher codes to be written.
	 */
	public function writeValues($values) {


		$tmpName = tempnam(sys_get_temp_dir(), 'data');
		$file = fopen($tmpName, 'w');
		fputcsv($file, $values);
		fclose($file);

		header('Content-Description: File Transfer');
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename=data.csv');
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


?>