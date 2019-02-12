<?php
require_once('defines.php');
require_once('classes/Leads/LeadEmailMailbox.class.php');
$db = new ARDB();
echo 'Starting Job - ' . Date('Y-m-d H:i:s') . PHP_EOL;
$mailbox = new LeadEmailMailbox();
$mailbox->parse();
echo 'Finished Job - ' . Date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;
?>