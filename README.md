# Grav CORS Plugin

The **CORS plugin** for [Grav](http://github.com/getgrav/grav) allows to enable and manage [CORS (Cross-Origin Resource Sharing)](https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS) on your site.

With CORS, it is possible to let your site become remotely available for Ajax requests.

## Installation

The CORS plugin is easy to install with GPM.

```bash
$ bin/gpm install cors
```

## Config Defaults

```yaml
enabled: true
routes:
  - '*'
origins:
  - '*'
methods:
  - OPTIONS
  - GET
  - HEAD
  - POST
  - PUT
  - DELETE
  - TRACE
  - CONNECT
credentials: false
```

If you need to change any value, then the best process is to copy the [cors.yaml](cors.yaml) file into your users/config/plugins/ folder (create it if it doesn't exist), and then modify there. This will override the default settings.

## Settings

### Routes

One ore more **relative** URIs, matching any of the  site routes. This can be a full route (`/blog/entry`).

Routes are always interpreted as regular expressions, which allows for routes like `/blog/*` or even more complex ones such as `^/.*\.json(\?\d{1,})?$` (**/some-url.json?1470810103393**).

To make the whole site available for CORS, set the Route value to `*` (wildcard).

### Allow Origin

The origin specifies one or multiple URI that may access the site. You might specify `*` as a wildcard, allowing any origin to access the site.

### Allow Methods

The method or methods allowed when accessing the site.

### Expose Headers

This setting allows to whitelist headers that browsers are allowed to access. For example:

```
Access-Control-Expose-Headers: X-My-Grav-Header, X-Custom-Grav
```

This allows the `X-My-Grav-Header` and `X-Custom-Grav` headers to be exposed to the browser.

The `XMLHttpRequest 2` object has a `getResponseHeader()` method that returns the value of a particular response header. During a CORS request, the `getResponseHeader()` method can only access simple response headers. Simple response headers are defined as follows:

* Cache-Control
* Content-Language
* Content-Type
* Expires
* Last-Modified
* Pragma

If you want clients to be able to access other headers, you have to specify them through this setting.

### Allow Credentials

By default, cookies are not included in CORS requests. By enabling this setting, cookies will be included in CORS requests. If you don't need cookies, don't enable this option.

The `Access-Control-Allow-Credentials` header works in conjunction with the `withCredentials` property on the XMLHttpRequest 2 object. Both these properties must be set to true in order for the CORS request to succeed. If `withCredentials` is true, but there is no `Access-Control-Allow-Credentials` header, the request will fail (and vice versa).

Its recommended that you don’t enable this setting unless you are sure you want cookies to be included in CORS requests.
