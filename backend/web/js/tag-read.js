var tagRead = {
    baseUrl: 'http://127.0.0.1:10288',
    _http: function (url, data, successCallback) {
        $.ajax({
            type: 'GET',
            url: tagRead.baseUrl + url,
            data: data,
            success: function(data) {
                if(data.Code == 200) {
                    if(successCallback) {
                        successCallback(data.Data);
                    }
                } else {
                    alert('读取失败');
                }
            },
            timeout: 2000,
            complete: function (XMLHttpRequest, status) {
                if (status == 'timeout') {
                    alert("读取失败,设备未连通");
                }
            }
        });
    },
    get: function (successCallback) {
        tagRead._http('/get', {}, successCallback);
    },
    beginSession: function (id, successCallback) {
        tagRead._http('/begin-session', {id:id}, successCallback);
    },
    session: function (id, successCallback) {
        tagRead._http('/session', {id:id}, successCallback);
    },
    endSession: function (id, successCallback) {
        tagRead._http('/end-session', {id:id}, successCallback);
    }
};

$('body').on('click', '.read_tag', function() {
    var _this = $(this);
    tagRead.get(function(data) {
        _this.parents('.input-group').find('input').val(data);
    });
});