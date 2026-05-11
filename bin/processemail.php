<?php

use Peanut\PeanutMailings\db\EMailQueue;

include_once __DIR__ . '/pnutstart.inc';

$count = EMailQueue::ProcessMessageQueue();

print sprintf("Processed %d messages\n", $count);