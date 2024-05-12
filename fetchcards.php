<?php
include 'components/connect.php';
require("campcolor.php");
if (isset($_POST["query"]) || isset($_POST["Category"]) || isset($_POST["status"])) {
    $search = isset($_POST["query"]) ? $_POST["query"] : "";
    $Category = isset($_POST["Category"]) ? $_POST["Category"] : "";
    $status = isset($_POST["status"]) ? $_POST["status"] : "";
    $location = isset($_POST["Location"]) ? $_POST["Location"] : "";
    $newrownbrs = isset($_POST["newrownbrs"]) ? $_POST["newrownbrs"] : "12";

    $query = "SELECT * FROM campaign JOIN user ON campaign.creator_id = user.user_id WHERE 1=1";

    if (!empty($search)) {
        $query .= " AND (Category LIKE :search OR city LIKE :search OR Country LIKE :search OR title LIKE :search OR description LIKE :search OR currency LIKE :search OR user.firstname LIKE :search OR user.lastname LIKE :search)";
    }

    if (!empty($Category)) {
        $query .= " AND Category LIKE :Category";
    }

    if (!empty($location)) {
        $query .= " AND Country = :location";
    }

    // Add ORDER BY clause
    if (!empty($status)) {
        $query .= " ORDER BY CASE WHEN financialstatus = :financialstatus THEN 0 ELSE 1 END, targetamount DESC";
    }

    // Prepare the statement
    $statement = $pdo->prepare($query);

    // Bind parameters
    if (!empty($search)) {
        $searchParam = "%$search%";
        $statement->bindParam(":search", $searchParam, PDO::PARAM_STR);
    }
    if (!empty($Category)) {
        $statement->bindParam(":Category", $Category, PDO::PARAM_STR);
    }
    if (!empty($location)) {
        $statement->bindParam(":location", $location, PDO::PARAM_STR);
    }
    if (!empty($status)) {
        $statement->bindParam(":financialstatus", $status, PDO::PARAM_STR);
    }

    $statement->execute();


    $output = '';
    if ($statement->rowCount() > 0) {
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            if (is_array($row)) {
                $imageQuery = $pdo->prepare("SELECT image FROM campaignimages WHERE campaign_fk_id = :campaign_id AND ismaincover = 1");
                $imageQuery->bindParam(":campaign_id", $row['campaign_id'], PDO::PARAM_INT);
                $imageQuery->execute();
                $imageResult = $imageQuery->fetch(PDO::FETCH_ASSOC);
                $imageData = $imageResult['image'];

                $imageSrc = 'data:image/jpeg;base64,' . base64_encode($imageData);

                $percentage = ($row['Currentamount'] * 100) / $row['targetAmount'];
                $percentage = number_format($percentage, 2);

                $donorQuery = $pdo->prepare("SELECT COUNT(*) AS donorCount FROM transaction WHERE campaign_fk_id = :campaign_id");
                $donorQuery->bindParam(":campaign_id", $row['campaign_id'], PDO::PARAM_INT);
                $donorQuery->execute();
                $donorResult = $donorQuery->fetch(PDO::FETCH_ASSOC);
                $donorCount = $donorResult['donorCount'];
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
        }

        echo $output;
        echo '<div class="card fix-flex"></div>
                  <div class="card fix-flex"></div>';
    } else {
        echo '<div class="notfound"><img src="images/notfound.png" alt="notfound">No Campaigns Found</div>';
    }
}
?>
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