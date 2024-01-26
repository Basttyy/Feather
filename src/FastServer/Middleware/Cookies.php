<?php
namespace Basttyy\FastServer\Middleware;
use Basttyy\FastServer\Middleware\Cookies\Cookie;

class Cookies {
  
  function __invoke( $request, $response, $next ){
    $cookie = $request->getHeader( 'Cookie' );
    if ( $cookie && !property_exists( $request, 'cookies' ) ) {
      $request->cookies = $this->parseCookie( $cookie );
    } else {
      $request->cookies = array();
    }
    $response->cookies = new CookieJar();
    $response->on( 'headers', function() use ( $response ){
      if( $value = $response->cookies->headerValues() ){
        $response->setHeader( 'Set-Cookie',  $value );
      }
    });
    $next();
  }
  
  function parseCookie( $cookie ){
    $pairs = explode( '; ', $cookie );
    $cookies = array();
    foreach ( $pairs as $pair ) {
      list( $name, $val ) = explode( '=', $pair, 2 );
      $cookies[$name] = urldecode( $val );
    }
    return $cookies;
    
  }
  
}

class CookieJar implements \ArrayAccess {
  
  private $cookies = array();
  
  public function headerValues( ){
    $cookies = array();
    if ( count( $this->cookies ) == 0 ) {
      return false;
    }
    foreach ( $this->cookies as $key => $value ) {
      array_push( $cookies, "$key=$value" );
    }
    if( count( $cookies ) == 1 ){
      return $cookies[0];
    } else {
      return $cookies;
    }
  }
  
  public function offsetExists (mixed $offset ): bool {
    return array_key_exists( $offset, $this->cookies );
  }
  
  public function offsetGet (mixed $offset ): mixed{
    if ( array_key_exists( $offset, $this->cookies ) ) {
      return $this->cookies[$offset];
    }
  }
  
  public function offsetSet (mixed $offset , mixed $value ): void{
    if ( $value instanceof Cookie ) {
      $this->cookies[$offset] = $value;
    } else {
      $this->cookies[$offset] = new Cookie( $value );
    }
  }
  
  public function offsetUnset (mixed $offset ): void{
    unset( $this->cookies[$offset] );
  }
  
}

