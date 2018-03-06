<?php
namespace App;

use Carbon\Carbon;

/**
 * UserController
 *
 * @author Leandro Antonello <lantonello@gmail.com>
 * @version 2.0
 * @copyright (c) 2018
 */
class UserController extends Controller
{
    // PROPERTIES =============================================================
    /** @var \GUMP Holds an intance of GUMP validator. */
    protected static $validator;
    /** @var array Holds an array with sanitized input data. */
    protected static $input;
    
    /** @var string Holds the static session filepath. */
    protected static $session_file = __DIR__ . '/../../app/sessions.json';
    
    // PUBLIC STATIC METHODS ==================================================
    /**
     * Handle the API user authentication, returning the Token
     * @return void
     */
    public static function getToken()
    {
        // Validate data
        $validated = self::validateLogin();
        
        if( $validated ===  false )
        {
            // Get Error
            $errors = self::$validator->get_errors_array();
            
            \Flight::json([ 'success' => false, 'error' => reset($errors) ]);
            return;
        }
        
        // API credentials
        $username = 'user@neoassist.com';
        $password = 'q1w2e3';
        
        // Check the Credentials
        if( (self::$input['username'] !== $username) || (self::$input['password'] !== $password) )
        {
            \Flight::json([ 'success' => false, 'error' => 'Username and/or password is not valid.' ]);
            return;
        }
        
        // Creates the session
        $key = self::makeSession();
        
        \Flight::json([ 'success' => true, 'api_token' => $key ]);
    }
    
    /**
     * Validates authentication data
     * @return bool
     */
    public static function validateLogin()
    {
        // Creates the Gump instance
        self::$validator = new \GUMP();
        self::$input     = self::$validator->sanitize( $_POST );
        
        // Rules
        self::$validator->validation_rules([
            'username' => 'required|valid_email',
            'password' => 'required|max_len,12|min_len,6'
        ]);
        
        return self::$validator->run( self::$input );
    }
    
    /**
     * Creates a new session entry in session file
     * @return string The generated API Token.
     */
    public static function makeSession()
    {
        // Get the session file
        $sessions  = json_decode( file_get_contents( self::$session_file ) );
        
        // Generate the new Api token
        $key = md5( microtime(true) );
        $now = Carbon::now();
        
        // Add a new session
        $sessions[] = (object) [
            'Token' => $key,
            'Expire' => $now->addMinutes( 60 )->format( 'Y-m-d H:i:s' )
        ];
        
        // Writes down the file
        file_put_contents( self::$session_file, json_encode( $sessions, JSON_PRETTY_PRINT ) );
        
        return $key;
    }
    
    /**
     * Check the token send by Request headers
     * @return boolean
     */
    public static function checkToken()
    {
        if( ! isset( $_SERVER['HTTP_AUTHORIZATION'] ) )
            return false;
        
        if( empty( $_SERVER['HTTP_AUTHORIZATION'] ) )
            return false;
        
        // Get token
        $auth = explode(' ', $_SERVER['HTTP_AUTHORIZATION']);
        $key  = $auth[1];
        
        // Load session file
        $sessions  = json_decode( file_get_contents( self::$session_file ) );
        
        foreach( $sessions as $entry )
        {
            // Check for token
            if( $entry->Token === $key )
            {
                // Parse dates
                $now     = Carbon::now();
                $expires = Carbon::createFromFormat( 'Y-m-d H:i:s', $entry->Expire );
                
                // Check the expires date
                if( $now->gte( $expires ) )
                    return false;
                
                // Autorization complete
                return true;
            }
        }
        
        return false;
    }
}
