<?php
namespace App;

/**
 * Flash notification messages: messages for one-time display using the session for storage between requests.
 * 
 */
class Flash
{
    /**
     * message type
     * @var string
     */
    const SUCCESS = 'success';
    const INFO = 'info';
    const WARNING = 'warning';

    /**
     * Add a message
     * @param string $message the message content
     * @return void
     */
    public static function addMessage($message, $type = 'success'){
         //create array in the session if it doesn't already exist
         if(!isset($_SESSION['flash_notifications'])){
             $_SESSION['flash_notifications'] =[];
         }

         //append the message to the array
         $_SESSION['flash_notifications'][] = [
             'body' => $message,
             'type' => $type
         ];
    }

    /**
     * Get all the messages
     * @return mixed An array with all the messages or null if none set
     */
    public static function getMessages(){
        if(isset($_SESSION['flash_notifications'])) {
            $messages = $_SESSION['flash_notifications'];
            unset($_SESSION['flash_notifications']);

            return $messages;
        }
    }
}