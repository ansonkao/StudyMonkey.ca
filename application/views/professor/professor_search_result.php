<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Professor Search Results
 */

$num_results = sizeof( $professors );

?>
    <table border="0" cellspacing="10" cellpadding="0" style="width: 520px; margin: 0 auto;">
        <tr>
            <td align="left" colspan="2" style="font: italic bold 14px arial; color: #888; padding-bottom: 5px;"><?php
                // Presentation if viewing page 2 or higher
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

                // Presentation if viewing first page
                else if( $total_professors > ITEMS_PER_PAGE )
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

                // Presentation if this school has only 10 or less professors
                else
                {
                    switch( $num_results )
                    {
                        case 0:
                            echo 'There are no professor ratings for this school yet. <a href="#add-professor" class="add_professor_lightbox">Add a professor!</a>';
                            break;
                        default:
                            echo "Showing all professors at this school.";
                            break;
                    }
                }
            ?></td>
        </tr>
<?php foreach( $professors as $professor ) { ?>
        <tr>
            <td align="left" valign="top" style="width: 130px;">
<?php       if( $professor['overall_rating'] == NULL ) { ?>
                <div style="padding: 2px 0 0 2px; font: bold 14px arial; color: #BBB;">
                    (No reviews yet)
                </div>
<?php       } else { ?>
                <div style="height: 24px; width: 120px; background: transparent url('<?php echo site_url()."image/rating/star_rating.gif"; ?>') repeat-x scroll top; text-align: left;">
                    <div style="height: 24px; width: <?php if ($professor['overall_rating']) { echo round($professor['overall_rating'] * 120.0 / 5.0); } else { echo "0"; } ?>px; background: transparent url('<?php echo site_url()."image/rating/star_rating.gif"; ?>') repeat-x scroll left bottom;">
                        &nbsp;
                    </div>
                </div>
                <div style="padding-left: 5px; font-size: 11px;"><?php
                    switch( $professor['total_reviews'] )
                    {
                        case 0:
                            echo "No ratings yet";
                            break;
                        case 1:
                            echo "Based on 1 rating";
                            break;
                        default:
                            echo "Based on {$professor['total_reviews']} ratings";
                            break;
                    }
                ?></div>
<?php       } ?>
            </td>
            <td align="left" valign="top">
                <a href="<?php echo site_url( $school['uri']."/professors/".$professor['uri'] ); ?>" style="font-size: 16px;">
                    <strong><?php echo $professor['last_name'].", ".$professor['first_name']; ?></strong>
                </a>
                <div style="max-width: 320px; font-size: 14px;"><?php echo $professor['department']; ?></div>
                <div style="font-size: 11px; color: #888;"><?php echo $school['full_name']; ?></div>
            </td>
        </tr>
<?php } ?>
<?php
    // Show previous/next page buttons?
    if( $total_search_results > ITEMS_PER_PAGE )
    {
?>
        <tr>
<?php
        // Show previous page button
        if( $page > 1 ) {
?>
            <td align="left">
                <form name="show_more" method="post">
                    <input type="hidden" name="page" value="<?=$page - 1?>" />
                    <input type="hidden" name="search" value="<?=$previous_query?>" />
                    <input type="submit" value="&#171; Previous <?=ITEMS_PER_PAGE?>" />
                </form>
            </td>
<?php
            // Show next page button ALSO
            if( $num_results == ITEMS_PER_PAGE AND ( $total_search_results % ITEMS_PER_PAGE > 0 ) ) {
?>
            <td align="right">
                <form name="show_more" method="post">
                    <input type="hidden" name="page" value="<?=$page + 1?>" />
                    <input type="hidden" name="search" value="<?=$previous_query?>" />
                    <input type="submit" value="Next <?=ITEMS_PER_PAGE?> &#187;" />
                </form>
            </td>
<?php
            // Show spacer, no next page button
            } else {
?>
            <td align="right">
            </td>
<?php
            }

        // No previous page
        } else {
?>
            <td align="right" colspan="2">
                <form name="show_more" method="post">
                    <input type="hidden" name="page" value="<?=$page + 1?>" />
                    <input type="hidden" name="search" value="<?=$previous_query?>" />
                    <input type="submit" value="Next <?=ITEMS_PER_PAGE?> results &#187;" />
                </form>
            </td>
<?php
        }
?>
        </tr>
<?php
    }
?>
    </table>



<?php

/* End of file professor_search_result.php */
/* Location: ./application/views/professor/professor_search_result.php */