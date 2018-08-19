<?php

use FakeApiClient\FakeApiClient;

require './vendor/autoload.php';

(new FakeApiClient())->makeRequests();
