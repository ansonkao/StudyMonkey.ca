<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * PHP MATH CAPTCHA
 * Copyright (C) 2010  Constantin Boiangiu  (http://www.php-help.ro)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 **/

/**
 * @author Constantin Boiangiu
 * @link http://www.php-help.ro
 *
 * This script is provided as-is, with no guarantees.
 */

/**
 * Implemented in CodeIgniter by Anson Kao Sept 11 2011
 */

class Captcha extends CI_Controller {

    public function index()
    {
        //===============================================================
        // General captcha settings
        //===============================================================
        $captcha_w = 100;                   // captcha width
        $captcha_h = 26;                    // captcha height
        $min_font_size = 21;                // minimum font size; each operation element changes size
        $max_font_size = 24;                // maximum font size
        $angle = 10;                        // rotation angle
        $bg_size = 13;                      // background grid size
        $operators = array('+');            // array of possible operators
        $first_num = rand(1,9);             // first number random value; keep it lower than $second_num
        $second_num = rand(1,9);            // second number random value
        $font_path = APPPATH.'controllers/captcha_font.ttf';
                                            // path to font - needed to display the operation elements

        //===============================================================
        // From here on you may leave the code intact unless you want
        // or need to make it specific changes.
        //===============================================================

        shuffle($operators);
        $expression = $second_num.$operators[0].$first_num;

        // operation result is stored in $session_var
        eval("\$captcha_answer=".$second_num.$operators[0].$first_num.";");

        // save the operation result in session to make verifications
        $this->session->set_flashdata('captcha_answer', $captcha_answer);

        // start the captcha image
        $img = imagecreate( $captcha_w, $captcha_h );

        // Some colors. Text is $black, background is $white, grid is $grey
        $black = imagecolorallocate($img,0,0,0);
        $white = imagecolorallocate($img,255,255,255);
        $grey  = imagecolorallocate($img,215,215,215);

        // make the background white
        imagefill( $img, 0, 0, $white );

        // the background grid lines - vertical lines
        for ($t = $bg_size; $t < $captcha_w; $t += $bg_size) {
                imageline($img, $t, 0, $t, $captcha_h, $grey);
        }
        // background grid - horizontal lines
        for ($t = $bg_size; $t < $captcha_h; $t += $bg_size) {
                imageline($img, 0, $t, $captcha_w, $t, $grey);
        }

        // This determinates the available space for each operation element
        // it's used to position each element on the image so that they don't overlap
        $item_space = $captcha_w/3;

        // first number
        imagettftext(
            $img,
            rand(
                $min_font_size,
                $max_font_size
            ),
            rand( -$angle , $angle ),
            rand( 10, $item_space-20 ),
            rand( 20, $captcha_h-5 ),
            $black,
            $font_path,
            $second_num);

        // operator
        imagettftext(
            $img,
            rand(
                $min_font_size,
                $max_font_size
            ),
            rand( -$angle, $angle ),
            rand( $item_space, 2*$item_space-20 ),
            rand( 20, $captcha_h-5 ),
            $black,
            $font_path,
            $operators[0]);

        // second number
        imagettftext(
            $img,
            rand(
                $min_font_size,
                $max_font_size
            ),
            rand( -$angle, $angle ),
            rand( 2*$item_space, 3*$item_space-20),
            rand( 20, $captcha_h-5 ),
            $black,
            $font_path,
            $first_num);

        // image is .jpg
        header("Content-type:image/jpeg");
        // name is secure.jpg
        header("Content-Disposition:inline ; filename=secure.jpg");
        // output image
        imagejpeg($img);
    }
}

/* End of file info.php */
/* Location: ./application/controllers/info.php */