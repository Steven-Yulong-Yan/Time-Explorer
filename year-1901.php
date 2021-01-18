<?php

$pageTitle = "Explore 1901";
include('header.php');
include('slq-integrations.php');

// Get newspapers from the SLQ newspaper dataset
if(!isset($_SESSION['1901_newspaperName']) || empty($_SESSION['1901_newspaperName'])){
    $newspaperAPI = new SlqIntegrations();
    $newspaperAPI->setApiName("newspaper");
    $newspaperAPI->setApiLimit(100);
    $newspaperAPI->setResourceId();
    $newspaperAPI->setCache();
    $newspaperAPI_getData_status = $newspaperAPI->getData();

    // Get 1 images and titles from 1901
    if($newspaperAPI_getData_status){
        $newspaperAPI->removeBlankRecords(["title", "temporal", "150_pixel", "1000_pixel"]);
        $newspaperAPI->filterByCondition("temporal", "/(01)$/");
        $newspaperAPI->filterByCondition("150_pixel", "/(\.jpg)$/");
        $newspaperAPI->extractNumberOfRandomRecords("1");
        $newspaperName = $newspaperAPI->extractData("title");
        $newspaperImageHighRes = $newspaperAPI->extractData("1000_pixel");

        $_SESSION['1901_newspaperName'] = $newspaperName;
        $_SESSION['1901_newspaperImage'] = $newspaperImageHighRes;

    }
    else {
        echo "error with connection to APIs";
    }

// restore the data from session data
} else {
    $newspaperName = $_SESSION['1901_newspaperName'];
    $newspaperImageHighRes = $_SESSION['1901_newspaperImage'];

}

// Get languages from the SLQ language dataset
if(!isset($_SESSION['1901_languageUsed']) || empty($_SESSION['1901_languageUsed'])){
    $languageAPI = new SlqIntegrations();
    $languageAPI->setApiName("language");
    $languageAPI->setApiLimit(100);
    $languageAPI->setResourceId();
    $languageAPI->setCache();
    $languageAPI_cached = $languageAPI->checkIfCached();
    $languageAPI_getData_status = $languageAPI->getData();
    if ($languageAPI_cached){
        $languageAPI->extractLanguageUsed();
    }

    // save all the words and definitions into an HTML table
    if($languageAPI_getData_status){
        $languageAPI->removeBlankRecords(["English"]);
        $languageAPI->extractNumberOfRandomRecords(100);
        $languageUsed = $languageAPI->returnLanguageUsed();
        $languageIndigenous = $languageAPI->extractData($languageUsed);
        $languageListHTML = $languageAPI->printRecordsAsHTML(["English", $languageUsed, "Pronunciation"]);

        $_SESSION['1901_languageUsed'] = $languageUsed;
        $_SESSION['1901_languageListHTML'] = $languageListHTML;
        $_SESSION['1901_languageIndigenousWords'] = $languageIndigenous;
    }
    else {
        echo "error with connection to APIs";
    }

// restore the data from session data
} else {
    $languageUsed = $_SESSION['1901_languageUsed'];
    $languageListHTML = $_SESSION['1901_languageListHTML'];
    $languageIndigenous = $_SESSION['1901_languageIndigenousWords'];

}

?>

<h1>1901</h1>

<!-- Main game screen with images -->
<div class="gameScreen" id="year1901">
    <div class="train-image"></div>
    <figure class="icon" id="newspaper" onclick="openmodal('Newspaper')">
        <img src="images/newspaper.png" alt="Newspaper" width="100px">
    </figure>
    <figure class="icon" id="indigenous">
        <img src="images/indigenous.png" alt="indigenous family" width="400px">
    </figure>
    <figure class="icon" id="languageBook">
        <img src="images/language_book.png" alt="a language book" width="70px">
    </figure>
    <figure class="icon" id="boat">
        <img src="images/boat.png" alt="a boat" width="110px">
    </figure>
    <figure class="icon" id="dog">
        <img src="images/dog.png" alt="a dog" width="100px">
    </figure>
</div>

<!-- Modal for newspaper -->
<div id="modalNewspaper" class="popup">
    <div class="content">
        <span onclick="exitmodal('Newspaper')" class="exit">&times;</span>
        <h2><?php echo $newspaperName[0];?></h2>
        <div class="apiImage">
            <?php echo "<img src=$newspaperImageHighRes[0] alt=\"image of an old newspaper\">" ?>
        </div>
        <div class="navigation-rowCentered">
            <button onclick="exitmodal('Newspaper')" class="button">Back</button>
        </div>
    </div>
</div>

<!-- Modal for languages HTML table -->
<div id="modalLanguages" class="popup">
    <div class="content">
        <span onclick="exitmodal('Languages')" class="exit">&times;</span>
        <h2><?php echo $languageUsed;?> translations</h2>
        <table class="languageListTable">
            <tr>
                <th>English</th>
                <th><?php echo $languageUsed;?></th>
                <th>Pronunciation</th>
            </tr>
            <?php echo $languageListHTML;?>
        </table>
        <div class="navigation-rowCentered">
            <button onclick="exitmodal('Languages')" class="button">Back</button>
        </div>
    </div>
</div>

<!-- Modal for talking to the indigenous family -->
<div id="modalIndigenous" class="popup">
    <div class="content">
        <span onclick="exitmodal('Indigenous')" class="exit">&times;</span>
        <h2>Indigenous Family</h2>
        <img src="images/indigenous.png" alt="indigenous family" width="400px">
        <p>The indigenous family speak to you in <?php echo $languageUsed ?>:</p>
        <p>"<?php echo $languageIndigenous[0] . " " . $languageIndigenous[1] . " " .  $languageIndigenous[2] . " " .  $languageIndigenous[3] . " " .  $languageIndigenous[4] . " " .  $languageIndigenous[5] . " " .  $languageIndigenous[6] . " " .  $languageIndigenous[7] . " " .  $languageIndigenous[8] . " " .  $languageIndigenous[9] . " " .  $languageIndigenous[10] ?>".<p>
        <p class="hint">To continue you must find a language translation book.</p>
        <div class="navigation-rowCentered">
            <button onclick="exitmodal('Indigenous')" class="button">Back</button>
            <button id="translateButton" class="button disabled">Translate</button>
        </div>
    </div>
</div>

<!-- Modal for talking to the indigenous family when translated -->
<div id="modalTranslated" class="popup">
    <div class="content">
        <span onclick="exitmodal('Translated')" class="exit">&times;</span>
        <h2>Indigenous Family</h2>
        <img src="images/indigenous.png" alt="indigenous family" width="400px">
        <p>You talk to the indigenous family who are very excited to hear about your adventures.</p>
        <p>You ask them whether they have found anything strange around here and they tell you about a strange looking rock they found. They show you the rock, which is actually the cog you need for the train! They are more than happy to help out and decide to give you the cog.</p>
        <p>Nice work time explorer!</p>
        <div class="navigation-rowCentered">
            <button onclick="exitmodal('Translated')" class="button">Back</button>
        </div>
    </div>
</div>

<?php include('dashboard.php'); ?>
<?php include('footer.php'); ?>
