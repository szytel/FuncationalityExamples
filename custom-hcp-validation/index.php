<?php
/*
This demo requires 2 API calls. The first one gets the user data. The second, updates the record.
Some simple HTML has been included to demonstrate how a form can interact with getting and updating
a user record
*/
require('config.example.php');

if (!empty($_POST['entityType'])) {
    //The entity.find call retrieves the user records whose verifiedStatus = pending
    //and only sends back the uuid, givenName and familyName. This can be modified
    $find_call = '/entity.find';
    $find_params = array(
        'client_id' => JANRAIN_LOGIN_CLIENT_ID,
        'client_secret' => JANRAIN_LOGIN_CLIENT_SECRET,
        'type_name' => $_POST['entityType'],
        'attributes' => '["uuid", "givenName", "familyName", "validationStatus"]',
        'filter' => "validationStatus='pending'"
    );
    //Make the POST, decode the response and save in variable called findResponse
    $findResponse = json_decode(apiCall($find_call, $find_params), true); 
}

?>
<html>
    <head>
        <title>HCP Validation Example</title>
        <script>
            function updateUser(uuid, entityType) {
                window.location.href = '/hcp-validation/update-record.php?uuid=' + uuid + "&entityType=" + entityType;

            }
        </script>
    </head>
    <body>
        <h1>HCP Validation Example</h1>
        <hr>  
            <p>
                Enter the entity type below
            </p>
            <br/>
            <form method="post" action="index.php">
                <div><input type="text" name="entityType" placeholder="Entity Type"></div>
                <br/>
                <input type="submit" value="Submit">
            </form>
            
            
            <?php
            if (isset($findResponse)) {
                foreach($findResponse['results'] as $attribute) {
                    $firstName = $attribute['givenName'];
                    $lastName = $attribute['familyName'];
                    $uuid = $attribute['uuid']; 
                    $validationStatus = $attribute['validationStatus']; 
                    $entityType = $_POST['entityType'];
                    echo $uuid." - ".$firstName." ".$lastName." : ".$validationStatus;
                    echo "<input onclick='updateUser(\"$uuid\",\"$entityType\")' type='submit' /><br/><hr>";
                }
            }
            ?>
            
    </body>
</html>

<?php 
//Generic function used to POST the API calls
function apiCall($api_call, $params) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, JANRAIN_CAPTURE_URL.$api_call);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));

        $api_response = curl_exec($curl);
        curl_close($curl);
    
        return $api_response;
    }
?>
