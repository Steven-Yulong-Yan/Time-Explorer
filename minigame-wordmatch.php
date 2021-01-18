<?php
$pageTitle = "Word Match Game";
include('header.php');
include('slq-integrations.php');

// Get list of languages from the SLQ inidengous languages dataset
$languageAPI = new SlqIntegrations();
$languageAPI->setApiName("language");
$languageAPI->setApiLimit(100);

// Set the language used from session data
if(isset($_SESSION['1901_languageUsed']) && !empty($_SESSION['1901_languageUsed'])){
    $languageUsed = $_SESSION['1901_languageUsed'];
    $languageAPI->setLanguageUsed($languageUsed);

    $languageAPI->setCache();
    $languageAPI_cached = $languageAPI->checkIfCached();
    $languageAPI_getData_status = $languageAPI->getData();

    if($languageAPI_getData_status){
        $languageAPI->removeBlankRecords(["English"]);

        // Save a html version of all words and definitions
        $languageHTML = $languageAPI;
        $languageHTML->extractNumberOfRandomRecords(100);
        $languageListHTML = $languageHTML->printRecordsAsHTML(["English", $languageUsed, "Pronunciation"]);

        // Save 4 english and indigenous words
        $languageAPI->extractNumberOfRandomRecords(4);
        $languageEnglish = $languageAPI->extractData("English");
        $languageIndigenous = $languageAPI->extractData($languageUsed);

        // separate the answers into a comma delim string
        $answers = implode(',', $languageEnglish);

    }
    else {
        echo "error with connection to APIs";
        exit();
    }

} else {
    echo "error with connection to APIs";
    exit();
}




?>
<!-- Used to store the language answers -->
<p class="answers">
    <?php echo $answers ?>
</p>
<h1>Language word match mini-game</h1>
<p class="instructions">Using the language translation book, match up the <?php echo $languageUsed; ?> word on the left to the correct English translation on the right. You can view the language book again by clicking the language book icon in the bottom right corner.</p>
<p class="instructions">Click the "Check answers" button to see if you are correct</p>

<div class="minigame-wordmatch">
    <div class="indigenousWords">
        <h2>
            <?php echo $languageUsed; ?>
        </h2>
        <ul>
            <li>
                <?php echo $languageIndigenous[0] ?>
            </li>
            <li>
                <?php echo $languageIndigenous[1] ?>
            </li>
            <li>
                <?php echo $languageIndigenous[2] ?>
            </li>
            <li>
                <?php echo $languageIndigenous[3] ?>
            </li>
        </ul>
    </div>
    <div class="englishWords">
        <h2>English translation</h2>
        <ul>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
</div>
<p class="answerCorrectNumber"></p>
<p class="minigameFeedback"></p>

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

<div class="navigation-rowCentered">
    <button class="button" id="returnTo1901">Back</a>
    <button class="button" id="newWords">New words</a>
    <button class="button" id="resetWords">Reset</button>
    <button class="button" id="checkAnswers">Check answers</button>
    <img id="languageBookMinigamePage" src="images/language_book.png" alt="a language book" width="160px">
</div>

<?php include('footer.php'); ?>