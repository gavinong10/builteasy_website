zWidgetsAdmin = {
    fetchEncryptedValue: function (stringToEncrypt, stringType, curControl, curAPIUrl) {
        var isIE7 = (navigator.userAgent.indexOf('MSIE 7.0') > -1) ? true : false, isIE8 = (navigator.userAgent.indexOf('MSIE 8.0') > -1 || navigator.userAgent.indexOf('MSIE 9.0') > -1) ? true : false;
        if (isIE7) {
            rr = document.createElement('script');
            rr.async = true;
            rr.id = 'domainScript';
            rr.type = "text/javascript";
            rr.src = curAPIUrl + "Encrypt/?targetString=" + stringToEncrypt + "&targetObject=domain&authType=Basic&curDomain=" + window.location.hostname + "&objectName_=error";
            rr.onload = rr.onreadystatechange = function () {
                if (typeof window.error != 'undefined') {
                    alert('We had a problem authenticating this domain');
                }
                else {
                    curControl.value = window['encrypted_' + stringType];
                }
            };
            document.getElementsByTagName('head')[0].appendChild(rr);
        }
        else {
            var invocation, curURL = curAPIUrl + "Encrypt/?targetString=" + stringToEncrypt + "&targetObject=domain&authType=CORS&objectName_=error";
            if (isIE8) {
                invocation = new window.XDomainRequest();
            }
            else {
                invocation = new XMLHttpRequest(); ;
            }
            if (invocation) {
                if (isIE8) {/**IE8 CORS uses proprietary object for this communication */
                    invocation.onload = function () {
                        curControl.value = eval(invocation.responseText);
                    };
                    invocation.open("GET", curURL);
                    invocation.send();
                }
                else {/**all other browsers use native standard stuff for CORS*/
                    invocation.onreadystatechange = function (evtXHR) {
                        if (invocation.readyState == 4) {
                            if (invocation.status == 200) {
                                var curObject = eval(invocation.responseText);
                                if (typeof curObject['listingsError'] != 'undefined') {
                                    alert(curObject['listingsError'][0]['Message']);
                                }
                                else {
                                    curControl.value = window['encrypted_' + stringType];
                                }
                            }
                            else {
                                alert("Invocation Errors Occured");
                            }
                        }
                    };
                    invocation.open('GET', curURL, true);
                    invocation.send();
                }
            }
            else {
                alert("No Invocation TookPlace At All");
            }
        }
    }
}