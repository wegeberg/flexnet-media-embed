/*
    Title: Flexnet Media Embed
    Version: 1.0.1, 2020-09-15
    Author: Martin Wegeberg, 2019-
    Description:  A plugin for embedding media content to TinyMCE 4.
    Add Facebook, Twitter, YouTube, Vimeo, Soundcloud, Infogram

    For full example see: https://www.flexnet.dk/github/flexnet-media-embed/example/
*/

// Relative path from site-root to this folder
const plugin_path = "../";



/* No need to change anything below this line */
tinymce.PluginManager.add('flexnet-media-embed', function(editor, url) {
    var icon_url = plugin_path + 'src/images/fl.png';


    editor.on('init', function (args) {
        editor_id = args.target.id;

    });
    editor.addButton('flexnet-media-embed', {
        text:false,
        icon: true,
        image:icon_url,
        tooltip: 'Embed Tweet, FB, YouTube, Vimeo, Soundcloud, Infogram',

        onclick: function () {

            editor.windowManager.open({
                title: 'Flexnet URL embed',

                body: [
                    {
                        type: 'textbox',
                        size: 40,
                        height: '100px',
                        name: 'url',
                    },
                    {
                        type: 'label',
                        label: 'URL for the embedded content (Facebook, Twitter, YouTube, Vimeo, Soundcloud, Infogram)'
                    }
                ],
                onsubmit: function(e) {
                    if(e.data.url.indexOf("facebook") > 0) {
                        getFacebook(e.data.url);
                    } else {
                        const url = "../src/api/flexnet-media-embed.php?url=" + e.data.url;
                        $.getJSON(url, function(data, status) {
                            if(data.success) {
                                tinyMCE.activeEditor.insertContent(
                                    '<p>' + data.result.html + '</p>'
                                );
                                $("#preview").html(data.result.html);
                            } else {
                                alert("Returned error: " + data.error);
                            }
                        })
                        .fail(function() {
                            console.log( "getJSON error:", url );
                        });
                    }

                }
            });
        }
    });
});

function getFacebook(facebookUrl) {
    $.ajax({
        url: "https://www.facebook.com/plugins/post/oembed.json/?url="+facebookUrl,
        dataType: "jsonp",
        async: false,
        success: function(data) {
            // console.log(data);
            // $("#embedCode").val(data.html);
            $("#preview").html(data.html)
            tinyMCE.activeEditor.insertContent(
                data.html
            );
        },
        error: function (jqXHR, exception) {
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }
            alert(msg);
        },
    });
}
