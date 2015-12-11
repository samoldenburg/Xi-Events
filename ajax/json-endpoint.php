<?php
    // This file is just a simple endpoint intended to find a global variable, and display it as json data.
    header('Content-Type: application/json');
    global $xi_json_data;
    echo json_encode($xi_json_data);
?>
