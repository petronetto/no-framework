<?php

declare(strict_types=1);

use Neomerx\Cors\Strategies\Settings;
use Neomerx\Cors\Contracts\Http\ParsedUrlInterface;

return [
    /**
     * Array should be in parse_url() result format.
     * @see http://php.net/manual/function.parse-url.php
     */
    Settings::KEY_SERVER_ORIGIN        => [
        Settings::KEY_SERVER_ORIGIN_SCHEME => '',
        Settings::KEY_SERVER_ORIGIN_HOST   => '',
        Settings::KEY_SERVER_ORIGIN_PORT   => ParsedUrlInterface::DEFAULT_PORT,
    ],
    /**
     * A list of allowed request origins (lower-cased, no trail slashes).
     * Value `true` enables and value `null` disables origin.
     * If all origins '*' are enabled all settings for other origins are ignored.
     * For example, [
     *     'http://example.com:123'     => true,
     *     'http://evil.com'            => null,
     *     Settings::VALUE_ALLOW_ORIGIN_ALL => null,
     * ];
     */
    Settings::KEY_ALLOWED_ORIGINS      => [],
    /**
     * A list of allowed request methods (case sensitive).
     * Value `true` enables and value `null` disables method.
     * For example, [
     *     'GET'    => true,
     *     'PATCH'  => true,
     *     'POST'   => true,
     *     'PUT'    => null,
     *     'DELETE' => true,
     * ];
     * Security Note: you have to remember CORS is not access control system and you should not expect all
     * cross-origin requests will have pre-flights. For so-called 'simple' methods with so-called 'simple'
     * headers request will be made without pre-flight. Thus you can not restrict such requests with CORS
     * and should use other means.
     * For example method 'GET' without any headers or with only 'simple' headers will not have pre-flight
     * request so disabling it will not restrict access to resource(s).
     * You can read more on 'simple' methods at http://www.w3.org/TR/cors/#simple-method
     */
    Settings::KEY_ALLOWED_METHODS      => [
        'GET'    => null,
        'PATCH'  => null,
        'POST'   => null,
        'PUT'    => null,
        'DELETE' => null,
    ],
    /**
     * A list of allowed request headers (lower-cased). Value `true` enables and
     * value `null` disables header.
     * For example, [
     *     'content-type'                => true,
     *     'x-custom-request-header'     => null,
     *     Settings::VALUE_ALLOW_ALL_HEADERS => null,
     * ];
     * Security Note: you have to remember CORS is not access control system and you should not expect all
     * cross-origin requests will have pre-flights. For so-called 'simple' methods with so-called 'simple'
     * headers request will be made without pre-flight. Thus you can not restrict such requests with CORS
     * and should use other means.
     * For example method 'GET' without any headers or with only 'simple' headers will not have pre-flight
     * request so disabling it will not restrict access to resource(s).
     * You can read more on 'simple' headers at http://www.w3.org/TR/cors/#simple-header
     */
    Settings::KEY_ALLOWED_HEADERS      => [],
    /**
     * A list of headers (case insensitive) which will be made accessible to
     * user agent (browser) in response.
     * Value `true` enables and value `null` disables header.
     * For example, [
     *     'Content-Type'             => true,
     *     'X-Custom-Response-Header' => true,
     *     'X-Disabled-Header'        => null,
     * ];
     */
    Settings::KEY_EXPOSED_HEADERS      => [],
    /**
     * If access with credentials is supported by the resource.
     */
    Settings::KEY_IS_USING_CREDENTIALS => false,
    /**
     * Pre-flight response cache max period in seconds.
     */
    Settings::KEY_FLIGHT_CACHE_MAX_AGE => 0,
    /**
     * If allowed methods should be added to pre-flight response when
     * 'simple' method is requested (see #6.2.9 CORS).
     * @see http://www.w3.org/TR/cors/#resource-preflight-requests
     */
    Settings::KEY_IS_FORCE_ADD_METHODS => false,
    /**
     * If allowed headers should be added when request headers are 'simple' and
     * non of them is 'Content-Type' (see #6.2.10 CORS).
     * @see http://www.w3.org/TR/cors/#resource-preflight-requests
     */
    Settings::KEY_IS_FORCE_ADD_HEADERS => false,
    /**
     * If request 'Host' header should be checked against server's origin.
     */
    Settings::KEY_IS_CHECK_HOST        => false,
];
