//Get token and UUID out of local storage when session is created
janrain.events.onCaptureSessionFound.addHandler(function(result){
    var token = localStorage.janrainCaptureToken,
        a = localStorage.janrainCaptureProfileData,
        b = JSON.parse(a);
    if (token){
        //Do something with token and/or UUID
        return [token, b.uuid];
    }
});

=================================================================
//Write out result object with a successful login
janrain.events.onCaptureLoginSuccess.addHandler(function(result) {
	  console.log(result);
});
//Write out UUID with a successful login
janrain.events.onCaptureLoginSuccess.addHandler(function(result) {
	  console.log(result.userData.uuid);
});

=================================================================
//Check whether user has a session when the traditional reg screen is rendered
janrain.events.onCaptureRenderComplete.addHandler(function(result) {
    if (result.screen == "returnTraditional") {
        if (janrain.capture.ui.hasActiveSession() == true){
            console.log("User has a session");
        } 
        else if (janrain.capture.ui.hasActiveSession() == false){
            console.log("User does not have a session");
        }
    }
});

=================================================================
//Set screen to render and re-initialize the widget
//onclick should be put as an HTML attribute
onclick="editProfileRender()"
//Function defined anywhere
function editProfileRender(){
   janrain.settings.capture.screenToRender = 'editProfile';
   janrain.capture.ui.start();
   janrain.capture.ui.renderScreen('editProfile');
}

=================================================================

