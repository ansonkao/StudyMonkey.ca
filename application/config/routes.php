<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/


$route['default_controller'] = "info";
$route['404_override'] = '';


/******************************************************************************/
//////////////////////////////////// CUSTOM ////////////////////////////////////
/******************************************************************************/

// Info Controller
$route['contact']       = 'info/contact';
$route['privacy']       = 'info/privacy';
$route['terms']         = 'info/terms';
$route['notesolution']  = 'info/notesolution';

// Course Controller
$route['(:any)/courses/autocomplete']       // http://www.studymonkey.ca/university-of-waterloo/courses/create
    = 'course/autocomplete/$1';             // http://www.studymonkey.ca/courses/autocomplete       ... $1 = "university-of-waterloo"
$route['(:any)/courses/create']             // http://www.studymonkey.ca/university-of-waterloo/courses/create
    = 'course/create/$1';                   // http://www.studymonkey.ca/courses/create             ... $1 = "university-of-waterloo"
$route['(:any)/courses/(:any)']             // http://www.studymonkey.ca/university-of-waterloo/courses/psych101
    = 'course/view/$1/$2';                  // http://www.studymonkey.ca/courses/view               ... $1 = "university-of-waterloo" $2 = "psych101"
$route['(:any)/courses']                    // http://www.studymonkey.ca/university-of-waterloo/courses
    = 'course/search/$1';                   // http://www.studymonkey.ca/courses/search             ... $1 = "university-of-waterloo"

// Professor Controller
$route['(:any)/professors/autocomplete']    // http://www.studymonkey.ca/university-of-waterloo/professors/create
    = 'professor/autocomplete/$1';          // http://www.studymonkey.ca/professors/autocomplete    ... $1 = "university-of-waterloo"
$route['(:any)/professors/create']          // http://www.studymonkey.ca/university-of-waterloo/professors/create
    = 'professor/create/$1';                // http://www.studymonkey.ca/professors/create          ... $1 = "university-of-waterloo"
$route['(:any)/professors/(:any)']          // http://www.studymonkey.ca/university-of-waterloo/professors/mary-ann_vaughan
    = 'professor/view/$1/$2';               // http://www.studymonkey.ca/professors/view            ... $1 = "university-of-waterloo" $2 = "mary-ann_vaughan"
$route['(:any)/professors']                 // http://www.studymonkey.ca/university-of-waterloo/professors
    = 'professor/search/$1';                // http://www.studymonkey.ca/professors/search          ... $1 = "university-of-waterloo"

// Ratings Controller
$route['(:any)/course-professor-review']    // http://www.studymonkey.ca/university-of-waterloo/course-professor-review
    = 'review/course_professor/$1';         // http://www.studymonkey.ca/review/course_professor    ... $1 = "university-of-waterloo"

/* End of file routes.php */
/* Location: ./application/config/routes.php */