<?php
/**
 * @version        4.9
 * @package        Joomla
 * @subpackage     Joom Donation
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2009 - 2017 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

$jinput = JFactory::getApplication()->input;
$donationAmount = $jinput->get('amount', 1, FLOAT);

if ($this->config->use_https)
{
    $url = JRoute::_('index.php?option=com_jdonation&Itemid='.$this->Itemid, false, 1);
}
else
{
    $url = JRoute::_('index.php?option=com_jdonation&Itemid='.$this->Itemid, false);
}
DonationHelperJquery::validateForm();
//Validation rule fo custom amount
$amountValidationRules = '';
$minDonationAmount = (int) $this->config->minimum_donation_amount;
$maxDonationAmount = (int) $this->config->maximum_donation_amount;
if ($minDonationAmount)
{
    $amountValidationRules .= ",min[$minDonationAmount]";
}
if ($maxDonationAmount)
{
    $amountValidationRules .= ",max[$maxDonationAmount]";
}
$selectedState = '';
?>
<script type="text/javascript">
    <?php echo $this->recurringString ;?>
    var siteUrl = "<?php echo DonationHelper::getSiteUrl(); ?>";
</script>
<script type="text/javascript" src="<?php echo DonationHelper::getSiteUrl().'media/com_jdonation/assets/js/jdonation.js'?>"></script>
<script type="text/javascript" src="<?php echo DonationHelper::getSiteUrl().'media/com_jdonation/assets/js/fblike.js'?>"></script>
<div id="donation-form" class="row-fluid jd-container">
<h1 class="jd-page-title"><?php echo JText::_('JD_DONATION'); ?></h1>
<?php
//show campaign
if($this->campaign->id > 0){
    $campaign_link = JUri::getInstance()->toString(array('scheme', 'user', 'pass', 'host')).JRoute::_(DonationHelperRoute::getDonationFormRoute($this->campaign->id,JRequest::getInt('Itemid',0)));
    ?>
    <div class="row-fluid">
        <div class="span12">
            <?php
            $config=JFactory::getConfig();
            if(JVERSION>=3.0)
                $site_name=$config->get( 'sitename' );
            else
                $site_name=$config->getvalue( 'config.sitename' );

            require_once(JPATH_SITE . "/components/com_jdonation/helper/integrations.php");
            $doc = JFactory::getDocument();
            $doc->addCustomTag( '<meta property="og:title" content="'.$this->campaign->title.'" />' );
            if(($this->campaign->campaign_photo != "") && (file_exists(JPATH_ROOT.'/images/jdonation/'.$this->campaign->campaign_photo))){
                $doc->addCustomTag( '<meta property="og:image" content="'.JUri::root().'images/jdonation/'.$this->campaign->campaign_photo.'" />' );
            }
            $doc->addCustomTag( '<meta property="og:url" content="'.$campaign_link.'" />' );
            $doc->addCustomTag( '<meta property="og:description" content="'.nl2br(strip_tags(addslashes($this->campaign->description))).'" />' );
            $doc->addCustomTag( '<meta property="og:site_name" content="'.$site_name.'" />' );
            $doc->addCustomTag( '<meta property="og:type" content="article" />' );
            if($this->config->social_sharing == 1)
            {
                if($this->config->social_sharing_type == 1)
                {
                    $add_this_share='
                    <!-- AddThis Button BEGIN -->
                    <div class="addthis_toolbox addthis_default_style">
                    <a class="addthis_button_facebook_like" fb:like:layout="button_count" class="addthis_button" addthis:url="'.$campaign_link.'"></a>
                    <a class="addthis_button_google_plusone" g:plusone:size="medium" class="addthis_button" addthis:url="'.$campaign_link.'"></a>
                    <a class="addthis_button_tweet" class="addthis_button" addthis:url="'.$campaign_link.'"></a>
                    <a class="addthis_button_pinterest_pinit" class="addthis_button" addthis:url="'.$campaign_link.'"></a>
                    <a class="addthis_counter addthis_pill_style" class="addthis_button" addthis:url="'.$campaign_link.'"></a>
                    </div>
                    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid="'.$this->config->addthis_publisher.'"></script>
                    <!-- AddThis Button END -->' ;
                    $add_this_js='https://s7.addthis.com/js/300/addthis_widget.js';
                    $JdonationIntegrationsHelper=new JdontionIntegrationsHelper();
                    $JdonationIntegrationsHelper->loadScriptOnce($add_this_js);
                    //output all social sharing buttons
                    echo' <div id="rr" style="">
                        <div class="social_share_container">
                        <div class="social_share_container_inner">'.
                            $add_this_share.
                        '</div>
                    </div>
                    </div>
                    ';
                }
                else
                {
                    echo '<div class="jd_horizontal_social_buttons">';
                        echo '<div class="jd_float_left">
                                <div class="fb-like" data-href="'.$campaign_link.'" data-send="true" data-layout="button_count" data-width="450" data-show-faces="true">
                                </div>
                            </div>';
                        echo '

                        <div class="jd_float_left">
                                &nbsp; <div class="g-plus" data-action="share" data-annotation="bubble" data-href="'.$campaign_link.'">
                                    </div>
                        </div>';
                    echo '<div class="jd_float_left">
                            &nbsp; <a href="https://twitter.com/share" class="twitter-share-button"  data-url="'.$campaign_link.'" data-counturl="'.$campaign_link.'">Tweet</a>
                        </div>';
                    echo '</div>
                        <div class="clearfix"></div>';
                }
            }
            ?>
        </div>
    </div>
    <?php
}
?>
<?php
if($this->campaign->donation_form_msg)
{
    $message = $this->campaign->donation_form_msg;
}
else
{
    if (!$this->showCampaignSelection && strlen(trim(strip_tags($this->campaign->description))))
    {
        $message = $this->campaign->description;
    }
    else
    {
    $message = $this->config->donation_form_msg;
    }
}
if (strlen($message))
{
?>
    <div class="jd-message clearfix"><?php echo $message; ?></div>
<?php
}
if (!$this->userId && ($this->config->registration_integration == 1 || $this->config->registration_integration == 2) && $this->config->show_login_box)
{
    $actionUrl = JRoute::_('index.php?option=com_users&task=user.login');
    $validateLoginForm = 1;
    ?>
    <div class="registration_form">
        <form method="post" action="<?php echo $actionUrl ; ?>" name="jd-login-form" id="jd-login-form" autocomplete="off" class="form form-horizontal">
            <h3 class="jd-heading"><?php echo JText::_('JD_EXISTING_USER_LOGIN'); ?></h3>
            <div class="control-group">
                <label class="control-label" for="username">
                    <?php echo  JText::_('JD_USERNAME') ?><span class="required">*</span>
                </label>
                <div class="controls">
                    <input type="text" name="username" id="username" class="input-large validate[required]" value=""/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="password">
                    <?php echo  JText::_('JD_PASSWORD') ?><span class="required">*</span>
                </label>
                <div class="controls">
                    <input type="password" id="password" name="password" class="input-large validate[required]" value="" />
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <input type="submit" value="<?php echo JText::_('JD_LOGIN'); ?>" class="button btn btn-primary" />
                </div>
            </div>
            <h3 class="eb-heading"><?php echo JText::_('JD_NEW_USER_REGISTER'); ?></h3>
            <?php
            if (JPluginHelper::isEnabled('system', 'remember'))
            {
            ?>
                <input type="hidden" name="remember" value="1" />
            <?php
            }
            ?>
            <input type="hidden" name="return" value="<?php echo base64_encode(JUri::getInstance()->toString()); ?>" />
            <?php echo JHtml::_( 'form.token' ); ?>
        </form>
    </div>
<?php
}
else
{
    $validateLoginForm = 0;
}

?>

<form method="post" name="os_form" id="os_form" action="<?php echo $url ; ?>" autocomplete="off" class="form form-horizontal" enctype="multipart/form-data">

	<div class="control-group donation-amount">
		<div class="controls" id="amount_container">
			<?php
				$amountSelected = false;
				if ($this->config->donation_amounts)
				{
					$explanations = explode("\r\n", $this->config->donation_amounts_explanation) ;
					$amounts = explode("\r\n", $this->config->donation_amounts);
					if ($this->config->amounts_format == 1)
					{
						for ($i = 0 , $n = count($amounts) ; $i < $n ; $i++)
						{
							$amount = (float)$amounts[$i] ;
							if ($amount == $donationAmount)
							{
								$amountSelected = true;
								$checked = ' checked="checked" ';
								$fixedAmountFlag = true; // G.C. added 18/9/17
								$active = ' active';
							}
							else
							{
								$active = $checked = '' ;
							}
						?>
							<label class="<?php echo $active; ?>">
								<input type="radio" name="rd_amount" class="validate[required] input-large" value="<?php echo $amount; ?>" <?php echo $checked; ?> onclick="clearTextbox();" data-errormessage="<?php echo JText::_('JD_AMOUNT_IS_REQUIRED'); ?>" />
								<?php echo '£<span>'.$amount.'</span>'; //DonationHelperHtml::formatAmount($this->config, $amount)?>
								<?php
								if (isset($explanations[$i]) && $explanations[$i])
								{
									echo '   <span class="amount_explaination">[ '.$explanations[$i].' ]</span>  ' ;
								}
								?>
							</label>
						<?php
						}
					}
					else
					{
						$options = array() ;
						$options[] = JHtml::_('select.option', 0, JText::_('JD_AMOUNT')) ;
						for ($i = 0 , $n = count($amounts) ; $i < $n ; $i++)
						{
							$amount = (float)$amounts[$i] ;
							if ($amount == $this->rdAmount)
							{
								$amountSelected = true;
							}
							if (isset($explanations[$i]) && $explanations[$i])
							{
								$options[] = JHtml::_('select.option', $amount, DonationHelperHtml::formatAmount($this->config, $amount)." [$explanations[$i]]") ;
							}
							else
							{
								$options[] = JHtml::_('select.option', $amount, DonationHelperHtml::formatAmount($this->config, $amount)) ;
							}
						}
						echo  $this->config->currency_symbol.'  '.JHtml::_('select.genericlist', $options, 'rd_amount', ' class="validate[required] input-large" onchange="clearTextbox();" ', 'value', 'text', $this->rdAmount).'<br /><br />';
					}
				}
				if ($this->config->display_amount_textbox)
				{
					if ($this->config->donation_amounts)
					{
						$placeHolder = JText::_('JD_OTHER_AMOUNT');
					}
					else
					{
						$placeHolder = '';
					}
					if ($amountSelected)
					{
						$amountCssClass = 'validate[custom[number]'.$amountValidationRules.'] input-small';
					}
					else
					{
						$amountCssClass = 'validate[required,custom[number]'.$amountValidationRules.'] input-small';
					}
					if ($this->config->currency_position == 0)
					{
					?>
						<div class="input-prepend inline-display">
							<p class="text">Own amount</p>
							<span class="add-on"><?php echo $this->config->currency_symbol;?></span>
							<div class="input-wrapper">
								<input type="number" step="0.01" min="1" class="<?php echo $amountCssClass; ?>" name="amount" value="<?php if ( !$fixedAmountFlag ) echo $this->amount;  // G.C. added 18/9/17 ?>" onchange="deSelectRadio();" data-errormessage="<?php echo JText::_('JD_AMOUNT_IS_REQUIRED');?>" data-errormessage="<?php echo JText::_('JD_AMOUNT_IS_REQUIRED');?>" data-errormessage-range-underflow="<?php echo JText::sprintf('JD_MIN_DONATION_AMOUNT_ALLOWED', $this->config->minimum_donation_amount); ?>" data-errormessage-range-overflow="<?php echo JText::sprintf('JD_MAX_DONATION_AMOUNT_ALLOWED', $this->config->maximum_donation_amount); ?>" />
							</div>
						</div>
					<?php
					}
					else
					{
					?>
						<div class="input-append inline-display">
							<p class="text">Own amount</p>
							<input type="number" step="0.01" placeholder="<?php //echo $placeHolder; ?>" class="<?php echo $amountCssClass; ?>" name="amount" value="<?php if ( !$fixedAmountFlag ) echo $this->amount;  // G.C. added 18/9/17 ?>" onchange="deSelectRadio();" data-errormessage="<?php echo JText::_('JD_AMOUNT_IS_REQUIRED');?>" data-errormessage="<?php echo JText::_('JD_AMOUNT_IS_REQUIRED');?>" data-errormessage-range-underflow="<?php echo JText::sprintf('JD_MIN_DONATION_AMOUNT_ALLOWED', $this->config->minimum_donation_amount); ?>" data-errormessage-range-overflow="<?php echo JText::sprintf('JD_MAX_DONATION_AMOUNT_ALLOWED', $this->config->maximum_donation_amount); ?>" />
							<span class="add-on"><?php echo $this->config->currency_symbol;?></span>
						</div>
					<?php
					}
				}
			?>
		</div>
	</div>

<div class="form-wrapper">

	<div class="control-group form-title">
		<h3 class="jd-heading">Your Details</h3> <?php //echo JText::_('JD_DONOR_INFO'); ?>
		<p>Mandatory fields are indicated with an <span>*</span></p>
	</div>

	<?php // Email Section ?>
    <div class="control-group campaign_0" id="field_email">
        <label class="control-label" for="email" title="">Email address<span class="required">*</span>
        </label>
        <div class="controls">
            <div class="emailformError parentFormos_form formError" style="opacity: 0.87; position: absolute; top: 1610.59px; left: 680px; margin-top: -46px;">
                <div class="formErrorContent">* This field is required<br>* Invalid email address<br></div>
                <div class="formErrorArrow">
                    <div class="line10"><!-- --></div>
                    <div class="line9"><!-- --></div>
                    <div class="line8"><!-- --></div>
                    <div class="line7"><!-- --></div>
                    <div class="line6"><!-- --></div>
                    <div class="line5"><!-- --></div>
                    <div class="line4"><!-- --></div>
                    <div class="line3"><!-- --></div>
                    <div class="line2"><!-- --></div>
                    <div class="line1"><!-- --></div>
                </div>
            </div>
            <input type="text" name="email" id="email" placeholder="Enter email address" value="" class="input-large validate[required,custom[email]]">
        </div>
    </div>

    <?php // (re)Captcha Section ?>
    <div class="control-group campaign_0" id="field_">
        <label class="control-label" for="Title" title=""></label>
        <div class="controls">
            <div class="g-recaptcha" data-sitekey="6LcFmzEUAAAAAKjno9bzlYLhrIee0FByTRgXSH6c"></div>
        </div>
    </div>

    <?php // Title Section ?>
    <div class="control-group campaign_0" id="field_Title">
        <label class="control-label" for="Title" title="">Title<span class="required">*</span>
        </label>
        <div class="controls">
            <select id="Title" name="Title" class="validate[required]">
                <option value="" selected="selected">Select</option>
                <option value="Mr.">Mr.</option>
                <option value="Ms.">Ms.</option>
                <option value="Mrs.">Mrs.</option>
                <option value="Miss">Miss</option>
                <option value="Mx">Mx</option>
                <option value="Dr.">Dr.</option>
            </select>
        </div>
    </div>

    <?php // First Name Section ?>
    <div class="control-group campaign_0" id="field_first_name">
        <label class="control-label" for="first_name" title="">First Name<span class="required">*</span>
        </label>
        <div class="controls">
            <div class="first_nameformError parentFormos_form formError" style="opacity: 0.87; position: absolute; top: 1743.59px; left: 680px; margin-top: -31px;">
                <div class="formErrorContent">* This field is required<br></div>
                <div class="formErrorArrow"><div class="line10"><!-- --></div><div class="line9"><!-- --></div><div class="line8"><!-- --></div><div class="line7"><!-- --></div><div class="line6"><!-- --></div><div class="line5"><!-- --></div><div class="line4"><!-- --></div><div class="line3"><!-- --></div><div class="line2"><!-- --></div><div class="line1"><!-- --></div></div></div>
                <input type="text" name="first_name" id="first_name" placeholder="Enter first name" value="" class="input-large validate[required]">
        </div>
    </div>

    <?php // Last Name Section ?>
    <div class="control-group campaign_0" id="field_last_name">
        <label class="control-label" for="last_name" title="">Last Name<span class="required">*</span>
        </label>
        <div class="controls">
            <input type="text" name="last_name" id="last_name" placeholder="Enter last name" value="" class="validate[required]">
        </div>
    </div>

    <?php // House Name/Number Section ?>
    <div class="control-group campaign_0" id="field_house_id">
        <label class="control-label" for="house_id" title="">House name or number<span class="required">*</span>
        </label>
        <div class="controls">
            <input type="text" name="house_id" id="house_id" placeholder="Enter house name or number" value="" class="validate[required]">
        </div>
    </div>

<div class="address-group">

        <?php // Address Section ?>
        <div class="control-group campaign_0" id="field_address">
                <label class="control-label" for="address" title="">Address<span class="required">*</span></label>
            <div class="controls">
                <div class="addressformError parentFormos_form formError" style="opacity: 0.87; position: absolute; top: 2207.59px; left: 680px; margin-top: -31px;"><div class="formErrorContent">* This field is required<br></div><div class="formErrorArrow"><div class="line10"><!-- --></div><div class="line9"><!-- --></div><div class="line8"><!-- --></div><div class="line7"><!-- --></div><div class="line6"><!-- --></div><div class="line5"><!-- --></div><div class="line4"><!-- --></div><div class="line3"><!-- --></div><div class="line2"><!-- --></div><div class="line1"><!-- --></div></div></div>
                <input type="text" name="address" id="address" placeholder="Enter address" value="" class="input-large validate[required]">
            </div>
        </div>
        <?php // City Section ?>
        <div class="control-group campaign_0" id="field_city">
            <label class="control-label" for="city" title="">Town / City</label>
            <div class="controls">
                <input type="text" name="city" id="city" placeholder="Enter city" value="" class="input-large">
            </div>
        </div>

</div> <!-- end address-group 1 -->

    <?php // Postcode Section ?>
    <div class="control-group campaign_0" id="field_postcode">
        <label class="control-label" for="postcode" title="">Post code<span class="required">*</span>
        </label>
        <div class="controls">
            <input type="text" name="postcode" id="postcode" placeholder="Enter post code" value="" class="input-large validate[required]">
            <div class="lookup-error">Lookup error: Please check your details and try again</div>
            <div class="manual-address">Enter address manually</div>
        </div>
        <div class="find-address btn">Find address</div>
    </div>

<div class="address-group">

        <?php // Country Section ?>
        <div class="control-group campaign_0" id="field_country">
            <label class="control-label" for="country" title="">Country<span class="required">*</span>
            </label>
            <div class="controls">
                <select id="country" name="country" class="input-large validate[required]">
                    <option value="">Select Country</option>
                    <option value="Afghanistan">Afghanistan</option>
                    <option value="Albania">Albania</option>
                    <option value="Algeria">Algeria</option>
                    <option value="American Samoa">American Samoa</option>
                    <option value="Andorra">Andorra</option>
                    <option value="Angola">Angola</option>
                    <option value="Anguilla">Anguilla</option>
                    <option value="Antarctica">Antarctica</option>
                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                    <option value="Argentina">Argentina</option>
                    <option value="Armenia">Armenia</option>
                    <option value="Aruba">Aruba</option>
                    <option value="Australia">Australia</option>
                    <option value="Austria">Austria</option>
                    <option value="Azerbaijan">Azerbaijan</option>
                    <option value="Bahamas">Bahamas</option>
                    <option value="Bahrain">Bahrain</option>
                    <option value="Bangladesh">Bangladesh</option>
                    <option value="Barbados">Barbados</option>
                    <option value="Belarus">Belarus</option>
                    <option value="Belgium">Belgium</option>
                    <option value="Belize">Belize</option>
                    <option value="Benin">Benin</option>
                    <option value="Bermuda">Bermuda</option>
                    <option value="Bhutan">Bhutan</option>
                    <option value="Bolivia">Bolivia</option>
                    <option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option>
                    <option value="Botswana">Botswana</option>
                    <option value="Bouvet Island">Bouvet Island</option>
                    <option value="Brazil">Brazil</option>
                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                    <option value="Brunei Darussalam">Brunei Darussalam</option>
                    <option value="Bulgaria">Bulgaria</option>
                    <option value="Burkina Faso">Burkina Faso</option>
                    <option value="Burundi">Burundi</option>
                    <option value="Cambodia">Cambodia</option>
                    <option value="Cameroon">Cameroon</option>
                    <option value="Canada">Canada</option>
                    <option value="Canary Islands">Canary Islands</option>
                    <option value="Cape Verde">Cape Verde</option>
                    <option value="Cayman Islands">Cayman Islands</option>
                    <option value="Central African Republic">Central African Republic</option>
                    <option value="Chad">Chad</option>
                    <option value="Chile">Chile</option>
                    <option value="China">China</option>
                    <option value="Christmas Island">Christmas Island</option>
                    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                    <option value="Colombia">Colombia</option>
                    <option value="Comoros">Comoros</option>
                    <option value="Congo">Congo</option>
                    <option value="Cook Islands">Cook Islands</option>
                    <option value="Costa Rica">Costa Rica</option>
                    <option value="Cote D'Ivoire">Cote D'Ivoire</option>
                    <option value="Croatia">Croatia</option>
                    <option value="Cuba">Cuba</option>
                    <option value="Cyprus">Cyprus</option>
                    <option value="Czech Republic">Czech Republic</option>
                    <option value="Denmark">Denmark</option>
                    <option value="Djibouti">Djibouti</option>
                    <option value="Dominica">Dominica</option>
                    <option value="Dominican Republic">Dominican Republic</option>
                    <option value="East Timor">East Timor</option>
                    <option value="East Timor">East Timor</option>
                    <option value="Ecuador">Ecuador</option>
                    <option value="Egypt">Egypt</option>
                    <option value="El Salvador">El Salvador</option>
                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                    <option value="Eritrea">Eritrea</option>
                    <option value="Estonia">Estonia</option>
                    <option value="Ethiopia">Ethiopia</option>
                    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                    <option value="Faroe Islands">Faroe Islands</option>
                    <option value="Fiji">Fiji</option>
                    <option value="Finland">Finland</option>
                    <option value="France">France</option>
                    <option value="France, Metropolitan">France, Metropolitan</option>
                    <option value="French Guiana">French Guiana</option>
                    <option value="French Polynesia">French Polynesia</option>
                    <option value="French Southern Territories">French Southern Territories</option>
                    <option value="Gabon">Gabon</option>
                    <option value="Gambia">Gambia</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Germany">Germany</option>
                    <option value="Ghana">Ghana</option>
                    <option value="Gibraltar">Gibraltar</option>
                    <option value="Greece">Greece</option>
                    <option value="Greenland">Greenland</option>
                    <option value="Grenada">Grenada</option>
                    <option value="Guadeloupe">Guadeloupe</option>
                    <option value="Guam">Guam</option>
                    <option value="Guatemala">Guatemala</option>
                    <option value="Guinea">Guinea</option>
                    <option value="Guinea-bissau">Guinea-bissau</option>
                    <option value="Guyana">Guyana</option>
                    <option value="Haiti">Haiti</option>
                    <option value="Heard and Mc Donald Islands">Heard and Mc Donald Islands</option>
                    <option value="Honduras">Honduras</option>
                    <option value="Hong Kong">Hong Kong</option>
                    <option value="Hungary">Hungary</option>
                    <option value="Iceland">Iceland</option>
                    <option value="India">India</option>
                    <option value="Indonesia">Indonesia</option>
                    <option value="Iran (Islamic Republic of)">Iran (Islamic Republic of)</option>
                    <option value="Iraq">Iraq</option>
                    <option value="Ireland">Ireland</option>
                    <option value="Isle of Man">Isle of Man</option>
                    <option value="Israel">Israel</option>
                    <option value="Italy">Italy</option>
                    <option value="Jamaica">Jamaica</option>
                    <option value="Japan">Japan</option>
                    <option value="Jersey">Jersey</option>
                    <option value="Jordan">Jordan</option>
                    <option value="Kazakhstan">Kazakhstan</option>
                    <option value="Kenya">Kenya</option>
                    <option value="Kiribati">Kiribati</option>
                    <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                    <option value="Korea, Republic of">Korea, Republic of</option>
                    <option value="Kuwait">Kuwait</option>
                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                    <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                    <option value="Latvia">Latvia</option>
                    <option value="Lebanon">Lebanon</option>
                    <option value="Lesotho">Lesotho</option>
                    <option value="Liberia">Liberia</option>
                    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                    <option value="Liechtenstein">Liechtenstein</option>
                    <option value="Lithuania">Lithuania</option>
                    <option value="Luxembourg">Luxembourg</option>
                    <option value="Macau">Macau</option>
                    <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                    <option value="Madagascar">Madagascar</option>
                    <option value="Malawi">Malawi</option>
                    <option value="Malaysia">Malaysia</option>
                    <option value="Maldives">Maldives</option>
                    <option value="Mali">Mali</option>
                    <option value="Malta">Malta</option>
                    <option value="Marshall Islands">Marshall Islands</option>
                    <option value="Martinique">Martinique</option>
                    <option value="Mauritania">Mauritania</option>
                    <option value="Mauritius">Mauritius</option>
                    <option value="Mayotte">Mayotte</option>
                    <option value="Mexico">Mexico</option>
                    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                    <option value="Moldova, Republic of">Moldova, Republic of</option>
                    <option value="Monaco">Monaco</option>
                    <option value="Mongolia">Mongolia</option>
                    <option value="Montenegro">Montenegro</option>
                    <option value="Montserrat">Montserrat</option>
                    <option value="Morocco">Morocco</option>
                    <option value="Mozambique">Mozambique</option>
                    <option value="Myanmar">Myanmar</option>
                    <option value="Namibia">Namibia</option>
                    <option value="Nauru">Nauru</option>
                    <option value="Nepal">Nepal</option>
                    <option value="Netherlands">Netherlands</option>
                    <option value="Netherlands Antilles">Netherlands Antilles</option>
                    <option value="New Caledonia">New Caledonia</option>
                    <option value="New Zealand">New Zealand</option>
                    <option value="Nicaragua">Nicaragua</option>
                    <option value="Niger">Niger</option>
                    <option value="Nigeria">Nigeria</option>
                    <option value="Niue">Niue</option>
                    <option value="Norfolk Island">Norfolk Island</option>
                    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                    <option value="Norway">Norway</option>
                    <option value="Oman">Oman</option>
                    <option value="Pakistan">Pakistan</option>
                    <option value="Palau">Palau</option>
                    <option value="Panama">Panama</option>
                    <option value="Papua New Guinea">Papua New Guinea</option>
                    <option value="Paraguay">Paraguay</option>
                    <option value="Peru">Peru</option>
                    <option value="Philippines">Philippines</option>
                    <option value="Pitcairn">Pitcairn</option>
                    <option value="Poland">Poland</option>
                    <option value="Portugal">Portugal</option>
                    <option value="Puerto Rico">Puerto Rico</option>
                    <option value="Qatar">Qatar</option>
                    <option value="Reunion">Reunion</option>
                    <option value="Romania">Romania</option>
                    <option value="Russian Federation">Russian Federation</option>
                    <option value="Rwanda">Rwanda</option>
                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                    <option value="Saint Lucia">Saint Lucia</option>
                    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                    <option value="Samoa">Samoa</option>
                    <option value="San Marino">San Marino</option>
                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                    <option value="Saudi Arabia">Saudi Arabia</option>
                    <option value="Senegal">Senegal</option>
                    <option value="Serbia">Serbia</option>
                    <option value="Seychelles">Seychelles</option>
                    <option value="Sierra Leone">Sierra Leone</option>
                    <option value="Singapore">Singapore</option>
                    <option value="Slovakia (Slovak Republic)">Slovakia (Slovak Republic)</option>
                    <option value="Slovenia">Slovenia</option>
                    <option value="Solomon Islands">Solomon Islands</option>
                    <option value="Somalia">Somalia</option>
                    <option value="South Africa">South Africa</option>
                    <option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
                    <option value="Spain">Spain</option>
                    <option value="Sri Lanka">Sri Lanka</option>
                    <option value="St. Barthelemy">St. Barthelemy</option>
                    <option value="St. Eustatius">St. Eustatius</option>
                    <option value="St. Helena">St. Helena</option>
                    <option value="St. Pierre and Miquelon">St. Pierre and Miquelon</option>
                    <option value="Sudan">Sudan</option>
                    <option value="Suriname">Suriname</option>
                    <option value="Svalbard and Jan Mayen Islands">Svalbard and Jan Mayen Islands</option>
                    <option value="Swaziland">Swaziland</option>
                    <option value="Sweden">Sweden</option>
                    <option value="Switzerland">Switzerland</option>
                    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                    <option value="Taiwan">Taiwan</option>
                    <option value="Tajikistan">Tajikistan</option>
                    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                    <option value="Thailand">Thailand</option>
                    <option value="The Democratic Republic of Congo">The Democratic Republic of Congo</option>
                    <option value="Togo">Togo</option>
                    <option value="Tokelau">Tokelau</option>
                    <option value="Tonga">Tonga</option>
                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                    <option value="Tunisia">Tunisia</option>
                    <option value="Turkey">Turkey</option>
                    <option value="Turkmenistan">Turkmenistan</option>
                    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                    <option value="Tuvalu">Tuvalu</option>
                    <option value="Uganda">Uganda</option>
                    <option value="Ukraine">Ukraine</option>
                    <option value="United Arab Emirates">United Arab Emirates</option>
                    <option value="United Kingdom" selected="selected">United Kingdom</option>
                    <option value="United States">United States</option>
                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                    <option value="Uruguay">Uruguay</option>
                    <option value="Uzbekistan">Uzbekistan</option>
                    <option value="Vanuatu">Vanuatu</option>
                    <option value="Vatican City State (Holy See)">Vatican City State (Holy See)</option>
                    <option value="Venezuela">Venezuela</option>
                    <option value="Viet Nam">Viet Nam</option>
                    <option value="Virgin Islands (British)">Virgin Islands (British)</option>
                    <option value="Virgin Islands (U.S.)">Virgin Islands (U.S.)</option>
                    <option value="Wallis and Futuna Islands">Wallis and Futuna Islands</option>
                    <option value="Western Sahara">Western Sahara</option>
                    <option value="Yemen">Yemen</option>
                    <option value="Zambia">Zambia</option>
                    <option value="Zimbabwe">Zimbabwe</option>
                </select>
            </div>
        </div>

</div> <!-- end address-group 2 -->

    <?php // Phone Number Section ?>
    <div class="control-group campaign_0" id="field_phone">
        <label class="control-label" for="phone" title="">Phone number
        </label>
        <div class="controls">
            <input type="text" name="phone" id="phone" value="" placeholder="Enter phone number" class="input-large">
        </div>
    </div>

    <?php // Reason Section ?>
    <div class="control-group campaign_0" id="field_reason">
        <label class="control-label" for="reason" title="">Reason for donation</label>
        <div class="controls">
            <textarea name="reason" id="reason" placeholder="Let us know here..."></textarea>
        </div>
    </div>

	<?php // Gift Aid Section ?>
	<div class="control-group gift-aid-title">
		<img src="images/gift-aid.png" alt="Gift Aid It" />
		<h3 class="jd-heading">Increase your gift by <span class="hilight">25%</span> at no extra cost to you</h3>
	</div>
	<div class="control-group campaign_0 gift-aid-content" id="field_gift_aid">
		<?php
		$donation     = $jnput->get('amount', 1, FLOAT);
		$giftAidTotal = $donation * 1.25;
		?>
		<p class="intro">With Gift Aid, your <span class="hilight">£<span class="ga-amount"><?php echo $donation ?></span></span> donation would be worth <span class="hilight">£<span class="ga-worth"><?php echo number_format((float)$giftAidTotal, 2, '.', '') ?></span></span>!</p>
		<label class="control-label" for="gift_aid">
			<div class="controls">
				<input type="checkbox" id="gift_aid1" name="gift_aid[]" value="1">
				<p>Yes, I want to Gift Aid any donations made to The Donna Louise Children’s Hospice now</p>
			</div>
		</label>
		<p class="disclaimer">I am a UK taxpayer and would like The Donna Louise to reclaim tax on the donations
		I have made in the last four years and any future gifts I make. I understand that if
		I pay less Income and/or Capital Gains Tax than the amount claimed on all my donations,
		it is my responsibility to pay any difference.</p>
		<a class="learn-more" href="https://www.gov.uk/donating-to-charity/gift-aid" target="_new">Want to learn more about Gift Aid?</a>
	</div>

</div> <!-- end form wrapper -->

	<?php // Join Us section ?>
	<div class="control-group join-us">
		<h3 class="jd-heading title">Join Us</h3>
		<p class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ac viverra quam. Sed suscipit metus sit amet magna sagittis ultrices. Phasellus cursus leo sit amet augue sagittis impdiet. Vivamus a porta nibh. Donec feugiat elit eu tristique vulputate. Phasellus non ligula imperdiet est tempus elementum.</p>
	</div>
	<div class="control-group campaign_0" id="field_join-email">
		<label class="control-label" for="join-email" title="">Email<span class="required">*</span></label>
		<div class="controls">
			<fieldset id="join-email"><ul class="clearfix"><li class="span12"><label class="radio" for="join-email1"><input type="radio" id="join-email1" name="join-email" value="Yes">Yes</label></li></ul><ul class="clearfix"><li class="span12"><label class="radio" for="join-email2"><input type="radio" id="join-email2" name="join-email" value="No">No</label></li></ul></fieldset>
		</div>
	</div>
	<div class="control-group campaign_0" id="field_join-text">
		<label class="control-label" for="join-text" title="">Text message<span class="required">*</span></label>
		<div class="controls">
			<fieldset id="join-text"><ul class="clearfix"><li class="span12"><label class="radio" for="join-text1"><input type="radio" id="join-text1" name="join-text" value="Yes">Yes</label></li></ul><ul class="clearfix"><li class="span12"><label class="radio" for="join-text2"><input type="radio" id="join-text2" name="join-text" value="No">No</label></li></ul></fieldset>
		</div>
	</div>
	<div class="control-group campaign_0" id="field_join-post">
		<label class="control-label" for="join-post" title="">Post<span class="required">*</span></label>
		<div class="controls">
			<fieldset id="join-post"><ul class="clearfix"><li class="span12"><label class="radio" for="join-post1"><input type="radio" id="join-post1" name="join-post" value="Yes">Yes</label></li></ul><ul class="clearfix"><li class="span12"><label class="radio" for="join-post2"><input type="radio" id="join-post2" name="join-post" value="No">No</label></li></ul></fieldset>
		</div>
	</div>
	<div class="control-group campaign_0" id="field_join-phone">
		<label class="control-label" for="join-phone" title="">Phone<span class="required">*</span>
		</label>
		<div class="controls">
			<fieldset id="join-phone"><ul class="clearfix"><li class="span12"><label class="radio" for="join-phone1"><input type="radio" id="join-phone1" name="join-phone" value="Yes">Yes</label></li></ul><ul class="clearfix"><li class="span12"><label class="radio" for="join-phone2"><input type="radio" id="join-phone2" name="join-phone" value="No">No</label></li></ul></fieldset>
		</div>
	</div>

</div>

	<?php // Process Donation section ?>
	<div class="form-actions">
		<input type="submit" class="btn btn-primary" name="btnSubmit" id="btn-submit" value="Process Donation" /> <?php //echo  JText::_('JD_PROCESS_DONATION') ?>
	</div>
	<?php
		if (count($this->methods) == 1) {
			?><input type="hidden" name="payment_method" value="<?php echo $this->methods[0]->getName(); ?>" /><?php
		 }
		if (!$this->config->enable_recurring) {
			?><input type="hidden" name="donation_type" value="onetime" /><?php
		}
		if (!$this->showCampaignSelection) {
			?><input type="hidden" id="campaign_id" name="campaign_id" value="<?php echo $this->campaignId; ?>" /><?php
		}
	?>
	<input type="hidden" name="validate_form_login" value="<?php echo $validateLoginForm; ?>" />
	<input type="hidden" name="receive_user_id" value="<?php echo $this->input->getInt('receive_user_id'); ?>" />
	<input type="hidden" name="amounts_format" value="<?php echo $this->config->amounts_format; ?>" />
	<input type="hidden" name="field_campaign" value="<?php echo $this->config->field_campaign; ?>" />
	<input type="hidden" name="amount_by_campaign" value="<?php echo $this->config->amount_by_campaign; ?>" />
	<input type="hidden" name="enable_recurring" value="<?php echo $this->config->enable_recurring; ?>" />
	<input type="hidden" name="count_method" value="<?php echo count($this->methods); ?>" />
	<input type="hidden" name="current_campaign" value="<?php echo $this->campaignId; ?>" />
	<input type="hidden" name="donation_page_url" value="<?php echo $this->donationPageUrl; ?>" />
	<input type="hidden" name="task" value="donation.process">
	<?php echo JHtml::_( 'form.token' ); ?>

    <script type="text/javascript">
		var amountInputCssClasses = '<?php echo "validate[required,custom[number] $amountValidationRules ] input-small"; ?>';
		<?php echo os_payments::writeJavascriptObjects() ; ?>
		JD.jQuery(function($){
			$(document).ready(function(){
				$("#os_form").validationEngine('attach', {
					onValidationComplete: function(form, status){
						if (status == true) {
							form.on('submit', function(e) {
								e.preventDefault();
							});

							form.find('#btn-submit').prop('disabled', true);

							if (typeof stripePublicKey !== 'undefined')
							{
								if($('input:radio[name^=payment_method]').length)
								{
									var paymentMethod = $('input:radio[name^=payment_method]:checked').val();
								}
								else
								{
									var paymentMethod = $('input[name^=payment_method]').val();
								}

								if (paymentMethod.indexOf('os_stripe') == 0)
								{
								 Stripe.card.createToken({
								  number: $('#x_card_num').val(),
								  cvc: $('#x_card_code').val(),
								  exp_month: $('select[name^=exp_month]').val(),
								  exp_year: $('select[name^=exp_year]').val(),
								  name: $('#card_holder_name').val()
								 }, stripeResponseHandler);

								 return false;
								}
							}

							return true;
						}
						return false;
					}
				});

				if($("[name*='validate_form_login']").val() == 1)
				{
					JDVALIDATEFORM("#jd-login-form");
				}
				<?php
					if (isset($fields['state']) && JString::strtolower($fields['state']->type) == 'state')
					{
					?>
						buildStateField('state', 'country', '<?php echo $selectedState; ?>');
					<?php
					}
				?>
			})
		});
	</script>
</form>

</div>
