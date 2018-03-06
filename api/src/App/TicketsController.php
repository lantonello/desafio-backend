<?php
namespace App;

use Carbon\Carbon;

/**
 * Handles the Tickets listing
 *
 * @author Leandro Antonello <lantonello@gmail.com>
 * @version 2.0
 * @copyright (c) 2018
 */
class TicketsController extends Controller
{
    // PROPERTIES =============================================================
    private static $tickets_file = __DIR__ . '/../../../tickets.json';

    // PUBLIC STATIC METHODS ==================================================
    /**
     * Handles the Tickets listing
     * @return void
     */
    public static function index()
    {
        // Validates the session
        if( UserController::checkToken() === false )
        {
            \Flight::json([ 'success' => false, 'error' => 'Invalid or expired API Token.' ]);
            return;
        }
        
        // Load tickets file
        $tickets = self::loadTickets();
        
        if( is_null($tickets) )
        {
            \Flight::json([ 'success' => false, 'error' => 'An error has occurred open the Tickets file.' ]);
            return;
        }
        
        // Apply the ordering
        $tickets = self::applyOrder( $tickets );
        
        // Apply the filter
        $tickets = self::applyFilter( $tickets );
        
        // Paginate the tickets
        $list = self::paginate( $tickets );
        
        // Returns the Ticket list
        \Flight::json([ 'success' => true, 'tickets' => $list ]);
    }
    
    /**
     * Paginate the tickets list
     * @param array $tickets
     * @return array
     */
    public static function paginate( array $tickets )
    {
        // Get current page, array count and page size
        $page  = self::get('page', 1);
        $total = count($tickets);
        $psize = 10;
        
        // Calculates the last page
        $last  = ceil( $total / $psize );
        
        // Fix wrong page values
        $page = max($page, 1);
        $page = min($page, $last);
        
        // Calculates the offset
        $offset = ($page - 1) * $psize;
        $offset = ( ($offset < 0) ? 0 : $offset );
        
        return array_slice( $tickets, $offset, $psize );
    }
    
    /**
     * Apply filters
     * @param array $tickets
     * @return array
     */
    public static function applyFilter( array $tickets )
    {
        // First, get the filter parameter
        $param = self::get('filter');
        
        if( is_null($param) )
            return $tickets;
        
        $split  = explode( ':', $param );
        
        if( $split[0] === 'DateCreate' )
            return self::filterByDate( $tickets, $split[1] );
        
        if( $split[0] === 'Priority' )
            return self::filterByPriority( $tickets, $split[1] );
    }
    
    /**
     * Filter tickets by Date
     * @param array $tickets
     * @param string $interval
     * @return array
     */
    public static function filterByDate( array $tickets, string $interval )
    {
        $split = explode(',', $interval);
        $ini   = ( isset( $split[0] ) ? $split[0] : null );
        $end   = ( isset( $split[1] ) ? $split[1] : null );
        
        if( is_null( $ini ) || is_null( $end ) )
            return $tickets;
        
        // Parse dates
        $dt_ini = Carbon::createFromFormat( 'Y-m-d', $ini );
        $dt_end = Carbon::createFromFormat( 'Y-m-d', $end );
        
        // The result array
        $filtered = [];
        
        foreach( $tickets as $ticket )
        {
            // Get date
            $date = Carbon::createFromFormat( 'Y-m-d H:i:s', $ticket->DateCreate );
            
            if( $date->gte($dt_ini) && $date->lte($dt_end) )
                $filtered[] = $ticket;
        }
        
        return $filtered;
    }
    
    /**
     * Filter tickets by Priority
     * @param array $tickets
     * @param string $interval
     * @return array
     */
    public static function filterByPriority( array $tickets, string $priority )
    {
        // The result array
        $filtered = [];
        
        foreach( $tickets as $ticket )
        {
            // Get priority
            if( $ticket->Priority === $priority )
                $filtered[] = $ticket;
        }
        
        return $filtered;
    }
    
    /**
     * Apply order
     * @param array $tickets
     * @return array
     */
    public static function applyOrder( array $tickets )
    {
        // First, check for given order parameter.
        $param = self::get('order', 'DateCreate');
        $split = explode( ',', $param );
        $order = $split[0];
        $dir   = ( isset( $split[1] ) ? $split[1] : 'desc' );
        
        switch( $order )
        {
            case 'DateCreate':
            case 'DateUpdate':
                return self::orderByDate( $tickets, $order, $dir );
            case 'Priority':
                return self::orderByPriority( $tickets );
        }
    }
    
    /**
     * Ordering tickets by date
     * @param array $tickets
     * @param string $date_field
     * @param string $direction
     * @return array
     */
    public static function orderByDate( array $tickets, string $date_field, string $direction )
    {
        // Check field
        if( ($date_field !== 'DateCreate') && ($date_field !== 'DateUpdate') )
            $date_field = 'DateCreate';
        
        // Format direction
        $direction = strtoupper($direction);
        
        // Ordering
        usort($tickets, function($a, $b) use ($date_field, $direction) {
            // Get dates
            $a_date = Carbon::createFromFormat('Y-m-d H:i:s', $a->{$date_field});
            $b_date = Carbon::createFromFormat('Y-m-d H:i:s', $b->{$date_field});

            if( $direction === 'DESC' )
                return $b_date->gte($a_date);
            else
                return $b_date->lte($a_date);
        });
        
        return $tickets;
    }
    
    /**
     * Order tickets by priority
     * @param array $tickets
     * @return array
     */
    public static function orderByPriority( array $tickets )
    {
        // Ordering
        usort($tickets, function($a, $b) {
            // Get priorities
            if( $a->Priority === 'Alta' )
                return 0;
            else
                return 1;
        });
        
        return $tickets;
    }
    
    /**
     * Loads tickets file, returning the tickets array.
     * @return array|null
     */
    public static function loadTickets()
    {
        $file = json_decode( file_get_contents( self::$tickets_file ) );
        
        if( is_null( $file ) )
            return null;
        
        if( ! is_array( $file ) )
            return null;
        
        return $file;
    }
}
