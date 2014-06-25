<?php
/**
* This file contains the main functions responsible for the IN3008 - Electronic Commerce coursework project
*
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @version 1.0
* @since 0.1
*/

/**
* function Register
*/
function registerUser($user, $password, $email)
{
// insert the data into the database
$dbQuery("INSERT INTO users (userName, password, email, status) VALUES ($username, $password, $email, 'customer'");
// get the results of the registration information
// perform the correct action according to the received value
// return true if registration is successfull and false otherwise
return true;
}