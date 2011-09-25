<?php
/**
 * CONTACT US
 */

$more_info_links = array
    ( 'terms'           => 'Terms and Conditions'
    , 'privacy'         => 'Privacy Policy'
    , 'notesolution'    => 'Partnership with Notesolution'
    );

?>
<div class="left_column">

    <p>
        Questions or comments about StudyMonkey?
        Please feel free to email us and tell us how we're doing at
        <a href="mailto:info@studymonkey.ca">info@studymonkey.ca</a>.
        Or, use our handy little message box:
    </p>

    <form action="" method="post">
        <table border="0" cellspacing="0" cellpadding="5" style="margin: 20px;">
            <tr>
                <td valign="top" align="right">
                    <label for="subject">Topic</label>
                </td>
                <td valign="top" align="left">
                    <select type="text" id="subject" name="subject" style="width: 308px;">
<?php foreach ($subject_topics as $key => $value) { ?>
                        <option value="<?=$key?>" <?php echo ($inquiry_default == $key)? 'selected' : ''; ?>><?=$value?></option>
<?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td valign="top" align="right">
                    <label for="name">Name</label>
                </td>
                <td valign="top" align="left">
                    <input type="text" id="name" name="name" value="<?=$name?>" style="width: 200px;" />
                </td>
            </tr>
            <tr>
                <td valign="top" align="right">
                    <label for="email">Your Email</label>
                </td>
                <td valign="top" align="left">
                    <input type="text" id="email" name="email" value="<?=$email?>" style="width: 300px;" />
                </td>
            </tr>
            <tr>
                <td valign="top" align="right">
                    <label for="body">Message</label>
                </td>
                <td valign="top" align="left">
                    <textarea style="width: 300px; height: 100px; resize: vertical;" id="body" name="message"><?=$message?></textarea>
                </td>
            </tr>
            <tr>
                <td valign="top" align="right">
                    <label for="captcha">Math is Fun!</label>
                </td>
                <td valign="top" align="left">
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="top" align="center" style="width: 100px;">
                                <img src="/captcha" alt="Captcha!" style="border: 1px solid #000; -moz-border-radius: 5px;" />
                            </td>
                            <td valign="center" align="center" style="width: 40px;">
                                <span style="font: bold 24px arial;">
                                    =
                                </span>
                            </td>
                            <td valign="top" align="left">
                                <input type="text" id="captcha" name="captcha" value="<?=$captcha?>" style="width: 32px; text-align: center;" />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign="top" align="left">
                    &nbsp;
                </td>
                <td valign="top" align="left">
                    <input type="hidden" value="send_message" name="action" />
                    <input type="submit" value="Send!" id="account_submit" onclick="this.blur()" style="width: 125px;"/>
                    &nbsp;
                    &nbsp;
                    <span id="submit_loading_image" style="display: none; color: #352;">Processing... <img src="/image/loading.gif"; ?>" alt="Processing..." /></span>
                </td>
            </tr>
        </table>
    </form>

</div>

<div class="right_column">

    <div style="font: bold 14px arial; padding-bottom: 10px;">More Info:</div>

    <ul>
<?php foreach($more_info_links as $uri => $title) { ?>
        <li style="padding-bottom: 10px;">
            <a href="<?php echo site_url().$uri; ?>">
                <?php echo $title; ?>
            </a>
        </li>
<?php } ?>
    </ul>

</div>
<?php
