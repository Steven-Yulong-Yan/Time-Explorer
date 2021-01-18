<?php

$pageTitle = "Contact";
include('header.php');

// Check whether the form has returned success or failure
if (isset($_GET['success'])) {
    if ($_GET['success'] == 1) {
        $feedback = 'Thank you for your feedback';	
    } else {
        $feedback = 'An error occured attempting to send your message';
    }
}

?>

<h1>Contact form</h1>
<section id="contactPage">

    <form id="contact-form" method="post" action="emailSend.php">
            <div>
                <label for="contact-name">Name:</label>
                <input type="text" id="contact-name" name="contact-name" placeholder="Enter your name">
            </div>
            <p class="errorMessage"></p>
            <div>
                <label for="contact-email">Email:</label>
                <input type="email" id="contact-email" name="contact-email" placeholder="Enter your email">
            </div>
            <p class="errorMessage"></p>
            <div>
                <label for="contact-body">Feedback:</label>
                <textarea id="contact-body" name="contact-body" rows="10" placeholder="Enter your feedback here ..."></textarea>
            </div>
            <p class="errorMessage"></p>
            <p class="navigationMessage"><?php echo $feedback; ?></p>
            <div class="navigation-rowCentered"> 
                <button type="submit" class="button" id="contact-submit" name="contact-submit" tabindex="8" >Submit Feedback</button>
                <button type="button" class="button" id="backToHomeButton">Back to home</button>
            </div>
    </form>

</section>
            
<?php include('footer.php'); ?>