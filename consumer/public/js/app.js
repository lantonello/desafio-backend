/* 
    Created on : 13/11/2017, 09:15:05
    Author     : Leandro Antonello <lantonello@gmail.com>
*/

// Global XHR object
var Xhr;

$(document).ready(function(){
    // Set moment locale
    moment.locale('pt-BR');
    
    // Load tickets
    var api_url = $('#api_url').val();
    
    if( (api_url !== '') && (api_url !== null) && (api_url !== undefined) )
        loadTickets();
});

/**
 * Generic form post function.
 * @param {HTMLElement} sender Reference to clicked button.
 * @returns {Void}
 */
function formPost( sender, callback )
{
    var _form = $(sender).closest('form');
    var _url  = $(_form).attr('action');
    var _data = $(_form).serialize();
    
    Xhr = $.ajax({
        url: _url,
        data: _data,
        type: 'POST',
        beforeSend: function( xhr )
        {
            console.log('Posting form to [' + _url + ']...');
            
            // Disable button
            disable( sender, true );
        },
        complete: function( jqXHR, textStatus )
        {
            // Re-enable button
            enable( sender );
            
            // Handle response
            if( callback )
            {
                callback( jqXHR );
                return;
            }
                
            handleResponse( jqXHR, _form );
        }
    });
}

/**
 * Handler for Ajax response.
 * @param {Object} response   Server response.
 * @param {HTMLElement} _form Reference to form element.
 * @returns {Void}
 */
function handleResponse( response, _form )
{
    //console.log( 'Raw response:' );
    //console.log( response );
    
    // Check response
    //var json = JSON.parse(response);
    var json = response.responseJSON;
    
    //console.log('Handle Json response:');
    //console.log('{ Status: ' + json.success + ', Message: "' + json.message + '" }');

    if( json.success === false )
    {
        alert( json.error );
        return;
    }
    
    // Reset form
    $(_form)[0].reset();

    // Shows message, if exists
    if( json.message )
    {
        alert( json.message );
    }
    
    // Next Route, if exists
    if( json.goto !== undefined )
    {
        var delay = 2000;
        
        if( json.delay !== undefined )
            delay = json.delay * 1000;
        
        setTimeout(function(){
            document.location.href = json.goto;
        }, delay);
    }
}

/**
 * Handler for Ajax response.
 * @param {Object} response   Server response.
 * @param {HTMLElement} _form Reference to form element.
 * @returns {Void}
 */
function handleLoginResponse( response )
{
    console.log( 'handleLoginResponse' );
    //console.log( response );
    
    // Check response
    //var json = JSON.parse(response);
    var json = response.responseJSON;
    
    if( json.success === false )
    {
        alert( json.error );
        return;
    }
    
    if( json.api_token )
    {
        Cookies.set('neotoken', json.api_token);
    }
    
    setTimeout(function(){
        document.location.href = BaseUrl + 'tickets';
    }, 1000);
}

// To work with API calls
var LastUri;

/**
 * Consumes the API to load tickets
 * @returns {Void}
 */
function loadTickets( queryString )
{
    LastUri = $('#api_url').val() + '?' + queryString;
    
    Xhr = $.ajax({
        url: $('#api_url').val(),
        data: queryString,
        type: 'GET',
        headers: {
            "Authorization": "Bearer " + Cookies.get('neotoken')
        },
        beforeSend: function( xhr )
        {
            // Clear table
            $('#tickets').find('tbody').empty();
        },
        complete: function( jqXHR, textStatus )
        {
            var json = jqXHR.responseJSON;

            if( json.success === false )
            {
                alert( json.error );
            }

            if( ! json.tickets )
            {
                location.href = BaseUrl;
                return;
            }
            
            buildTicketList( json.tickets, json.paging );
        }
    });
}

/**
 * Builds the Ticket list
 * @param {Array} tickets
 * @returns {Void}
 */
function buildTicketList( tickets, paging )
{
    var row, created, updated;
    
    // Ticket rows
    for( var i = 0; i < tickets.length; i++ )
    {
        created = moment(tickets[i].DateCreate, 'YYYY-MM-DD HH:mm:ss');
        updated = moment(tickets[i].DateUpdate, 'YYYY-MM-DD HH:mm:ss');
        
        row = '<tr>'
            +   '<td>'+ tickets[i].TicketID +'</td>'
            +   '<td>'+ tickets[i].CustomerName +'</td>'
            +   '<td>'+ created.format('D.MMM.YYYY [-] HH:mm') +'</td>'
            +   '<td>'+ updated.format('D.MMM.YYYY [-] HH:mm') +'</td>'
            +   '<td>'+ tickets[i].Priority +'</td>'
            + '</tr>';
    
        $('#tickets').find('tbody').append( $(row) );
    }
    
    // Parse current Url
    var uri = new URI( LastUri );
    var data = uri.search(true);
    
    // Remove existing links
    $('#page-links').empty();
    
    // Pagination links
    var link;
    
    for( var j = 1; j <= paging.last; j++ )
    {
        // Set page param
        data.page = j;
        uri.search(data);
        
        link = '<a class="btn btn-default" href="javascript:loadTickets(\''+ uri.search() +'\');">'+ j +'</a>';
        
        $('#page-links').append( $(link) );
    }
}


/**
 * Disable a button
 * @param {type} who
 * @param {type} loader
 * @returns {undefined}
 */
function disable( who, loader )
{
    $(who).addClass('disabled');
    
    if( loader )
    {
        var ld = '<i class="fa fa-spinner fa-pulse fa-fw"></i>';
        var bt = ld + $(who).html();
        $(who).html( bt );
    }
}

/**
 * Enable a button
 * @param {type} who
  * @returns {undefined}
 */
function enable( who )
{
    $(who).removeClass('disabled');
    
    var ld = '<i class="fa fa-spinner fa-pulse fa-fw"></i>';
    var bt = $(who).html();
    
    $(who).html( bt.replace(ld, '') );
}
