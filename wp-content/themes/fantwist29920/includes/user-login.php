<div id="user-login-wrap" class="user-login-overlay">
	
	<div id="login-close">
		<svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="svg-inline--fa fa-times fa-w-10 fa-3x"><path fill="currentColor" d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z" class=""></path></svg>
	</div>
	
	<div class="user-login-form centered-vertical">
		
		<img src="<?php echo get_template_directory_uri(); ?>/library/images/logo-temp.png" alt="EsportsGrind" />
		
		<?php 
		wp_login_form(array(
			'label_username' => 'Username or Email Address',
			'label_password' => 'Password',
		));
		?>
		
		<div class="no-account">Don't have an account? <a class="signup" href="javascript:void(0);">Sign up</a></div>
	
	</div>
	
</div>