<?php
    include 'common-top.php';
?>

<h2>Add a New Character</h2>

<form method="POST" action="add-supe.php" class="styled-block"> <!-- Form for character information -->
    
    <label for="alias">Alias</label>
    <input type="text" name="alias" placeholder="e.g. Spiderman" required>
    <br>
    <label for="forename">Forename</label>
    <input type="text" name="forename" placeholder="e.g. Peter" required>
    <br>
    <label for="surname">Surname</label>
    <input type="text" name="surname" placeholder="e.g. Parker" required>
    <br>
    <label for="description">Description</label>
    <textarea type="text" name="description" placeholder="e.g. His can shoot webs out of his hands and swing on them." required cols="40" rows="5" ></textarea>
    <br>
    <label for="team">Team</label>
    <select name="team">
<?php
    $link = connectToDB();
    if( $link->connect_error ) exit( '<p>Error connecting to the database: '.$link->connect_error.'</p>' );

    // Setup the DB query and prepare to run it on the server
    $sql = 'SELECT id, name
            FROM teams
            ORDER BY id ASC';

    $query = $link->prepare( $sql );
    if( !$query ) exit( '<p>Error with the database query: '.$link->error.'</p>' );

    // Run the DB query on the server
    $query->execute();
    if( $query->error ) exit( '<p>Error running the database query: '.$query->error.'</p>' );

    // See if any records found
    $result = $query->get_result();
    if( $result->num_rows == 0 ) exit( 'No relevant data could be found' );

    // Retrieve records from the server and display them
    while( $record = $result->fetch_assoc() ) {
        echo '<option value="'.$record['id'].'">'.$record['name'].'</option>';
    }

    // Tidy up afterwards
    $result->close();
    $query->close();
    $link->close();
?>
    </select>
    <br>
    <input class="submit-button" type="submit" value="Add a New Character">
    
</form>


<?php
    include 'common-bottom.php';
?>