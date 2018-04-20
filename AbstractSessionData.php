<?php

/**
 * Class for retrieving session data
 * 
 * Features:
 * Retrieving of all session data saved by PHP, not just the current user
 * Encoding and decoding in PHP session format (not serialize|unserialize)
 * 
 * @author gabe@fijiwebdesign.com
 * @link http://www.fijiwebdesign.com/
 * 
 * This class must be implemented for each Session Handler as retrieving session data will be different for each
 * 
 */
abstract class AbstractSessionData
{
  
  /**
   * Retrieve a single instance of this class
   * 
   * @usage 
   * 
   * $SessionData = AbstractSessionData::singleton('FileSessionData');
   * 
   */
  public static function singleton(array $args = array(), string $classname = null)
  {
    static $instance = array();
    if (!$classname) {
      $classname = get_called_class();
    }
    if (!isset($instance[$classname])) 
    {
      $instance[$classname] = new $classname;
    }
    
    return $instance[$classname];
  }
  
  /**
   * Get the session Object for a session id
   * 
   * @param string Session ID
   * 
   * @return array|Bool
   */
  abstract public function get($sess_id);
  
  /**
   * Save the session Object for a session id
   * 
   * @param string Session ID
   * @param array Session Object
   * 
   * @return Bool
   */
  abstract public function set($sess_id, array $sess);
  
  /**
   * Unset a session
   * 
   * @param string Session ID
   * 
   * @return Bool
   */
  abstract public function un_set($sess_id);
  
  /**
   * Returns if a session exists given it's ID
   * 
   * @param string Session ID
   * 
   * @return Bool
   */
  abstract public function is_set($sess_id);
  /**
   * Write raw session data to a session
   * 
   * @param string Session Id
   * @param string Session encoded data
   * 
   * @return Bool Write result
   */
  abstract public function write($sess_id, $data);
  
  /**
   * Read raw session data from a session
   * 
   * @param string Session Id
   * 
   * @return string Session Data
   */
  abstract public function read($sess_id);
  
  /**
   * Retrieve all session Ids
   * 
   * @return array
   */
  abstract public function getIds();
  
  
  /**
   * Decode/Unserialize encoded session data string
   * 
   * @param string Session encoded data
   * 
   * @return string Session Object
   */
  public static function decode($data) 
  {
    // save current session var and empty it
    $sess_orig = $_SESSION;
    $_SESSION = array();
    
    // decode session data to $_SESSION
    session_decode($data);
    
    // restore original session
    $sess = $_SESSION;
    $_SESSION = $sess_orig; 
    
    return $sess;
    
  }
  
  /**
   * Encode/Serialize Object in session format
   * 
   * @param array Session Object
   * 
   * @return string Session encoded data
   */
  public static function encode(array $sess) 
  {
    // save current session var
    $sess_orig = $_SESSION;
    $_SESSION = $sess;
    
    // decode session data in $_SESSION
    $data = session_encode();
    
    // restore original session
    $_SESSION = $sess_orig; 
    
    return $data;
    
  }
  
}
