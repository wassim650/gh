<?php
include 'components/connect.php';
include("campcolor.php");
// Start or resume session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to get user information
function getUserInfo($pdo, $user_id)
{
    $query = "SELECT * FROM user WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
if ($pdo) {
    // Check if user is logged in
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $user = getUserInfo($pdo, $user_id);

        // Fetch user type
        $userType = ($user && isset($user['UserType'])) ? $user['UserType'] : '';

        // Determine which header to include based on user type
        if ($userType === 'Admin') {
            include 'components/admin_header.php';
        } else {
            include 'components/user_header.php';
        }
    } else {
        // Display default header if user is not logged in
        include 'components/normal_header.php';
    }
} else {
    // Handle database connection error
    echo "Error: Database connection failed.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore</title>
    <link rel="icon" type="image/png" href="images/ico.png" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- Include your CSS file for styling -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<style>
    .warp2 {
        height: auto;
        width: auto;
    }

    .nir {
        display: flex;
        justify-content: center;
        align-items: center;
        width: auto;
    }

    .cards {
        max-width: 1237px;
        margin-right: auto;
        margin-left: auto;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 4rem;
        justify-content: flex-start;
        margin-bottom: 25px;
    }

    .srch {
        padding-bottom: 40px;
        display: flex;
        gap: 2rem;
        /* align-items: center; */
        justify-content: center;
        flex-wrap: wrap;
        border-bottom: 1px solid #f5f6f7;
        border-top: 1px solid #f5f6f7;
    }

    p {
        margin-bottom: 16px;
    }

    #select1 {
        width: 300px;
        height: 60px;
    }

    #select2 {
        width: 300px;
        height: 60px;
    }

    .button {
        background-color: cadetblue;
        margin: 20px auto;
        width: 1400px;
        padding: 5px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .card {
        max-width: 365px;
        height: 428px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        transition: 0.3s;
        border-radius: 16px;
        box-sizing: border-box;
    }

    .card img {
        border-top-left-radius: 16px;
        /* Top-left corner radius */
        border-top-right-radius: 16px;
        /* Top-right corner radius */
    }

    .fix-flex {
        width: 384px;
        height: 428px;
        background-color: #eaebee;
        visibility: hidden;
    }

    .card:hover {
        margin-top: -14px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .18);
    }

    .beneath-pic {
        display: flex;
        justify-content: space-between;
        color: #2f435a;
        margin-bottom: 11.5px;
    }

    .camp-cover img {
        height: 150px;
        width: 100%;
        object-fit: cover;
        aspect-ratio: 19/6;

    }

    .beneath-progress-bar {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .full-pb {
        position: relative;
        display: flex;
        justify-content: flex-start;
        width: 100%;
        background: #f5f6f7;
        border-radius: 10px;
        height: 12px;
        margin-bottom: 5px;
    }

    .current-pb {
        background-color: #34ca96;
        height: 100%;
        height: 12px;
        overflow: hidden;
        border-radius: 10px;
    }

    .form-control:hover {
        border-color: #028858;
    }

    .camp-content {
        padding: 14px;
    }

    #camp-description {
        color: #2f435a;
        font-weight: 700;
        font-size: 18px;
        overflow: hidden;
        max-height: 53px;
        height: 53px;
    }

    #camp-description2 {
        max-height: 72px;
        height: 72px;
        overflow: hidden;
    }

    .beneath-progress-bar {
        margin-top: 2px;
    }

    .percentage {
        transform: translate(-50%, -50%);
        font-size: 17px;
        /* Adjust the font size as needed */
        font-family: Arial, sans-serif;
        /* You can change the font family */
        color: #333;
    }

    a {
        text-decoration: none;
    }

    a:hover {
        text-decoration: none;

    }

    #search_text {
        height: 46px;
        padding-right: 15px;
        padding-left: 40px;
    }

    .form-control {
        border: 1px solid #eaebee;
        box-sizing: border-box;
        border-radius: 8px;
        width: 100%;
        height: 65px;
        padding: 0 16px;
        font-size: 18px;
        color: #2f435a;
        transition: .4s;
        box-shadow: none !important;
        -webkit-appearance: none;
        outline: 0;
    }

    ::marker {
        content: none;
    }

    .search-field {
        width: 485px;
        margin-top: 2px;
    }

    .field-group label,
    .search-field label,
    .sort label {
        display: block;
        font-family: 'Poppins', sans-serif;
        font-style: normal;
        font-weight: 700;
        font-weight: 500;
        font-size: 18px;
        color: #2f435a;
        display: block;
    }

    .sort label {
        display: inline-block !important;
    }

    .search_text ::before {
        content: "";
        position: absolute;
        left: 16px;
        top: 0;
        bottom: 0;
        width: 16px;
        background: url("images/search.png") center/contain no-repeat;
    }

    .search_text {
        position: relative;

    }

    .input-has-value ::before {
        left: -440px;
    }

    .input-has-value .eraser {
        content: "";
        position: absolute;
        right: 14px;
        top: 0;
        bottom: 0;
        width: 18px;
        background: url("https://assets.gogetfunding.com/wp-content/uploads/customdata/others/Clear-Input.svg") center/contain no-repeat;
        cursor: pointer;
        z-index: 1;
    }

    .sort {
        display: flex;
        flex-wrap: wrap;
        /* justify-content: flex-start; */
        width: auto;
        /* margin-right: auto; */
        /* margin-left: auto; */
        /*margin-bottom: 30px;*/
    }

    .field-group {
        width: 350px;
    }

    .sort-field {
        width: 240px;
        flex-shrink: 1
    }

    .notfound {
        height: auto;
        width: 100%;
        align-items: center;
        font-family: Arial, sans-serif;
        margin-bottom: 50px;
        font-size: 32px;
        text-align: center;
        display: flex;
        flex-direction: column;
        font-weight: 300;
        color: #798798;
        line-height: 1.5;
    }

    .notfound img {
        width: 440px;
    }

    .beneath-pic>span:nth-child(2):hover {
        cursor: pointer;
        text-decoration: underline;
        color: #4a90e2;
    }

    #cardcategory:hover {
        cursor: pointer;
        opacity: 0.6;
    }

    .uil-angle-down:before {
        content: '\eb3a';

    }

    ::selection {
        color: #fff;
        background: #4285f4;
    }

    .wrapper {
        border: 1px solid #eaebee;
        border-radius: 8px;
        width: 100%;
        align-content: center;
        height: 46px;
        position: relative;
    }

    .sort {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .select-btn,
    li {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .select-btn {
        font-size: 18px;
        border-radius: 7px;
        padding-left: 12px;
        padding-right: 12px;
        justify-content: space-between;
    }

    .select-btn i {
        font-size: 31px;
        transition: transform 0.3s linear;
    }

    .wrapper.active .select-btn i {
        transform: rotate(-180deg);
    }

    .select-btn i {
        font-size: 31px;
        transition: transform 0.3s linear;
    }

    .wrapper.active .select-btn i {
        transform: rotate(-180deg);
    }

    .content {
        margin-top: 5px;
        display: none;
        padding: 20px;
        background: #fff;
        border-radius: 7px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        position: absolute;
        /* Change position to absolute */
        top: 100%;
        width: 100%;
        /* Position it below the select button */
        left: 0;
        /* Align it with the left edge of the wrapper */
        z-index: 1;
        /* Add a higher z-index */
    }

    .wrapper.active .content {
        display: list-item;

    }

    .content .search {
        position: relative;
    }

    .content ::marker {
        display: none;
    }

    .search i {
        top: 50%;
        left: 15px;
        color: #999;
        font-size: 20px;
        pointer-events: none;
        transform: translateY(-50%);
        position: absolute;
        display: none;
    }

    .search input {
        height: 50px;
        width: 100%;
        outline: none;
        font-size: 17px;
        border-radius: 5px;
        padding: 0 20px 0 43px;
        border: 1px solid #B3B3B3;
        display: none;
    }

    .search input:focus {
        padding-left: 42px;
        border: 2px solid #4285f4;
    }

    .search input::placeholder {
        color: #bfbfbf;
    }

    .content .options {
        margin-top: 10px;
        max-height: 250px;
        overflow-y: auto;
        padding-right: 7px;
        padding-left: 0 !important;
    }

    .options::-webkit-scrollbar {
        width: 7px;
    }

    .options::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 25px;
    }

    .options::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 25px;
    }

    .options::-webkit-scrollbar-thumb:hover {
        background: #b3b3b3;
    }

    .options li {
        height: 50px;
        padding: 0 13px;
        font-size: 16px;
    }

    .options li:hover,
    li.selected {
        background: rgba(74, 144, 226, .1);
        color: #4a90e2;
        border-radius: 8px;
    }


    .wrapper:hover {
        border-color: #028858 !important;

    }

    .active {
        border-color: #028858 !important;
    }

    body:hover .wrapper.active:not(:hover) {
        border-color: #028858;
    }

    .cab {
        width: 100%;
        height: 200px;
        align-content: center;
        position: relative;
        /* top: 65px; */
        background-color: #eef5fe;
        margin-bottom: 40px;
        border: 1px solid;
        border-color: #eaebee;
    }

    .cab .text {
        color: #2f435a;
        text-align: center;
        font-size: 32px;
        font-weight: bold;
    }

    .cab .p9 {
        color: #2f435a;
        text-align: center;
        font-size: 16px;
    }

    @media only screen and (max-width: 1000px) {
        .search-field {
            width: 400px;
            padding-top: 30px;
        }

        .cab {
            top: 65px;
        }

        .cards {
            justify-content: center;
        }
    }
</style>

<body>

    <div class=warp2>
        <div class="cab">
            <h1 class="text">Explore campaigns</h1>
        </div>
        <div class="nir">
            <div class="srch">
                <div class="search-field">
                    <label for="search_text">Search</label>
                    <label class="search_text">
                        <input type="text" id="search_text" name="search_text" value="" placeholder="Try searching " class="form-control" required="required">
                        <span class="eraser" data-id="search_text"></span>
                    </label>
                </div>



                <div class="field-group" id="categoryDropdown">
                    <label for="categoryDropdown">Category</label>
                    <div class="wrapper" id="categoryDropdown">
                        <div class="select-btn">
                            <span>All categories</span>
                            <i class="uil uil-angle-down"></i>
                        </div>
                        <div class="content">
                            <div class="search">
                                <i class="uil uil-search"></i>
                                <input spellcheck="false" type="text" placeholder="Search">
                            </div>
                            <ul class="options"></ul>
                        </div>
                    </div>
                </div>
                <div class="field-group" id="countryDropdown">
                    <label for="countryDropdown">Location</label>
                    <div class="wrapper" id="countryDropdown">
                        <div class="select-btn">
                            <span>Global</span>
                            <i class="uil uil-angle-down"></i>
                        </div>
                        <div class="content">
                            <div class="search">
                                <i class="uil uil-search"></i>
                                <input spellcheck="false" type="text" placeholder="Search">
                            </div>
                            <ul class="options"></ul>
                        </div>
                    </div>
                </div>

                <div class="sort" id="sort">
                    <label for="sort">Sort by :</label>
                    <div class="sort-field">
                        <div class="wrapper" id="sort">
                            <div class="select-btn">
                                <span>Getting funded</span>
                                <i class="uil uil-angle-down"></i>
                            </div>
                            <div class="content">
                                <div class="search">
                                    <i class="uil uil-search"></i>
                                    <input spellcheck="false" type="text" placeholder="Search">
                                </div>
                                <ul class="options"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>


        <div class="cards" id="result">
            <?php $sql = "SELECT * FROM campaign WHERE campaignstatus = 'accepted' ORDER BY CASE WHEN financialstatus = 'gettingfunded' THEN 0 ELSE 1 END, targetamount DESC LIMIT 12"; //WHERE campaignstatus = 'accepted' to be added later after campaig in $sql
            $statement = $pdo->query($sql);
            $output = "";
            $rowCount = $statement->rowCount();
            if ($statement->rowCount() > 0) {
                while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                    // Fetch main cover image
                    $imageQuery = $pdo->prepare("SELECT image FROM campaignimages WHERE campaign_fk_id = :campaign_id AND ismaincover = 1");
                    $imageQuery->bindParam(":campaign_id", $row['campaign_id'], PDO::PARAM_INT);
                    $imageQuery->execute();
                    $imageResult = $imageQuery->fetch(PDO::FETCH_ASSOC);
                    $imageData = $imageResult['image'];

                    // Convert BLOB data to base64 encoded string
                    $imageSrc = 'data:image/jpeg;base64,' . base64_encode($imageData);

                    // Calculate percentage
                    $percentage = ($row['Currentamount'] * 100) / $row['targetAmount'];
                    $percentage = number_format($percentage, 2);

                    // Count donors
                    $donorQuery = $pdo->prepare("SELECT COUNT(*) AS donorCount FROM transaction WHERE campaign_fk_id = :campaign_id");
                    $donorQuery->bindParam(":campaign_id", $row['campaign_id'], PDO::PARAM_INT);
                    $donorQuery->execute();
                    $donorResult = $donorQuery->fetch(PDO::FETCH_ASSOC);
                    $donorCount = $donorResult['donorCount'];
                    //set categocolor
                    $categoryColor = $categoryColors[$row['Category']];
                    $output .= '
                    <div class="card">
                    <a href="campaign.php?id=' . $row['campaign_id'] . '&title=' . $row['title'] . '">
                     <div class="camp-cover"><img src="' . $imageSrc . '" alt=""></div></a>
                        <div class="camp-content">
                            <div class="beneath-pic">
                                <span id="cardcategory" style="color: ' . $categoryColor . ';" onclick="setDropdownValue(\'categoryDropdown\', \'' . $row['Category'] . '\');">' . $row['Category'] . '</span>        
                                <span onclick="setDropdownValue(\'countryDropdown\', \'' . $row['Country'] . '\');"><i class="flag flag-' . strtolower($row['Country']) . '"></i>' . $row['Country'] . '</span>                             
                            </div>
                            <a href="campaign.php?id=' . $row['campaign_id'] . '&title=' . $row['title'] . '">
                            <p id="camp-description">' . $row['title'] . '</p></a>
                            <p id="camp-description2">' . $row['description'] . '</p>
                            <span class="percentage" id="percentage-' . $row['campaign_id'] . '">' . $percentage . '%</span>
                        <div class="full-pb">
                            <div id="progress-bar-' . $row['campaign_id'] . '" class="current-pb" style="width: ' . $percentage . '%;"></div>
                        </div>
                        <div class="beneath-progress-bar">
                            <span>' . $row['currency'] . '<span id="current-amount-' . $row['campaign_id'] . '">' . $row['Currentamount'] . '</span> raised</span>
                            <span id="donor-count-' . $row['campaign_id'] . '">' . $donorCount . ' donors</span>
                        </div>
                        </div>
                    </div>
                ';
                }
                echo $output;
            } else {
                echo 'No Active Campaigns yet';
            }
            ?>
            <!--<div class="card fix-flex"></div> do not remove 
            <div class="card fix-flex"></div>-->
        </div>


        <!--         
        <?php if ($rowCount > 12) : ?>
        <div class="button">
            <button id="showmore">show more</button>
        </div>
         <?php endif; ?>
                  -->

    </div>

    <?php include '../PFE/components/fotter.php'; ?>

    <script src="menuspluscall.js"></script>
    <script src="getsein.js"></script>
    <script src="js\script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script>
        function updateCampaignData() {
            $.ajax({
                url: 'update_progress.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    data.forEach(function(campaign) {
                        var campaignId = campaign.campaign_id;
                        var CurrentAmount = campaign.Currentamount;
                        var targetAmount = campaign.targetAmount;
                        var percentage = (CurrentAmount * 100) / targetAmount;
                        percentage = percentage.toFixed(2);

                        // Update progress bar width
                        $('#progress-bar-' + campaignId).css('width', percentage + '%');

                        // Update donor count
                        $('#donor-count-' + campaignId).text(campaign.donorCount + ' donors');

                        // Update percentage
                        $('#percentage-' + campaignId).text(percentage + '%');

                        // Update Currentamount
                        $('#current-amount-' + campaignId).text(campaign.Currentamount);
                    });
                }
            });
        }


        // Call updateCampaignData initially and every 10 seconds
        $(document).ready(function() {
            updateCampaignData(); // Update initially
            setInterval(updateCampaignData, 2000); // Update every 2 seconds
        });
    </script>

    <script>
        $(document).ready(function() {
            // var newrownbrs = 12;
            // $('#showmore').click(function(){
            //     var Category = $('#select1').val();
            // 	var status = $('#select2').val();
            // 	var searchQuery = $('#search_text').val(); 
            // 	newrownbrs = newrownbrs + 8;
            // 	$("#result").load("fetchcards.php", { 
            //         newrownbrs: newrownbrs,
            // 		query: searchQuery,
            // 		status: status,
            // 		Category: Category
            // 	});
            // });




            $('#search_text').on('input', function() {
                if ($(this).val() === '') {
                    getseinData();
                }
            });
            $('#search_text').on('keyup', function(event) {
                if (event.keyCode === 13) { // Check if the key pressed is Enter key
                    getseinData(); //get cards upon hitting Enter key
                }
            });
        });

        //search input eraser functionalities 
        const searchInput = document.getElementById('search_text');
        const element = document.querySelector('.search_text');
        searchInput.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                this.parentElement.classList.add('input-has-value');
            } else {
                this.parentElement.classList.remove('input-has-value');
            }
            let searchbefore = window.getComputedStyle(element, '::before')
        });
        $(document).ready(function() {
            $(".eraser").click(function() {
                var inputId = $(this).data("id");
                $("#" + inputId).val("");
                getseinData()
                searchInput.parentElement.classList.remove('input-has-value');
            });
        });

        const activeCategoryDropdown = document.querySelector('.field-group active');
        // Function to change border color of the active categoryDropdown
        function changeBorderColor(event) {
            console.log("haha")
            // Check if the cursor is hovering over the body
            if (event.target === document.body) {
                // Change border color of the active categoryDropdown
                if (activeCategoryDropdown) {
                    activeCategoryDropdown.style.borderColor = 'red'; // Change to the color you desire
                }
            }
        }

        // Listen for mouseover event on the body
        document.body.addEventListener('mouseover', changeBorderColor);
    </script>
</body>

</html>