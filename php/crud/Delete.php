<?php 
    include_once('../connect.php');
    // QUERY DATABASE FROM DATA
    $InvestorID =$_REQUEST['InvestorID'];
    $sql=" DELETE FROM investor where InvestorID = '$InvestorID' "; 
    
    // $sql=" SELECT * FROM investor where id='".$InvestorID."'"; 
    $result = mysqli_query($conn, $sql) or die ( mysqli_error());
    echo '<p style="color:#FF0000;">Record Deleted Successfully.</p> </br>' .'<small>You will be redirected in 3 sec...</small> </br></br>';
    header( "refresh: 3;url= ../tabs/investor.php" );
?>