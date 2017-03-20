function MediaManager() {
    var media = {};
    var self  = this;

    this.addBlobs = function(blobs, callback) {
        var total = blobs.length;
        var count = 0;
        for (var i = 0; i < total; i++) {
            (function(key) {
                var thisBlob = blobs[key];
                $.ajax({
                    url: '/media',
                    contentType: thisBlob.type,
                }).done(function(data) {
                    media[data.mediaId] = {id: data.mediaId, blob: thisBlob};
                    count++;
                    if (count > total - 1) (callback.bind(self))(media);
                });
            })(i);
        }
    };

    this.getMediaFilenames = function() {
        return Object.keys(media).map(function(mediaId) {
            var m   = media[mediaId];
            var ext = (m.blob.type == 'image/jpeg' ? '.jpg' : false) ||
                      (m.blob.type == 'image/png'  ? '.png' : false) ||
                      (m.blob.type == 'image/gif'  ? '.gif' : false) ||
                      '.wtf';

            return m.id + ext;
        });
    };

    this.upload = function() {
        for (var mediaId in media) {
            (function(mediaId) {
                var oReq = new XMLHttpRequest();
                oReq.mediaId = mediaId;
                media[mediaId].request = oReq;
                oReq.upload.addEventListener('progress', function(e) {
                    var percent = e.loaded / e.total * 100;
                    EventBus.dispatch(
                        'chat:media-upload-progress',
                        {'mediaId': mediaId, 'percent': percent}
                    );
                });
                oReq.open('POST', '/media/' + mediaId, true);
                oReq.onload = function (oEvent) {
                    var mediaId = JSON.parse(this.responseText).mediaId;

                    $.ajax({
                        url: '/media/' + mediaId,
                        type: 'GET',
                    }).done(function(data) {
                        EventBus.dispatch(
                            'chat:media-upload-complete',
                            mediaId
                        );
                    });
                };
                oReq.send(media[mediaId].blob);
            })(mediaId);
        }
    };
};
