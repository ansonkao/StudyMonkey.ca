<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Course Search Results
 */

$num_results = sizeof( $courses );

?>
    <table border="0" cellspacing="10" cellpadding="0" style="width: 520px; margin: 0 auto;">
        <tr>
            <td align="left" colspan="2" style="font: italic bold 14px arial; color: #888; padding-bottom: 5px;"><?php
                if( $page > 1 )
                {
                    switch( $num_results )
                    {
                        case 0:
                            echo "No more results for \"{$previous_query}\"";
                            break;
                        case 1:
                            echo "Showing the last result for \"{$previous_query}\"";
                            break;
                        default:
                            echo "Showing the last {$num_results} results for \"{$previous_query}\"";
                            break;
                        case ITEMS_PER_PAGE:
                            echo "Showing ".ITEMS_PER_PAGE." more results for \"{$previous_query}\".";
                            break;
                    }
                }
                else
                {
                    switch( $num_results )
                    {
                        case 0:
                            echo "No results for \"{$previous_query}\"";
                            break;
                        case 1:
                            echo "Showing 1 result for \"{$previous_query}\"";
                            break;
                        default:
                            echo "Showing {$num_results} results for \"{$previous_query}\"";
                            break;
                        case ITEMS_PER_PAGE:
                            echo "Showing the first ".ITEMS_PER_PAGE." results for \"{$previous_query}\" - try refining your search.";
                            break;
                    }
                }
            ?></td>
        </tr>
<?php foreach( $courses as $course ) { ?>
        <tr>
            <td align="left" valign="top" style="width: 130px;">
<?php       if( $course['overall_rating'] == NULL ) { ?>
                <div style="padding: 2px 0 0 2px; font: bold 14px arial; color: #BBB;">
                    (No reviews yet)
                </div>
<?php       } else { ?>
                <div style="height: 24px; width: 120px; background: transparent url('<?php echo site_url()."image/rating/star_rating.gif"; ?>') repeat-x scroll top; text-align: left;">
                    <div style="height: 24px; width: <?php if ($course['overall_rating']) { echo round($course['overall_rating'] * 120.0 / 5.0); } else { echo "0"; } ?>px; background: transparent url('<?php echo site_url()."image/rating/star_rating.gif"; ?>') repeat-x scroll left bottom;">
                        &nbsp;
                    </div>
                </div>
                <div style="padding-left: 5px; font-size: 11px;"><?php
                    switch( $course['total_reviews'] )
                    {
                        case 0:
                            echo "No reviews yet";
                            break;
                        case 1:
                            echo "Based on 1 review";
                            break;
                        default:
                            echo "Based on {$course['total_reviews']} reviews";
                            break;
                    }
                ?></div>
<?php       } ?>
            </td>
            <td align="left" valign="top">
                <a href="<?php echo site_url().string2uri($school['full_name'])."/courses/".string2uri($course['course_code']); ?>" style="font-size: 16px;">
                    <strong><?php echo $course['course_code']; ?></strong>
                </a>
                <div style="max-width: 320px; font-size: 14px;"><?php echo $course['course_title']; ?></div>
                <div style="font-size: 11px; color: #888;"><?php echo $school['full_name']; ?></div>
            </td>
        </tr>
<?php } ?>
<?php if( $num_results == ITEMS_PER_PAGE AND $page > 1 ) { ?>
        <tr>
            <td align="left">
                <form name="show_more" method="post">
                    <input type="hidden" name="page" value="<?=$page - 1?>" />
                    <input type="hidden" name="search" value="<?=$previous_query?>" />
                    <input type="submit" value="&#171; Previous <?=ITEMS_PER_PAGE?>" />
                </form>
            </td>
            <td align="right">
                <form name="show_more" method="post">
                    <input type="hidden" name="page" value="<?=$page + 1?>" />
                    <input type="hidden" name="search" value="<?=$previous_query?>" />
                    <input type="submit" value="Next <?=ITEMS_PER_PAGE?> &#187;" />
                </form>
            </td>
        </tr>
<?php } else if( $num_results == ITEMS_PER_PAGE ) { ?>
        <tr>
            <td align="right" colspan="2">
                <form name="show_more" method="post">
                    <input type="hidden" name="page" value="<?=$page + 1?>" />
                    <input type="hidden" name="search" value="<?=$previous_query?>" />
                    <input type="submit" value="Next <?=ITEMS_PER_PAGE?> results &#187;" />
                </form>
            </td>
        </tr>
<?php } else if( $page > 1 ) { ?>
        <tr>
            <td align="left" colspan="2">
                <form name="show_more" method="post">
                    <input type="hidden" name="page" value="<?=$page - 1?>" />
                    <input type="hidden" name="search" value="<?=$previous_query?>" />
                    <input type="submit" value="&#171; Previous <?=ITEMS_PER_PAGE?>" />
                </form>
            </td>
        </tr>
<?php } ?>
    </table>



<?php

/* End of file course_search_result.php */
/* Location: ./application/views/course/course_search_result.php */