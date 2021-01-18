<?php

    $contactName = validateData($_POST["contact-name"]);
    $contactEmail = validateData($_POST["contact-email"]);
    $contactFeedback = validateData($_POST["contact-body"]);

    $to = "brian.stewart@uqconnect.edu.au";
    $subject = "Contact form from Time Explorer";

    $message = "You have recieved a new contact form message: \n
    \n
    Name: $contactName \n
    Email: $contactEmail \n
    Message: $contactFeedback";

    // If email was sent successfully/failed redirect to contact page with success/failure
    if(mail($to,$subject,$message)){
        header('Location: contact.php?success=1');
    } else {
        header('Location: contact.php?success=0');
    }

    // Strip out any start or end spaces or malicious code
    function validateData($data) {
        $data = trim($data);
        $data = strip_tags($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

?>
