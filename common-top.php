<?php require 'common-functions.php'; ?> <!-- Allows link to the database -->

<html>
    <head>
        <title>Mystice</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Orbitron&family=Roboto&display=swap">
    </head>
    <body>
        <header> <!-- Top bar (Desktop layout)/ Side bar (Mobile Layout)-->
        
            <h1>Mystice</h1> <!-- Main website title -->
            
            <nav>
                <label for="menubutton">&#9776</label>
                <input id="menubutton" type="checkbox">
                <ul>
                    <li><a href="index.php">Home</a></li> <!-- Links to the Home page -->
                    <li><a href="form-supe.php">New Character</a></li> <!-- Links to New Character page -->
                    <li><a href="form-team.php">New Team</a></li> <!-- Links to the New Team page  -->
                    <li><a href="search.php?table=characters&field=alias&value=&order=&radio=ASC">Search</a></li> <!-- Links to the Search page -->
                </ul>
            </nav>
        </header>
        <main>