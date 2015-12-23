<?php
require 'config.php';
require 'functions.php';
require 'database/database.php';
require 'errors/error.php';
require 'api/api_retrievable.php';
require 'api/api_data_set.php';
require 'content/content_type.php';
require 'content/all.php';
require 'api/api.php';

$path = $_GET['__path'];
unset( $_GET['__path'] );

echo new Api( $path );
