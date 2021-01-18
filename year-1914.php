<?php
$pageTitle = "Explore 1914";
include('header.php');
include('slq-integrations.php');

// Get newspapers from the SLQ newspaper dataset
if(!isset($_SESSION['1914_newspaperName']) || empty($_SESSION['1914_newspaperName'])){
    $newspaperAPI = new SlqIntegrations();
    $newspaperAPI->setApiName("newspaper");
    $newspaperAPI->setApiLimit(100);
    $newspaperAPI->setResourceId();
    $newspaperAPI->setCache();
    $newspaperAPI_getData_status = $newspaperAPI->getData();

    // Get 1 images and titles from 1914
    if($newspaperAPI_getData_status){
        $newspaperAPI->removeBlankRecords(["title", "temporal", "150_pixel", "1000_pixel"]);
        $newspaperAPI->filterByCondition("temporal", "/(14)$/");
        $newspaperAPI->filterByCondition("150_pixel", "/(\.jpg)$/");
        $newspaperAPI->extractNumberOfRandomRecords("1");
        $newspaperName = $newspaperAPI->extractData("title");
        $newspaperImageHighRes = $newspaperAPI->extractData("1000_pixel");

        $_SESSION['1914_newspaperName'] = $newspaperName;
        $_SESSION['1914_newspaperImage'] = $newspaperImageHighRes;

    }
    else {
        echo "error with connection to APIs";
    }

// restore the data from session data
} else {
    $newspaperName = $_SESSION['1914_newspaperName'];
    $newspaperImageHighRes = $_SESSION['1914_newspaperImage'];

}

?>

<h1>1914</h1>

<!-- Main game screen with images -->
<div class="gameScreen" id="year1914">
    <div class="train-image"></div>
    <figure class="icon" id="newspaper" onclick="openmodal('Newspaper')">
        <img src="images/newspaper.png" alt="a newspaper" width="70px">
    </figure>
    <figure class="icon" id="musician">
        <img src="images/musician.png" alt="a musician" width="90px">
    </figure>
    <figure class="icon" id="guitar">
        <img src="images/guitar.png" alt="a guitar" width="100px">
    </figure>
    <figure class="icon" id="map">
        <img src="images/map.png" alt="a map" width="70px">
    </figure>
    <figure class="icon" id="patron">
        <img src="images/patron.png" alt="a patron in the crowd" width="200px">
    </figure>
    <figure class="icon" id="presenter">
        <img src="images/presenter.png" alt="a presenter on stage" width="50px">
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

<!-- Modal when talking to musician  -->
<div id="modalMusician" class="popup">
    <div class="content">
        <span onclick="exitmodal('Musician')" class="exit">&times;</span>
        <h2>Musician</h2>
        <img src="images/musician.png" alt="musician" width="100px">
        <p>Oh no! This can't be happening! I have left my sheet music somewhere backstage and the performance starts in 30 minutes!</p>
        <p>Hi there, are you able to help me out? Really? You will? Thank you so much!</p>
        <p>The backstage of this theatre is like a maze so I would recommend bringing a map or your will be lost in their forever! I'm sure there is a map lying around here somewhere...</p>
        <p class="hint">To continue you must find a map of the backstage area.</p>
        <div class="navigation-rowCentered">
            <button onclick="exitmodal('Musician')" class="button">Back</button>
            <button id="maze_minigame" class="button disabled">play minigame</a>
        </div>
    </div>
</div>

<!-- Modal when talking to musician completed -->
<div id="modalMusicianComplete" class="popup">
    <div id="musician_txt" class="content">
        <span onclick="exitmodal('MusicianComplete')" class="exit">&times;</span>
        <h2>Musician</h2>
        <img src="images/musician.png" alt="musician" width="100px">
        <p>Thank you so much for helping me, just in the nick of time too! The show wouldn't of gone on without your help!</p>
        <p>Time for my big performance, wish me luck!</p>
        <p>Oh and I noticed this weird looking pipe in one of our bags, prehaps it belongs to you?</p>
        <div class="navigation-rowCentered">
            <button class="button" onclick="exitmodal('MusicianComplete')">Continue</button>
        </div>
    </div>
</div>

<?php include('dashboard.php'); ?>
<?php include('footer.php'); ?>
