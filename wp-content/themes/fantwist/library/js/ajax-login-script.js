//  jQuery(document).ready(function($) {
//     // Show the login dialog box on click
//     $('a#show_login').on('click', function(e){
//         $('body').prepend('<div class="login_overlay"></div>');
//         $('form#login').fadeIn(500);
//         $('div.login_overlay, form#dminSubmit a.close').on('click', function(){
//             $('div.login_overlay').remove();
//             $('form#dminSubmit').hide();
//         });
//         e.preventDefault();
//     });
//     //, 'security': $('form#loginform #security').val() }, 
    
//     // Perform AJAX login on form submit
//     jQuery('form').on('submit', function(e) {
//         e.preventDefault();
//         $('form#loginform p.status').show().text(ajax_login_object.loadingmessage);
//         console.log( $('form#loginform #user_login').val() );
//         console.log( $('form#loginform #user_pass').val() );
//         $.ajax({
//             type: 'POST',
//             dataType: 'json',
//             url: ajax_login_object.ajaxurl,
//             data: { 
//                 'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
//                 'username': $('form#loginform #user_login').val(), 
//                 'password': $('form#loginform #user_pass').val()
//             },
//             success: function(data){
//                 $('form#adminSubmit p.status').text(data.message);
//                 if (data.loggedin == true){
//                     document.location.href = ajax_login_object.redirecturl;
//                 }
//             }
//         });
//         e.preventDefault();
//     });

// });