<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';
require APPPATH.'/libraries/WolframAlphaEngine.php';
require APPPATH.'/libraries/panacea_api.php';

class Api extends REST_Controller
{
    function ask_post() {
        $message = array('question' => $this->post('question'));
        $answer = $this->_ask_wolfram($message['question']);
        $res = "Question: ".$message['question'].". Answer: ".$answer;
        
        $this->response($res, 200); // 200 being the HTTP response code
    }

    function _ask_wolfram ($question) {
		$appID = '6V7YLR-6YX73LGQ6U';

		// instantiate an engine object with your app id
		$engine = new WolframAlphaEngine( $appID );

		// we will construct a basic query to the api with the input 'pi'
		// only the bare minimum will be used
		$response = $engine->getResults( $question );

		// we can check if there was an error from the response object
		/*
		if ( $response->isError ) {
			echo "An error occurred";
			die();
		}
		*/
		var_dump($response);
		if ( count($response->getPods()) > 0 ) {
			foreach ( $response->getPods() as $pod ) {
				$title = trim($pod->attributes['id']);
				if ($title == 'Result') {
					// each pod can contain multiple sub pods but must have at least one
					foreach ( $pod->getSubpods() as $subpod ) {
					// if format is an image, the subpod will contain a WAImage object
						return $subpod->plaintext;
					}//end foreach subpod
				}//end if result
			}//end foreach pod
		}//end get pods
    }//end ask wolfram
}
