<?php
defined( 'ABSPATH' ) || exit;
?>

<div class="brand-login-container">
  <div class="brand-login-logo">
    <img src="<?php echo esc_url( get_theme_file_uri('/assets/images/logo_new_black.svg') ); ?>" alt="<?php bloginfo('name'); ?>" width="200" height="80" />
  </div>

  <div class="woocommerce-login-form-wrapper">
    <?php wc_print_notices(); ?>

    <form class="woocommerce-form woocommerce-form-login login" method="post">

      <?php do_action( 'woocommerce_login_form_start' ); ?>

      <p class="form-row form-row-wide">
        <label for="username"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
        <input type="text" class="input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
      </p>

      <p class="form-row form-row-wide">
        <label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
        <input class="input-text" type="password" name="password" id="password" autocomplete="current-password" />
      </p>

      <?php do_action( 'woocommerce_login_form' ); ?>

      <p class="form-row">
        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
          <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
        </label>
        <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
        <button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Log in', 'woocommerce' ); ?></button>
      </p>

      <p class="woocommerce-LostPassword lost_password">
        <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
      </p>

      <?php do_action( 'woocommerce_login_form_end' ); ?>

    </form>
  </div>
</div>

