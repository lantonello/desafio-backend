<?php
namespace App;

use Google\Cloud\Language\LanguageClient;

/**
 * Responsible for analyse and classification of NeoAssist Tickets
 *
 * @author Leandro Antonello <lantonello@gmail.com>
 * @version 1.0
 * @copyright (c) 2018
 */
class TicketClassifier
{
    // PROPERTIES =============================================================
    /**
     * Holds the Google Cloud Project ID.
     * @var string
     */
    private static $projectId = 'neoassist-1520253728113';
    
    /**
     * Holds the Google Cloud Service Account key.
     * @var string
     */
    private static $keyFilePath = 'D:\work\neoassist\NeoAssist-2a8869fb7c8e.json';
    
    /**
     * Holds the Google Cloud Language Client instance.
     * @var LanguageClient 
     */
    private $language;
    
    /**
     * Holds the JSON ticket file path.
     * @var string
     */
    public $jsonTicketFilePath;
    
    /**
     * Holds a feedback message.
     * @var string
     */
    public $feedback;
    
    /**
     * Holds the parsed tickets.
     * @var array
     */
    protected $tickets;
    
    /**
     * Indicates if classification will be overwrite, if exists.
     * @var bool
     */
    private $overwrite;
    

    // CONSTRUCTOR ============================================================
    /**
     * Creates a new instante of Ticket Classifier
     */
    public function __construct( string $ticket_file_path )
    {
        // Sets the ticket filepath
        $this->jsonTicketFilePath = $ticket_file_path;
        $this->overwrite = false;
    }
    
    /**
     * Classify the Tickets using Natural Language Sentiment.
     * @param bool $force
     * @return void
     */
    public function classify( bool $force = false )
    {
        echo PHP_EOL, 'Ticket Classifier', PHP_EOL, PHP_EOL;
        
        // First, loads and decodes tickets
        $this->loadTickets();
        
        // Sets the Force mode
        $this->overwrite = $force;
        
        // Check tickets
        if( $this->checkTickets() === false )
        {
            echo $this->feedback;
            return;
        }
        
        // Initializes the Language service
        $this->initService();
        
        echo 'Starting classification', PHP_EOL;
        
        // Last interaction, for date classification
        $last_interaction = (object) [ 'Sender' => null, 'CreatedAt' => null, 'Score' => null ];
        
        // Read the Tickets
        foreach( $this->tickets as $ticket )
        {
            echo '>> Ticket ....: ', $ticket->TicketID, PHP_EOL;
            echo '>> Name ......: ', $ticket->CustomerName, PHP_EOL;
            
            // Initializes the Priority level
            $ticket->Priority = 'Normal';
            
            // Get the Customer interactions
            foreach( $ticket->Interactions as $it )
            {
                // Detects the sentiment of message
                $annotation = $this->language->analyzeSentiment( $it->Message );
                $sentiment  = $annotation->sentiment();

                // Saves to this interaction
                $it->SentimentScore = $sentiment['score'];
                
                // Check score
                if( (int) $sentiment['score'] < 0 )
                    $ticket->Priority = 'Alta';
                
                // Holds the Last Interaction
                $last_interaction->Sender    = $it->Sender;
                $last_interaction->CreatedAt = $it->DateCreate;
                $last_interaction->Score     = $it->SentimentScore;

                echo '>> Score .....: ', $it->Sentiment->Score, PHP_EOL;
                echo '>> Magnitude .: ', $it->Sentiment->Magnitude, PHP_EOL;
            }
            
            // Check the Date
            if( ($last_interaction->Sender === 'Customer') )
            {
                // Parse the date
                $created  = \DateTime::createFromFormat('Y-m-d H:i:s', $last_interaction->CreatedAt);
                $today    = new \DateTime();
                $interval = $created->diff( $today );
                
                if( $interval->days >= 5 )
                    $ticket->Priority = 'Alta';
                
                // If the last interaction of customer is positive, back to normal priority
                if( (int) $last_interaction->Score > 0 )
                    $ticket->Priority = 'Normal';
            }
            
            // Simple separator
            echo '--------------------------------------', PHP_EOL;
        }
        
        // Writes the file
        file_put_contents($this->jsonTicketFilePath, json_encode($this->tickets, JSON_PRETTY_PRINT));
    }
    
    public function refine()
    {
        //
    }
    
    // PROTECTED METHODS ======================================================
    /**
     * Loads and decodes the Ticket file.
     */
    protected function loadTickets()
    {
        // Check file
        if( ! file_exists( $this->jsonTicketFilePath ) )
        {
            $this->feedback = 'The ticket file does not exists.';
            $this->tickets = null;
            return;
        }
        
        if( ! is_file( $this->jsonTicketFilePath ) )
        {
            $this->feedback = 'The ticket file path is not a valid file.';
            $this->tickets = null;
            return;
        }
        
        $this->tickets = json_decode( file_get_contents( $this->jsonTicketFilePath ) );
    }
    
    /**
     * Validates the Tickets
     * @return boolean
     */
    protected function checkTickets()
    {
        // Check if ticket is loaded.
        if( is_null( $this->tickets ) )
        {
            return false;
        }
        
        // Check tickets array
        if( ! is_array( $this->tickets ) )
        {
            $this->feedback = 'The ticket file is not valid.';
            return false;
        }
        
        foreach( $this->tickets as $ticket )
        {
            // Check for interactions
            if( ! isset( $ticket->Interactions ) )
            {
                $this->feedback = 'The tickets does not have Interactions.';
                return false;
            }
            
            foreach( $ticket->Interactions as $it )
            {
                // Check if it is already classified
                if( isset( $it->Sentiment ) && ($this->overwrite === false) )
                {
                    $this->feedback = 'The tickets is already classified.';
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * Initializes the Language Client service.
     * @return void
     */
    protected function initService()
    {
        if( ! is_null( $this->language ) )
            return;
        
        // Instantiates the Language Client
        $this->language = new LanguageClient([
            'projectId' => self::$projectId,
            'keyFile' => json_decode(file_get_contents( self::$keyFilePath ), true)
        ]);
    }
}
