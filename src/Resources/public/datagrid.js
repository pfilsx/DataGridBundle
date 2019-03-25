document.addEventListener('DOMContentLoaded', function () {
    var filterElements = document.getElementsByClassName('data_grid_filter');
    for (var i = 0; i < filterElements.length; i++) {
        var element = filterElements[i];
        if (element.tagName.toLowerCase() == 'input') {
            filterElements[i].addEventListener('keypress', function (e) {
                if ((typeof (e.code) !== 'undefined' && e.code == 'Enter') || e.keyCode == 13) {
                    e.preventDefault();
                    _datagrid_setFilter(this);
                }
            });
        } else if (element.tagName.toLowerCase() == 'select') {
            filterElements[i].addEventListener('change', function () {
                _datagrid_setFilter(this);
            });
        }
    }
});

function _datagrid_setFilter(obj) {
    var filter = {};
    filter['data_grid[' + obj.getAttribute('name') + ']'] = obj.value;
    _datagrid_reloadGrid(filter);
}

function _datagrid_reloadGrid(data) {
    var url = window.location.href.split('?');
    var params = _datagrid_getUrlParams();
    params = Object.assign(params, data);
    var resultFilters = [];
    for (var prop in params) {
        if (params.hasOwnProperty(prop) && params[prop].trim() == '') {
            delete params[prop];
        } else {
            resultFilters.push(prop + '=' + params[prop]);
        }
    }
    window.location.href = url[0] + '?' + resultFilters.join('&');
}

function _datagrid_getUrlParams() {
    var url = decodeURIComponent(window.location.href).split('?');
    var params = {};
    if (url[1]) {
        url[1].split('&').forEach(function (val) {
            var parts = val.split('=');
            if (parts[1]) {
                params[parts[0]] = parts[1];
            }
        });
    }
    return params;
}
