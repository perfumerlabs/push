What is it
==========

Docker container for building personal pushs or chats.

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
-d perfumerlabs/push:v1.3.0
```

Database must be created before container startup.

Environment variables
=====================

- FEED_TIMEZONE - Timezone of incoming or outcoming dates. Optional. Default is "Utc".
- CENTRIFUGO_HOST - host of centrifugo server. Optional.
- CENTRIFUGO_API_KEY - Centrifugo API Key. Optional.
- CENTRIFUGO_SECRET_KEY - Centrifugo Secret Key. Optional.
- BADGES_HOST - host of Badges server. Optional.
- PG_HOST - PostgreSQL host. Required.
- PG_PORT - PostgreSQL port. Default value is 5432.
- PG_DATABASE - PostgreSQL database name. Required.
- PG_USER - PostgreSQL user name. Required.
- PG_PASSWORD - PostgreSQL user password. Required.
- PHP_PM_MAX_CHILDREN - number of FPM workers. Default value is 10.
- PHP_PM_MAX_REQUESTS - number of FPM max requests. Default value is 500.

Volumes
=======

This image has no volumes.

If you want to make any additional configuration of container, mount your bash script to /opt/setup.sh. This script will be executed on container setup.

How it works
============

This microservice is invented to provide data structure and API for personal pushs like notification push,
some kind of article push with personal algorithms, chats and so on.

When a record appears you push it to Feed. Then you can fetch a number of records
from the top of list or fetch a particular record and send it to client-side.
Feed has integration with websocket service [Centrifugo](https://github.com/centrifugal/centrifugo) and [Badges](https://github.com/perfumerlabs/badges) microservice.
If config for those integrations is set, then Feed will send pushes to the service by itself.
Also Feed will automatically delete badges from [Badges](https://github.com/perfumerlabs/badges) when read API is called.

Database tables
===============

After setup there are 1 predefined table in database:

### push_collection

Registry of collections. Fields:

- name [string] - Name of collection
- websocket_module [string] - module of websocket pushes.
- badges_collection [string] - collection of Badges to push badges to.
- badges_prefix [string] - prefix of badges names.

API Reference
=============

### Create a collection

`POST /collection`

Parameters (json):
- name [string,required] - name of the collection.
- websocket_module [string,optional] - module of websocket pushes.
- badges_collection [string,optional] - collection of Badges to push badges to.
- badges_prefix [string,optional] - prefix of badges names.

Request example:

```json
{
    "name": "foobar"
}
```

Response example:

```json
{
    "status": true
}
```

### Update collection

`PATCH /collection`

Parameters (json):
- name [string,required] - name of the collection.
- websocket_module [string,optional] - module of websocket pushes.
- badges_collection [string,optional] - collection of Badges to push badges to.
- badges_prefix [string,optional] - prefix of badges names.

Request example:

```json
{
    "websocket_module": "foobar"
}
```

Response example:

```json
{
    "status": true
}
```

### Create a record

`POST /record`

Request parameters (json):
- collection [string,required] - name of the collection.
- recipient [string,required] - name of record recipient.
- sender [string,optional] - name of record author.
- thread [string,optional] - additional field for tagging record.
- title [string,optional] - title of record.
- text [string,optional] - text of record.
- image [string,optional] - image of record.
- payload [json,optional] - any JSON-serializable content.
- created_at [datetime,optional] - date of record creation.
- websocket_channel [string,optional] - centrifugo channel to push event to (if not equal to "recipient").
- badge_user [string,optional] - badge user to save badge for (if not equal to "recipient").

Request example:

```json
{
    "collection": "foobar",
    "recipient": "client1",
    "sender": "client2",
    "thread": "chat",
    "title": "Hello",
    "text": "World",
    "image": "https://example.com/image.jpg",
    "payload": {
        "foo": "bar"
    }
}
```

Response parameters (json):
- id [integer] - unique identity of inserted document.
- recipient [string] - name of record recipient.
- sender [string] - name of record author.
- thread [string] - additional field for tagging record.
- title [string] - title of record.
- text [string] - text of record.
- image [string] - image of record.
- payload [json] - any JSON-serializable content.
- created_at [datetime] - date of creation.

Response example:

```json
{
    "status": true,
    "content": {
        "record": {
            "id": 1,
            "recipient": "client1",
            "sender": "client2",
            "thread": "chat",
            "title": "Hello",
            "text": "World",
            "image": "https://example.com/image.jpg",
            "payload": {
                "foo": "bar"
            },
            "created_at": "2020-10-01 00:00:00"
        }
    }
}
```

If "websocket_module" is set for collection, then websocket push will be sent:

```json
{
    "module": "{{websocket_module}}",
    "event": "{{collection_name}}.record",
    "content": {
        "record": {
            "id": 1,
            "recipient": "client1",
            "sender": "client2",
            "thread": "chat",
            "title": "Hello",
            "text": "World",
            "image": "https://example.com/image.jpg",
            "payload": {
                "foo": "bar"
            },
            "created_at": "2020-10-01 00:00:00"
        }
    }
}
```

If "badges_collection" is set for collection, then badge will be saved:

```json
{
    "collection": "{{badges_collection}}",
    "name": "{{badges_prefix if set}}}/{{collection_name}}/record/{{ID of inserted record}}",
    "user": "client1"
}
```

### Get a record

`GET /record`

Request parameters (json):
- collection [string,required] - name of the collection.
- id [integer,required] - the id of document.

Request example:

```json
{
    "collection": "foobar",
    "id": 1
}
```

Response parameters (json):
- id [integer] - unique identity of inserted document.
- recipient [string] - name of record recipient.
- sender [string] - name of record author.
- thread [string] - additional field for tagging record.
- title [string] - title of record.
- text [string] - text of record.
- image [string] - image of record.
- payload [json] - any JSON-serializable content.
- created_at [datetime] - date of creation.
- is_read [boolean] - whether record read or not.

Response example:

```json
{
    "status": true,
    "content": {
        "record": {
            "id": 1,
            "recipient": "client1",
            "sender": "client2",
            "thread": "chat",
            "title": "Hello",
            "text": "World",
            "image": "https://example.com/image.jpg",
            "payload": {
                "foo": "bar"
            },
            "is_read": false,
            "created_at": "2020-10-01 00:00:00"
        }
    }
}
```

### Delete a record

`DELETE /record`

Request parameters (json or URL):
- collection [string,required] - name of the collection.
- id [integer,required] - the id of document.

Request example:

```json
{
    "collection": "foobar",
    "id": 1
}
```

Response example:

```json
{
    "status": true
}
```

If "badges_collection" is set for collection, then badge will be removed:

```json
{
    "collection": "{{badges_collection}}",
    "name": "{{badges_prefix if set}}}/{{collection_name}}/record/{{ID of read record}}",
    "user": "client1"
}
```

### Get records

`GET /records`

Request parameters (json):
- collection [string,required] - name of the collection.
- recipient [string,required] - name of the recipient.
- sender [string,optional] - name of the sender.
- user [string,optional] - name of the sender or recipient. If user is present, recipient and sender will be ignored.
- thread [string,optional] - name of the thread.
- search [string,optional] - searching string in "title" or "text".
- id [integer,optional] - the id of document to start from.
- order [string,optional] - type of ordering ("asc" or "desc"). Default is "desc".
- is_read [boolean,optional] - if true return only read records, if false - only unread, if null - both. Default is "null".

Request example:

```json
{
    "collection": "foobar",
    "recipient": "client1"
}
```

Response parameters (json):
- id [integer] - unique identity of inserted document.
- recipient [string] - name of record recipient.
- sender [string] - name of record author.
- thread [string] - additional field for tagging record.
- title [string] - title of record.
- text [string] - text of record.
- image [string] - image of record.
- payload [json] - any JSON-serializable content.
- created_at [datetime] - date of creation.
- is_read [boolean] - whether record read or not.

Response example:

```json
{
    "status": true,
    "content": {
        "records": [
            {
                "id": 1,
                "recipient": "client1",
                "sender": "client2",
                "thread": "chat",
                "title": "Hello",
                "text": "World",
                "image": "https://example.com/image.jpg",
                "payload": {
                    "foo": "bar"
                },
                "is_read": false,
                "created_at": "2020-10-01 00:00:00"
            }
        ]
    }
}
```
### Update records
`PATCH /records`

Request parameters (json):
- collection [string,required] - name of the collection.
- where [object,required] - parameter object for WHERE.
- where.recipient [string,optional] - name of the recipient.
- where.sender [string,optional] - name of the sender.
- where.thread [string,optional] - name of the thread.
- where.user [string,optional] - name of the sender or recipient.
- set [object,required] - parameter object for SET.
- set.recipient [string, optional] - name of record recipient.
- set.sender [string, optional] - name of record author.
- set.thread [string, optional] - additional field for tagging record.
- set.user [string, optional] - name of the sender or recipient.
- set.title [string, optional] - title of record.
- set.text [string, optional] - text of record.
- set.image [string, optional] - image of record.
- set.payload [object, optional] - any JSON-serializable content.

Request example:

```json
{
    "collection": "foobar",
    "where": {
        "thread": "foo"
    },
    "set": {
        "sender": "bar",
        "payload": {
             "foo": "bar"
        }
    }
}
```

Response parameters (json):
- status [boolean] - result status.

Response example:

```json
{
    "status": true
}
```

### Read a record

`POST /record/read`

Request parameters (json):
- id [integer,required] - id of the record.
- collection [string,required] - name of the collection.
- badge_user [string,optional] - badge user to remove badge from (if not equal to "recipient").

Request example:

```json
{
    "collection": "foobar",
    "id": 1
}
```

Response example:

```json
{
    "status": true
}
```

If "badges_collection" is set for collection, then badge will be removed:

```json
{
    "collection": "{{badges_collection}}",
    "name": "{{badges_prefix if set}}}/{{collection_name}}/record/{{ID of read record}}",
    "user": "client1"
}
```

### Unread a record

`POST /record/unread`

Request parameters (json):
- id [integer,required] - id of the record.
- collection [string,required] - name of the collection.
- badge_user [string,optional] - badge user to save badge for (if not equal to "recipient").

Request example:

```json
{
    "collection": "foobar",
    "id": 1
}
```

Response example:

```json
{
    "status": true
}
```

If "badges_collection" is set for collection, then badge will be saved:

```json
{
    "collection": "{{badges_collection}}",
    "name": "{{badges_prefix if set}}}/{{collection_name}}/record/{{ID of read record}}",
    "user": "client1"
}
```

### Read all records of recipient

`POST /records/read`

Request parameters (json):
- recipient [string,required] - name of recipient.
- collection [string,required] - name of the collection.
- badge_user [string,optional] - badge user to remove badges from (if not equal to "recipient").

Request example:

```json
{
    "collection": "foobar",
    "recipient": "client1"
}
```

Response example:

```json
{
    "status": true
}
```

If "badges_collection" is set for collection, then badges will be removed:

```json
{
    "collection": "{{badges_collection}}",
    "name": "{{badges_prefix if set}}}/{{collection_name}}/record",
    "user": "client1"
}
```

### Delete all records of recipient

`DELETE /records`

Request parameters (json):
- recipient [string,required] - name of recipient.
- collection [string,required] - name of the collection.
- badge_user [string,optional] - badge user to remove badges from (if not equal to "recipient").

Request example:

```json
{
    "collection": "foobar",
    "recipient": "client1"
}
```

Response example:

```json
{
    "status": true
}
```

If "badges_collection" is set for collection, then badges will be removed:

```json
{
    "collection": "{{badges_collection}}",
    "name": "{{badges_prefix if set}}}/{{collection_name}}/record",
    "user": "client1"
}
```

Contributors
============

- Ilyas Makashev [mehmatovec@gmail.com](mailto:mehmatovec@gmail.com)
- Temirlan Kasen [temirlankasen@gmail.com](mailto:temirlankasen@gmail.com)

Software
========

1. Ubuntu 16.04 Xenial
1. Nginx 1.16
1. PHP 7.4