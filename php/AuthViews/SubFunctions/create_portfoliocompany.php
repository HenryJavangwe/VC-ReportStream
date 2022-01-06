<?php 
    include_once('../../App/connect.php');
    
    //===================================================
    //===================================================
    //Pulling data from the database and into dropdowns to create a new company with standardized data 
    //===================================================
    //===================================================

    // ACCESSING CURRENCIES TO POPULATE DROPDOWN FROM DATABASE
    $sql100 = "   SELECT DISTINCT 
                    Currency
                FROM 
                    Currency 
                WHERE 
                    Currency IS NOT NULL ORDER BY Currency ASC";
    $result100 = mysqli_query($conn, $sql100);
    // ACCESSING COUNTRIES TO POPULATE DROPDOWN FROM DATABASE
    $sql101 = "   SELECT DISTINCT 
                    Country
                FROM 
                    Country 
                WHERE 
                    Country IS NOT NULL ORDER BY Country ASC";
    $result101 = mysqli_query($conn, $sql101);
 
    

    if ( isset($_POST['submit']))
    {
        // DEFINED VAR FOR THE SECOND TABLE
        // PORTFOLIO COMPANY TABLE
        $PortfolioCompanyName    = mysqli_real_escape_string($conn, $_POST['PortfolioCompanyName']);
        $Currency                = $_POST['Currency'];
        $PortfolioCompanyWebsite = mysqli_real_escape_string($conn, $_POST['Website']);
        $Industry                = $_POST['Industry'];
        $Sector                  = $_POST['Sector'];
        $Details                 = mysqli_real_escape_string($conn, $_POST['Details']);
        $YearFounded             = $_POST['YearFounded'];
        $Headquarters            = $_POST['Headquarters'];
        $Logo                    = $_FILES['img']['name'];

        
        // Company Logo Insert code
        $Logo = addslashes(file_get_contents($_FILES["img"]["tmp_name"]));

        // PORTFOLIO COMPANY NOTE INSERT
        $sql = "INSERT INTO PortfolioCompany( PortfolioCompanyID, CreatedDate, ModifiedDate, Deleted, DeletedDate, PortfolioCompanyName, CurrencyID, Website, Details, YearFounded, Headquarters, Logo)
            VALUES (uuid(), now(), now(), 0, NULL,'$PortfolioCompanyName', (select C.CurrencyID FROM Currency C where C.Currency = '$Currency' ), '$PortfolioCompanyWebsite', '$Details', '$YearFounded', (select Country.CountryID FROM Country where Country.Country = '$Headquarters'), '$Logo')";
            $query = mysqli_query($conn, $sql);

        // LINKING COMPANY WITH SectorS AND INDUSTRY
        if($query){
            // echo 'This is the value inside the $PortfolioCompanyName variable : '.$PortfolioCompanyName;
            foreach($Sector as $sects)  
            {  
                $sql99 = "  INSERT INTO 
                                PortfolioCompanySector(PortfolioCompanySectorID, CreatedDate, ModifiedDate, Deleted, DeletedDate, PortfolioCompanyID, SectorID)
                            VALUES 
                                (uuid(), now(), now(), 0, NULL,(select P.PortfolioCompanyID FROM PortfolioCompany P where P.PortfolioCompanyName = '$PortfolioCompanyName'), (select S.SectorID FROM Sector S where S.Sector = '$sects'))";
                $query99 = mysqli_query($conn, $sql99);

                if($query99){
                    // echo 'For each iteration the Sector ID for '.$sects. 'was inserted'.'<br/>';
                } else {
                    echo 'Oops! There was an error inserting the Sector ID from the array'.mysqli_error($conn).'<br/>';
                }
            }
        
            $sql3 ="INSERT INTO 
                        PortfolioCompanyIndustry(PortfolioCompanyIndustryID, CreatedDate, ModifiedDate, Deleted, DeletedDate, PortfolioCompanyID, IndustryID)
                    VALUES 
                        (uuid(), now(), now(), 0, NULL,(select PortfolioCompany.PortfolioCompanyID FROM PortfolioCompany where PortfolioCompany.PortfolioCompanyName = '$PortfolioCompanyName'), (select Industry.IndustryID FROM Industry where Industry.Industry = '$Industry'))
            ";
            $query3 = mysqli_query($conn, $sql3);

            echo 
                '<div style="background-color:#d1e7dd; color: #0f5132; margin:5px 0 ;">
                    <H4>Thank you for your contribution</H4>
                    <p style="margin:0;"> <small> New Portfolio Company created successfully! </small> </p>
                </div>'
            ;
            
            echo" <a style=\"padding:3px; border:1px solid red;font-size:18px; text-decoration:none;\" href=\"javascript:window.open('','_self').close();\">Close</a>";
            $conn->close();

            exit;
        } else {
            echo 'Oops! There was an error creating PortfolioCompany'.mysqli_error($conn).'<br/>';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../../../resources/DCA_Icon.png" type="image/x-icon">
        <title>VC Reportstream | Investor</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="../../../css/select2.min.css">
        <!-- <link rel="stylesheet" href="../../../css/bootstrap.min.css">
        <link rel="stylesheet" href="../../../css/bootstrap.css"> -->
        <link rel="stylesheet" href="../../../css/main.css">
    </head>
    <body class="pb-5">
        <!-- HEADER CONTENT -->
        <?php include('../../Views/navBar/sub_navbar.php');?>
        <!-- BODY CONTENT -->
        <main class="container ">
            <!-- ==== LIST OF INVESTORS ==== -->
            <div class=" my-5">
                <form class="container" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                            <label for="PortfolioCompanyName" class="form-label"> Portfolio Company Name</label>
                            <input type="text" class="form-control" id="PortfolioCompanyName" name="PortfolioCompanyName" required>
                        </div>
                        <!-- Actual Currencies as in the DB --> 
                        <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                            <label for="Currency" class="form-label">Currency</label>
                            <select class="form-select" id="Currency" name="Currency" required>
                                <option> Select Currency...</option>
                                <?php
                                    while ($row100 = mysqli_fetch_assoc($result100)) {
                                        # code...
                                        echo "<option>".$row100['Currency']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                            <label for="Website" class="form-label">Company Website</label>
                            <input type="text" class="form-control" id="Website" name="Website" required>
                        </div>
                        <!--   INDUSTRY DROPDOWN  -->
                        <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                            <label for="Industry" class="form-label">Industry</label>
                            <select id="Industry" name="Industry" class="form-select" required>
                                <option>choose...</option>
                                <option value="Artificial Intelligence">Artificial Intelligence</option>
                                <option value="Clothing and Apparel">Clothing and Apparel</option>
                                <option value="Administrative Services">Administrative Services</option>
                                <option value="Advertising">Advertising</option>
                                <option value="Agriculture and Farming">Agriculture and Farming</option>
                                <option value="Apps">Apps</option>
                                <option value="Biotechnology">Biotechnology</option>
                                <option value="Commerce and Shopping">Commerce and Shopping</option>
                                <option value="Community and Lifestyle">Community and Lifestyle</option>
                                <option value="Consumer Electronics">Consumer Electronics</option>
                                <option value="Consumer Goods">Consumer Goods</option>
                                <option value="Content and Publishing">Content and Publishing</option>
                                <option value="Data and Analytics">Data and Analytics</option>
                                <option value="Design">Design</option> 
                                <option value="Education">Education</option>
                                <option value="Energy">Energy</option>
                                <option value="Events">Events</option>
                                <option value="Financial Services">Financial Services</option>
                                <option value="Food and Beverage">Food and Beverage</option>
                                <option value="Gaming">Gaming</option>
                                <option value="Government and Military">Government and Military</option>
                                <option value="Hardware">Hardware</option>
                                <option value="Health Care">Health Care</option>
                                <option value="Information Technology">Information Technology</option>
                                <option value="Internet Services">Internet Services</option>
                                <option value="Lending and Investments">Lending and Investments</option>
                                <option value="Manufacturing">Manufacturing</option>
                                <option value="Media and Entertainment">Media and Entertainment</option>
                                <option value="Messaging and Telecommunications">Messaging and Telecommunications</option>
                                <option value="Mobile">Mobile</option>
                                <option value="Music and Audio">Music and Audio</option>
                                <option value="Natural Resources">Natural Resources</option>
                                <option value="Navigation and Mapping">Navigation and Mapping</option>
                                <option value="Payments">Payments</option>
                                <option value="Platforms">Platforms</option>
                                <option value="Privacy and Security">Privacy and Security</option>
                                <option value="Professional Services">Professional Services</option>
                                <option value="Real Estate">Real Estate</option>
                                <option value="Sales and Marketing">Sales and Marketing</option>
                                <option value="Science and Engineering">Science and Engineering</option>
                                <option value="Software">Software</option>
                                <option value="Sports">Sports</option>
                                <option value="Sustainability">Sustainability</option>
                                <option value="Transportation">Transportation</option>
                                <option value="Travel and Tourism">Travel and Tourism</option>
                                <option value="Video">Video</option>
                                <option value="Other">Other</option>
                                <option value="Unknown">Unknown</option>
                            </select>
                        </div>
                        <!-- Sector DROPDOWN | Data being fed through JQuery -->
                        <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 " id="ArtificialIntelligenceDrop">
                            <label for="Sector" class="form-label" >Sector </label>
                            <select id="Sector" name="Sector[]"  class="form-select sectorDropdowns" multiple="true" required>
                                <option>choose...</option>
                            </select>
                            <br>
                            <small style="color:red;">First select an industry </small>
                        </div>
                        <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                            <label for="Details" class="form-label">Details</label>
                            <input type="text" class="form-control" id="Details" name="Details">
                        </div>
                        <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                            <label for="YearFounded" class="form-label">Year Founded</label>
                            <select class="form-control" name="YearFounded" id="YearFounded">
                                    <option value=""> Select...</option>
                            </select>
                        </div>
                        <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12 ">
                            <label for="Headquarters" class="form-label">Country</label>
                            <select class="form-select" id="Headquarters" name="Headquarters" required>
                                <option> Select...</option>
                                <?php
                                    while ($row101 = mysqli_fetch_assoc($result101)) {
                                        # code...
                                        echo "<option>".$row101['Country']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                <label for="img" class="form-label">Logo</label>
                                <input type="file" class="form-control" id="img" name="img" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>
                    <a class="btn btn-danger" href="javascript:window.open('','_self').close();">Close</a>
                </form>
            </div>
        </main>
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="../../../js/scripts.js"></script>
        <script src="../../../js/select2.min.js"></script>
        <script src="../../../js/DateDropDown.js"></script>
        <script src="../../../js/MultiSelect.js"></script>
    </body>
</html>
