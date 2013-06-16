<?php
class Zend_View_Helper_Alert
{
	public function Alert($translator = NULL)
	{
		// Set up some variables, including the retrieval of all flash messages.
		$messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages();
		$statMessages = array();
		$output = '';
	
		// If there are no messages, don't bother with this whole process.
		if (count($messages) > 0)
		{
			// This chunk of code takes the messages (formatted as in the above sample
			// input) and puts them into an array of the form:
			//    Array(
			//        [status1] => Array(
			//            [0] => "Message 1"
			//            [1] => "Message 2"
			//        ),
			//        [status2] => Array(
			//            [0] => "Message 1"
			//            [1] => "Message 2"
			//        )
			//        ....
			//    )
			foreach ($messages as $message)
			{
				if (!array_key_exists($message['status'], $statMessages))
					$statMessages[$message['status']] = array();

				if ($translator != NULL && $translator instanceof Zend_Translate)
					array_push($statMessages[$message['status']], $translator->_($message['message']));
				else
					array_push($statMessages[$message['status']], $message['message']);
			}
			
			// This chunk of code formats messages for HTML output (per
			// the example in the class comments).
			foreach ($statMessages as $status => $messages)
			{
				$output .= '<div class="alert alert-block alert-' . $status . '"><a href="#" class="close" data-dismiss="alert">&times;</a>';
				
				// If there is only one message to look at, we don't need to deal with
				// ul or li - just output the message into the div.
				if (count($messages) == 1)
					$output .=  $messages[0];
					
				// If there are more than one message, format it in the fashion of the
				// sample output above.
				else
				{
					$output .= '<ul>';
					foreach ($messages as $message)
						$output .= '<li>' . $message . '</li>';
					$output .= '</ul>';
				}
				
				$output .= '</div>';
			}
			
			// Return the final HTML string to use.
			return $output;
		}
		
	}
}