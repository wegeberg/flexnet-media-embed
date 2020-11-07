# flexnet-media-embed
A plugin for embedding media content to TinyMCE 4. Add Facebook, Twitter, YouTube, Vimeo, Soundcloud, Infogram

For a full example see https://www.flexnet.dk/github/flexnet-media-embed/example/

## Facebook
To embed Facebook posts you'll have to create an access token using your developer account.
See: https://developers.facebook.com/docs/graph-api/reference/oembed-post/

## Configuration
In /src/js/flexnet-media-embed.js:
- Set the value of fbAccessToken  to the generated access token.
- Set plugin_path to the relative path from site root
