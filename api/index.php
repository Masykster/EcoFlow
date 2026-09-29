<?php

/*
 * Vercel serverless entrypoint.
 *
 * vercel.json routes every non-static request here, and this file
 * forwards handling to the standard Laravel front controller.
 */

require __DIR__.'/../public/index.php';
