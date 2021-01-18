<?php

$pageTitle = "Explore 1916";
include('header.php');
include('slq-integrations.php');

// Get newspapers from the SLQ newspaper dataset
if(!isset($_SESSION['1916_newspaperName']) || empty($_SESSION['1916_newspaperName'])){
    $newspaperAPI = new SlqIntegrations();
    $newspaperAPI->setApiName("newspaper");
    $newspaperAPI->setApiLimit(100);
    $newspaperAPI->setResourceId();
    $newspaperAPI->setCache();
    $newspaperAPI_getData_status = $newspaperAPI->getData();

    // Get 1 images and titles from 1916
    if($newspaperAPI_getData_status){
        $newspaperAPI->removeBlankRecords(["title", "temporal", "150_pixel", "1000_pixel"]);
        $newspaperAPI->filterByCondition("temporal", "/(16)$/");
        $newspaperAPI->filterByCondition("150_pixel", "/(\.jpg)$/");
        $newspaperAPI->extractNumberOfRandomRecords("1");
        $newspaperName = $newspaperAPI->extractData("title");
        $newspaperImageHighRes = $newspaperAPI->extractData("1000_pixel");

        $_SESSION['1916_newspaperName'] = $newspaperName;
        $_SESSION['1916_newspaperImage'] = $newspaperImageHighRes;

    }
    else {
        echo "error with connection to APIs";
    }

// restore the data from session data
} else {
    $newspaperName = $_SESSION['1916_newspaperName'];
    $newspaperImageHighRes = $_SESSION['1916_newspaperImage'];

}

?>

<h1>1916</h1>

<!-- Main game screen with images -->
<div class="gameScreen" id="year1916">
    <div class="train-image"></div>
    <figure class="icon" id="newspaper" onclick="openmodal('Newspaper')">
        <img src="images/newspaper.png" alt="Newspaper" width="80px">
    </figure>
    <figure class="icon" id="photographer">
        <img src="images/businessman.png" alt="man in a suit standing looking for help" width="100px">
    </figure>
    <figure class="icon" id="camera">
        <img src="images/camera.png" alt="An old fashioned camera" width="100px">
    </figure>
    <figure class="icon" id="paperpile">
        <img src="images/paperpile.png" alt="A pile of paper" width="80px">
    </figure>
    <figure class="icon" id="bench">
        <img src="images/bench.png" alt="A wooden bench" width="120px">
    </figure>
    <figure class="icon" id="dog">
        <img src="images/dog.png" alt="a dog" width="80px">
    </figure>
    <figure class="icon" id="pigeon">
        <img src="images/pigeon.png" alt="a pigeon" width="50px">
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

<!-- Modal when talking to photographer  -->
<div id="modalPhotographer" class="popup">
    <div class="content">
        <span onclick="exitmodal('Photographer')" class="exit">&times;</span>
        <h2>Photographer</h2>
        <img src="images/businessman.png" alt="man in a suit standing looking for help" width="160px">
        <p id="patron_txt">I can't talk right now as I accidentally dropped all my photographs on the ground. I have taken photos over the past two years of World War 1 soldiers while they were in the city so I would hate to lose them!</p>
        <p>Would you please help me pick up and sort out my photos?</p>
        <p>I had a second copy of each photo printed so I could send one to the soldier and give the other one to their family. If you match the photographs that are the same then I would be very greatful!</p>
        <p class="hint">To continue you must pick up the photographs dropped on the ground.</p>
        <div class="navigation-rowCentered">
            <button onclick="exitmodal('Photographer')" class="button">Back</button>
            <button id="memory_minigame" class="button disabled">play minigame</button>
        </div>
    </div>
</div>

<!-- Modal when talking to photographer complete  -->
<div id="modalPhotographerComplete" class="popup">
    <div id="photographer_txt" class="content">
        <span onclick="exitmodal('PhotographerComplete')" class="exit">&times;</span>
        <h2>Photographer</h2>
        <img src="images/businessman.png" alt="man in a suit standing looking for help" width="160px">
        <p>You did it! The families of the soldiers are going to be so happy! As a reward... ahh... you can have this shiny silver thing I found the other day...</p>
        <div class="navigation-rowCentered">
            <button class="button" onclick="exitmodal('PhotographerComplete')">Continue</button>
        </div>
    </div>
</div>

<?php include('dashboard.php'); ?>
<?php include('footer.php'); ?>
