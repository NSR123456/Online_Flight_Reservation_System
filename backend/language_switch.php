<?php
session_start();

// Check if a language is selected
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    
    // Validate the language code
    $available_languages = ['en', 'es'];  // Add more languages as needed
    if (in_array($lang, $available_languages)) {
        $_SESSION['lang'] = $lang;  // Store the selected language in session
    }
}

// Redirect back to the previous page (or home page)
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>
