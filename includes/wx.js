// this function checks the server for new data
// and updates the timings on the page
function updateTimes() {
    $.get("ajax.php", { action: "updateTimes" }, function(data) {
        if(data != 0) { // empty db
            explode = data.split("|");
            uts = explode[0];
            if(load_uts == 0) { // the JS var hasn't been populated yet
                load_uts = uts;
            }
            if(uts != load_uts && load_uts != 0) { // there's been an update
                infotext = "old. Contacting update server...";
                $("#infotable").fadeTo(400, 0.5);
                $("#infostatus").html(infotext);
                $("#infobar").fadeIn(1000);
                setTimeout("populateTable()",3000); // wait 2 secs
            }
            time_ago = explode[1]; // create vars
            time_next = explode[2];
            desc_ago = explode[3];
            desc_next = explode[4];
            $("#timelast").html(time_ago); // populate the html elements
            $("#timenext").html(time_next);
            $("#ago").html(desc_ago);
            $("#next").html(desc_next);

            last_check_uts = getUTS(); // set the last check JS var
            secs_since_last = 0;
        }
    });
}

// updates the data table
// gets rid of the infobar if success
// displays an error on failure
function populateTable() {
    $.get("ajax.php", { action: "getDataString" }, function(data) {
        if(data != 0) { // make sure we got something
            explode = data.split("|");
            uts = explode[0]; // this is needed for AAG update
            $("#ajaxlight").html(explode[1]);
            $("#ajaxtemp").html(explode[2]);
            $("#ajaxtemp2").html(explode[3]);
            $("#ajaxpressure").html(explode[4]);
            $("#ajaxrain").html(explode[5]);
            $("#ajaxwind_spd").html(explode[6]);
            $("#ajaxwind_dir").html(explode[7]);
            $("#ajaxmoisture").html(explode[8]);
            $("#ajaxhumidity").html(explode[9]);
            $("#ajaxbatt").html(explode[10]);
            $("#ajaxserver").html(explode[11]);
            $("#temps").html(explode[12]);

            // if the temp sensors are/aren't working, change their cell colours
            if(explode[2] == "85.0" || explode[2] == "0.0") {
                $("#ajaxtemp").css("background-color", "#FF3D3D");
            } else {
                $("#ajaxtemp").css("background-color", "#CCFFCC");
            }
            
            if(explode[3] == "85.0" || explode[3] == "0.0") {
                $("#ajaxtemp2").css("background-color", "#FF3D3D");
            } else {
                $("#ajaxtemp2").css("background-color", "#CCFFCC");
            }

            // now update the at a glance images
            updateAAG(uts, explode[2]);
                
            load_uts = uts; // set the loaded uts value to the new one
            $("#infostatus").html("up to date.");
            $("#infotable").fadeTo(400, 1);
            $("#infobar").fadeOut(1500); // get rid of the infobar
            $("#infobar").hide();

            return true;
        } else {
            // let the user know the update failed
            $("#infostatus").html("out of date. The update failed.");
            return false;
        }
    });
}

function getLastUTS() {
    $.get("ajax.php", { action: "getLastUTS" }, function(data) {

    });
}

// returns the LOCAL UNIX TIMESTAMP
// don't trust the time to be correct
function getUTS() {
    var ts = Math.round(new Date().getTime() / 1000);
    return ts;
}

function sinceLast() {
    secs_since_last++;
    if(secs_since_last < 60) {
        if(secs_since_last == 1) {
            $("#sincelast").html(secs_since_last + " second");
        } else {
            $("#sincelast").html(secs_since_last + " seconds");
        }
    } else if(secs_since_last <= 3600) {
        if(Math.round(secs_since_last/60) == 1) {
            $("#sincelast").html(Math.round((secs_since_last)/60) + " minute");
        } else {
            $("#sincelast").html(Math.round((secs_since_last)/60) + " minutes");
        }
    } else {
        $("#sincelast").html("more than 60  minutes");
    }
}

// this function appends the UTS to the querystring for the images
// this is NOT required but it gets around browser caching
function updateAAG(uts, temp) {
    $("#aaglight").attr("src", "images/lightim.php?uts="+uts);
    $("#aagtemp").attr("src", "images/tempim.php?uts="+uts);
    $("#aagwind").attr("src", "images/windim.php?uts="+uts);
    $("#aagpressure").attr("src", "images/pr_img.php?uts="+uts);
}

// run when user clicks "turn auto off"
function autoOff(){
    clearInterval(auto_int);
    clearInterval(since_last);
    $("#sincelast").html("[?]");
    $("#sincelastwrapper").fadeOut(500);
    $("#autoopt").html("Auto Update Off - <a href='#' onClick='autoOn()'>Turn Auto Update On</a>");
    auto_status = 0;
    auto_status_force = 0;
}

// run when user clicks "turn auto on"
function autoOn() {
    //updateTimes();
    // 
    // update the difference as it hasnt been updated during off-time
    secs_since_last = getUTS() - last_check_uts;
    since_last = setInterval("sinceLast()",1000); // set the sincelast interval
    $("#sincelastwrapper").fadeIn(500); // show the sincelast text
    auto_int = setInterval("updateTimes()",30000);
    $("#autoopt").html("Auto Update On - <a href='#' onClick='autoOff()'>Turn Auto Update Off</a>");
    auto_status = 1;
    auto_status_force = 1;
}

// run by jquery on page load
function loadFn() {
    since_last = setInterval("sinceLast()",1000);
    auto_int = setInterval("updateTimes()",30000);
}

// puts the refresh img back to the static one and run update
function restoreRefImg() {
    updateTimes();
    $("#refreshimg").attr("src", "images/refresh.gif");
}

// animates the img, schedules img restore and ajax update
function forceRefresh() {
    $("#refreshimg").attr("src", "images/refresh-animated.gif");
    setTimeout("restoreRefImg()", 1300);
}
