<?php

//If origin param is set use it, otherwise if HTTP_REFERER is set, use it; otherwise use current page
$origin = (isset($_GET['origin'])) ? $_GET['origin'] : ((isset($_SERVER['HTTP_REFERER'])) ? urlencode($_SERVER['HTTP_REFERER']) : get_permalink($post->ID));

//If error is set
$error = (isset($_GET['err'])) ? urldecode($_GET['err']) : false;
/**
 * @package WordPress
 * @subpackage White Label
 */

if(! is_ajax()):

get_template_part('parts/header'); ?>
	<section class="span8">
		<?php endif;?>		
		<article class="content-container register span12">
			
			<section class="content-body clearfix">

				
				<h6 class="content-headline">Register</h6>
				
				<?php if($error):?>
					<div><?php echo $error;?></div>
				<?php endif;?>
				
				<form class="form_register" id="register-form" method="post" action="<?php echo '?ssoregister&origin=' . $origin; ?>">
            <ul class="form-fields">
                
                <li>
                    <dl class="clearfix">
                        <dt class="span3"><label for="loginId">Email:</label></dt>
                        <dd class="span9"><input type="text" name="loginId" class="input_text" id="loginId" shc:gizmo:form="{required:true, pattern: '^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$'}" /></dd>
                        
                    </dl>
                    							
                </li>
                
                <li>
                    <dl class="clearfix">
                        <dt class="span3"><label for="login_confirm-email">Confirm Email:</label></dt>
                        <dd class="span9">
                        	<input type="text" name="login_confirm-email" class="input_text" id="login_confirm-email" shc:gizmo:form="{required:true}" />
                        </dd>
                    </dl>
                </li>
                
                <li>
                    <dl class="clearfix">
                        <dt class="span3"><label for="logonPassword">Password:</label></dt>
                        <dd class="span9"><input type="password" name="logonPassword" class="input_text input_password" id="logonPassword" shc:gizmo:form="{required:true}" /></dd>
                    </dl>
                </li>
                
                <li>
                    <dl class="clearfix">
                        <dt class="span3"><label for="zipcode">ZIP Code:</label></dt>
                        <dd class="span9"><input type="text" name="zipcode" class="input_text input_password" id="zipcode" shc:gizmo:form="{required:true, pattern: /(^\d{5})(-\d{4})?$/}" /></dd>
                    </dl>
                </li>
                
                
                <li class="clearfix">
                    <dl>
                        <dd class="span3">&nbsp;</dd>
                        <dd class="span9">
                            <p>
                                <input type="checkbox" name="offers" id="offers" value="True" class="input_checkbox" /> I would like to receive offers, updates and sale alerts from Sears
                            </p>
                        </dd>
                    </dl>
                </li>
                
                <li class="clearfix">
                    <dl>
                        <dd class="span3">&nbsp;</dd>
                        <dd class="span9">
                            <button type="submit" class="<?php echo theme_option("brand"); ?>_button">Register</button>
                        </dd>
                    </dl>
                </li>
                
                <li class="clearfix">
                    <dl>
                        <dd class="span3">&nbsp;</dd>
                        <dd class="span9">
                            <p>
                                By clicking &quot;Register&quot;, I agree to the <a href="#" title="Terms of Use">Terms of Use</a> and <a href="#" title="Privacy Policy">Privacy Policy</a>.
                            </p>
                        </dd>
                    </dl>
                </li>
                
            </ul>
				</form>
				
				<ul>
            <li class="clearfix">
                <dl>
                    <dd class="span3">&nbsp;</dd>
                    <dd class="span9">
                        <p class="bold">
                            Existing Customer? <a href="/login/" title="Sign In" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}}}">Sign In</a>
                        </p>
                    </dd>
                </dl>
            </li>
        </ul>
				
				<section id="login-open-id" class="open-id" shc:gizmo="openID">
					<span class="or">OR</span>
					<p>use your account from</p>
					<ul class="open-id-services clearfix">
						<li class="open-id_service open-id_facebook"><a href="#" shc:openID="facebook">Facebook</a></li>
						<li class="open-id_service open-id_yahoo"><a href="#" shc:openID="yahoo">Yahoo!</a></li>
						<li class="open-id_service open-id_google"><a href="#" shc:openID="google">Google</a></li>
						<li class="open-id_service open-id_twitter"><a href="#" shc:openID="twitter">Twitter</a></li>
					</ul>
				</section>
				
			</section>
		</article>
		
		<?php if(! is_ajax()):?>
	</section>

<?php get_template_part('parts/footer'); 

endif;
?>



