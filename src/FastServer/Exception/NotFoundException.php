<?php

namespace Feather\Exception;
use Feather\Exception\Exception;

class NotFoundException extends Exception {
  function __construct( $message, $code = 404, $previous = null ) {
    parent::__construct( $message, $code, $previous );
  }
}