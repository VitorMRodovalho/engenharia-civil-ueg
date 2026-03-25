<?php

define( "LM_SUBSCRIBE_SUBJECT", "Your Newsletter Subscription at [mosConfig_live_site]" );
define( "LM_SUBSCRIBE_MESSAGE", 
"Hallo [NAME],

U bent succesvol toegevoegt tot onze Nieuwsbrief van :<br> 
[mosConfig_live_site].<br>
Bedankt!

Wij vragen u wel om op de onderstaande bevestigings link te klikken of <br>
de onderstaande bevestigings link in uw internet brouwser te kopieeren <br>
<br>
[LINK]
<p>
met vriendelijke groeten,<br> 
_________________________
[mosConfig_live_site]" );

define( "LM_UNSUBSCRIBE_SUBJECT", "Newsletter Service at [mosConfig_live_site]: Unsubscription" );
define( "LM_UNSUBSCRIBE_MESSAGE", 
"Hallo [NAME],

U bent succesvol uitgeschreven van onze nieuwsbrief van [mosConfig_live_site].<br>
<br>
Met vriendelijke groeten, <br>
________________________
[mosConfig_live_site]" );

define( "LM_NEWSLETTER_FOOTER", 
"___________________________________________________________<br/>
U krijgt deze nieuwsbrief van [mosConfig_live_site],<br/>
waar u uwzelf heeft toegevoegt aan deze nieuwsbrief.<br/>
Om uw uit te schrijven volg deze link: [UNLINK]" );


/* Module */
define( "LM_FORM_NOEMAIL", "Please enter a valid email address." );
define( "LM_FORM_SHORTERNAME", "Please enter use a shorter Subscriber Name. Thanks." );
define( "LM_FORM_NONAME", "Please enter a Subscriber Name. Thanks." );
define( "LM_SUBSCRIBE", "Subscribe" );
define( "LM_UNSUBSCRIBE", "Unsubscribe" );
define( "LM_BUTTON_SUBMIT", "Go!" );

/* Backend */
define( "LM_ERROR_NEWSLETTER_COULDNTBESENT", "Newsletter could not be send!" );
define( "LM_NEWSLETTER_SENDTO_X_USERS", "Newsletter sent to {X} users" );
define( "LM_IMPORT_USERS", "Import Subscribers" );
define( "LM_EXPORT_USERS", "Export Subscribers" );
define( "LM_UPLAOD_FAILED", "Upload Failed" );
define( "LM_ERROR_PARSING_XML", "Error Parsing the XML File" );
define( "LM_ERROR_NO_XML", "Please upload only xml files" );
define( "LM_ERROR_EMAIL_ALREADY_ONLIST", "The email is already on the list" );
define( "LM_SUCCESS_ON_IMPORT", "Successfully imported {X} Subscribers." );
define( "LM_IMPORT_FINISHED", "Import finished" );
define( "LM_ERROR_DELETING_FILE", "File Deletion failed" );
define( "LM_DIR_NOT_WRITABLE", "Cannot write to directory ".$GLOBALS['mosConfig_cachepath'] );
define( "LM_ERROR_INVALID_EMAIL", "Invalid Email address" );
define( "LM_ERROR_EMPTY_EMAIL", "Empty Email address" );
define( "LM_ERROR_EMPTY_FILE", "Error: Empty file" );
define( "LM_ERROR_ONLY_TEXT", "Only text" );

define( "LM_SELECT_FILE", "Please select a file" );
define( "LM_YOUR_XML_FILE", "Your YaNC/Letterman XML Export File" );
define( "LM_YOUR_CSV_FILE", "CSV Import File" );
define( "LM_POSITION_NAME", "Position of the -Name- column" );
define( "LM_NAME_COL", "Name Column" );
define( "LM_POSITION_EMAIL", "Position of the -Email- column" );
define( "LM_EMAIL_COL", "Email Column" );
define( "LM_STARTFROM", "Start Importing from line..." );
define( "LM_STARTFROMLINE", "Start from line" );
define( "LM_CSV_DELIMITER", "CSV Delimiter" );
define( "LM_CSV_DELIMITER_TIP", "CSV Delimiter: , ; or Tabulator" );

/* Newsletter Management */
define( "LM_NM", "Newsletter Manager" );
define( "LM_MESSAGE", "Message" );
define( "LM_LAST_SENT", "Last send" );
define( "LM_SEND_NOW", "Send now" );
define( "LM_CHECKED_OUT", "Checked Out" );
define( "LM_NO_EXPIRY", "Finish: No Expiry" );
define( "LM_WARNING_SEND_NEWSLETTER", "Are you sure you want to send the newsletter?\\nWarning: If you send mail to a large group of users this could take a while!" );
define( "LM_SEND_NEWSLETTER", "Send Newsletter" );
define( "LM_SEND_TO_GROUP", "Send to group" );
define( "LM_MAIL_FROM", "Mail from" );
define( "LM_DISABLE_TIMEOUT", "Disable timeout" );
define( "LM_DISABLE_TIMEOUT_TIP", "Check to prevend the script generating a timeout error. <br/><strong>Doesn\'t work in safe mode!<strong>" );
define( "LM_REPLY_TO", "Reply to" );
define( "LM_MSG_HTML", "Message (HTML-WYSIWYG)" );
define( "LM_MSG", "Message (HTML-source)" );
define( "LM_TEXT_MSG", "alternative Text Message" );
define( "LM_NEWSLETTER_ITEM", "Newsletter Item" );

/* Subscriber Management */
define( "LM_SUBSCRIBER", "Subscriber" );
define( "LM_NEW_SUBSCRIBER", "New Subscriber" );
define( "LM_EDIT_SUBSCRIBER", "Edit Subscriber" );
define( "LM_SELECT_SUBSCRIBER", "Select a Subscriber" );
define( "LM_SUBSCRIBER_NAME", "Subscriber Name" );
define( "LM_SUBSCRIBER_EMAIL", "Subscriber Email" );
define( "LM_SIGNUP_DATE", "Signup Date" );
define( "LM_CONFIRMED", "Confirmed" );
define( "LM_SUBSCRIBER_SAVED", "The Subscriber Information has been saved" );
define( "LM_SUBSCRIBERS_DELETED", "You successfully deleted {X} Subscribers" );
define( "LM_SUBSCRIBER_DELETED", "The Subscriber was successfully deleted." );

/* Frontend */
define( "LM_ALREADY_SUBSCRIBED", "You're already subscribed to our Newsletters." );
define( "LM_NOT_SUBSCRIBED", "You are currently NOT subscribed to our Newsletters." );
define( "LM_YOUR_DETAILS", "Your Details:" );
define( "LM_SUBSCRIBE_TO", "Subscribe to our Newsletter" );
define( "LM_UNSUBSCRIBE_FROM", "Unsubscribe from our Newsletter" );
define( "LM_VALID_EMAIL_PLEASE", "Please enter a valid email-Address!" );
define( "LM_SAME_EMAIL_TWICE", "The Email-Address you entered is already on our Subscriber List!" );
define( "LM_ERROR_SENDING_SUBSCRIBE", "A subscribe message could not be sent:" );
define( "LM_SUCCESS_SUBSCRIBE", "Your email Address was added to our Newsletter." );
define( "LM_RETURN_TO_NL", "Return to the Newsletters" );
define( "LM_ERROR_UNSUBSCRIBE_OTHER_USER", "Sorry, but you cannot delete other Users from the List" );
define( "LM_ERROR_SENDING_UNSUBSCRIBE", "An unsubscribe message could not be sent:" );
define( "LM_SUCCESS_UNSUBSCRIBE", "Your email Address was removed from our Newsletter" );
define( "LM_SUCCESS_CONFIRMATION", "Your account has been sucessfully confirmed" );
define( "LM_ERROR_CONFIRM_ACC_NOTFOUND", "The Account associated with your Confirmation Link was not found." );

define( "LM_CONFIRMED_ACCOUNTS_ONLY", "Only confirmed Accounts?" );
define( "LM_CONFIRMED_ACCOUNTS_ONLY_TIP", "Send the Newsletter to <strong>confirmed</strong> Subscriber Accounts only. Subscribers that haven\'t confirmed their Subscription won\'t receive the Newsletter." );

define( "LM_NAME_TAG_USAGE", "You can use the Tag <strong>[NAME]</strong> in the Newsletter content to send personalized Newsletters. <br/>When sending the Newsletter, [NAME] is replaced by the Name of the User/Subscriber." );

define( "LM_USERS_TO_SUBSCRIBERS", "Make Users to subscribers" );
define( "LM_ASSIGN_USERS", "Assign Users" );

?>

