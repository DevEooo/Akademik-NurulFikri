<?php
/*
Template Name: Login
*/

// If user is already logged in, redirect
if ( is_user_logged_in() ):
  wp_redirect( home_url() ); exit;
endif;

get_header( 'min' ); ?>

<div class="wkp-panel wkp-panel--width380">
  <h3 class="wkp-panel__header"><?php esc_html_e( 'Login', 'wikipress' ); ?></h3>

  <form id="wkp-login" class="wkp-form" action="login" method="post">

    <p><?php esc_html_e( 'Enter your email address and password to login.', 'wikipress' ); ?></p>

    <div class="wkp-formstatus"></div>

    <div class="wkp-formgroup">
      <label for="email"><?php esc_html_e( 'Email:', 'wikipress' ); ?></label>
      <div class="wkp-inputcontainer">
        <input type="email" 
              name="email" 
              required 
              placeholder="yourname@example.com" 
              pattern="^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*(\.\w{2,})+$" />
      </div>
    </div>
    <div class="wkp-formgroup">
      <label for="password"><?php esc_html_e( 'Password:', 'wikipress' ); ?></label>
      <div class="wkp-inputcontainer">
        <input type="password" name="password" required placeholder="password" minlength="2" />
      </div>
    </div>

    <button class="wkp-formsubmit wkp-formsubmit--fw" type="submit">
      <span><?php esc_html_e( 'Login', 'wikipress' ); ?></span>
      <svg xmlns="http://www.w3.org/2000/svg" fill="#fff" viewBox="0 0 120 30"><circle cx="15" cy="15" r="15"><animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"/><animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"/></circle><circle cx="60" cy="15" r="9" fill-opacity=".3"><animate attributeName="r" from="9" to="9" begin="0s" dur="0.8s" values="9;15;9" calcMode="linear" repeatCount="indefinite"/><animate attributeName="fill-opacity" from=".5" to=".5" begin="0s" dur="0.8s" values=".5;1;.5" calcMode="linear" repeatCount="indefinite"/></circle><circle cx="105" cy="15" r="15"><animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"/><animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"/></circle></svg>
    </button>    

    <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
    <input type="hidden" name="redirect_to" value="<?php echo home_url(); ?>" />
    
  </form>

</div>

<?php get_footer(); ?>