<?php 
    // EXPORTING A CSV FILE OF THE DATA
    If(isset($_POST["export"])){
        include_once('../App/connect.php');
        // QUERY DATABASE FROM DATA
        header('Content-Type:text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');

        $output = fopen("php://output","w");
        fputcsv($output, array('PortfolioCompanyName','Investment Manager(s)','Fund(s)','Currency','Website','Industry','Sector','Details','Year Founded','Country','CEO'));
        $query = "  SELECT DISTINCT
                        PortfolioCompany.PortfolioCompanyName, GROUP_CONCAT(DISTINCT InvestorName) AS InvestorName, GROUP_CONCAT(DISTINCT FundName) AS FundName, Currency.Currency, PortfolioCompany.Website, GROUP_CONCAT(DISTINCT Industry) AS Industry, GROUP_CONCAT(DISTINCT Sector) AS Sector,  PortfolioCompany.Details, PortfolioCompany.YearFounded, Country.Country, UserDetail.UserFullName
                    FROM 
                        PortfolioCompany 
                    LEFT JOIN 
                        InvestorPortfolioCompany 
                    ON 
                        InvestorPortfolioCompany.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
                    LEFT JOIN 
                        Investor 
                    ON 
                        Investor.InvestorID = InvestorPortfolioCompany.InvestorID 
                    LEFT JOIN 
                        FundPortfolioCompany 
                    ON 
                        FundPortfolioCompany.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
                    LEFT JOIN 
                        Fund 
                    ON 
                        Fund.FundID = FundPortfolioCompany.FundID 
                    LEFT JOIN 
                        Currency 
                    ON 
                        Currency.CurrencyID = PortfolioCompany.CurrencyID 
                    LEFT JOIN 
                        PortfolioCompanyLocation
                    ON
                        PortfolioCompanyLocation.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
                    LEFT JOIN 
                        Country 
                    ON 
                        Country.CountryID = PortfolioCompanyLocation.CountryID
                    LEFT JOIN 
                        PortfolioCompanyIndustry 
                    ON 
                        PortfolioCompanyIndustry.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
                    LEFT JOIN 
                        Industry 
                    ON 
                        Industry.IndustryID = PortfolioCompanyIndustry.IndustryID
                    LEFT JOIN 
                        PortfolioCompanySector
                    ON 
                        PortfolioCompanySector.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
                    LEFT JOIN 
                        Sector 
                    ON 
                        Sector.SectorID = PortfolioCompanySector.SectorID
                    LEFT JOIN 
                        PortfolioCompanyUserDetail
                    ON 
                        PortfolioCompanyUserDetail.PortfolioCompanyID = PortfolioCompany.PortfolioCompanyID
                    LEFT JOIN 
                        UserDetail
                    ON 
                        UserDetail.UserDetailID = PortfolioCompanyUserDetail.UserDetailID
                    LEFT JOIN 
                        RoleType
                    ON 
                        RoleType.RoleTypeID = UserDetail.RoleTypeID
                    LEFT JOIN 
                        Gender
                    ON
                        Gender.GenderID = UserDetail.GenderID
                    LEFT JOIN 
                        Race 
                    ON 
                        Race.RaceID =UserDetail.RaceID
                    WHERE 
                        PortfolioCompany.Deleted = 0
                        
                    GROUP BY PortfolioCompany.PortfolioCompanyName, Currency.Currency, PortfolioCompany.Website, PortfolioCompany.Details, PortfolioCompany.YearFounded, Country.Country
        ";
        $result = mysqli_query($conn, $query);

        while( $row = mysqli_fetch_assoc($result))
        {
            fputcsv($output, $row);
        }
            fclose($output);
        }
?>