<?php
/**
 * Script name  : login.php
 * Author       : M McIntyre <mike@mak.net.nz>
 * Date created : 6 March 2013
 * Purpose      : Ensures the person is logged in to the voting system.
 **/
include 'includes/application_top.php';
include 'includes/session_start.php';

define('URL_LOGIN_PAGE', 'https://moodle.roehampton.ac.uk/login/index.php');
define('URL_LOGIN_PAGE', 'http://subudemocracy.bournemouth.ac.uk/Login/Login.aspx?Action=SUElection');

$use_posted_vars = false;
// if (  ( $_SERVER['REMOTE_ADDR'] == '202.89.61.45' || strpos($_SERVER['HTTP_VIA'], 'fred.mike-mac.gen.nz' ) !== 0 ) )
// {
//
// 		echo '<div style="background-color:white;color:black;text-align:left;font-size:12px;">' ;
// 		echo '$_SERVER<pre>' ;
// 		print_r($_SERVER) ;
// 		echo '</pre></div>' ;
//
// 	echo "Would have redirected to " . tep_href_link(FILENAME_LOGIN_SUBU) . '<br>' ;
// // 	die(__FILE__ . '::' . __LINE__ ) ;
// }

switch (true) {
    case false && (! array_key_exists('HTTPS', $_SERVER) || strtolower($_SERVER['HTTPS']) != 'on'):

        tep_redirect(tep_href_link(FILENAME_LOGIN_SUBU));
    case $_SERVER['REQUEST_METHOD'] == 'GET':
        break;
    case ! array_key_exists('username', $_POST):
    case ! array_key_exists('password', $_POST):
        // set an error message to tell the person to use the effin form !
        $_SESSION['login_error'] = 'Please use the form properly !';
        tep_redirect(tep_href_link(FILENAME_LOGIN_SUBU));

    case ! tep_not_null($_POST['username']):
    case ! tep_not_null($_POST['password']):
        // set an error message to tell the person to fill in the effin form !
        $_SESSION['login_error'] = 'Please provide both your username and password';
        tep_redirect(tep_href_link(FILENAME_LOGIN_SUBU));

    // we allow the server mak.co|net.net to use the query string "force_login"
    // in all other cases the user must validate with SUBU
    case ! array_key_exists('force_login', $_GET):
    case ! preg_match('/jason\.coders\.kiwi\.nz/', $_SERVER['SERVER_NAME']):
        // make sure the code knows that it must use the posted variables
        $use_posted_vars = true;

        // okay, username and password were posted to the page
        // we need to head off to SUBU to verify the inputs

        // it's an ASP form so there are control variables in the form. We need to get the form, get all inputs for the form, get
        // default values for the form, then repopulate the form with the data we received.

        // create a cookie file just in case SUBU uses it
        $cookie_file = tempnam(null, 'cookie');

        // create a cURL object
        $ch = curl_init(URL_LOGIN_PAGE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, CURLOPT_FOLLOWLOCATION);

        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);

        $result = curl_exec($ch);

        // if there is an error getting the page
        if (is_bool($result)) {
            // set an error saying there was a problem communicating with SUBU
            tep_redirect(tep_href_link(FILENAME_LOGIN_SUBU));
        }

        // put the form into a doc document so we can parse it
        $form_doc = new DOMDocument;

        // retrieve the inputs from the form
        @$form_doc->loadHTML($result);

        $form_query = new DOMXpath($form_doc);
        // get inputs that have a name
        $recipe = '//form[@id="login"]';
        $form_object = $form_query->query($recipe);
        $form_object_item = $form_object->item(0);

        $recipe = '//input[@name]';
        $form_items = $form_query->query($recipe, $form_object_item);

        $form_inputs = [];
        foreach ($form_items as $form_item) {
            $form_inputs[$form_item->getAttribute('name')] = $form_item->getAttribute('value');
        }

        $input_mapping = [];
        $input_mapping['username'] = 'ctl00$ContentPlaceHolder1$txtUserName';
        $input_mapping['password'] = 'ctl00$ContentPlaceHolder1$txtPassword';

        $input_mapping['username'] = 'username';
        $input_mapping['password'] = 'password';

        foreach ($input_mapping as $local_input => $remote_input) {
            if (! array_key_exists($remote_input, $form_inputs)) {
                // set a system error message
                tep_redirect(tep_href_link(FILENAME_LOGIN_SUBU));
            }
            $form_inputs[$remote_input] = $_POST[$local_input];
        }

        // get the target of the form
        $form_target = 'http://subudemocracy.bournemouth.ac.uk/Login/'.$form_object_item->getAttribute('action');
        $form_target = $form_object_item->getAttribute('action');

        // alter the cURL object to use the new form target
        curl_setopt($ch, CURLOPT_URL, $form_target);

        // alter the cURL object to POST
        curl_setopt($ch, CURLOPT_POST, true);

        // add the POST variables to the cURL object
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($form_inputs));

        // allow @ at the beginning of a field
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);

        // post the form back
        $result = curl_exec($ch);

        // if the result is a bad one
        if (is_bool($result)) {
            // set an error saying there was a problem communicating with SUBU
            $_SESSION['login_error'] = 'There was an error trying to validate your login credentials. Please try again';
            tep_redirect(tep_href_link(FILENAME_LOGIN_SUBU));
            // fi
        }

        // if there is a node form[name="aspnetForm"]
        @$form_doc->loadHTML($result);

        $form_query = new DOMXpath($form_doc);
        // get inputs that have a name
        $recipe = '//form[@id="aspnetForm"]//input[@name="ctl00$ContentPlaceHolder1$txtPassword"]';
        $recipe = '//form[@id="login"]//input[@name="username"]';
        $form_object = $form_query->query($recipe);
        if ($form_object->length > 0) {
            // tell the person they failed to log in
            $_SESSION['login_error'] = 'Invalid username and/or password';
            tep_redirect(tep_href_link(FILENAME_LOGIN_SUBU));
        }
    // fi

    default:
        // login was successful

        if ($use_posted_vars) {
            // 			$_SESSION['student_id'] = $_POST['username'] ;
            // 			$_SESSION['student_name'] = $_POST['hdnStudent'] ;
            $_SESSION['student_id'] = $_POST['username'];
            $_SESSION['student_name'] = 'Anonymous student';

            $recipe = '//div[@class="usermenu"]/ul[@class="nav"]/li[@class="dropdown"]/a[@class="dropdown-toggle"]';
            $name_object = $form_query->query($recipe);
            if ($name_object->length > 0) {
                $_SESSION['student_name'] = $name_object->item(0)->nodeValue;
            }
        } else {
            $_SESSION['student_id'] = 'i7833547';
            if (array_key_exists('student_id', $_GET)) {
                $_SESSION['student_id'] = $_GET['student_id'];
            }
            $_SESSION['student_name'] = 'Dummy Student';
        }

// define('DIE_NOW', '1') ;
        $redirect_to = tep_href_link('vote.php', '', 'NONSSL');

        tep_redirect($redirect_to);

}

if (TEST_REQUEST == 'yes' && array_key_exists('force_login', $_GET)) {
    $_SESSION['student_id'] = 'i7833547';
    if (array_key_exists('student_id', $_GET)) {
        $_SESSION['student_id'] = $_GET['student_id'];
    }
    $_SESSION['student_name'] = 'Dummy Student';

    // define('DIE_NOW', '1') ;
    $redirect_to = tep_href_link('vote.php', '', 'NONSSL');

    tep_redirect($redirect_to);
}

// add the content to the page class
// get the welcome content from the database
$page_template->load_template('login.tpl.html');

// $page_template->load_db_content('welcome', 'main_page') ;
$page_template->add_jquery();
$page_template->add_js_script('<script type="text/javascript" src="{template_dir}snippets/login_helper.jquery.js"></script>');

$page_template->load_snippet_content('subu_login_form.tpl.html', 'main_page');
if (array_key_exists('login_error', $_SESSION)) {
    $page_template->set_single_snippet($_SESSION['login_error'], 'error_message');
    unset($_SESSION['login_error']);
}
// dump the page to std out
echo $page_template->get_template_html();
