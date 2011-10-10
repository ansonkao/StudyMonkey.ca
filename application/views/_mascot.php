<?php
/**
 * MASCOT + SPEECH BUBBLE flash messages
 *
 * This is the "toolbar" where the mascot and his speech bubbles which act as flash messages live.
 */

// SPEECH BUBBLE
$flash = $this->session->flashdata("notification");
if( ! empty( $flash ) )
    $notification = $flash;

?>
    <script>

        // Speech bubble flag
        var speech_bubble_open = true;

        function show_speech_bubble()
        {
            speech_bubble_open = true;
            $("#speech_bubble").stop().hide().css({opacity: 1}).fadeIn(500).fadeOut(12000);
        }

        function new_speech_bubble( message )
        {
            $("#speech_bubble_text").html( message );
            show_speech_bubble();
        }

        $(document).ready(function(){

            // Hover to keep speech bubble alive
            $("#speech_bubble").mouseover(function(){
                if (speech_bubble_open)
                    $(this).stop().css({opacity: 1}).fadeOut(12000);
            });

            // Click to close the speech bubble
            $("#speech_bubble_close").click(function(){
                speech_bubble_open = false;
                $("#speech_bubble").stop().fadeOut(250);
                return false;
            });

            // Click on the mascot to get random messages
            var random_messages = new Array();
            random_messages[0] = "Stop clicking me - it tickles!";
            random_messages[1] = "Ow, that one really hurt!  Stop poking me!";
            random_messages[2] = "Glad you're here! I've been leaning against this pencil all day.";
            random_messages[3] = "Shouldn't you be studying?";
            random_messages[4] = "I could totally go for a banana right now...";
            $("#mascot").click(function(){
                //new_speech_bubble( random_messages[ Math.floor( Math.random() * 5 ) ] );
                new_speech_bubble( random_messages[0] );
            });

        });
    </script>

    <div id="toolbar_wrapper">
        <div id="toolbar">
            <img id="mascot" src="/image/layout/mascot.png" />
            <div id="speech_bubble" class="round_box" <?php echo empty( $notification )? 'style="display: none;"' : ''; ?> >
                <a id="speech_bubble_close" href="#">&times;</a>
                <span id="speech_bubble_text"><?php echo empty( $notification )? "" : $notification->message; ?></span>
                <div id="speech_bubble_tail"></div>
                <div id="speech_bubble_tail_border"></div>
            </div>
        </div>
    </div>
<?php

/* End of file _layout_main.php */
/* Location: ./application/views/_layout_main.php */