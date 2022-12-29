<?php
require_once("./thirdparty/mailtrap-io.php");

$sent_emails = array();

function pickAnother($current) {
    global $selected;
    global $selectedBase;
    $copy = json_decode(json_encode($selected), true);
    $found = null;
    $try = 0;
    
    shuffle($copy);

    while($found === null) {
        shuffle($copy);
        if ($copy[0]['Person'] !== $current['Person']) {
            $found = $copy[0];
            \array_splice($copy, 0, 1);
            $selected = $copy;
        }

        if ($try > 50) {
            $found = "?";
        }

        $try++;
    }

    return $found;
}

function shufflePeople() {
    global $selected;
    global $selectedBase;
    $diff = false;
    $list = [];
    $try = 0;
    $check_last_target = "?";

    if ($selectedBase == null) {
        return $list;
    }

    if (sizeof($selectedBase) > 0) {
        $selected = $selectedBase;
        while($check_last_target == "?") {
            $tempList = [];
            foreach($selected as $key => $value){
                $pick = pickAnother($value);
                if (is_array($pick)) {
                    array_push($tempList, [
                        "from" => $value['Person'],
                        "from_email" => $value['Email'],
                        "target" => $pick['Person'],
                        "target_email" => $pick['Email'],
                    ]);
                } else {
                    array_push($tempList, [
                        "from" => $value['Person'],
                        "from_email" => $value['Email'],
                        "target" => '?',
                        "target_email" => '?',
                    ]);
                }
            }

            if (sizeof($tempList) > 0) {
                $check_last_target = $tempList[sizeof($tempList)-1]['target'];
                if ($check_last_target != "?") {
                    $list = $tempList;
                }
            }

            if ($try > 50) {
                $check_last_target = "-";
                $list = $tempList;
            }

            $try++;
        }
    }
    return $list;
}

function sendEmails(){
    global $mail_title;
    global $mail_from;
    global $phpmailer;
    $array = $_SESSION['shuffle_people'];
    $errors = 0;

    //For each user
    foreach($array as $giver){
        try {
            //Send em via PHPmailer method
            $email_body = "Hello {$giver['from']},
                <br/><br/>
                For Secret Santa this year you will be buying a present for <strong>{$giver['target']}</strong> (<a href='mailto:{$giver['target_email']}'>{$giver['target_email']}</a>)
                <br/><br/>
                Good luck and Merry Christmas,
                <br/><br/>
                Santa
                "; 

            //Recipients
            $phpmailer->setFrom($mail_from, $mail_title);
            $phpmailer->addAddress($giver['from_email'], $giver['from']); //Add a recipient

            //Content
            $phpmailer->isHTML(true); //Set email format to HTML
            $phpmailer->Subject = $mail_title;
            $phpmailer->Body    = $email_body;
            $phpmailer->AltBody = 'For Secret Santa this year you will be buying a present for...';

            $phpmailer->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            $errors++;
            echo "Message could not be sent. Mailer Error: {$phpmailer->ErrorInfo}";
        }
    }	

    if ($errors == 0) {
        echo "<script>alert('Message has been sent');window.parent.location.href='/index.php';</script>";
    } else {
        echo "<script>alert('The message has been sent, but some people have not received the message');window.parent.location.href='/index.php';</script>";
    }
}