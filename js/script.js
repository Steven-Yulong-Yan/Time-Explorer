
// Global variables
var indigenousSelectedIndex;
var englishSelectedIndex;
var englishSelectedText;
var currentSelections = [false, false, false, false, false];
var timer = null;
var memoryClickCount = 0;
var delay = false;
var currentPosition = 0;


$(document).ready(function () {

    // Reset memory click count and set current maze complete to false (like a reset on page load)
    memoryClickCount = 0;
    localStorage.setItem("currentMazeComplete", "false");

    // Set current URL to variable
    currentUrl = window.location.href;

    // Change the train colour if a colour has been selected by the user
    if(localStorage.getItem("trainColour") !== null){
        $(".train-select.trainSelected").removeClass("trainSelected");
        var trainColour = localStorage.getItem("trainColour");
        $("#" + trainColour + " input").addClass("trainSelected");
        setTrainColour(trainColour);
    } else {
        $("#redtrain input").addClass("trainSelected");
        setTrainColour("redtrain");
    }

    // Change the username fields if a name has been entered by the user
    if(localStorage.getItem("userName") !== null){
        var userName = localStorage.getItem("userName");
        $("#userName").val(userName);
        $(".nameReplace").text(" " + userName);
    } else {
        $("#userName").val("");
    }

    // Word Match: extract answers, shuffle them and add lists to screen
    var wordmatch_answers = $(".answers").text().split(",");
    console.log("Wordmatch answers are: " + wordmatch_answers);
    shuffledAnswers = shuffleArray(wordmatch_answers);
    for (var i = 0; i < shuffledAnswers.length; i++) {
        childNumber = i + 1;
        $(".englishWords li:nth-child(" + childNumber + ")").text(shuffledAnswers[i]);
    }

    // If they have earned a cog, pipe, or bolt, show the image on the nav bar
    if(localStorage.getItem("cog")){
        $("#cog").attr("src", "images/cog.png");
    }
    if (localStorage.getItem('pipe') === 'true') {
        $("#pipe").attr("src", "images/pipe.png");
    }
    if (localStorage.getItem('bolt') === 'true') {
        $("#bolt").attr("src", "images/bolt.png");
    }

    // Check to see if in congrats game or if end game is complete, run guide dialogue depending on result
    if(currentUrl.indexOf("congratulations") != -1){
        guideDialogue("congrats");
    }
    else {
        if(checkIfEndGameConditionsMet()){
            guideDialogue("gameComplete");
        } else {
            guideDialogue("initial");
        }
    }


    // If page is 1901 change switch year 1901 to green
    if(currentUrl.indexOf("1901") != -1){
        $(".yearbuttons.current").removeClass("current");
        $(".yearbuttons:contains('1901')").addClass("current");

        // If user has already collected the language book, show the language book on dashboard and allow minigame
        if(localStorage.getItem("languageBook") === 'true'){
             $("#learningLanguageBook").parent().show();
             $("#languageBook").hide();
             $("#translateButton").removeClass("disabled");
             $(".hint").text("");
        }
     }

    // If page is 1914 change switch year 1914 to green
    if(currentUrl.indexOf("1914") != -1){
        $(".yearbuttons.current").removeClass("current");
        $(".yearbuttons:contains('1914')").addClass("current");

        // If user has already collected the map, show the map on dashboard and allow minigame
        if(localStorage.getItem("learningMap") === 'true'){
            $("#learningMap").parent().show();
            $("#map").hide();
            $("#maze_minigame").removeClass("disabled");
            $(".hint").text("");
        }
    }

    // If page is 1916 change switch year 1916 to green
    if(currentUrl.indexOf("1916") != -1){
       $(".yearbuttons.current").removeClass("current");
       $(".yearbuttons:contains('1916')").addClass("current");

       // If user has already collected the paperpile, show the paperpile on dashboard and allow minigame
       if(localStorage.getItem("paperpile") === 'true'){
            $("#learningPaperpile").parent().show();
            $("#paperpile").hide();
            $("#memory_minigame").removeClass("disabled");
            $(".hint").text("");
        }
    }

    // If page is the wordmatch mini-game set the session variable if null
    if(currentUrl.indexOf("wordmatchComplete") != -1){
        if(localStorage.getItem("wordmatchComplete") === null){
            localStorage.setItem("wordmatchComplete", "false");
        }
    }

    // If page is the maze mini-game set the session variable if null and set first position on maze
    if(currentUrl.indexOf("maze") != -1){
        if(localStorage.getItem("mazeComplete") === null){
            localStorage.setItem("mazeComplete", "false");
        }
        currentPosition = 0;
        $("tr:first-child td:first-child").addClass("mazeCurrentEast");
        $("tr:last-child td:last-child").addClass("mazeFinish");
    }

    // If page is the memory mini-game set the session variable if null, shuffle the portraits array, and place them on screen
    if(currentUrl.indexOf("memory") != -1){
        if(localStorage.getItem("memoryComplete") === null){
            localStorage.setItem("memoryComplete", "false");
        }
        var memory_answers = $(".portraitsArray").text().split(",");
        shuffledAnswers = shuffleArray(memory_answers);
        for (var i = 0; i < shuffledAnswers.length; i++) {
            childNumber = i + 1;
            $(".minigame-memory div:nth-child(" + childNumber + ")").children().attr("src", shuffledAnswers[i]);
        }
    }

    // On input check if name field is valid
    $("#contact-name").on("input", function (event) {
        var nameInput = $("#contact-name").val();
        if (checkValidName(nameInput)) {
            $("#contact-name").parent().next().text("");
            $("#contact-name").css("border-color", "");
        } else {
            $("#contact-name").parent().next().text("Please enter a valid name");
            $("#contact-name").css("border-color", "red");
        }
    });

    // On input check if email field is valid
    $("#contact-email").on("input", function (event) {
        var emailInput = $("#contact-email").val();
        if (checkValidEmail(emailInput)) {
            $("#contact-email").parent().next().text("");
            $("#contact-email").css("border-color", "");
        } else {
            $("#contact-email").parent().next().text("Please enter a valid email");
            $("#contact-email").css("border-color", "red");
        }
    });

    // On input check if feedback field is not empty
    $("#contact-body").on("input", function (event) {
        var feedbackInput = $("#contact-body").val();
        if (checkForFeedback(feedbackInput)) {
            $("#contact-body").parent().next().text("");
            $("#contact-body").css("border-color", "");
        } else {
            $("#contact-body").parent().next().text("Please enter a some feedback for the website");
            $("#contact-body").css("border-color", "red");
        }
    });

});

// Function to check if name field is valid
function checkValidName(name){
    var nameValidRegEx = /^([a-zA-Z-\s])+$/;
    if(nameValidRegEx.test(name)){
        return true;
    } else {
        return false;
    }
}

// Function to check if email field is valid
function checkValidEmail(email){
    var emailValidRegEx = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if(emailValidRegEx.test(email)){
        return true;
    } else {
        return false;
    }
}

// Function to check if feedback field is not empty
function checkForFeedback(feedback){
    if(feedback.length > 1){
        return true;
    } else {
        return false;
    }
}

// Check if the user has all train items, reveal the stop button if they have and display end dialogue from guide
function checkIfEndGameConditionsMet(){
    if(localStorage.getItem('bolt') === 'true' && localStorage.getItem('pipe') === 'true' && localStorage.getItem("cog") === 'true'){
        $("#endgame").children().attr("src", "images/stopbuttonactive.png");
        $("#endgame").children().addClass("endGameComplete");
        guideDialogue("gameComplete");
        return true;
    } else{
        return false;
    }
}

// Check that fields are valid when submitting form
$("#contact-submit").click(function (event){
    event.preventDefault();

    var nameInput = $("#contact-name").val();
    var emailInput = $("#contact-email").val();
    var feedbackInput = $("#contact-body").val();

    if(checkValidName(nameInput) && checkValidEmail(emailInput) && checkForFeedback(feedbackInput)){
        $(".navigationMessage").text("");
        $("#contact-form").submit();
    } else {
        $(".navigationMessage").text("All fields must be valid before submitting");
    }
});

// Erase data button
$("#eraseDataButton").click(function (event) {
    localStorage.clear();
    window.location.replace("delete-data.php");
    console.log("Data cleared");
});

// Start click on button events
$("#aboutButton").click(function (event) {
    window.location.replace("about.php");
});

$("#contactButton").click(function (event) {
    window.location.replace("contact.php");
});

$("#referencesButton").click(function (event) {
    window.location.replace("references.php");
});

$("#backToHomeButton").click(function (event) {
    window.location.replace("index.php");
});

$(".yearbuttons:contains('1901')").click(function (event) {
    window.location.replace("year-1901.php");
});

$(".yearbuttons:contains('1914')").click(function (event) {
    window.location.replace("year-1914.php");
});

$(".yearbuttons:contains('1916')").click(function (event) {
    window.location.replace("year-1916.php");
});

$("#returnTo1901").click(function (event) {
    window.location.replace("year-1901.php");
});

$("#returnTo1914").click(function (event) {
    window.location.replace("year-1914.php");
});

$("#returnTo1916").click(function (event) {
    window.location.replace("year-1916.php");
});

$("header div").click(function (event) {
    window.location.replace("index.php");
});

$("#translateButton").click(function (event) {
    if(!$("#translateButton").hasClass("disabled")){
        exitmodal('Indigenous');
        redirectWithTimeParameter("minigame-wordmatch.php");
    }
});

$("#maze_minigame").click(function (event) {
    if(!$("#maze_minigame").hasClass("disabled")){
        exitmodal('Musician');
        redirectWithTimeParameter("minigame-maze.php");
    }
});

$("#memory_minigame").click(function (event) {
    if(!$("#memory_minigame").hasClass("disabled")){
        exitmodal('Photographer');
        redirectWithTimeParameter("minigame-memory.php");
    }
});

$("#startExploringButton").click(function (event) {
    $("body").addClass('loading');
    window.location.replace("year-1901.php");
});

$("#newWords").click(function (event) {
    redirectWithTimeParameter("minigame-wordmatch.php");
});

$("#newPhotos").click(function (event) {
    redirectWithTimeParameter("minigame-memory.php");
});

$("#newMaze").click(function (event) {
    localStorage.setItem("currentMazeComplete", "false");
    currentPosition = 0;
    redirectWithTimeParameter("minigame-maze.php");
});

$("#deletedReturnButton").click(function (event) {
    window.location.replace("index.php");
});

$("#endgame").click(function (event) {
    if(checkIfEndGameConditionsMet()){
        window.location.replace("congratulations.php");
    } else{
        guideDialogue("end");
    }
});

// end click on button events

// start click on objects events for guide dialogue
$(".train-image").click(function (event) {
    guideDialogue("object", "train");
});

$("#cog").click(function (event) {
    guideDialogue("object", "cog");
});

$("#patron").click(function (event) {
    guideDialogue("object", "patron");
});

$("#pipe").click(function (event) {
    guideDialogue("object", "pipe");
});

$("#learningMap").click(function (event) {
    guideDialogue("object", "learningMap");
});

$("#learningPaperpile").click(function (event) {
    guideDialogue("object", "learningPaperpile");
});

$("#bolt").click(function (event) {
    guideDialogue("object", "bolt");
});

$("#camera").click(function (event) {
    guideDialogue("object", "camera");
});

$("#guitar").click(function (event) {
    guideDialogue("object", "guitar");
});

$("#boat").click(function (event) {
    guideDialogue("object", "boat");
});

$("#dog").click(function (event) {
    guideDialogue("object", "dog");
});

$("#bench").click(function (event) {
    guideDialogue("object", "bench");
});

$("#pigeon").click(function (event) {
    guideDialogue("object", "pigeon");
});

$("#presenter").click(function (event) {
    guideDialogue("object", "presenter");
});

$("#guideCongratsPage").click(function (event) {
    guideDialogue("congrats", "guideCongratsSpeechBubble");
});

$("#indigenousCongratsPage").click(function (event) {
    guideDialogue("congrats", "indigenousCongratsSpeechBubble");
});

$("#photographerCongratsPage").click(function (event) {
    guideDialogue("congrats", "photographerCongratsSpeechBubble");
});

$("#musicianCongratsPage").click(function (event) {
    guideDialogue("congrats", "musicianCongratsSpeechBubble");
});


// end click on objects events for guide dialogue

// If click on language book, remove the item, show it in dashboard, and activate mini-game button
$("#languageBook").click(function (event) {
    localStorage.setItem("languageBook", 'true');
    guideDialogue();
    $("#learningLanguageBook").parent().show();
    $("#languageBook").hide();
    $("#translateButton").removeClass("disabled");
    $(".hint").text("");
});

// If click on map, remove the item, show it in dashboard, and activate mini-game button
$("#map").click(function (event) {
    localStorage.setItem("learningMap", 'true');
    guideDialogue();
    $("#learningMap").parent().show();
    $("#map").hide();
    $("#maze_minigame").removeClass("disabled");
    $(".hint").text("");
});

// If click on paper pile, remove the item, show it in dashboard, and activate mini-game button
$("#paperpile").click(function (event) {
    localStorage.setItem("paperpile", 'true');
    guideDialogue();
    $("#learningPaperpile").parent().show();
    $("#paperpile").hide();
    $("#memory_minigame").removeClass("disabled");
    $(".hint").text("");
});

// If click on guide run dialogue function to determine what he says
$("#guide").click(function (event){
    if(checkIfEndGameConditionsMet()){
        guideDialogue("gameComplete");
    } else {
        guideDialogue();
    }
});

// Click on the book in minigame then open language modal
$("#languageBookMinigamePage").click(function (event) {
    openmodal('Languages');
});


// Word Match: If user clicks on an indigenous word, remove previous highlight, highlight it, remember index
$(".indigenousWords li").click(function (event) {
    $(".indigenousWords li").css("color", "black");
    $(".indigenousWords li").css("background-color", "#88e751");
    var indigenousSelected = $(this);
    indigenousSelected.css("color", "white");
    indigenousSelected.css("background-color", "#3f7c2f");
    var indigenousSelectedText = indigenousSelected.text();
    indigenousSelectedIndex = indigenousSelected.index();

});

// Word Match: If user clicks on an english word, remove previous highlight, highlight it, remember index
$(".englishWords li").click(function (event) {
    $(".englishWords li").css("color", "black");
    $(".englishWords li").css("background-color", "#88e751");
    var englishSelected = $(this);
    englishSelected.css("color", "white");
    englishSelected.css("background-color", "#3f7c2f");
    englishSelectedText = englishSelected.text();
    englishSelectedIndex = englishSelected.index();
});

// Word Match: If user clicks on an any word, check if a word in each list is clicked, draw line between two words, remove highlight
$(".minigame-wordmatch ul li").click(function () {
    if (indigenousSelectedIndex >= 0 && englishSelectedIndex >= 0) {

        $(".indigenous" + indigenousSelectedIndex).remove();
        $(".english" + englishSelectedIndex).remove();

        positionY1 = 1.5;
        positionX1 = 0;
        positionIncrementY = 4.5;
        positionIncrementX = 8;
        positionOffsetY1 = indigenousSelectedIndex;
        positionOffsetY2 = englishSelectedIndex;
        var htmlSVG = '<svg class="indigenous' + indigenousSelectedIndex + ' english' + englishSelectedIndex + '" height="20em" width="8em"><line x1="' + positionX1 + 'em" y1="' + (positionY1 + positionOffsetY1 * positionIncrementY) + 'em" x2="' +
            (positionX1 + positionIncrementX) + 'em" y2="' + (positionY1 + positionOffsetY2 * positionIncrementY) +
            'em" /></svg>';
        $(".indigenousWords").prepend(htmlSVG);

        englishChildNumber = (englishSelectedIndex + 1);
        indigenousChildNumber = (indigenousSelectedIndex + 1);
        setTimeout(function () {
            $('.englishWords li:nth-child(' + englishChildNumber + ')').css("color", "black");
            $('.englishWords li:nth-child(' + englishChildNumber + ')').css("background-color", "#88e751");

            $('.indigenousWords li:nth-child(' + indigenousChildNumber + ')').css("color", "black");
            $('.indigenousWords li:nth-child(' + indigenousChildNumber + ')').css("background-color", "#88e751");
        }, 500);

        // Check if the english word has already been selected, if so remove from array
        var indexToDelete = currentSelections.indexOf(englishSelectedIndex);
        if (indexToDelete !== -1) {
            currentSelections[indexToDelete] = false;
        }

        // Overwrite the array position with the english word text
        currentSelections[indigenousSelectedIndex] = englishSelectedText;

        indigenousSelectedIndex = -1;
        englishSelectedIndex = -1;
        englishSelectedText = "";
    }
});

//  Word Match: If user clicks check answer button, check answer array against selected answer array, change colour of lines
$("#checkAnswers").click(function (event) {
    var wordmatch_answers = $(".answers").text().split(",");
    answerCorrectCount = 0
    optionCount = wordmatch_answers.length;
    for (var i = 0; i < optionCount; i++) {
        if (currentSelections[i] == wordmatch_answers[i]) {
            $(".indigenous" + i).css("stroke", "green");
            answerCorrectCount++;
        } else {
            $(".indigenous" + i).css("stroke", "red")
        }
    }

    // Display feedback to the user depending on how they went
    $(".answerCorrectNumber").text(answerCorrectCount + " out of " + optionCount);
    if (answerCorrectCount >= optionCount) {
        $(".minigameFeedback").html("Well done! You got them all correct!<br>Click back to return to the year page.");
        $(".minigameFeedback").removeClass("incorrect");
        $(".minigameFeedback").addClass("correct");
        localStorage.setItem("wordmatchComplete", true);
    } else if (answerCorrectCount >= optionCount - 2) {
        $(".minigameFeedback").text("You were so close! Please try again!");
        $(".minigameFeedback").removeClass("correct");
        $(".minigameFeedback").addClass("incorrect");
    } else {
        $(".minigameFeedback").text("Bad luck! Please try again!");
        $(".minigameFeedback").removeClass("correct");
        $(".minigameFeedback").addClass("incorrect");
    }

});

// If user clicks reset button, remove lines, reset variables, and remove feedback text
$("#resetWords").click(function (event) {
    $("svg").remove();

    currentSelections = [false, false, false, false, false]
    indigenousSelectedIndex = -1;
    englishSelectedIndex = -1;
    englishSelectedText = "";

    $(".minigameFeedback").text("");
    $(".answerCorrectNumber").text("");
});

// If user clicks indigenous family, check if mini-game completed, display appropriate modal. If completed, show cog
$("#indigenous").click(function (event) {
    if(localStorage.getItem("wordmatchComplete") == "true"){
        openmodal('Translated');
        localStorage.setItem("cog", true);
        $("#cog").attr("src", "images/cog.png");
        checkIfEndGameConditionsMet();
    }
    else {
        localStorage.setItem("indigenousViewed", "true");
        openmodal('Indigenous');
    }
});

// If user clicks musician, check if mini-game completed, display appropriate modal. If completed, show pipe
$("#musician").click(function (event) {
    if(localStorage.getItem("mazeComplete") == "true"){
        openmodal('MusicianComplete');
        localStorage.setItem("pipe", true);
        $("#pipe").attr("src", "images/pipe.png");
        checkIfEndGameConditionsMet();
    }
    else {
        localStorage.setItem("musicianViewed", "true");
        openmodal('Musician');
    }
});

// If user clicks photographer, check if mini-game completed, display appropriate modal. If completed, show bolt
$("#photographer").click(function (event) {
    if(localStorage.getItem("memoryComplete") == "true"){
        openmodal('PhotographerComplete');
        localStorage.setItem("bolt", true);
        $("#bolt").attr("src", "images/bolt.png");
        checkIfEndGameConditionsMet();
    }
    else {
        localStorage.setItem("photographerViewed", "true");
        openmodal('Photographer');
    }
});


// Open modal
function openmodal(modal) {
    document.getElementById('modal' + modal).style.display = "block";
    guideDialogue("modal", modal);
}

// Close modal
function exitmodal(modal) {
    document.getElementById('modal' + modal).style.display = "none";
}


// Add a time parameter at the end to fix randomise caching issue (as server caches the code causing random numbers to not work)
function redirectWithTimeParameter(url) {
    currentDate = Date.now();
    url = url + "?time=" + currentDate;
    window.location.replace(url);
}

// Display diaglogue above the guides head
function guideDialogue(type, element){
    if(type == "congrats"){
        var elementName = element;
    }
    else {
        var elementName = "guideSpeechBubble";
    }

    // change guide image to him speaking
    if(elementName == "guideSpeechBubble"){
        $("#guide img").attr("src", "images/guideanimated.png");
    } else if (elementName == "guideCongratsSpeechBubble"){
        $("#guideCongratsPage img").attr("src", "images/guideanimated.png");
    }


    $("#" + elementName).show();

    // A timer that removes the speechbubble after 8 seconds. Timer resets each time the function is called
    if(type != "congrats"){
        if (timer) {
            clearTimeout(timer);
            timer = null;
        }
    }
    timer = setTimeout(function(){
        $("#" + elementName).hide();
        if(elementName == "guideSpeechBubble"){
            $("#guide img").attr("src", "images/guide.png");
        } else if (elementName == "guideCongratsSpeechBubble"){
            $("#guideCongratsPage img").attr("src", "images/guide.png");
        }
    },8000);

    currentUrl = window.location.href;



    // Guide dialogue: Game complete
    if(type == "gameComplete"){
        $("#guideSpeechBubble p").html("We have all the parts we need! Quick stop the train by clicking the <strong>emergency brakes button</strong>!");
    }

    // Guide dialogue: Clicking on train parts
    else if(element == "cog") {
        if(localStorage.getItem('cog') === 'true'){
            $("#guideSpeechBubble p").html("We can definitely use this cog to fix the train!");
        } else {
            $("#guideSpeechBubble p").html("This is one of the parts we need to find to fix the train.");
        }
    } else if (element == "pipe") {
        if(localStorage.getItem('pipe') === 'true'){
            $("#guideSpeechBubble p").html("We can definitely use this lead pipe to fix the train!");
        } else {
            $("#guideSpeechBubble p").html("This is one of the parts we need to find to fix the train.");
        }
    } else if (element == "bolt") {
        if(localStorage.getItem('bolt') === 'true'){
            $("#guideSpeechBubble p").html("We can definitely use this bolt to fix the train!");
        } else {
            $("#guideSpeechBubble p").html("This is one of the parts we need to find to fix the train.");
        }

    // Guide dialogue: 1901
    } else if(currentUrl.indexOf("1901") != -1){
        if(type == "modal"){
            if (element == "Newspaper"){
                $("#guideSpeechBubble p").html("Wow this is a really old newspaper!");
            } else if (element == "Indigenous"){
                if(localStorage.getItem("languageBook") === 'true'){
                    $("#guideSpeechBubble p").html("We have the language book and are ready to <strong>translate</strong> their language!");
                } else {
                    $("#guideSpeechBubble p").html("We might need to look around here for something to translate the indigenous language.");
                }
            } else if (element == "Translated"){
                $("#guideSpeechBubble p").html("They have given us a <strong>cog</strong> that we can use in the train brake system!");
            } else if (element == "Languages"){
                $("#guideSpeechBubble p").html("Here are all the translations! Study carefully and then let's try to translate their language.");
            }
        } else if (type == "end") {
            $("#guideSpeechBubble p").html("The brakes won't work yet. Keep finding <strong>brake parts</strong> and when that light goes green we can stop the train!");
        } else if (element == "train") {
            $("#guideSpeechBubble p").html("Yep there goes the train... I hope we can find the <strong>brake parts</strong> needed to stop it soon.");
        } else if (element == "boat") {
            $("#guideSpeechBubble p").html("At least none of our time-travelling boats are out of control.");
        } else if (element == "dog") {
            $("#guideSpeechBubble p").html("Oh it's a cute little doggy!");
        } else if(localStorage.getItem("cog") == "true"){
            $("#guideSpeechBubble p").html("Looks like we have everything we need from here. Let's try <strong>another location</strong>!");
        } else if (localStorage.getItem("wordmatchComplete") == "true"){
            $("#guideSpeechBubble p").html("We should talk to the <strong>indigenous family</strong> now that we know how to speak the language!");
        } else if (type == "initial"){
            $("#guideSpeechBubble p").html("Looks like we are in the year 1901!<br>Perhaps the <strong>locals</strong> might have seen a train part?");
        } else if (localStorage.getItem("languageBook") === 'true'){
            $("#guideSpeechBubble p").html("We have the language book and are ready to <strong>translate</strong> their language!");
        } else if (localStorage.getItem("indigenousViewed") === "true"){
            $("#guideSpeechBubble p").html("We might need to <strong>look around</strong> here for something to translate the indigenous language.");
        } else {
            $("#guideSpeechBubble p").html("Looks like we are in the year 1901!<br>Perhaps the <strong>locals</strong> might have seen a train part?");
        }
    }

    // Guide dialogue: 1914
    else if(currentUrl.indexOf("1914") != -1){
        if(type == "modal"){
            if (element == "Newspaper"){
                $("#guideSpeechBubble p").html("Wow this is a really old newspaper!");
            } else if (element == "Musician"){
                if(localStorage.getItem("learningMap") === 'true'){
                    $("#guideSpeechBubble p").html("We have the map needed to go backstage and find the music sheets. Let's go!");
                } else {
                    $("#guideSpeechBubble p").html("We might need to look around here for <strong>a map</strong> of the backstage area.");
                }
            } else if (element == "MusicianComplete"){
                $("#guideSpeechBubble p").html("The musician gave us a <strong>lead pipe</strong>. This is exactly what we needed for the train brake system!");
            }
        } else if (type == "end") {
            $("#guideSpeechBubble p").html("The brakes won't work yet. Keep finding <strong>brake parts</strong> and when that light goes green we can stop the train!");
        } else if (element == "train") {
            $("#guideSpeechBubble p").html("What is the train doing in here? I hope we can find the <strong>brake parts</strong> needed to stop it soon.");
        } else if (element == "presenter") {
            $("#guideSpeechBubble p").html("The presenter looks a bit nervous... I hope everything is going alright with the show.");
        } else if (element == "patron") {
            $("#guideSpeechBubble p").html("Looks like people are getting ready to watch the show.");
        } else if (element == "learningMap") {
            $("#guideSpeechBubble p").html("We can use this map to find the musician's sheet music backstage. Let's talk to him.");
        } else if (element == "guitar") {
            $("#guideSpeechBubble p").html("Looks like a spare guitar for the performance.");
        } else if(localStorage.getItem("pipe") == "true"){
            $("#guideSpeechBubble p").html("Looks like we have everything we need from here. Let's try <strong>another location</strong>!");
        } else if (localStorage.getItem("mazeComplete") == "true"){
            $("#guideSpeechBubble p").html("We should talk to the <strong>musician</strong> and give him his sheet music.");
        } else if (type == "initial"){
            $("#guideSpeechBubble p").html("Oh a theatre? Looks like we are in the year 1914!<br>I wonder if the <strong>musician</strong> is ready for a performance?");
        } else if (localStorage.getItem("learningMap") === 'true'){
            $("#guideSpeechBubble p").html("We have the map of the backstage area. Let's talk to the <strong>musician</strong> and find his sheet music.");
        } else if (localStorage.getItem("musicianViewed") === "true") {
            $("#guideSpeechBubble p").html("We might need to look around here for <strong>a map</strong> of the backstage area.");
        } else {
            $("#guideSpeechBubble p").html("Oh a theatre? Looks like we are in the year 1914!<br>I wonder if the <strong>musician</strong> is ready for a performance?");
        }
    }

    // Guide dialogue: 1916
    else if(currentUrl.indexOf("1916") != -1){
        if(type == "modal"){
            if (element == "Newspaper"){
                $("#guideSpeechBubble p").html("Wow this is a really old newspaper!");
            } else if (element == "Photographer"){
                if(localStorage.getItem("paperpile") === 'true'){
                    $("#guideSpeechBubble p").html("We have all the photographs. Let's sort them out!");
                } else {
                    $("#guideSpeechBubble p").html("Let's find the photographs that the photographer <strong>dropped on the ground</strong>");
                }
            } else if (element == "PhotographerComplete"){
                $("#guideSpeechBubble p").html("The photographer gave us a <strong> bolt</strong>. This is exactly the size we needed for the train brake system!");
            }
        } else if (type == "end") {
            $("#guideSpeechBubble p").html("The brakes won't work yet. Keep finding <strong>brake parts</strong> and when that light goes green we can stop the train!");
        } else if (element == "train") {
            $("#guideSpeechBubble p").html("The train is flying??? I hope we can find the <strong>brake parts</strong> needed to stop it soon.");

        } else if (element == "camera") {
            $("#guideSpeechBubble p").html("That is a very old looking camera. In the future we can take photos with our phones.");
        } else if (element == "learningPaperpile") {
            $("#guideSpeechBubble p").html("We have all the photographs. Let's talk to the <strong>photographer</strong> and help him sort them out!");
        } else if (element == "dog") {
            $("#guideSpeechBubble p").html("I swear that dog looks identical to the one from the 1901 location...");
        } else if (element == "pigeon") {
            $("#guideSpeechBubble p").html("Sometimes I wish I could fly like a bird.");
        } else if (element == "bench") {
            $("#guideSpeechBubble p").html("Doesn't look very comfortable...");
        } else if(localStorage.getItem("bolt") == "true"){
            $("#guideSpeechBubble p").html("Looks like we have everything we need from here. Let's try <strong>another location</strong>!");
        } else if (localStorage.getItem("memoryComplete") == "true"){
            $("#guideSpeechBubble p").html("We should talk to the <strong>photographer</strong> and give him back the sorted photographs.");
        } else if (type == "initial"){
            $("#guideSpeechBubble p").html("Ah 1916.. I think we are somewhere in Brisbane.<br>Is that a <strong>photographer</strong> I see over there?");
        } else if (localStorage.getItem("paperpile") === 'true'){
            $("#guideSpeechBubble p").html("We have all the photographs. Let's talk to the <strong>photographer</strong> and help him sort them out!");
        } else if(localStorage.getItem("photographerViewed") === "true"){
            $("#guideSpeechBubble p").html("We might need to look around here for <strong>the pile of photographs</strong> that the photographer dropped.");
        } else {
            $("#guideSpeechBubble p").html("Ah 1916.. I think we are somewhere in Brisbane.<br>Is that a <strong>photographer</strong> I see over there?");
        }
    }
}

// If a key is pressed and user is on the maze minigame, check if the key was up, down, left, right and run the appropriate function to move the cursor in the maze
$(document).keydown(function(event) {
    if(currentUrl.indexOf("maze") != -1){
        if(localStorage.getItem("mazeComplete") === null){
            localStorage.setItem("mazeComplete", "false");
        }
        if(localStorage.getItem("currentMazeComplete") === null){
            localStorage.setItem("currentMazeComplete", "false");
        }

        if(localStorage.getItem("currentMazeComplete") == "false"){
            if(event.which == 37) {event.preventDefault(); mazeMove("left");}
            if(event.which == 38) {event.preventDefault(); mazeMove("up");}
            if(event.which == 39) {event.preventDefault(); mazeMove("right");}
            if(event.which == 40) {event.preventDefault(); mazeMove("down");}
        }
    }
});

// Move the cursor in the maze depending on direction and whether it is possible to move that direction
function mazeMove(direction){

    // Get the dimensions of the maze
    mazeCellAmount = $("#mazeTable td").length;
    mazeDimension = Math.sqrt(mazeCellAmount);

    // If direction is right and possible add the appropriate classes
    if(direction == "right"){
        if($("#mazeTable td:eq(" + (currentPosition) + ")").hasClass("east")){
            previousPosition = parseInt(currentPosition);
            currentPosition = previousPosition + 1;
            $("#mazeTable td:eq(" + (previousPosition) + ")").removeClass("mazeCurrentWest");
            $("#mazeTable td:eq(" + (previousPosition) + ")").removeClass("mazeCurrentEast");
            $("#mazeTable td:eq(" + (previousPosition) + ")").removeClass("mazeCurrentNorth");
            $("#mazeTable td:eq(" + (previousPosition) + ")").removeClass("mazeCurrentSouth");
            $("#mazeTable td:eq(" + (currentPosition) + ")").addClass("mazeCurrentEast");
        }
    }

    // If direction is left and possible add the appropriate classes
    if(direction == "left"){
        if($("#mazeTable td:eq(" + (currentPosition) + ")").hasClass("west")){
            previousPosition = parseInt(currentPosition);
            currentPosition = previousPosition - 1;
            $("#mazeTable td:eq(" + (previousPosition) + ")").removeClass("mazeCurrentWest");
            $("#mazeTable td:eq(" + (previousPosition) + ")").removeClass("mazeCurrentEast");
            $("#mazeTable td:eq(" + (previousPosition) + ")").removeClass("mazeCurrentNorth");
            $("#mazeTable td:eq(" + (previousPosition) + ")").removeClass("mazeCurrentSouth");
            $("#mazeTable td:eq(" + (currentPosition) + ")").addClass("mazeCurrentWest");
        }
    }

    // If direction is up and possible add the appropriate classes
    if(direction == "up"){
        if($("#mazeTable td:eq(" + (currentPosition) + ")").hasClass("north")){
            previousPosition = parseInt(currentPosition);
            currentPosition = previousPosition - mazeDimension;
            $("#mazeTable td:eq(" + (previousPosition) + ")").removeClass("mazeCurrentWest");
            $("#mazeTable td:eq(" + (previousPosition) + ")").removeClass("mazeCurrentEast");
            $("#mazeTable td:eq(" + (previousPosition) + ")").removeClass("mazeCurrentNorth");
            $("#mazeTable td:eq(" + (previousPosition) + ")").removeClass("mazeCurrentSouth");
            $("#mazeTable td:eq(" + (currentPosition) + ")").addClass("mazeCurrentNorth");
        }
    }

    // If direction is down and possible add the appropriate classes
    if(direction == "down"){
        if($("#mazeTable td:eq(" + (currentPosition) + ")").hasClass("south")){
            previousPosition = parseInt(currentPosition);
            currentPosition = previousPosition + mazeDimension;
            $("#mazeTable td:eq(" + (previousPosition) + ")").removeClass("mazeCurrentWest");
            $("#mazeTable td:eq(" + (previousPosition) + ")").removeClass("mazeCurrentEast");
            $("#mazeTable td:eq(" + (previousPosition) + ")").removeClass("mazeCurrentNorth");
            $("#mazeTable td:eq(" + (previousPosition) + ")").removeClass("mazeCurrentSouth");
            $("#mazeTable td:eq(" + (currentPosition) + ")").addClass("mazeCurrentSouth");
        }
    }

    // If cursor is in the bottom right corner, maze has been completed
    if(currentPosition == (mazeCellAmount - 1)){
        mazeVictory();
    }
}

// If maze is complete display message and set local storage as complete
function mazeVictory(){
    localStorage.setItem("mazeComplete", "true");
    $(".minigameFeedback").html("Congratulations! You have completed the maze!<br>Click the back button to return to the year page.");
}

// If user clicks the save button on the train select screen, save the name entered
function saveData(){
    var userName = $('#userName').val();
    localStorage.setItem("userName", userName);
    $(".nameReplace").text(" " + userName);
}

// If user clicks a train from the train select screen, save the train selected
$(".train-select").click(function() {
    $(".train-select.trainSelected").removeClass("trainSelected");
    var trainColour = $(this).parent().attr("id");
    $(this).addClass("trainSelected");
    localStorage.setItem("trainColour", trainColour);
    setTrainColour(trainColour);
});

// Replace train image with the one selected
function setTrainColour(trainColour) {
    $(".train-image").css("content", "url('./images/" + trainColour + ".png')");
}

// If user clicks on an item in the memory game, reveal item and check if a match
$(".memoryItem").click(function(event){
    if(delay === false){

        // If the user clicks on an image that is not currently being displayed
        if($(this).children().hasClass("hidden")){

            // Display the image by changing classes
            $(this).children().removeClass("hidden");
            $(this).children().addClass("active");

            // If they have already clicked previously check to see if it matches any other active image
            if (memoryClickCount >= 1){
                memoryClickCount = 0;
                var elementsActiveList = [];
                $(".memoryItem .active").each(function(position, element) {
                    elementActive = $(this);
                    elementsActiveList.push(elementActive);
                });

                elementActiveOne = elementsActiveList[0];
                elementActiveTwo = elementsActiveList[1];

                // If the element does not match change them both back to hidden after 1 second
                if(elementActiveOne.attr("src").trim() != elementActiveTwo.attr("src").trim()){
                    delay = true;
                    setTimeout(function() {
                        elementActiveOne.addClass("hidden");
                        elementActiveTwo.addClass("hidden");
                        delay = false;
                    }, 1000);
                }

                // Remove the active classes of both
                elementActiveOne.removeClass("active");
                elementActiveTwo.removeClass("active");
            }

            // else if first click then add to click counter
            else {
                memoryClickCount++;
            }
        }

        // if there are no longer any hidden images then user has completed the game
        if(!$(this).parent().children().children().hasClass("hidden") && delay === false){
            $(".minigameFeedback").html("Congratulations! You matched all the photographs!<br>Click the back button to return to the year page.");
            localStorage.setItem("memoryComplete", "true");
        }
    }

});


/**
 * Shuffles array in place. ES6 version
 * Sourced from: https://stackoverflow.com/questions/6274339/how-can-i-shuffle-an-array
 * @param {Array} array items An array containing the items.
 */
function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}
