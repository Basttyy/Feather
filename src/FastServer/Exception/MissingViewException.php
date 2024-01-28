<?php
namespace Feather\Exception;
use Feather\Exception\Exception;

class MissingViewException extends Exception {
  function __construct( $message ){
    parent::__construct( $message, 404 );
  }
}