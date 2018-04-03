<?php

return [
    'secret' => getenv('JWT_SECRET') ?: 'secret_key',
    'jti'    => getenv('JWT_JTI') ?: 'jwt_jti_claim',
    'ttl'    => getenv('JWT_TTL') ?: (60 * 60 * 24),
];
