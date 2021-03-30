What is it
==========

Docker container for send push on all devices with one api

Installation
============

```bash
docker run \
-p 80:80/tcp \
-e PG_HOST=db \
-e PG_PORT=5432 \
-e PG_DATABASE=push_db \
-e PG_USER=user \
-e PG_PASSWORD=password \
-e APPLE_FILE="apple.pem" \
-e APPLE_BUNDLE_ID="app" \
-e APPLE_URL="https://api.push.apple.com:443/3/device/" \
-e GOOGLE_FILE="google.json" \
-e GOOGLE_URL="https://fcm.googleapis.com/v1/projects/myproject-b5ae1/messages:send" \
-e HUAWEI_FILE="huawei.json" \
-e HUAWEI_URL="https://push-api.cloud.huawei.com/v1/%s/messages:send" \
-d perfumerlabs/push:v1.0.0
```

Database must be created before container startup.

Environment variables
=====================

- APPLE_FILE - apple apns .pem certificate. Required. Default value "apple.pem"
- APPLE_BUNDLE_ID -  your app bundle id. Required. Default value "app"
- APPLE_URL - url for send push. Required. Default value "https://api.push.apple.com:443/3/device/"
- GOOGLE_FILE - service account json file. Required. Default value "google.json"
- GOOGLE_URL - url for send push.  Required. Default value "https://fcm.googleapis.com/v1/projects/myproject-b5ae1/messages:send"
- HUAWEI_FILE - service account json file.  Required. Default value "huawei.json"
- HUAWEI_URL - url for send push. %s on token place.  Required. Default value "https://push-api.cloud.huawei.com/v1/%s/messages:send"
- PG_HOST - PostgreSQL host. Required.
- PG_PORT - PostgreSQL port. Default value is 5432.
- PG_DATABASE - PostgreSQL database name. Required.
- PG_USER - PostgreSQL user name. Required.
- PG_PASSWORD - PostgreSQL user password. Required.
- PHP_PM_MAX_CHILDREN - number of FPM workers. Default value is 10.
- PHP_PM_MAX_REQUESTS - number of FPM max requests. Default value is 500.
- ADMIN_USER - login for sign in admin panel. Optional.
- ADMIN_PASSWORD - password for sign in admin panel. Optional.

Volumes
=======

/opt/config - config files

If you want to make any additional configuration of container, mount your bash script to /opt/setup.sh. This script will be executed on container setup.

How it works
============

This microservice is invented to provide data structure and API for send push notifications on IOS(apns), Google(fcm), Huawei(hms), Web(fcm).


First, you save push tokens of any provider via /token/save. Then push via /send. Pushes are sent to all tokens that have been saved under the desired custom_token

Database tables
===============

After setup there are 1 predefined table in database:

### push_token

Registry of collections. Fields:

- user [string] - User uniq token.
- apple [string] - apple push token.
- google [string] - google push token.
- huawei [string] - huawei push token.
- web [string] - web push token.

API Reference
=============

### Save token

`POST /token`

Parameters (json):
- user [string,required] - User uniq token.
- provider [string,required] - provider name. One of [apple, google, huawei, web].
- token [string,required] - push token.

Request example:

```json
{
  "user" :  "216952s",
  "provider": "apple",
  "token": "10fc1ce7defde41b3c04a083f9c03873095292d75090d95fc2002a83e128acdc"
}
```

Response example:

```json
{
    "status": true,
    "content": {
      "token": {
          "user" :  "216952s",
          "provider": "apple",
          "token": "10fc1ce7defde41b3c04a083f9c03873095292d75090d95fc2002a83e128acdc"}
    } 
}
```

### delete collection

`DELETE /token`

Parameters (json):
- user [string,required] - User uniq token.
- provider [string,required] - provider name. One of [apple, google, huawei, web].

Request example:

```json
{
  "user" :  "216952s",
  "provider": "apple"
}
```

Response example:

```json
{
    "status": true
}
```

### Create a record

`POST /send`

Request parameters (json):
- user [array|string,required] - array or string of user.
- title [string,required] - title text.
- subtitle [string,optional] - subtitle text.
- text [string,required] - body text.
- image [string,required] - image url. Apple not allowed.
- payload [object,optional] - object of some data.
- sound [string,optional] - object of some data.

Request example:

```json
{
  "user" :  ["test1", "test2", "test3"],
    "title": "test",
    "text": "test",
    "image": "https://test.kz/test.png",
    "payload": {
      "event": "test",
      "track": "test"
    },
    "sound": "test"
}
```

Response example:

```json
{
    "status": true
}
```

Contributors
============

- Ilyas Makashev [mehmatovec@gmail.com](mailto:mehmatovec@gmail.com)
- Temirlan Kasen [temirlankasen@gmail.com](mailto:temirlankasen@gmail.com)

Software
========

1. Ubuntu 18.04 Bionic
1. Nginx 1.16
1. PHP 7.4