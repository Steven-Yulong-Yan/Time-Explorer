<?php
$pageTitle = "Memory Game";
include('header.php');
include('slq-integrations.php');


// Get portraits from the SLQ WW1 portrait dataset
$portraitAPI = new SlqIntegrations();
$portraitAPI->setApiName("portrait");
$portraitAPI->setApiLimit(100);
$portraitAPI->setResourceId();
$portraitAPI->setCache();
$portraitAPI_getData_status = $portraitAPI->getData();

// Get 6 image URLs and names for the years 1914 and 1915
if($portraitAPI_getData_status){
    $portraitAPI->removeBlankRecords(["Full name (from National Archives of Australia)", "Temporal", "Thumbnail image", "High resolution image"]);
    $portraitAPI->filterByCondition("Temporal", "/(1[4-5])$/");
    $portraitAPI->extractNumberOfRandomRecords("6");
    $portraitName = $portraitAPI->extractData("Full name (from National Archives of Australia)");
    $portraitTemporal = $portraitAPI->extractData("Temporal");
    $portraitImageHighRes = $portraitAPI->extractData("High resolution image");


    
    // Create an array with 2 copies of each potrait
    $portraitsFirstCopy = implode(',', $portraitImageHighRes);
    $portraitsDuplicate = implode(',', $portraitImageHighRes);
    $portraitsArray = $portraitsFirstCopy . "," . $portraitsDuplicate;

}
else {
    echo "error with connection to APIs";
}


?>
<!-- Used to store the portrait array -->
<p class="portraitsArray">
    <?php echo $portraitsArray ?>
</p>

<h1>Photograph memory mini-game</h1>
<p class="instructions">Click on the shapes below to reveal a photograph. Select a second square to see if it is identical. Try to match all the photographs to complete the game!<p>

<div class="minigame-memory">
    <div id="memoryItemsContainer">
        <div class="memoryItem">
            <img class="hidden" src="" width="100%" />
        </div>
        <div class="memoryItem">
            <img class="hidden" src="" width="100%" />
        </div>
        <div class="memoryItem">
            <img class="hidden" src="" width="100%" />
        </div>
        <div class="memoryItem">
            <img class="hidden" src="" width="100%" />
        </div>
        <div class="memoryItem">
            <img class="hidden" src="" width="100%" />
        </div>
        <div class="memoryItem">
            <img class="hidden" src="" width="100%" />
        </div>
        <div class="memoryItem">
            <img class="hidden" src="" width="100%" />
        </div>
        <div class="memoryItem">
            <img class="hidden" src="" width="100%" />
        </div>
        <div class="memoryItem">
            <img class="hidden" src="" width="100%" />
        </div>
        <div class="memoryItem">
            <img class="hidden" src="" width="100%" />
        </div>
        <div class="memoryItem">
            <img class="hidden" src="" width="100%" />
        </div>
        <div class="memoryItem">
            <img class="hidden" src="" width="100%" />
        </div>
    </div>
</div>
<p class="minigameFeedback correct"></p>
<div class="navigation-rowCentered">
    <button class="button" id="returnTo1916">Back</a>
    <button class="button" id="newPhotos">New game</a>
</div>

<?php include('footer.php'); ?>