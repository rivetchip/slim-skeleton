<?php


function write( $response, $output )
{
    return $response->write($output);
}

function nohtml( $input )
{
    return htmlentities($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}


// Render a template file

function render( $template, array $data = [] )
{
    $file = 'themes/'.$template.'.php';

    if( !is_readable($file) )
    {
        throw new RuntimeException('Cannot render "' . $file . '" : template is not readable');
    }

    // ob, fetch content

    extract($data);

    ob_start();

    require $file;

    return ob_get_clean();
}


// guess the prefered language based on http header

function getPreferedLanguage( $default )
{
    static $prefered;

    if( isset($prefered) )
    {
        return  $prefered;
    }

    $httpAccept = getenv('HTTP_ACCEPT_LANGUAGE');

    if( isset($httpAccept) ) // fr,fr-FR;q=0.8,en-US;q=0.5,en;q=0.3
    {
        $langs = [];

        foreach( explode(',', $httpAccept) as $values )
        {
            list($lang, $quality) = array_pad(explode(';q=', $values, 2), 2, 1); // default q=1

            $langs[$lang] = $quality;
        }

        if( !empty($langs) )
        {
            // sort list based on value	
            arsort($langs, SORT_NUMERIC);

            // get the first of the list
            $prefered = key($langs);

            $prefered = substr($prefered, 0, 2); // 2 frst char 'fr-FR'
        }
    }

    return $prefered = isset($prefered) ? $prefered : $default; 
}


// basic String translation

function __( $phrase )
{
    static $translations = [];

    if( is_string($phrase) ) // get a translate
    {
        $args = func_get_args();
        $format = array_shift($args); // phrase
		
        if( isset($translations[$phrase]) )
        {
            $format = $translations[$phrase];
        }

        return vsprintf($format, $args);
    }
    elseif( is_array($phrase) ) // set the translations
    {
        $translations = $phrase;
    }
}


// Fetch data from sessions

function session( $key, $value = null )
{
    if( is_array($key) ) // set array to session
    {
        $_SESSION = array_merge($_SESSION, $key);
    }
    elseif( isset($key, $value) ) // simple set
    {
        $_SESSION[$key] = $value;
    }
    elseif( isset($key) ) // try to get a prop
    {
        return ( isset($_SESSION[$key]) ) ? $_SESSION[$key] : null;
    }
    else // return the session
    {
        return $_SESSION;
    }
}


// add events ; fire events ( second parameter is sended to callbacks )

function event( $event, $callback = null )
{
	static $events = [];

    if( isset($callback) && is_callable($callback) ) // add event
    {
        $events[$event][] = $callback;
    }
    elseif(isset($events[$event])) // fire a callback
    {
        $result = $callback; // value passed thru

		foreach( $events[$event] as $function )
		{
			$result = call_user_func($function, $result);
		}

		return $result;
    }
}


