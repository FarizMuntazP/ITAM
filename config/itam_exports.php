<?php

return [
    'queue_threshold' => (int) env('ITAM_EXPORT_QUEUE_THRESHOLD', 10000),
    'retention_hours' => (int) env('ITAM_EXPORT_RETENTION_HOURS', 48),
];
