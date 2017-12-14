<?php

echo __( "You have a new signup from the source", "thrive-leads" ) . ": " . $contact->source . "\n\n";

echo __( "Contact details submitted", "thrive-leads" ) . ": " . "\n\n";

echo __( "Name", "thrive-leads" ) . ": " . $contact->name . "\n";
echo __( "Email", "thrive-leads" ) . ": " . $contact->email . "\n";

foreach ( $contact->custom_fields as $field => $value ):
	echo $field . ": " . $value . "\n";
endforeach;
