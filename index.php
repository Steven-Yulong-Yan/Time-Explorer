<?php
$pageTitle = "Home";
include('header.php');
?>

<div class="gameScreen" id="home">
    <h1 id="mainHeading">Time Explorer <br>and the run-away train!</h1>
    <div class="train-image"></div>
</div>

<div id="spinner"></div>
<div id="loaderDiv"></div>

<div id="modalPlay" class="popup">
    <div class="content" id="howToPlay">
        <span onclick="exitmodal('Play')" class="exit">&times;</span>
        <h2>Welcome to Time Explorer!</h2>
        <p class="center">Hi<span class="nameReplace"></span>! My name is Tommy and I am the leader of Time Explorer.<p>
        <p class="center">I have brought you here because we need your help!<p>
        <img src="images/guide.png" alt="Tommy: the leader of the Time Explorer" width="150" />

        <p>One of our time-machine trains are out of control and is creating havoc through the early 20th century! We believe that it's brakes have been broken and therefore not able to stop.</p>
        <p>Please help us! We have detected that parts of the brakes have been scattered around 3 different locations. If you could retireve the parts you could stop the train!</p>
        <p>Quick looks like the train is coming past now, jump on and it will take you somewhere into Queensland's past</p>
        <p>Goodluck brave explorer!</p>
        <div class="navigation-rowCentered">
            <button class="button" id="startExploringButton">Start Exploring!</button>
        </div>
    </div>
</div>

<div id="modalSelectTrain" class="popup">
    <div class="content" id="trainSelect">
        <span onclick="exitmodal('SelectTrain')" class="exit">&times;</span>
        <h2>Time Explorer</h2>
        <h3>Enter your name:</h3>
        <div class="entryFieldDiv">
            <input id="userName" type="text" value=""/>
        </div>
        <h3>Select your train:</h3>
        <div id="redtrain">
            <input class="train-select" type="button" value="">
        </div>
        <div id="bluetrain">
            <input class="train-select" type="button" value="">
        </div>
        <div id="greentrain">
            <input class="train-select" type="button" value="">
        </div>
        <button type="button" id="trainSave" class="button" onclick="exitmodal('SelectTrain'); saveData()">Save</button>
  </div>
</div>

<div id="modalMore" class="popup">
    <div class="content" id="moreOptions">
        <span onclick="exitmodal('More')" class="exit">&times;</span>
        <h2>Other options</h2>
        <button class="button" id="aboutButton">About the website</button>
        <button class="button" id="contactButton">Contact us</button>
        <button class="button" id="referencesButton">References</button>
        <button class="button" id="eraseDataButton">Erase Data</button>
    </div>
</div>

<div class="navigation-rowCentered">
    <button class="button" id="trainSelect" onclick="openmodal('SelectTrain')">Train Select</button>
    <button class="button" onclick="openmodal('Play')">Start</button>
    <button class="button" onclick="openmodal('More')">Other options</button>
</div>

<?php include('footer.php'); ?>
