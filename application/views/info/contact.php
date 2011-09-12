<?php
/**
 * CONTACT US
 */
?>
<p style="width: 64%;">
    Questions or comments about StudyMonkey?
    Please feel free to email us and tell us how we're doing at
    <a href="mailto:info@studymonkey.ca">info@studymonkey.ca</a>.
    Or, use our handy little message box:
</p>

<form action="" method="post">
    <table border="0" cellspacing="0" cellpadding="5" style="margin: 20px;">
        <tr>
            <td valign="top" align="right">
                <label for="subject">Your Inquiry</label>
            </td>
            <td valign="top" align="left">
                <select type="text" id="subject" name="subject" style="width: 308px;">
<?php foreach ($inquiry_categories as $key => $value) { ?>
                    <option value="<?=$key?>" <?php echo ($inquiry_default == $key)? 'selected' : ''; ?>><?=$value?></option>
<?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td valign="top" align="right">
                <label for="name">Your Name</label>
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
                <label for="body">Your Message</label>
            </td>
            <td valign="top" align="left">
                <textarea style="width: 300px; height: 100px;" id="body" name="message"><?=$message?></textarea>
            </td>
        </tr>
        <tr>
            <td valign="top" align="right">
                <label for="captcha">Math is Fun!</label>
            </td>
            <td valign="top" align="left">
                <table>
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
                            <input type="text" id="captcha" name="captcha" value="<?=$captcha?>" style="height: 24px; width: 40px; text-align: center;" />
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
                <input type="submit" value="Send!" class="input_submit_main nice" id="account_submit" onclick="this.blur()" style="width: 125px;"/>
                &nbsp;
                &nbsp;
                <span id="submit_loading_image" style="display: none; color: #352;">Processing... <img src="/image/loading.gif"; ?>" alt="Processing..." /></span>
            </td>
        </tr>
    </table>
</form>
<?php
