<?php
function connectToDB() {
    return new mysqli( 'localhost',
                       'tjbulmer_db',
                       '4Pr',
                       'tjbulmer_supes' );
}
?>