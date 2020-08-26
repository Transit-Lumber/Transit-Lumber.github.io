<?php
    include 'common-top.php';

    echo '<h2>Adding Character to Database...</h2>';

    $alias = $_POST['alias'];
    $forename = $_POST['forename'];
    $surname = $_POST['surname'];
    $description = $_POST['description'];
    $team = $_POST['team'];
    
    $link = connectToDB();
    if( $link->connect_error ) exit( '<p>Error connecting to the database: '.$link->connect_error.'</p>' );

    // Setup the DB query and prepare to run it on the server
    $sql = 'INSERT INTO characters(id, alias, forename, surname, description, team) VALUES ( 0 , ? , ? , ? , ? , ? )';

    $query = $link->prepare( $sql );
    if( !$query ) exit( '<p>Error with the database query: '.$link->error.'</p>' );

    // Run the DB query on the server
    $query->bind_param('ssssi', $alias, $forename, $surname, $description, $team);
    $query->execute();
    if( $query->error ) exit( '<p>Error running the database query: '.$query->error.'</p>' );

    // Retrieve records from the server and display them

    // Tidy up afterwards
    $query->close();
    $link->close();
?>
  <h2>Character added successfully, Redirecting...</h2>
  <meta http-equiv='refresh' content='2; https://dt.waimea.school.nz/~tjbulmer/200DTS/Assessment/search.php?table=characters&field=alias&value=&order=&radio=ASC'>