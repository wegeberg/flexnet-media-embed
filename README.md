# flexnet-media-embed
*Version: 1.0.2, 2020-11-06*

A plugin for embedding media content in TinyMCE 4. 
Add Facebook, Twitter, YouTube, Vimeo, Soundcloud, Infogram

For a full example see https://www.flexnet.dk/github/flexnet-media-embed/example/

## Facebook
To embed Facebook posts you'll have to create an access token using your developer account.
See: https://developers.facebook.com/docs/graph-api/reference/oembed-post/

## Configuration
In /src/js/flexnet-media-embed.js:
- Set the value of fbAccessToken  to the generated access token.
- Set icon_url to the path from site root to the plugin icon
- If you have already included the necessary javascript for embedding FB posts set omitscript to "true"
