function Chat(roomId) {

    this.roomId         = roomId;
    this.loadedMessages  = {};


    this.reset = function() {
        this.loadedMessages = {};
    }

    this.loadMessages = function(limit, before) {

        params = {};
        if (limit)  params.limit  = limit;
        if (before) params.before = before;

        $.ajax({
          dataType: 'json',
          tryCount : 0,
          retryLimit : 50000,
          url: '/messages/' + this.roomId,
          data: params,
          success: xhrSuccess.bind(this),
          error: xhrError
        });
    }

    var xhrError = function(xhr, textStatus, errorThrown) {
        $('svg.logo')
          .removeClass('connected')
          .addClass('disconnected');
        this.tryCount++;
        if (this.tryCount <= this.retryLimit) {
          console.log('Error getting messages. Retrying...');
          setTimeout(function(xhr) {
              $.ajax(xhr);
          }.bind(null, this), 1500);
        }
    }

    var xhrSuccess = function(fetchedMessages) {
        this.newMessageCount = 0;
        fetchedMessages.forEach((function(message) {
            if (this.loadedMessages[message.id]) {
                return;
            }
            this.newMessageCount++;
            this.loadedMessages[message.id] = true;
            EventBus.dispatch('chat:message-loaded', this, message);
        }).bind(this));

        EventBus.dispatch('chat:messages-loaded', this, this.newMessageCount);
    }
}
