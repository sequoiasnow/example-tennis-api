<?php
require 'includes.php';
require 'database/database.php';
require 'content/content_type.php';
require 'content/all.php';
require 'api/api.php';

$path = $_GET['path'];

echo new Api( $path );
