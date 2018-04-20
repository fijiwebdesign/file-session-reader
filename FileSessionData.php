/**
 * Class for retrieving session data when save handler is files
 * 
 * Features:
 * Retrieving of all session data saved by PHP, not just the current user
 * Encoding and decoding in PHP session format (not serialize|unserialize)
 * 
 * @author gabe@fijiwebdesign.com
 * @link http://www.fijiwebdesign.com/
 * 
 */
class FileSessionData extends AbstractSessionData
{
  
  /**
   * @var array Sesssion Files
   */
  static $sess_files;
  /**
   * @var array Session Ids
   */
  static $sess_ids;
  
  /**
   * Do not call constructor, use FileSessionData::singleton();
   */
  protected function __construct()
  {
    // configs
    $this->handler = ini_get('session.save_handler');
    $this->save_path = ini_get('session.save_path');
    
    // make sure session is using files
    if ( $this->handler !== 'files') 
    {
      throw new Exception('This class (' . __CLASS__ .') only works for file based session handling.');
    }
    
    // start session (it may already be started so suppress errors)
    @session_start();
  }
  
  /**
   * Retrieve a single instance of this class
   * 
   * @usage 
   * 
   * $SessionData = FileSessionData::singleton();
   * 
   */
  public static function singleton(array $args = array())
  {
    return parent::singleton(__CLASS__, $args);
  }
  
  /**
   * Get the session Object for a session id
   * 
   * @param string Session ID
   * 
   * @return array|bool
   */
  public function get($sess_id)
  {
    $data = $this->read($sess_id);
    if ($data)
    {
      return self::decode($data);
    }
    return false;
  }
  
  /**
   * Save the session Object for a session id
   * 
   * @param string Session ID
   * @param array Session Object
   * 
   * @return Bool
   */
  public function set($sess_id, array $sess)
  {
    $data = self::encode($sess);
    return $this->write($sess_id, $data);
  }
  
  /**
   * Unset a session
   * 
   * @param string Session ID
   * 
   * @return Bool
   */
  public function un_set($sess_id)
  {
    return unlink($this->getPath($sess_id));
  }
  
  /**
   * Returns if a session exists given it's ID
   * 
   * @param string Session ID
   * 
   * @return Bool
   */
  public function is_set($sess_id)
  {
    return file_exists($this->getPath($sess_id));
  }
  
  /**
   * Write raw session data to a session
   * 
   * @param string Session Id
   * @param string Session encoded data
   * 
   * @return Bool Write result
   */
  public function write($sess_id, $data)
  {
    return file_put_contents($this->getPath($sess_id), $data);
  }
  
  /**
   * Read raw session data from a session
   * 
   * @param string Session Id
   * 
   * @return string Session Data
   */
  public function read($sess_id)
  {
    return file_get_contents($this->getPath($sess_id));
  }
  
  /**
   * Retrieve all session Ids
   * 
   * @return array
   */
  public function getIds()
  {
    
    if (!self::$sess_ids) 
    {
      
      $files = $this->getFiles();
      
      self::$sess_ids = array();
      foreach($files as $file) {
        // @todo use substr ? 
        $parts = explode('_', $file);
        self::$sess_ids[] = array_pop($parts);
      }
    }
    
    return self::$sess_ids;
    
  }
  
  /**
   * Retrieve the file to the session given its id
   * 
   * @param string Session ID
   * 
   * @return string Session Path (Path may not exist)
   */
  private function getPath($sess_id)
  {
    return $this->save_path . '/sess_' . $sess_id;
  }
  
  /**
   * Retrieve the list of session files
   * 
   * @return array
   */
  private function getFiles()
  {
    if (!self::$sess_files) 
    {
      self::$sess_files = glob($this->save_path . '/sess_*');
    }
    return self::$sess_files;
  }
  
   /**
   * Decode/Unserialize encoded session data string
   * 
   * @param string Session encoded data
   * 
   * @return string Session Object
   */
  public static function decode($data) 
  {
    return parent::decode($data);
    
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
    return parent::encode($sess);
  }
  
  /**
   * Maps isset() and unset()
   */
  public function __call($method, $args)
  {
    if ($method == 'isset')
    {
      return call_user_func_array(array($this, 'is_set'), $args);
    }
    else if ($method == 'unset')
    {
      return call_user_func_array(array($this, 'un_set'), $args);
    }
    else 
      {
        throw new Exception('Call to non-existant method ' . htmlentities($method) . ' of class ' . htmlentities(__CLASS__));
      }
  }
  
}
