# v0.8.0
+ Remove template field.

# v0.7.1
+ Add asset metadata endpoint.

# v0.7.0
+ Add support for allocate link endpoint.
+ Add support for serial endpoints.
+ Add support for short code endpoints.
+ Rejig exception handling so that it's not repeated for each endpoint.

# v0.6.3
+ Fix decryption of short codes in CreateLinksResponseData.

# v0.6.2
+ Speed up the creation of CreateLinksResponseData.

# v0.6.1
+ Support the redeem API returning ShowLinkResponseData.

# v0.6.0
+ Add support for decrypting short codes.

# v0.5.0
+ Add support for show-link-group endpoint.
+ Add support for short codes on create endpoint.
+ Update CreateLinksResponseData.php to support detailed responses.
+ Add support for additional headers.

# v0.4.2
+ Add support for mixed Meta types.
+ Fix bug with exception handling  where request body would appear empty.

# v0.4.1
+ Add `CachedTokenResolver` for caching token resolution.

# v0.4.0
+ Add support for Meta field.

# v0.3.0
+ Support updated create link response.
+ Remove unused expiration field from LinkData.

# v0.2.4

+ Allow ShowLinkResponseData value to store a boolean value.

# v0.2.3

+ Add template to show link response.

# v0.2.2

+ Update composer.json to use semantic versioning for mblsolutions/link-module-php package

# v0.2.1

+ Add support for `cancel` link endpoint

# v0.2.0

+ Add Support for update links endpoint
+ Add the ability to disable open auth for development purposes
+ Update readme to include installation information
+ Fix status enum typo

# v0.1.2

+ Handle requests which don't return a response
+ Update show link response to allow NULL & array values

# v0.1.1

+ Update show to use it's own DTO

# v0.1.0

+ Initial release