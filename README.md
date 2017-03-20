# Chat site with gleeph integration

-----------------------------------------------

## Users

### Hash `user:{userId}`

* `id` guid of this user
* `username` username of this user, case preserved
* `passwordHash`

### [Index] Hash `index:usernames`

* `key` username lowercased
* `value` user guid

-----------------------------------------------

## Chat Rooms

### Sorted Set `chat:messages:{chatId}`

* `{messageKeyN}`
* `{messageKeyN}`
* `{messageKeyN}`
* `{messageKeyN}`
* ...etc

> **Note:** The values stored in this sorted set are _not_ the message GUIDs. They
> are the full Redis key of the message hash, such as
> `message:02a723b2-7f33-4868-a962-51864c880273` or
> `gleeph:message:5877950d3f47a5022667871e`

Latest 100 message keys for a room:

```
redis-cli zrevrangebyscore chat:messages:dd0c62bd-c4f2-4286-affa-256bfcc93955 +inf -inf LIMIT 0 100
```

Latest 100 messages with full content for a room:

```
redis-cli zrevrangebyscore chat:messages:dd0c62bd-c4f2-4286-affa-256bfcc93955 +inf -inf LIMIT 0 100 | xargs -L 1 redis-cli hgetall
```


### Hash `message:{messageId}`

These are specifically messages sent from THIS client, not imported messages from gleeph (see `gleeph:message:{gleephMessageId}`).

* `id` guid of this message
* `sender` guid of the user who sent the message
* `roomId` guid of the room the message was sent to
* `message` text content of the message.
* `timestamp` unix timestamp to 0.1ms
* `media` (optional) media filenames, split by #


-----------------------------------------------

## gleeph integration data

### Hash: `gleeph:user:{gleephId}`

* `gleeph_id`
* `facet:Pseudonym`
* `facet:Username`
* `facet:First Name`
* `facet:Last Name`
* `facet:Email`
* `facet:Phone Number`
* `facet:Facebook Profile`
* `facet:Twitter`
* `last_update`
* `userId` (if linked)
* `identity` (if linked)
* `credential` (if linked)


### Hash: `gleeph:message:{gleephMessageId}`

* `id` (gleeph message id)
* `connection` (gleeph connection id)
* `sender` (gleeph user id)
* `timestamp`
* `text`
* `media` Formatted as `thumbnailID|fullsizeID#thumbnailID|fullsizeID`

-----------------------------------------------

## Communities (Not really used yet)

### Hash `community:{communityId}`

* `id`
* `path`
* `name`
* `ownerId`

### Set `community:members:{communityId}`

* `{memberUserIdN}`
* `{memberUserIdN}`
* `{memberUserIdN}`
* `{memberUserIdN}`
* ...etc

### Hash `index:community-paths`

* `{path}`: `{communityId}`


### Sorted Set `index:featured-communities`

* `{featuredCommunityIdN}`
* `{featuredCommunityIdN}`
* `{featuredCommunityIdN}`
* `{featuredCommunityIdN}`
* ...etc
