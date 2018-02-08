var tagRead = {
    baseUrl: 'http://127.0.0.1:10888',
    _http: function (url, data, successCallback, errorCallback, timeoutCallback) {
        $.ajax({
            type: 'GET',
            url: tagRead.baseUrl + url,
            data: data,
            success: function (data) {
                if (data.Code == 200) {
                    if (successCallback) {
                        successCallback(data.Data);
                    }
                } else {
                    if (!errorCallback) {
                        alert('读取失败');
                    } else {
                        errorCallback(data.Data);
                    }
                }
            },
            error: function () {
                if(!timeoutCallback) {
                    alert("读取失败,设备未连通");
                } else {
                    timeoutCallback();
                }
            },
            timeout: 2000,
            complete: function (XMLHttpRequest, status) {
                if (status === 'timeout') {
                    if(!timeoutCallback) {
                        alert("读取失败,请求超时");
                    } else {
                        timeoutCallback();
                    }
                }
            }
        });
    },
    // 读取无源标签
    get: function (successCallback) {
        tagRead._http('/get', {}, successCallback);
    },
    // 扫描无源设备
    beginSession: function (id, successCallback) {
        tagRead._http('/begin-session', {id: id}, successCallback);
    },
    session: function (id, successCallback) {
        tagRead._http('/session', {id: id}, successCallback);
    },
    endSession: function (id, successCallback) {
        tagRead._http('/end-session', {id: id}, successCallback);
    },
    // 检查有源设备是否在线
    checkActiveStatus: function (successCallback, errorCallback, timeoutCallback) {
        tagRead._http('/check-y', {}, successCallback, errorCallback, timeoutCallback);
    },
    // 检查无源设备是否在线
    checkPassiveStatus: function (successCallback, errorCallback, timeoutCallback) {
        tagRead._http('/check-w', {}, successCallback, errorCallback, timeoutCallback);
    },
};

$('body').on('click', '.read_tag', function () {
    var _this = $(this);
    tagRead.get(function (data) {
        _this.parents('.input-group').find('input').val(data);
    });
});