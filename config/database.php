<?php
$DB_DSN = ...;
$DB_USER = ...;
$DB_PASSWORD = ...;


• A config/database.php file, containing your database configuration, that will be
instanced via PDO in the following format:

    DSN (Data Source Name) contains required information needed to connect to the
database, for instance ’mysql:dbname=testdb;host=127.0.0.1’. Generally, a DSN is
composed of the PDO driver name, followed by a specific syntax for that driver. For
more details take a look at the PDO doc of each driver1
.
