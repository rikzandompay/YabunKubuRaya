<?php

return [
    // Only explicitly configured proxy addresses may supply client IP and HTTPS headers.
    'proxies' => array_values(array_filter(array_map(trim(...), explode(',', (string) env('TRUSTED_PROXIES', ''))))),
];
