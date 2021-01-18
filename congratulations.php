<?php
    $pageTitle = "Congratulations";
    include('header.php');
?>

<div class="gameScreen" id="congratulations">
    <h2 id="congratulationsMessage">Congratulations! You have successfully stopped the train!</h2>
    <div class="train-image"></div>
    <img class="streamers" id="streamer1" src="images/streamers.png" alt="streamers" width="250px">
    <img class="streamers" id="streamer2" src="images/streamers.png" alt="streamers" width="250px">
    <img class="streamers" id="streamer3" src="images/streamers.png" alt="streamers" width="250px">
    <img class="streamers" id="streamer4" src="images/streamers.png" alt="streamers" width="250px">
    <img class="streamers" id="streamer5" src="images/streamers.png" alt="streamers" width="250px">


    <figure class="icon" id="musicianCongratsPage">
        <div class="speechBubble" id="musicianCongratsSpeechBubble"><p>The performance was a huge hit, thanks again for your help!</p></div>
        <img src="images/musician.png" alt="musician" width="120px">
    </figure>
    <figure class="icon" id="photographerCongratsPage">
        <div class="speechBubble" id="photographerCongratsSpeechBubble"><p>Thanks to you the WW1 families were so happy with the photographs!</p></div>
        <img src="images/businessman.png" alt="photographer" width="120px">
    </figure>
    <figure class="icon" id="indigenousCongratsPage">
        <div class="speechBubble" id="indigenousCongratsSpeechBubble"><p>It was nice to meet you!</p></div>
        <img src="images/indigenous.png" alt="indigenous family" width="400px">
    </figure>
    <figure class="icon" id="guideCongratsPage">
        <div class="speechBubble" id="guideCongratsSpeechBubble"><p>You did well kid!</p></div>
        <img src="images/guide.png" alt="guide" width="120px">
    </figure>
</div>

<div class="navigation-rowCentered">
    <a href="index.php" class="button">Back to Home</a>
</div>

<?php include('footer.php'); ?>
