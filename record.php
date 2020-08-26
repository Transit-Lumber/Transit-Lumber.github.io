<?php

    include 'common-top.php';


    $recordID = $_GET['id'];        //Gets the record's id
    $recordTYPE = $_GET['type'];    //Gets the record's type (Team or Character)
    
    $link = connectToDB();  //Connects to the database
    if( $link->connect_error ) exit( '<p>Error connecting to the database: '.$link->connect_error.'</p>' );

    if($recordTYPE == 'characters'){    //If the record is a Character then it needs to get the info on the Team it is a member of
        $querySELECT = '*, characters.description AS characters_description';
        $queryINTERQUERY = 'JOIN teams ON characters.team = teams.id';
    }
    elseif($recordTYPE == 'teams'){
        $querySELECT = '*';
        $queryINTERQUERY = '';
    };

    //========================================================================================

    // Setup the DB query and prepare to run it on the server
    $sql =  'SELECT '.$querySELECT. //Which fields to get from the table and any name changes
            ' FROM '.$recordTYPE.   //The table to get the info from
            ' '.$queryINTERQUERY.   //Any join statments
            ' WHERE '.$recordTYPE.  //The table that the record is in
            '.id = '.$recordID;     //The ID of the record
            
    $query = $link->prepare( $sql );
    if( !$query ) exit( '<p>Error with the database query: '.$link->error.'</p>' );

    // Run the DB query on the server
    $query->execute();
    if( $query->error ) exit( '<p>Error running the database query: '.$query->error.'</p>' );

    // See if any records found
    $result = $query->get_result();
    if( $result->num_rows == 0 ) exit( 'No relevant data could be found' );
    
    // Retrieve the single record from the server and display it
    $record = $result->fetch_assoc();
    if ($recordTYPE == 'characters') {
        echo '<h2>'.$record['alias'].'</h2>';   //The Character's Name
        echo '<div class="results-block">';
        echo    '<h4>Forename:</h4>';
        echo    '<h5>'.$record['forename'].'</h5>'; //The Character's Forename
        echo    '<h4>Surname:</h4>';
        echo    '<h5>'.$record['surname'].'</h5>';  //The Character's Surname
        echo    '<h4>Description:</h4>';
        echo    '<h5>'.$record['characters_description'].'</h5>';   //The Character's Description
        echo    '<h4>Team:</h4>';
        echo    '<h5><a href="record.php?type=teams&id='.$record['team'].'">'.$record['name'].'</a></h5>';  //A link to the Character's Team
    }
        elseif ($recordTYPE == 'teams') {
        echo '<h2>'.$record['name'].'</h2>';    //The Team's Name
        echo '<div class="results-block">';
        echo    '<h4>Description:</h4>';
        echo    '<h5>'.$record['description'].'</h5>';  //The Team's Description
        echo    '<h4>Members:</h4>';
        echo    '<h5><a href="search.php?table=characters&field=team&value='.$record['id'].'&order=&radio=ASC">Shows all members of '.$record['name'].'</a></h5>';  //A link to the Team's Members
    };
    echo '</div>';
    // Tidy up afterwards
    $result->close();
    $query->close();
    include 'common-bottom.php';
    ?>