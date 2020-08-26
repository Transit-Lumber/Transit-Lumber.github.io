<?php

    include 'common-top.php';
    
    echo '<h2>Criteria</h2>';
    
    $searchTABLE = $_GET['table'];  //Gets the Table to search in from the URL
    $searchFIELD = $_GET['field'];  //Gets the Field to search in from the URL
    $searchVALUE = $_GET['value'];  //Gets the Value to search for from the URL
    $searchORDER = $_GET['order'];  //Gets the Order to show the results from the URL
    $searchRADIO = $_GET['radio'];  //Gets the Direction to show the results in from the URL
    
    echo '<form method="GET" action="search.php">
        
        <div class="criteria">
            <div class="criteria-block">
                <label for="table">Table</label>
                <div class="criteria-radio-pair">
                    <input type="radio" name="table" id="tableteam" value="teams" '.($searchTABLE=='teams' ? 'checked' : '').'>
                    <label for="tableteam">Teams</label>
                </div>
                <div class="criteria-radio-pair">
                    <input type="radio" name="table" id="tablechar" value="characters" '.($searchTABLE=='characters' ? 'checked' : '').'>
                    <label for="tablechar">Characters</label>
                </div>
            </div>
            <div class="criteria-block">
                <label for="field">Field</label>
                <select name="field">
                <option value="id"'.($searchFIELD=='id' ? ' selected="selected" ' : '').'>id</option>
                <option value="alias" class="opt-char"'.($searchFIELD=='alias' ? ' selected="selected" ' : '').'>alias</option>
                <option value="name" class="opt-team"'.($searchFIELD=='name' ? ' selected="selected" ' : '').'>name</option>
                <option value="description"'.($searchFIELD=='description' ? ' selected="selected" ' : '').'>description</option>
                <option value="forename" class="opt-char"'.($searchFIELD=='forename' ? ' selected="selected" ' : '').'>forename</option>
                <option value="surname" class="opt-char"'.($searchFIELD=='surname' ? ' selected="selected" ' : '').'>surname</option>
                <option value="team" class="opt-char"'.($searchFIELD=='team' ? ' selected="selected" ' : '').'>team</option>';
                echo '</select>
            </div>
            <div class="criteria-block">
                <label for="value">Value</label>
                <input type="text" name="value" value="'.$searchVALUE.'" placeholder="Value">
            </div>
            <div class="criteria-block">
                <label for="order">Order By</label>
                <select name="order">
                <option value="alias" class="opt-char"'.($searchORDER=='alias' ? ' selected="selected" ' : '').'>alias</option>
                <option value="name" class="opt-team"'.($searchORDER=='name' ? ' selected="selected" ' : '').'>name</option>
                <option value="description"'.($searchORDER=='description' ? ' selected="selected" ' : '').'>description</option>
                <option value="forename" class="opt-char"'.($searchORDER=='forename' ? ' selected="selected" ' : '').'>forename</option>
                <option value="surname" class="opt-char"'.($searchORDER=='surname' ? ' selected="selected" ' : '').'>surname</option>
                <option value="team" class="opt-char"'.($searchORDER=='team' ? ' selected="selected" ' : '').'>team</option>';
                echo '</select>
            </div>
            <div class="criteria-block">
                <label for="radio">List Order</label>
                <div class="criteria-radio-pair">
                    <input type="radio" name="radio" id="radioasc" value="ASC" '.($searchRADIO=='ASC' ? 'checked' : '').'>
                    <label for="radioasc">Ascending</label>
                </div>
                <div class="criteria-radio-pair">
                    <input type="radio" name="radio" id="radiodesc" value="DESC" '.($searchRADIO=='DESC' ? 'checked' : '').'>
                    <label for="radiodesc">Descending</label>
                </div>
            </div>
        </div>
    <input type="submit" value="Search" id="criteria-submit">
    </form>';

    echo '<h2>Results</h2>';

    // Connect to the DB
    $link = connectToDB();
    if( $link->connect_error ) exit( '<p>Error connecting to the database: '.$link->connect_error.'</p>' );
    
    if ($searchTABLE !== "characters" and $searchTABLE !== "teams"){    //Sets the table to Charecters on default
        $searchTABLE = "characters";
    };
    if (strlen($searchFIELD) < 1){  //Sets the field to name on default (or alias if it is searching in the characters table)
        $searchFIELD = "name";
        if ($searchTABLE == "characters"){
            $searchFIELD = "alias";
        };
    };
    if (strlen($searchVALUE) < 1){  //Sets the value to nothing if left blank
        $searchVALUE = "";
    };
    if (strlen($searchORDER) < 1){  //Sets the default order to Alias or Name
        if ($searchTABLE == "characters"){
            $searchORDER = "alias";
        }
        elseif ($searchTABLE == "teams"){
            $searchORDER = "name";
        };
    }
    elseif ($searchORDER == "description"){ //Since both Teams and Characters have a description field it needs to change (Explained above)
        $searchORDER = $searchTABLE.".description";
    };

    if ($searchTABLE == "characters"){  //If it is the Characters table then make sure it gets the Team info
        $queryINTERQUERY = "JOIN teams ON characters.team = teams.id";
        $querySELECT = "characters.id as characters_id,";
        if ($searchFIELD == "name"){    //Changes the field from Name to Alias to stop a possible error
            $searchFIELD = "alias";
        };
        if ($searchORDER == "name"){    //Changes the order from Name to Alias to stop a possible error
            $searchORDER = "alias";
        };
    }
    else{
        $queryINTERQUERY = "";
        $querySELECT = "";
        if ($searchFIELD == "forename" or $searchFIELD == "surname"){
            $searchFIELD = "name";
        }
        elseif($searchFIELD == "team"){
            $searchFIELD = "id";
        };
        if ($searchFIELD == "alias"){
            $searchFIELD = "name";
        };
        if ($searchORDER == "alias"){
            $searchORDER = "name";
        };
        if ($searchORDER == "forename" or $searchORDER == "surname"){
            $searchORDER = "name";
        }
        elseif($searchORDER == "team"){
            $searchORDER = $searchTABLE.".id";
        };
    };
    // Setup the DB query and prepare to run it on the server
    $sql = 'SELECT * , '.$querySELECT.' teams.id as teams_id 
            FROM '.$searchTABLE.' '.$queryINTERQUERY.
            ' WHERE '.$searchTABLE.'.'.$searchFIELD.' LIKE "%'.$searchVALUE.'%" ORDER BY '.$searchORDER.' '.$searchRADIO;

    $query = $link->prepare( $sql );
    if( !$query ) exit( '<p>Error with the database query: '.$link->error.'<br><br>QUERY SENT<br><br>'.$sql.'</p>' );

    // Run the DB query on the server
    $query->execute();
    if( $query->error ) exit( '<p>Error running the database query: '.$query->error.'<br><br>QUERY SENT<br><br>'.$sql.'</p>' );

    // See if any records found
    $result = $query->get_result();
    if( $result->num_rows == 0 ) exit( 'No relevant data could be found' );

    // Retrieve records from the server and display them
    echo    '<div class="result-block">';
    echo'<table>';
    echo    '<tr id="top-row">';
    
    if ($searchTABLE == 'characters') {
        echo        '<th>Alias</th>';
        echo        '<th>Forename</th>';
        echo        '<th>Surname</th>';
        echo        '<th>Team</th>';
    }
    elseif ($searchTABLE == 'teams') {
        echo        '<th>Name</th>';
        echo        '<th>Members</th>';
    };
    echo    '</tr>';
    while( $record = $result->fetch_assoc() ) {

    if ($searchTABLE == 'characters') {
        echo    '<tr>';
        echo        '<td class="alias"><a href="record.php?type=characters&id='.$record['characters_id'].'">'.$record['alias'].'</a></td>';
        echo        '<td class="forename">'.$record['forename'].'</td>';
        echo        '<td class="surname">'.$record['surname'].'</td>';
        echo        '<td class="team"><a href="record.php?type=teams&id='.$record['team'].'">'.$record['name'].'</a></td>';
        echo    '</tr>';
    }
    elseif ($searchTABLE == 'teams') {
        echo    '<tr>';
        echo        '<td class="name"><a href="record.php?type=teams&id='.$record['teams_id'].'">'.$record['name'].'</a></td>';
        echo        '<td class="members"><a href="search.php?table=characters&field=team&value='.$record['teams_id'].'&order=&radio=ASC">Shows all members of '.$record['name'].'</a></td>';
        echo    '</tr>';
    };
    }
    echo '</table>';
    echo    '</div>';
    
    //echo '<i>'.$sql.'</i>'; // FOR QUERY TESTING

    // Tidy up afterwards
    $result->close();
    $query->close();
    $link->close();


    include 'common-bottom.php';

?>