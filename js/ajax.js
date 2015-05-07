function ajax(opts) {
    var mod = opts.mod || 'GET',
        url = opts.url || '',
        getadat = opts.getadat || '',
        postadat = opts.postadat || '',
        siker = opts.siker || function() {},
        hiba = opts.hiba || function() {};
    
    mod = mod.toUpperCase();
    url = url + '?' + getadat;
    
    var xhr = new XMLHttpRequest();
    
    xhr.open(mod, url, true);
    if (mod === 'POST') {
        xhr.setRequestHeader('Content-Type',
            'application/x-www-form-urlencoded');
    }

    xhr.addEventListener('readystatechange', function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                siker(xhr, xhr.responseText);
            } else {
                hiba(xhr);
            }
        }
    }, false);
    
    xhr.send(mod == 'POST' ? postadat : null);
    
    return xhr;
}