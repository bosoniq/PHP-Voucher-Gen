<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <title>Example Usage</title>
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body>


<?php

try {


    //  Autoload classes from the ./classes folder
    function __autoload($class_name) {
        include './classes/'.$class_name . '.php';
    }


//////////////////////////////////////////////////////////////////////////////////////////////////
//Please note that validation and sanitization of data in the example code below is minimal and //
//that for a live implementation of the voucher classes more stringent checks should be         //
//implemented.                                                                                  //
//////////////////////////////////////////////////////////////////////////////////////////////////


    /**
     * If the generator form is submitted collect the data into an array and process
     */
    if(isset($_POST['process']) && ($_POST['process'] == 'process'))
    {
        $employee = filter_input(INPUT_POST, 'employee', FILTER_SANITIZE_STRING);
        $offer = filter_input(INPUT_POST, 'offer', FILTER_SANITIZE_STRING);
        $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
        $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_STRING);
        $writeto = $_POST['displaytype'];
        $vouchertype = $_POST['vouchertype'];
        
        $data = array(  'employee' => $employee,
                        'offer' => $offer,
                        'quantity' => $quantity,
                        'date' => $date,
                        'writeto' => $writeto,
                        'vouchertype' => $vouchertype
                    );

        $operation = new voucherOperations();
        $resultgen = $operation->processRequest($data);

        //  A quick but ugly way of avoiding php errors on the test page.
        $resultred ="&nbsp;";

    } 


    /**
     * If the redeemer form is submitted collect the voucher to check and insert into an array 
     * to process.
     */
    if(isset($_POST['process']) && ($_POST['process'] == 'process2'))
    {

        $redeem =  filter_input(INPUT_POST, 'redeem', FILTER_SANITIZE_STRING);

        $data = array(
                        'redeem' => $redeem
                    );

        $operation = new voucherOperations();
        $resultred = $operation->processRequest($data);

        //  A quick but ugly way of avoiding php errors on the test page.
        $resultgen ="&nbsp;";

    }

} catch(Exception $e) { echo '<p>Processing was interrupted with the following error : '.$e->getMessage()."</p>"; }

?>


<!-- HTML starts here -->

<div id="container">

    <!--    Generation Form Markup  -->
    <form action="./index.php" method="POST" novalidate>
    <h2>Generate a set of vouchers</h2>


    <div class="side">
    <label>Employee Identifier : </label><br>
    <input name="employee" type="text" maxlength="3" required><br>
    <label>Offer Identifier : </label><br>
    <input name="offer" type="text" maxlength="3" required><br>
    <label>Date : </label><br>
    <input name="date" type="text" placeholder="eg, <?=date('dmy')?>" maxlength="6" required><br>

    <label>Quantity : </label><br>
    <select name="quantity" type="text" placeholder="Quantity Reguired" required>
        <option value="20">20</option>
        <option value="40">40</option>
        <option value="60">60</option>
        <option value="80">80</option>
        <option value="100">100</option>
    </select><br><br>

    <input name="process" type="hidden" value="process">
    <input type="submit" value="Submit" id="submit">
    </div>

    <div class="side">
    <label>Voucher Type : </label><br>
    <input type="radio" name="vouchertype" value="0" >Type A<br>
    <input type="radio" name="vouchertype" value="1">Type B<br>
    <br><br>

    <label>Return Type : </label><br>
    <input type="radio" name="displaytype" value="2">Print to Screen<br>
    <input type="radio" name="displaytype" value="1">Download as Text file<br>
    <input type="radio" name="displaytype" value="0">Download as CSV file<br>
    <br><br>
    </div>
    <p><?=$resultgen?></p>
    </form>
    <br><br>


    <!--    Validation Form Markup  -->
    <form action="./index.php" method="POST" novalidate>
    <h2>Redeem an individual voucher</h2>
        <input name="redeem" type="text">
        <input name="process" type="hidden" value="process2">
        <input type="submit" value="Submit" id="submit">
            <p><?=$resultred?></p>
    </form>

</div>
    </body>
</html>