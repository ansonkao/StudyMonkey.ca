<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * School Search Results
 */
?>
    <table border="0" cellspacing="10" cellpadding="0" style="width: 520px; margin: 20px auto 0;">
        <tr>
            <td align="left" colspan="2" style="font: italic bold 14px arial; color: #888; padding-bottom: 5px;"><?php
                $num_results = sizeof($schools);

                switch( $num_results )
                {
                    case 0:
                        echo "No results for \"{$query}\"";
                        break;
                    case 1:
                        echo "Showing 1 result for \"{$query}\"";
                        break;
                    default:
                        echo "Showing {$num_results} results for \"{$query}\"";
                        break;
                    case 10:
                        echo "Showing the first 10 results for \"{$query}\" - try refining your search.";
                        break;
                }
            ?></td>
        </tr>
<?php foreach( $schools as $school ) { ?>
        <tr>
            <td align="left" valign="top">
                <a href="<?php echo site_url( $school['uri']."/courses" ); ?>" style="font-size: 16px;"><?php echo $school['full_name']; ?></a>
            </td>
        </tr>
<?php } ?>
    </table>

    <div style="margin: 20px auto; width: 500px; text-align: left;">
        Can't find your school? <a href="/contact">Request it!</a>
    </div>
<?php

/* End of file school_search_result.php */
/* Location: ./application/views/school/school_search_result.php */