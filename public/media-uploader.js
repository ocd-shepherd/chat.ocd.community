function MediaUploader(mediaBlobs) {
    var mediaBlobs = mediaBlobs;

    var media = {};
    var self  = this;

    var blobCount = 0;
    var uploadedCount = 0;

    for (var i = 0; i < mediaBlobs.length; i++) {
        if (mediaBlobs[i].lowRes)  blobCount++;
        if (mediaBlobs[i].highRes) blobCount++;
    }

    this.fetchMediaIdsForUpload = function(callback) {

        var total = blobCount;
        var count = 0;

        for (var i = 0; i < mediaBlobs.length; i++) {
            (function(key) {
                var thisBlob = mediaBlobs[key].lowRes.blob;
                $.ajax({
                    url: '/media',
                    contentType: thisBlob.type,
                }).done(function(data) {
                    mediaBlobs[key].lowRes.id = data.mediaId;
                    count++;
                    if (count > total - 1) (callback.bind(self))(mediaBlobs);

                    if (mediaBlobs[key].highRes) {
                        var thisBlob = mediaBlobs[key].highRes.blob;
                        $.ajax({
                            url: '/media',
                            contentType: thisBlob.type,
                        }).done(function(data) {
                            mediaBlobs[key].highRes.id = data.mediaId;
                            count++;
                            if (count > total - 1) (callback.bind(self))(mediaBlobs);
                        });
                    }
                });
            })(i);
        }

    };

    this.getMediaFilenames = function() {
        return Object.keys(mediaBlobs).map(function(i) {
            var m   = mediaBlobs[i].lowRes;
            var ext = (m.blob.type == 'image/jpeg' ? '.jpg' : false) ||
                      (m.blob.type == 'image/png'  ? '.png' : false) ||
                      (m.blob.type == 'image/gif'  ? '.gif' : false) ||
                      '.wtf';
            var result = {lowRes: m.id + ext};

            if (mediaBlobs[i].highRes) {
                var m   = mediaBlobs[i].highRes;
                var ext = (m.blob.type == 'image/jpeg' ? '.jpg' : false) ||
                          (m.blob.type == 'image/png'  ? '.png' : false) ||
                          (m.blob.type == 'image/gif'  ? '.gif' : false) ||
                          '.wtf';
                result.highRes = m.id + ext;
            }

            return result;
        });
    }

    function xhrUpload(i, key, callback) {
        if (!mediaBlobs[i][key]) {
            console.log('Media ' + i + ' does not have key ' + key);
            return;
        }
        var mediaId = mediaBlobs[i][key].id;
        var oReq = new XMLHttpRequest();
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
                uploadedCount++;
                if (uploadedCount > blobCount - 1) (callback.bind(self))();
            });
        };
        oReq.send(mediaBlobs[i][key].blob);
    }

    this.upload = function(callback) {

        for (var i = 0; i < mediaBlobs.length; i++) {
            xhrUpload(i, 'lowRes', callback);
        }

        for (var i = 0; i < mediaBlobs.length; i++) {
            xhrUpload(i, 'highRes', callback);
        }

    };
};

