<!-- Login -->
<div  id="loginModal" role="dialog">
    <div class="modal-dialog modal-dialog-centered login-modal" role="document">
        <div class="modal-content">
            <div class="auth-box">
                <h4 class="title"><?php echo trans("login"); ?></h4>
                <!-- form start -->
                <!--form method=post action='auth_controller/login_post' id="form_login" novalidate="novalidate"-->
                <form id="form_login" novalidate="novalidate">  
                    <div class="social-login">
                        <?php $this->load->view("partials/_social_login", ["or_text" => trans("login_with_email")]); ?>
                    </div>
                    <!-- include message block -->
                    <div id="result-login" class="font-size-13"></div>
                    <div class="form-group">
                        <input type="text" name="email" class="form-control auth-form-input" placeholder="<?php echo trans("username"); ?> / <?php echo trans("email"); ?>" maxlength="255" required>
                    </div>
                    <div class="form-group password">
                        <input type="password" id="password" name="password" class="form-control auth-form-input" placeholder="<?php echo trans("password"); ?>" minlength="4" maxlength="255" required>
                        <i class="fa fa-eye" id="togglePassword" onclick="togglePassword(this)"></i>            
                    </div>
                    <div class="form-group text-right">   
                        <a href="<?php echo generate_url("forgot_password"); ?>" class="link-forgot-password"><?php echo trans("forgot_password"); ?></a>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-md btn-custom btn-block"><?php echo trans("login"); ?></button>
                    </div>

                    <p class="p-social-media m-0 m-t-5"><?php echo trans("dont_have_account"); ?> <a href="<?php echo generate_url("register"); ?>" class="link"><?php echo trans("register"); ?></a></p>
                </form>
                <!-- form end -->
            </div>
        </div>
    </div>
</div>