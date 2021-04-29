<?php 
    include_once('./connect.php');
    // QUERY DATABASE FROM DATA
    $InvestorID =$_REQUEST['InvestorID'];
    $sql=" SELECT * FROM investor where InvestorID = '$InvestorID' "; 
    
    // $sql=" SELECT * FROM investor where id='".$InvestorID."'"; 
    $result = mysqli_query($conn, $sql) or die ( mysqli_error());
    $row = mysqli_fetch_assoc($result);
    
    $status = "";
    if(isset($_POST['new']) && $_POST['new']==1)
    {
        $InvestorID     =$_REQUEST['InvestorID'];
        $ModifiedDate   =$_REQUEST['ModifiedDate'];
        $InvestorName   =$_REQUEST['InvestorName'];
        $Website        =$_REQUEST['Website'];
        $Description    =$_REQUEST['Description'];
        $ImpactTag      =$_REQUEST['ImpactTag'];
        $YearFounded    =$_REQUEST['YearFounded'];
        $Headquarters   =$_REQUEST['Headquarters'];
        $Logo           =$_REQUEST['Logo'];

        $update="update investor set ModifiedDate='".$ModifiedDate."',InvestorName='".$InvestorName."', Website='".$Website."',Description='".$Description."', ImpactTag='".$ImpactTag."', YearFounded='".$YearFounded."', Headquarters='".$Headquarters."', Logo='".$Logo."' where InvestorID='".$InvestorID."'";
        mysqli_query($conn, $update) or die(mysqli_error());
        $status = "Record Updated Successfully. </br></br>
        <a href='investor.php'>View Updated Record</a>";
        echo '<p style="color:#FF0000;">'.$status.'</p>';
        header( "refresh: 3;url= investor.php" );
    }else {
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update Recird </title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/main.css">
    </head>
    <body>
    <!-- HEADER CONTENT -->
        <nav class="container navbar navbar-expand-lg align-middle" style="z-index: 1;">
            <div class="container-fluid">
                <a style="color:#ffffff;" class="navbar-brand" href="../index.php"><img style=" width: 80px;" class="home-ico" src="../resources/DCA_Icon.png" alt="Digital collective africa logo"> VC Reportstream  </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="https://www.digitalcollective.africa/ " target="_blank" >Digital Collective Africa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../WebInterface.php">New Deal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <main  class="my-5 ">
            <form name="form" method="post" action="" class="form container"> 
                <input type="hidden" name="new" value="1" />
                <input name="InvestorID" type="hidden" value="<?php echo $row['InvestorID'];?>" />
                <p><input class="form-control col" type="text" name="ModifiedDate" required value="<?php echo $row['ModifiedDate'];?>" /></p>
                <p><input class="form-control col" type="text" name="InvestorName" placeholder="Enter InvestorName" required value="<?php echo $row['InvestorName'];?>" /></p>
                <p><input class="form-control col" type="text" name="Website" placeholder="Enter Website" required value="<?php echo $row['Website'];?>" /></p>
                <p><input class="form-control col" type="text" name="Description" placeholder="Enter Description" required value="<?php echo $row['Description'];?>" /></p>
                <p><input class="form-control col" type="text" name="ImpactTag" placeholder="Enter ImpactTag" required value="<?php echo $row['ImpactTag'];?>" /></p>
                <p><input class="form-control col" type="text" name="YearFounded" placeholder="Enter YearFounded" required value="<?php echo $row['YearFounded'];?>" /></p>
                <p><input class="form-control col" type="text" name="Headquarters" placeholder="Enter Headquarters" required value="<?php echo $row['Headquarters'];?>" /></p>
                <p><input class="form-control col" type="text" name="Logo" placeholder="Enter Logo"  value="<?php echo $row['Logo'];?>" /></p>

                <p><input name="submit" type="submit" value="Update" /></p>
            </form>
            <?php } ?>
        </main>
    </body>
</html>