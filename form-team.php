<?php
    include 'common-top.php';
?>

<h2>Add a New Team</h2>

<form method="POST" action="add-team.php" class="styled-block">
    
    <label for="teamname">Name</label>
    <input type="text" name="teamname" placeholder="e.g. The Avengers" required>
    <br>
    <label for="description">Description</label>
    <textarea type="text" name="description" placeholder="e.g. Earth's mightiest heroes" required cols="40" rows="5" ></textarea>
    <br>
    <input class="submit-button" type="submit" value="Add a New Team">
    
</form>


<?php
    include 'common-bottom.php';
?>