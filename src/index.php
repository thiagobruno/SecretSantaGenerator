<?php
require_once('includes/config.php');
require_once('includes/database.php');
require_once('includes/functions.php');
require_once('includes/controllers.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Secret Santa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>

<body>
	<div class="heading">
		<h2 style="font-style: 'Arial';">Secret Santa Generator</h2>
	</div>

	<form method="post" action="index.php" class="input_form">
        <?php 
        // show errors
        if (isset($_GET['error'])): ?>
            <p style="text-align:center"><?php echo $_GET['error']; ?></p>
        <?php endif; ?>
		<input type="text" name="person" class="person_input" placeholder="Name" maxlength="100" autofocus>
        <input type="email" name="email" class="person_input" placeholder="Email" maxlength="100">
		<button type="submit" name="submit" id="add_btn" class="add_btn"><i class="fa-solid fa-user-plus"></i> Add Person</button>
	</form>

    <?php 
    // Get all the people
    $sslist = $conn->query("SELECT * FROM SecretSanta ORDER BY Id DESC");
    $rows = $sslist->fetch_all(MYSQLI_ASSOC);
    
    // show errors
    if (sizeof($rows) !== 0): ?>
        <table>
        <thead>
            <tr>
                <th style="width: 100%" colspan="2">People List</th>
                <th>&nbsp;</th>
            </tr>
        </thead>

        <tbody>
            <?php 
            $selectedBase = [];
            // List all the people
            foreach ($rows as $key => $value) {
                $isSelected = $value['Selected'] ? true : false;
                $class= $isSelected ? 'person_selected' : 'person_unselected';

                if ($isSelected) {
                    array_push($selectedBase, $value);
                }
                ?>
                <tr>
                    <td class="<?=$class?> toggle <?=$value['Selected'] ? 'selected' : 'unselected'; ?>" style="width:30px"> 
                        <a href="index.php?toggle_person=<?=$value['Id'] ?>">
                            <i class="fa-2xl <?=$value['Selected'] ? 'fa-solid fa-square-check' : 'fa-regular fa-square'; ?>"></i>
                        </a> 
                    </td>
                    <td class="<?=$class?>" style="width:100%"><span><?=$value['Person']; ?> (<?=$value['Email']; ?>)</span></td>
                    <td class="<?=$class?> delete"> 
                        <a href="index.php?del_person=<?=$value['Id'] ?>">
                            <i class="fa-solid fa-trash fa-lg"></i>
                        </a> 
                    </td>
                </tr>
                <?php 
            }
            ?>	
        </tbody>
        </table>
    
        <?php
        if (sizeof($selectedBase) > 0):
        ?>
        <table>
            <thead>
                <tr>
                    <th colspan="3">Shuffled List</th>
                </tr>
            </thead>

            <tbody>
                <?php
                    $sp = isset($_SESSION['shuffle_people']) ? $_SESSION['shuffle_people'] : [];
                    if (empty($sp) || isset($_GET['shuffle'])) {
                        $sp = shufflePeople();
                        $_SESSION['shuffle_people'] = $sp;

                        if (isset($_GET['shuffle'])) {
                            echo "<script>top.location.href='index.php'</script>";
                        }
                    }

                    if (sizeof($selectedBase) !== sizeof($sp)) {
                        echo "<script>top.location.href='index.php?success=1&shuffle=1'</script>";
                        die();
                    }
                    foreach($sp as $key => $value): ?>
                        <tr>
                            <td style="width:50%"><?=$value['from']?> (<?=$value['from_email']?>)</td>
                            <td style="width:50%" class="target" value="<?=$value['target']?> (<?=$value['target_email']?>)">*****</td>
                            <td style="text-align:right;padding-right:10px"><a href="javascript:void(0)" class="target_eye"><i class="fa-sharp fa-solid fa-eye-slash"></i></a></td>
                        </tr>
                    <?php
                    endforeach;
                ?>
            </tbody>
        </table>
        <div style="text-align: center;margin-bottom:30px">
            <a href="?download=1" target="action_iframe" class="add_btn"><i class="fa-solid fa-download"></i> Download List</a>
            <a href="javascript:void(0)" target="action_iframe" class="add_btn btn_send_list"><i class="fa-solid fa-paper-plane"></i> <span>Send List</span></a>
            <a href="?shuffle=1" class="add_btn"><i class="fa-solid fa-shuffle"></i> Shuffle</a>
            <a href="javascript:void(0)" class="add_btn toggle_eye_all"><i class="fa-solid fa-eye-slash"></i> <span>Display All</span></a>
        </div>
        <?php endif; ?>
    <?php endif; ?>
        
    <?php
    $sslist->free_result();
    $conn->close();
    ?>
    <iframe src="about:blank" name="action_iframe" class="action_iframe" style="width:1px;height:1px;display:none"></iframe>

    <script src="https://code.jquery.com/jquery-3.6.3.slim.min.js"></script>
    <script src="assets/js/scripts.js"></script>
</body>
</html>