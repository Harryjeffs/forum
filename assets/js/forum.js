/**
 * Created by JEFFH14 on 05/12/2016.
 */
$(document).ready(function () {
    /*
    *
    * VARIABLE THAT HANDLES THE REFRESH RATE OF DYNAMIC FUNCTIONS, EG; NOTIFICATIONS.
    *
    */
    var refreshTime = 45000;
    /*
     *
     *VARIABLE THAT DETERMINES WHETHER THE QUOTING FUNCTION IS ENABLED.
     *
     */
    var quoteDisabled = true;
    /*
     *
     *INITIATE SOME BOOTSTRAP FUNCTIONS
     *
     */
    $('[data-toggle="popover"]').popover({html:true});
    $('[data-toggle="tooltip"]').tooltip();

    /*
     *
     *FUNCTION THAT ALERTS THE USER IF THEY HAVE ALREADY LIKED A CERTAIN POST
     *
     */
    $(".liked-post").click(function() {
        new PNotify({
            title: 'Whoops',
            text: 'You have already liked this post!',
            type: 'error'
        });
    });
    /*
    *
    * FUNCTION THAT UPDATES PAGINATION WHEN CLICKED
    *
    */
    $(document).on( "click", ".pagination a", function (e){
        var page = $(this).data('page');

        fileName = location.pathname.split("/");

        if (fileName[2] == "thread"){
            pagination(e, this, page ,"thread-view", thread_id);
        }
        if(fileName[2] == "category"){
            pagination(e, this, page, "view_category", sub_cat_id);
        }
    });
    /*
     *
     *FUNCTION THAT ALLOWS A USER TO EDIT THEIR BIO
     *
     */
    $(document).on('blur', "#content", function(){
        var content = $(this).text();

        $.ajax({
            type: "POST",
            url: base_url+"/inc/functions/user_update_bio.php",
            data: {content:content},
            dataType: "json",
            success: function(data){
                    new PNotify({
                        title: data.title,
                        text: data.text,
                        type: data.type
                    });
            }
        });
    });
    /*
     *
     *FUNCTION THAT IMITATES THE WYSIBB EDITOR THAT ALLOWS THE USER TO EDIT THEIR POSTS WITH BBCODE
     *
     */
    function wysibbEditor() {
        var wbbOpt = {
            buttons: "bold,italic,underline,strike,|,img,imgleft,imgright,video,link,smileList,smilebox,|,bullist,numlist,|,fontcolor,|,justifyleft,justifycenter,justifyright,|,removeFormat"
        }
        $("#threadReplyContent").wysibb(wbbOpt);
    }
    /*
     *
     *FUNCTION THAT CONTAINS A JQUERY PLUGIN FOR PLAYING SOUNDS
     *
     */
    (function($){

        $.extend({
            playSound: function(){
                return $(
                    '<audio autoplay="autoplay" style="display:none;">'
                    + '<source src="' + arguments[0] + '.mp3" />'
                    + '<source src="' + arguments[0] + '.ogg" />'
                    + '<embed src="' + arguments[0] + '.mp3" hidden="true" autostart="true" loop="false" class="playSound" />'
                    + '</audio>'
                ).appendTo('body');
            }
        });

    })(jQuery);
    /*
    *
    *FUNCTION FOR DELETING A FORUM POST WITHIN A THREAD
    *
    */
    $(document).on('click', '.delete-post', function() {
        var postid = $(this).data("postid");

        $.ajax({
            type: "POST",
            url: base_url+"/inc/functions/admin/delete_post.php",
            data: 'post_id='+postid,
            cache: false,
            success: function(){
                $(".bs-example-modal-sm").modal('hide');
                new PNotify({
                    title: 'Success',
                    text: 'You have successfully deleted this post.',
                    type: 'success'
                });
                $( "div #"+postid+"" ).fadeOut( "slow", function() {
                    $("div #"+postid+"").remove();
                });
            }
        });
        return false;

    });
    /*
     *
     *FUNCTION FOR LIKING A FORUM POST WITHIN A THREAD
     *
     */
    $(document).on('click', '.like', function() {

        var post_id = $(this).data("post_id");
        var reciever_user_id = $(this).data("reciever_user_id");

        $.ajax({
            type: "POST",
            url: base_url+"/inc/functions/like_post.php",
            data: 'post_id='+post_id+'&reciever_user_id='+reciever_user_id,
            cache: false,
            dataType: "json",
            success: function(data){

                console.log(data);
                    if (!data.error){
                        $("a #"+post_id).toggleClass('like' + post_id + ' liked-post');
                        $("#likePost"+post_id).html('<a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="" class="liked" data-original-title="You have already liked this post"><i class="ion-ios-heart-outline liked-post"></i> </a>');
                        $("#likesBar"+post_id).html(data.HTML);
                        $('[data-toggle="tooltip"]').tooltip();

                        new PNotify({
                            title: data.title,
                            text: data.text,
                            type: 'success'
                        });
                    }else{
                        new PNotify({
                            title: data.title,
                            text: data.text,
                            type: 'error'
                        });
                    }
            }
        });
        return false;
    });
    /*
     *
     *FUNCTION FOR LINKING A FORUM POST WITH AN URL
     *
     */
    $(document).on('click', '.link-post', function() {

            $('.bs-example-modal-sm').modal('hide');
            var thread_url = $(this).attr("id");

            prompt("Copy this link for future reference.", thread_url);
        });
    /*
     *
     *FUNCTION FOR RETRIEVING INFORMATION ON AN USER
     *
     */
    $(document).on('click', '#getUser', function(e){

        e.preventDefault();

        var uid = $(this).data('id'); // get id of clicked row

        $('#dynamic-content').html(''); // leave this div blank
        $('#modal-loader').show();      // load ajax loader on button click

        $.ajax({
            url: '/forum/inc/functions/admin/view_user_details.php',
            type: 'POST',
            data: 'id='+uid,
            dataType: 'html'
        })
            .done(function(data){
                $('#dynamic-content').html(''); // blank before load.
                $('#dynamic-content').html(data); // load here
                $('#modal-loader').hide(); // hide loader
            })
            .fail(function(){
                $('#dynamic-content').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
                $('#modal-loader').hide();
            });

    });
    /*
     *
     *FUNCTION FOR RETRIEVING THE OPTION MODAL (...)
     *
     */
    $(document).on('click', '#optionModal', function(e){


        e.preventDefault();

        var uid = $(this).data('post_id'); // get id of clicked row

        $('#optionModalContent').html(''); // leave this div blank
        $('.bs-example-modal-sm').modal('show');      // load ajax loader on button click

        $.ajax({
            url: '/forum/inc/options-modal.php',
            type: 'POST',
            data: 'id='+uid,
            dataType: 'html'
        })
            .done(function(data){

                $('#optionModalContent').html(''); // blank before load.
                $('#optionModalContent').html(data); // load here
                $('#loadingContent').hide(); // hide loader
            })
            .fail(function(){
                $('#optionModalContent').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
                $('#loadingContent').hide();
            });

    });
    /*
     *
     *FUNCTION FOR READING ALL USER NOTIFICATIONS
     *
     */
    $(document).on("click", "#buttonNotification", function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: base_url+"/inc/functions/read-notifications.php",
            cache: false,
            success: function(){
                new PNotify({
                    title: 'Success',
                    text: 'All notifications have been marked as read!',
                    type: 'info'
                });
                $("#modalAlerts").modal('hide');
                $("#notification-count").html('<span class="badge badge-alert" style="font-style: normal !important">0</span>').fadeIn();
                getNotifications();
                current_notification_count = 0;
            }
        });
        return false;
    });
    /*
     *
     *FUNCTION FOR EDITING A USERS POST. INSERT THE POST DATA INTO THE EDIT EDITOR
     *
     */
    $(document).on('click', '#editBtn', function(e){

        e.preventDefault();
        $('.bs-example-modal-sm').modal('hide');
        var post_id = $(this).data('id'); // get id of clicked row
        $("#edit-modal").modal('show');//load the modal into view
        $('#edit-data').html(''); // leave this div blank

        $.ajax({
            url: base_url+'/inc/functions/edit_post.php',
            type: 'POST',
            data: 'post_id='+post_id,
            dataType: "html"
        })
            .done(function(data){
                $(".edit-data").html(data);
            })
            .fail(function(){
                $('#dynamic-content1').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
                $('#modal-loader').hide();
            });

    });
    /*
     *
     *FUNCTION FOR APPENDING THE QUOTE BBCODE INTO THE TEXTBOX
     *
     */
    $(document).on('click', '.quoteButton', function() {

        $(".bs-example-modal-sm").modal('hide');

        if (quoteDisabled) {
            new PNotify({
                title: 'Oops',
                text: 'This feature has been disabled by an administrator. ',
                type: 'error'
            });
        } else {
            var rand_id = $(this).attr('id');
            var quote = "[quote]" + rand_id + "[/quote] <br>";
            $(".wysibb-text-editor").append(quote);

            new PNotify({
                title: 'Just so you know!',
                text: 'Post quote has been added to the reply editor at the bottom of this page.',
                type: 'info'
            });
        }
    });
    /*
     *
     *FUNCTION FOR CLOSING A MODAL
     *
     */
    $(document).on('click',"#titleEditOption", function(){
        $(".bs-example-modal-sm").modal('hide');
    });
    /*
     *
     *FUNCTION FOR EDITING THE THREADS TITLE
     *
     */
    $(document).on('click','#editTitleBtn',function (e) {
        e.preventDefault();

        $("#editTitleBtn").attr("disabled", true);

        var threadTitle = $("#threadTitle").val();
        var threadId = thread_id;
        $.ajax({
            url: base_url+"/inc/functions/title-edit.php",
            type: 'POST',
            data: {threadTitle:threadTitle, threadId:threadId},
            dataType: "json",
            success: function(data){
                console.log(data);

                $(".jumbotron h1").html(data.threadTitle);
                $(".currentThreadTitle").text(data.threadTitle);
                $("#title-edit-modal").modal("hide");

                $("#threadTitle input").attr("disbaled", true);
                $("#editTitleBtn").remove();

                $(".warning").text("You can now no longer edit this page title.");

                document.title = "Habbo SS Forum - " + data.threadTitle;
                new PNotify({
                    title: data.title,
                    text: data.text,
                    type: 'success'
                });
            },
            error: function(data){
                console.log(data);
                $("#editTitleBtn").attr("disabled", false);
            }
        });
        return false;
    });
    /*
    * 
    *FUNCTION THAT ALLOWS ADMIN TO MOVE THREAD TO DIFFERENT SUB-CATEGORY 
    * 
    */
    $(document).on("change","#moveThread select",function(){
        someAJax("moveThread", $(this).val());
    });
    /*
     *
     *FUNCTION THAT CALCULATES VALUES AND BUTTONS FOR THE "THREAD MANAGE" MODAL
     *
     */
    $(document).on('click', '#threadManage .modal-content', function(e){

        var target = $("#"+e.target.id);
        var value;
        console.log(e.target.id);

        if(!target.prop("checked")){
            value = 0;
        }else if(target.attr("checked")){
            value = 1;
        }else{
            value = e.target.value;
        }
        var btnClicked = e.target.id;
        if(btnClicked == "delete"){
            if(confirm("Are you sure you want to delete this thread?")){
                if(confirm("Are you really sure?")){
                    if(confirm("This is your last chance! Are you 100% sure? You can't over-ride this decision.")){
                        someAJax(btnClicked, value);
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
        console.log(value);
        if(value == "undefined" || btnClicked == "undefined"){
            notify("ERROR", "It appears an error has occurred. We have submitted a report into this and a developer will fix this issue soon", "error");
        }else {
            someAJax(btnClicked, value);
        }
    });
    /*
     *
     *FUNCTION FOR SUBMITTING A NEW POST FORM.
     *
     */
    $("#formoid").submit(function(e){
        e.preventDefault();

        $("#register").prop('disabled', true);

        var threadId = $("#threadID").val();

        var replyContent = $("#threadReplyContent").bbcode();

        var dataString = 'replyContent='+ encodeURIComponent(replyContent) + '&threadID='+ threadId;

            // AJAX Code To Submit Form.
            $.ajax({
                type: "POST",
                url: base_url+"/inc/functions/new_post.php",
                data: dataString,
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    if(!data.error){
                        $("#register").prop('disabled', false);
                        if(data.page > current_page){
                            pagination(e,null, data.page, "thread-view", threadId);
                            window.history.pushState(null, 'Title', '/forum/thread/'+data.page);
                            $("#newPostJquery").append(data.newPostHtml).fadeIn('slow').removeAttr("id");
                            $(".wysibb-body").html("");
                        }else{
                            new PNotify({
                                title: data.title,
                                text: data.text,
                                type: 'success'
                            });

                                $("#newPostJquery").append(data.newPostHtml).fadeIn('slow').removeAttr("id");
                            $('[data-toggle="tooltip"]').tooltip();

                            $(".wysibb-body").html("");

                            console.log("New Post");

                        }
                    }else{
                        new PNotify({
                            title: data.title,
                            text: data.text,
                            type: 'error'
                        });
                        $("#register").prop('disabled', false);
                    }
                }

            });

        return false;
    });
    /*
     *
     *FUNCTION FOR SUBMITTING A NEW FORUM THREAD
     *
     */
    $("#formid").submit(function(e){
        e.preventDefault();

        $("#threadBtn").prop('disabled', true);

        var sub_cat_id = $("#subCat").val();
        var threadTitle = $("#threadTitle").val();
        var pinned = $("#pinned").val();
        var threadContent = $("#threadContents").bbcode();

        var dataString = 'threadContents='+ threadContent + '&threadTitle='+ threadTitle +'&pinned='+pinned+'&subCat='+sub_cat_id;

        $.ajax({
            type: "POST",
            url: base_url+"/inc/functions/new_thread.php",
            data: dataString,
            dataType: "json",
            success: function(data) {
                if(!data.error){
                    localStorage.setItem("new_thread", "loool");
                    window.location.href = data.href;
                }else{
                    new PNotify({
                        title: data.title,
                        text: data.text,
                        type: 'error'
                    });
                    $("#threadBtn").prop('disabled', false);
                }
            }
        });
        return false;
    });/*
     *
     *FUNCTION FOR SUBMITTING THE USERS SETTINGS
     *
     */
    $("#user-settings").submit(function(e){
        /*Stop the form from doing it's default action*/
        e.preventDefault();

        /* Disable the submit button to stop multiple form submission */
        $("#threadBtn").prop('disabled', true);

        /* Normal Preferences */
        var gender = $("input:radio[name ='inlineRadioOptions']:checked").val();
        var email = $("#email").val();
        var display_name = $("#display_name").val();

        /*IF statement to see whether the *show_online checkbox is clicked or not */
        var show_online;
        if ($('#show_online').is(":checked")) {show_online = 1;}else {show_online = 0;}

        /* Social Values */
        var skype = $("#social_skype").val();
        var twitter = $("#social_twitter").val();
        var facebook = $("#social_facebook").val();
        
        $.ajax({
            type: "POST",
            url: base_url+"/inc/functions/update-settings.php",
            data: {gender:gender, email:email, display_name: display_name, show_online:show_online, skype: skype, twitter: twitter, facebook: facebook},
            dataType: "json",
            success: function(data) {
                $("#threadBtn").prop('disabled', false);

                    $.each(data, function(idx, obj) {
                        new PNotify({
                            title: obj.title,
                            text: obj.text,
                            type: obj.type
                        });
                    });
            }
        });
        return false;
    });
    /*
     *
     *FUNCTION THAT CONTAINS AJAX FOR THE "THREAD MANAGE" MODAL
     *
     */
    function someAJax(btnClicked, value){
        $.ajax({
            url: base_url+"/inc/functions/admin/manage_thread.php",
            type: "POST",
            data: {event:btnClicked, value:value, threadId:thread_id},
            dataType: "json",
            success: function (data) {
                console.log(data);
                if(!data.error) {
                    switch (data.type) {
                        case 1:
                            if(data.clicked){
                                $(".panel-thread-reply").removeClass("panel panel-default ").html(data.html);
                                new PNotify({
                                    title: data.title,
                                    text: data.text,
                                    type: 'success'
                                });
                            }
                            if(!data.clicked){
                                $(".panel-thread-reply").removeClass("alert alert-warning").addClass("panel panel-default ").html(data.html);
                                wysibbEditor();
                                new PNotify({
                                    title: data.title,
                                    text: data.text,
                                    type: 'success'
                                });
                            }
                            break;
                        case 2:
                            $(".sub_cat_name").html(data.html);
                            new PNotify({
                                title: data.title,
                                text: data.text,
                                type: 'success'
                            });
                            break;
                        case 3:
                            localStorage.setItem("deleted_thread", "loool");
                            window.location.href = base_url+"/index.php";
                            break;
                        case 4:
                            new PNotify({
                                title: data.title,
                                text: data.text,
                                type: 'success'
                            });
                            break;
                    }
                }else{
                    new PNotify({
                        title: data.title,
                        text: data.text,
                        type: 'error'
                    });
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
    /*
     *
     *FUNCTION THAT CONTAINS AJAX THAT WILL HANDLE NOTIFICATIONS
     *
     */
     function updateNotification() {
         $.ajax({
             type: "POST",
             url: base_url+"/inc/functions/read-notifications.php",
             data: {type:3},
             dataType: "json",
             success: function (data) {
                 console.log(data);
                 if (data.notificationCount > current_notification_count) {
                     console.log("New Notification");
                     new PNotify({
                         title: "woo!",
                         text: "You have a new notification! ",
                         type: "info"
                     });
                     current_notification_count = data.notificationCount;
                     $.playSound('/forum/inc/sounds/notification-sound');
                 }
             }
         });
    }
    /*
     *
     *FUNCTION THAT FETCHES ALL ONLINE USERS AND SHOWS THEM ON SCREEN ON INDEX.PHP
     *
     */
    function loadlink() {
        $('.online-user-count').load(base_url +'/inc/online-users.php?id_=39257912475');
    }
    /*
     *
     *FUNCTION THAT FETCHES THE LIST OF NOTIFICATIONS
     *
     */
    function getNotifications() {
        $('div #notification-notices').load(base_url +'/inc/notification-model.php .modal-body');
    }
    /*
     *
     *FUNCTION THAT FETCHES THE NUMBER OF NOTIFICATIONS A USER HAS
     *
     */
    function getNotificationCount() {
        $('#notification-count').load(base_url +'/inc/notification-model.php .badge-alert');
    }
    /*
     *
     *FUNCTION THAT FETCHES THE FORUM'S HOMEPAGE HTML AND DISPLAYS IT TO THE USER
     *
     */
    function getForumView(){
        $("#forumMainView").load(base_url +'/inc/pages/forum-home.php');
    }
    /*
    *
    * FUNCTION FOR CHANGING PAGINATION FOR THREAD VIEW
    *
    */
    function pagination(e, obj, page, url, value) {
        e.preventDefault();
        $(".loading-div").show(); //show loading element
        if (obj != null) {
            var page = $(obj).attr("data-page"); //get page number from link
        }
        $("#results").load(base_url + "/inc/pages/" + url + ".php", {id: value, page: page}, function (data) { //get content from PHP page
            $(".loading-div").hide(); //once done, hide loading element
            $('[data-toggle="popover"]').popover({html: true});
            $('[data-toggle="tooltip"]').tooltip();
            current_page = page;
        });
    }
    /*
    * 
    *  
    * 
    */
    function checkUserStatus(){
        var request_uri = location.pathname + location.search;
        console.log(request_uri);
        $.getJSON(base_url +'/inc/online-check.php',{path:request_uri}, function(data){
            if(!data.online){
                window.location.href = data.href;
            }
        });
    }
    /*
     *
     *RUN ALL PREVIOUSLY MENTIONED FUNCTIONS
     *
     */
        getNotifications();
        getNotificationCount();
        getForumView();
        loadlink();
    /*
     *
     *FUNCTION THAT REFRESHES ALL FUNCTIONS EVERY 36 SECONDS.
     *
     */
        setInterval(function(){
            checkUserStatus();
            updateNotification();
            getNotificationCount();
            getNotifications();
            getForumView();
            loadlink();
        }, refreshTime);
    /*
     *
     *BELOW FUNCTIONS HANDLE ALL PAGE REFRESH NOTIFICATIONS ALERTS
     *
     */
    if(localStorage.getItem("new_thread")) {
        new PNotify({
            title: 'Success!',
            text: 'You have successfully created a new thread.',
            type: 'success'
        });

        localStorage.clear();
    }
    if(localStorage.getItem("deleted_thread")) {
        new PNotify({
            title: 'Success!',
            text: 'Thread successfully deleted.',
            type: 'success'
        });
        localStorage.clear();
    }
});
/*
 *
 *CONSOLE LOG SOME WARNINGS TO THE USER TO DO WITH THEIR ACCOUNT SECURITY
 *
 */
console.log("%cSTOP!", "background: #ffd2d2; color: red;font-size: 23px; display: block;","background: #ffd2d2; color: red;font-size: 13px; display: block");
console.log("%cWait a second! This is a browser tool intended for developers." + "%c ", "background: #ffd2d2; color: black;font-size: 23px; display: block;"," color: red;font-size: 13px; display: block");
console.log("%cIf someone told you to copy-paste something here, it's most likely an attempt to compromise your account and/or the saftey of others. Report any malicious acts to stupefystar",  "color: black;font-size: 13px; display: block;");
