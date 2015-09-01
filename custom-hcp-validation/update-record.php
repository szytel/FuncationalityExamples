<?php
    //This method uses passing the uuid and entityType in the query parameters
    $uuid = $_GET["uuid"];
    $entityType = $_GET["entityType"];
    require('config.example.php');
    
    //The entity.find call is used again to populate the form
    $entity_api_call = '/entity.find';
    $entityParams = array(
        'client_id' => JANRAIN_LOGIN_CLIENT_ID,
        'client_secret' => JANRAIN_LOGIN_CLIENT_SECRET,
        'type_name' => $entityType,
        'attributes' => '["uuid", "givenName", "familyName", "validationStatus"]',
        'filter' => "uuid='$uuid'"
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, JANRAIN_CAPTURE_URL.$entity_api_call);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($entityParams));
    $entity_response = json_decode(curl_exec($curl),true);
    $firstName = $entity_response["results"][0]["givenName"];
    $lastName = $entity_response["results"][0]["familyName"];
    $validationStatus = $entity_response["results"][0]["validationStatus"];
    curl_close($curl);
    
    //The entity.update form is submitted to update the user record
    if (isset($_POST['submit'])) {
        $api_call = '/entity.update';
        $params = array(
            'client_id' => JANRAIN_LOGIN_CLIENT_ID,
            'client_secret' => JANRAIN_LOGIN_CLIENT_SECRET,
            'type_name' => $_POST['entityType'],
            'uuid' => $_POST['uuid'],
            'value' => '{"givenName": "'.$_POST['firstName'].'", "familyName": "'.$_POST['lastName'].'", "validationStatus": "'.$_POST['validationStatus'].'"}'
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, JANRAIN_CAPTURE_URL.$api_call);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        $api_response = json_decode(curl_exec($curl));
        curl_close($curl);
        
        header("Location: index.php");
        die();
    }
?>

<html>
    <head>
        <title>Edit Profile</title>
    </head>
    <body>
        <h1>Edit User Record</h1>
        <hr>
            <!-- This form posts the user information to the user record-->
            <form method="post" action="update-record.php">
                <div><input type="text" name="firstName" placeholder="First Name" value="<?php echo $firstName ?>"></div>
                <div><input type="text" name="lastName" placeholder="Last Name" value="<?php echo $lastName ?>"></div>
                <div><input type="hidden" name="entityType" value="<?php echo $entityType ?>"></div>
                <div><input type="hidden" name="uuid" value="<?php echo $uuid ?>"></div>
                <div><select name="validationStatus">
                    <option value="<?php echo $validationStatus ?>"><?php echo $validationStatus ?></option>
                    <option value="valid">valid</option>
                    <option value="rejected">rejected</option>
                </select></div><hr>
                <input type="submit" name="submit" value="Save">
            </form>
            <!-- Gives response from entity.update call -->
             <?php
            if (isset($api_response)) {
                echo '<pre>';
                echo json_encode($api_response, JSON_PRETTY_PRINT);
                echo '</pre>';
            }
            ?>
    </body>
</html>
