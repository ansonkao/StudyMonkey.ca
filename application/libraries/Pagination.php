<?php
////////////////////////////////////////////////////////////////////////////////
// STUDYMONKEY.CA | Pagination
////////////////////////////////////////////////////////////////////////////////
/* This is our custom pagination class which tracks pagination parameters,
 * outputs them to html pages based on those parameters, and on subsequent
 * page loads gets the parameters passed through http-get.
 *
 * Much easier to use than original version implemented for TeamBuy.
 */

class Pagination {

    private $min_adjacent_pages = 2;
    private $min_end_pages = 1;
    public $current_page = 1;
    public $rows_per_page = 5;
    public $total_rows = 0;
    public $order_by = 'id';            // sorting of the records
    public $sort_descending = true;     // direction of sorting
    public $search;                     // for storing search terms
    public $sql_params;                 // sql version of $search for the query
    public $order_by_relevance;         // special ORDER BY command for $order_by = relevance
    public $parent_uri;                 // the string to add pagination params to

    public function __construct( $params = array() )
    {
        foreach ($params as $field => $value) {
            switch($field) {
                case 'page':
                    $this->current_page = $value;
                    break;
                case 'sort':
                    $this->order_by = $value;
                    break;
                case 'desc':
                    $this->sort_descending = ($value == 'true')? true : false;
                    break;
                case 'search':
                    $this->search = $value;
                    break;
                case 'rows_per_page':
                    $this->rows_per_page = $value;
                    break;
                case 'total_rows':
                    $this->total_rows = $value;
                    break;
                case 'current_page':
                    $this->current_page = $value;
                    break;
                case 'parent_uri':
                    $this->parent_uri = $value;
                    break;
            }
        }
    }

    // =========================================================================
    // PRIVATE FUNCTIONS

    private function total_pages()
    {
        return ceil($this->total_rows/$this->rows_per_page);
    }
    private function previous_page()
    {
        return $this->current_page -1;
    }
    private function next_page()
    {
        return $this->current_page +1;
    }
    private function has_previous_page()
    {
        return $this->previous_page() >= 1 ? true : false;
    }
    private function has_next_page()
    {
        return $this->next_page() <= $this->total_pages() ? true : false;
    }

    // For human reading of the current positions
    private function current_first_row()
    {
        return ($this->current_page - 1) * $this->rows_per_page + 1;
    }
    private function current_last_row()
    {
        $return_value = $this->current_page * $this->rows_per_page;
        if ($return_value < $this->total_rows)
        {
            return $return_value;
        }
        else
        {
            return $this->total_rows;
        }
    }

    /* Distance to current_page frequired for SQL LIMIT parameter.
     * Same as current_first_row() but zero based.
     */
    private function offset()
    {
        return ($this->current_page -1) * $this->rows_per_page;
    }

    /* Returns get parameters in url form.
     * Based on parameters saved in the object and an argument specifying
     * the column to be sorted, the method outputs the appropriate string
     * to append to the page's url to apply sorting to that column.
     */
    private function get_sort_params ($column, $page = 1, $toggle = true)
    {
        $result = "";
        $result .= $this->parent_uri;
        $result .= "/{$page}";
        return $result;
    }

    // =========================================================================
    // PUBLIC FUNCTIONS

    public function display_current_positions()
    {
        if ($this->total_rows > 0) {
            echo "Showing <b>{$this->current_first_row()}</b> ";
            echo "to <b>{$this->current_last_row()}</b> ";
            echo "out of <b>{$this->total_rows}</b> results";
        } else {
            echo "Showing <b>0</b> results";
        }
    }

    public function display_page_links( $additional_get_params = '' )
    {
        // PREVIOUS PAGE
        if ($this->has_previous_page())
        {
            echo "<a href='{$this->get_sort_params($this->order_by, $this->previous_page(), false)}{$additional_get_params}' class='pagination on'>&#171; Previous</a>\n";
        }
        else
        {
            echo "<span class='pagination off'>&#171; Previous</span>\n";
        }
        echo "|\n";

        // LOOP THROUGH EACH PAGE
        for ($i = 1; $i <= $this->total_pages(); $i++)
        {
            // SKIP PAGES IF THERE ARE TOO MANY BEFORE CURRENT
            if ($i > $this->min_end_pages && $i + ($this->min_adjacent_pages + 1) < $this->current_page)
            {
                $i = $this->current_page - ($this->min_adjacent_pages + 1);
                echo "...\n";
            // SKIP PAGES IF THERE ARE TOO MANY AFTER CURRENT
            }
            else if ($this->current_page + $this->min_adjacent_pages < $i && $i + $this->min_end_pages < $this->total_pages())
            {
                $i = $this->total_pages() - ($this->min_end_pages);
                echo "...\n";
            // ELSE PRINT $i'th PAGE LINK
            }
            else
            {
                if ($i == $this->current_page)
                {
                    echo "<span class='pagination current'>{$i}</span>\n";
                }
                else
                {
                    echo "<a href='{$this->get_sort_params($this->order_by, $i, false)}{$additional_get_params}' class='pagination on'>{$i}</a>\n";
                }
            }
            // PRINT VERTICAL SEPARATOR
            echo "|\n";
        }

        // NEXT PAGE
        if ($this->has_next_page())
        {
            echo "<a href='{$this->get_sort_params($this->order_by, $this->next_page(), false)}{$additional_get_params}' class='pagination on'>Next &#187;</a>\n";
        }
        else
        {
            echo "<span class='pagination off'>Next &#187;</span>\n";
        }
    }

    /* Prints to the html an anchor link for sorting a particular column
     * in an html table.
     * This is used to convert the headings of a html table into sorting links.
     */
    public function display_sort_link ($column, $link_text)
    {
        $result = "";
        $result .= "<a href='{$this->get_sort_params($column)}' class='list_sort_link'>";
        $result .= $link_text;
        if ( $this->order_by == $column )
        {
            $result .= " <img src='" . CLIENT_HTTP . "/_include/layout/image/";
            $result .= ($this->sort_descending)? 'down' : 'up';
            $result .= ".gif' style='margin-bottom: 2px;'>";
        }
        $result .= "</a>";
        echo $result;
    }

    /* This function performs any necessary corrections to make sure the
     * pagination is valid.  Invalid paginations can result from certain corner
     * cases (TODO) or from cracker wanker donkeys fiddling with our site.
     */
    public function validate()
    {
        // Reset the current_page if bad
        // (this can happen when the rows_per_page is changed dynamically)
        if ($this->current_page > $this->total_pages() || $this->current_page < 1) {
            $this->current_page = 1;
        }
    }
}
