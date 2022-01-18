<?php /* Template Name: Admin Login */ ?>
<?php wp_head(); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<style>
    /* config.css */
/* config.css */

/* helpers/align.css */

.align {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  place-items: center;
}

/* helpers/grid.css */

.grid {
  margin-left: auto;
  margin-right: auto;
  max-width: 320px;
  max-width: 20rem;
  width: 90%;
}

/* helpers/hidden.css */

.hidden {
  border: 0;
  clip: rect(0 0 0 0);
  height: 1px;
  margin: -1px;
  overflow: hidden;
  padding: 0;
  position: absolute;
  width: 1px;
}

/* helpers/icon.css */

.icons {
  display: none;
}

.icon {
  display: inline-block;
  fill: #606468;
  font-size: 16px;
  font-size: 1rem;
  height: 1em;
  vertical-align: middle;
  width: 1em;
}

/* layout/base.css */

* {
  -webkit-box-sizing: inherit;
          box-sizing: inherit;
}

html {
  -webkit-box-sizing: border-box;
          box-sizing: border-box;
  font-size: 100%;
  height: 100%;
}

body {
  background-color: #2c3338;
  color: #606468;
  font-family: 'Open Sans', sans-serif;
  font-size: 14px;
  font-size: 0.875rem;
  font-weight: 400;
  height: 100%;
  line-height: 1.5;
  margin: 0;
  min-height: 100vh;
}

/* modules/anchor.css */

a {
  color: #eee;
  outline: 0;
  text-decoration: none;
}

a:focus,
a:hover {
  text-decoration: underline;
}

/* modules/form.css */

input {
  background-image: none;
  border: 0;
  color: inherit;
  font: inherit;
  margin: 0;
  outline: 0;
  padding: 0;
  -webkit-transition: background-color 0.3s;
  transition: background-color 0.3s;
}

input[type='submit'] {
  cursor: pointer;
}

#adminSubmit {
  margin: 0 auto; 
  max-width: 420px;
}

#adminSubmit input[type='password'],
#adminSubmit input[type='text'],
#adminSubmit input[type='submit'] {
    margin-bottom: 40px;
  width: 100%;
}

#adminSubmit__field {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  margin: 14px;
  margin: 0.875rem;
}

#adminSubmit__input {
  -webkit-box-flex: 1;
      -ms-flex: 1;
          flex: 1;
}

/* modules/login.css */

#adminSubmit {
  color: #eee;
}

#adminSubmit label,
#adminSubmit input[type='text'],
#adminSubmit input[type='password'],
#adminSubmit input[type='submit'] {
  border-radius: 0.25rem;
  padding: 16px;
  padding: 1rem;
}

#adminSubmit label {

  background-color: #363b41;
  border-bottom-right-radius: 0;
  border-top-right-radius: 0;
  padding-left: 20px;
  padding-left: 1.25rem;
  padding-right: 20px;
  padding-right: 1.25rem;
  position: relative;
}

#adminSubmit input[type='password'],
#adminSubmit input[type='text'] {
  background-color: #3b4148;
  border-bottom-left-radius: 0;
  border-top-left-radius: 0;
}

#adminSubmit input[type='password']:focus,
#adminSubmit input[type='password']:hover,
#adminSubmit input[type='text']:focus,
#adminSubmit input[type='text']:hover {
  background-color: #434a52;
}

#adminSubmit input[type='submit'] {
  background-color: #ea4c88;
  color: #eee;
  font-weight: 700;
  text-transform: uppercase;
}

#adminSubmit input[type='submit']:focus,
#adminSubmit input[type='submit']:hover {
  background-color: #d44179;
}

/* modules/text.css */

p {
  margin-bottom: 24px;
  margin-bottom: 1.5rem;
  margin-top: 24px;
  margin-top: 1.5rem;
}

.text--center {
  text-align: center;
}
.inner-wrap {
    display: flex;
    justify-content: center;
    align-items: CENTER;
    align-self: center;
    height: 100%;
}

</style>
<div class="signup-page wrap">
	
	<div class="inner-wrap">

	<?php if ( is_user_logged_in() ) { ?>
    <script>
      document.location.href = "<?=home_url() ?>"+"/contest-manager";
    </script>

    <?php } 
else { ?>
 <script src="<?=get_template_directory_uri()?>/library/js/ajax-login-script.js"></script>
    <?php /*  $args = array(
        'echo'           => true,
        'redirect'       => home_url( $path = '/adminDashboard'), 
        'form_id'        => 'adminSubmit',
        'label_username' => __( 'Username' ),
        'label_password' => __( 'Password' ),
        'label_remember' => __( 'Remember Me' ),
        'label_log_in'   => __( 'Sign In' ),
        'id_submit'      => 'adminSumbitBtn',
        'remember'       => false,
        'value_username' => NULL,
        'value_remember' => false ); ?>
    <?php wp_login_form( $args ); */ ?>
    <form id="adminSubmit" action="login" method="post">
    <h1>Fantwist Admin Login</h1>
    <p class="status"></p>
    <label for="username">Username</label>
    <input id="username" type="text" name="username" required>
    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>
    <a style="display: none;" class="lost" href="<?php echo wp_lostpassword_url(); ?>">Lost your password?</a>
    <input  style="margin-bottom: 0; margin-top: -10px;" class="submit_button" type="submit" value="Login" name="submit">
    <a style="display:none;" class="close" href="">(close)</a>
    <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
    </form>
    <script>
         jQuery(document).ready(function($) {

    // Show the login dialog box on click
    $('a#show_login').on('click', function(e){
        $('body').prepend('<div class="login_overlay"></div>');
        $('form#login').fadeIn(500);
        $('div.login_overlay, form#dminSubmit a.close').on('click', function(){
            $('div.login_overlay').remove();
            $('form#dminSubmit').hide();
        });
        e.preventDefault();
    });

    // Perform AJAX login on form submit
    jQuery('form').on('submit', function(e) {
        e.preventDefault();
        $('form#adminSubmit p.status').show().text(ajax_login_object.loadingmessage);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_login_object.ajaxurl,
            data: { 
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#adminSubmit #username').val(), 
                'password': $('form#adminSubmit #password').val(), 
                'security': $('form#adminSubmit #security').val() },
            success: function(data){
                $('form#adminSubmit p.status').text(data.message);
                if (data.loggedin == true){
                    document.location.href = ajax_login_object.redirecturl;
                }
            }
        });
        e.preventDefault();
    });

});
    </script>
<?php 
} ?>

	</div>

</div>
