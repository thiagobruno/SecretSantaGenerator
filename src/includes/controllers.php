<?php
/**
 * DELETE A PERSON
 */
if (isset($_GET['del_person'])) {
    $id = $_GET['del_person'];

    // Will delete the Person with the specific ID, this query will be prepared to avoid SQL injection.
    $sql = "DELETE FROM SecretSanta WHERE Id=?";

    // Will overwrite the variables and execute the query
    executeQuery($sql, [
        ['type' => "s", 'value' => $id],
    ]);

    // It inserts a javascript snippet to redirect the page
    echo "<script>top.location.href='index.php?success=1'</script>";
    die();
}

/**
 * TOGGLE A PERSON AS SELECTED OR NOT
 */
if (isset($_GET['toggle_person'])) {
    $id = $_GET['toggle_person'];

    // Update the table, this query will be prepared to avoid SQL injection.
    $sql = "UPDATE SecretSanta SET Selected=NOT Selected WHERE Id=?";
    
    // Will overwrite the variables and execute the query
    executeQuery($sql, [
        ['type' => "s", 'value' => $id],
    ]);

    // It inserts a javascript snippet to redirect the page
    echo "<script>top.location.href='index.php?success=1'</script>";
    die();
}

/**
 * INSERT A NEW PERSON
 */
if (isset($_POST['submit'])) {
    // Validate the name field
    if (empty($_POST['person'])) {
        $errors = "You must fill in the person name";
        echo "<script>top.location.href='index.php?success=0&error=$errors'</script>";

    // Validate the email field
    }else if (empty($_POST['email'])) {
        $errors = "You must fill in the person email";
        echo "<script>top.location.href='index.php?success=0&error=$errors'</script>";

    // Insert it on database
    }else{
        // Request the field values
        $person = $_POST['person'];
        $email = $_POST['email'];

        // Insert a new Person, this query will be prepared to avoid SQL injection.
        $sql = "INSERT INTO SecretSanta (Person, Email, Selected) VALUES (?, ?, ?)";

        // Will overwrite the variables and execute the query
        executeQuery($sql, [
            ['type' => "s", 'value' => $person],
            ['type' => "s", 'value' => $email],
            ['type' => "i", 'value' => 1],
        ]);

        // Destroy the temporary session to force shuffle again
        session_destroy();

        // It inserts a javascript snippet to redirect the page
        echo "<script>top.location.href='index.php?success=1'</script>";
        die();
    }
}	


/**
 * PREPARE A FILE TO DOWNLOAD
 */
if (isset($_GET['download'])) {
    // Remove the header already sent
    if (!headers_sent()) {
        header_remove();
    }

    // Prepare the file name
    $date_formated = $date->format('Y-m-d_H-i-s');
    $filename = "secret_santa_$date_formated.csv";
    
    // Create the file in the memory
    $f = fopen('php://memory', 'w');

    // Get all the shuffled people list from the temporary session
    $array = $_SESSION['shuffle_people'];
    if ($array !== null && sizeof($array) > 0) {
        foreach ($array as $line) {
            // Write each person in a line
            fputcsv($f, $line, ',');
        }
    }
    
    // reset the file pointer to the start of the file
    fseek($f, 0);
    // tell the browser it's going to be a csv file
    header('Content-Type: text/csv');
    // tell the browser we want to save it instead of displaying it
    header('Content-Disposition: attachment; filename="'.$filename.'";');
    // make php send the generated csv lines to the browser
    fpassthru($f);
    die();
}

/**
 * SEND AN EMAIL
 */
if (isset($_GET['send'])) {
    // Remove the header already sent
    if (!headers_sent()) {
        header_remove();
    }

    // Send an email to each person
    sendEmails();
    die();
}