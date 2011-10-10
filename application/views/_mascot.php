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
            $("#speech_bubble").stop().hide().fadeIn(750).fadeOut(8000);
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
                    $(this).stop().css({opacity: 1}).fadeOut(8000);
            });

            // Click to close the speech bubble
            $("#speech_bubble_close").click(function(){
                speech_bubble_open = false;
                $("#speech_bubble").stop().fadeOut(250);
                return false;
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