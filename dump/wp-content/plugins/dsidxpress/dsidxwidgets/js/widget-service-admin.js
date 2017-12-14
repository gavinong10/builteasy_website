zWidgetsAdmin = {
    checkForAreaDataCallback: function (curObject) {
        if (curObject.historyError && curObject.historyError != 'undefined') {
            alert(curObject.historyError[0].Message);
        }
        else if (curObject['DataTrendLocationDataList'][0]['DataTrendRecordList'].length == 0) {
            alert('We were unable to find data for this property type/period/status combination. Please make a change to these values and try again.');
        }
    },
    checkForAreaData_: function (curControl, curRandomString, curTargetDomain, curAPIUrl, curAccountId, curSearchSetupId) {
        var randString = new String(Math.floor((Math.random() * 100) + 1)), topLevel = curControl.parentNode, curNodes, tractControlValue = '', communityControlValue = '', cityControlValue = '', stateControlValue = '', zipControlValue = '', propTypeControlValue = '', statusControlValue = '', periodControlValue = '', i, ii;

        topLevel = topLevel.parentNode;
        curNodes = topLevel.childNodes;

        for (i = 0; i < curNodes.length; i++) {//this code guarantees we have the correct id which can be a problem when creating the widget from scratch in ie8 and ff
            for (ii = 0; ii < curNodes[i].childNodes.length; ii++) {
                var curID = curNodes[i].childNodes[ii].id;
                if (curID) {
                    if (curID.indexOf('tract') > -1 && curNodes[i].childNodes[ii].className == 'widefat') {
                        tractControlValue = curNodes[i].childNodes[ii].value;
                        tractControlValue = tractControlValue.split(' ').join('+');
                    }
                    if (curID.indexOf('community') > -1 && curNodes[i].childNodes[ii].className == 'widefat') {
                        communityControlValue = curNodes[i].childNodes[ii].value;
                        communityControlValue = communityControlValue.split(' ').join('+');
                    }
                    if (curID.indexOf('city') > -1 && curNodes[i].childNodes[ii].className == 'widefat') {
                        cityControlValue = curNodes[i].childNodes[ii].value;
                        cityControlValue = cityControlValue.split(' ').join('+');
                    }
                    if (curID.indexOf('state') > -1 && curNodes[i].childNodes[ii].className == 'widefat') {
                        stateControlValue = curNodes[i].childNodes[ii].value;
                    }
                    if (curID.indexOf('zip') > -1 && curNodes[i].childNodes[ii].className == 'widefat') {
                        zipControlValue = curNodes[i].childNodes[ii].value;
                    }
                    if (curID.indexOf('propType') > -1 && curNodes[i].childNodes[ii].className == 'widefat') {
                        propTypeControlValue = curNodes[i].childNodes[ii].options[curNodes[i].childNodes[ii].selectedIndex].value;
                    }
                    if (curID.indexOf('periodType') > -1 && curNodes[i].childNodes[ii].className == 'widefat') {
                        periodControlValue = curNodes[i].childNodes[ii].options[curNodes[i].childNodes[ii].selectedIndex].value;
                    }
                    if (curID.indexOf('statusType') > -1 && curNodes[i].childNodes[ii].className == 'widefat') {
                        statusControlValue = curNodes[i].childNodes[ii].options[curNodes[i].childNodes[ii].selectedIndex].value;
                    }
                }
            }
        }

        var isIE7 = (navigator.userAgent.indexOf('MSIE 7.0') > -1) ? true : false, isIE8 = (navigator.userAgent.indexOf('MSIE 8.0') > -1 || navigator.userAgent.indexOf('MSIE 9.0') > -1) ? true : false;
        var params = 'corsresults/?requester.AccountID=' + curAccountId + '&query.SearchSetupID=' + curSearchSetupId + '&requester.ApplicationProfile=Widget&objectName_=mQ_' + curRandomString + randString + '&directive.HistoricalSearch=true&curDomain=' + curTargetDomain + '&querySchema=oXXi0sb6XR5WdK/vSGYAn12rcEoBW2Ngd/Oyx3/RCbZIV8mSvXrYIR4K5vWeaMSblc0c8/SrrXsKuifcmW5MBItoNGdGTYzHxJcxh9ISsYPoAsVhF+pY5eUXDftnio37&queryValues=' + (tractControlValue != '' ? '[' + tractControlValue + ']' : '[null]') + ',' + (communityControlValue != '' ? '[' + communityControlValue + ']' : '[null]') + ',' + (cityControlValue != '' ? '[' + cityControlValue + ']' : '[null]') + ',' + (stateControlValue != '' ? '[' + stateControlValue + ']' : '[null]') + ',' + (zipControlValue != '' ? '[' + zipControlValue + ']' : '[null]') + ',[' + statusControlValue + '],[' + propTypeControlValue + ']&directive.HistResultCols=DaysOnMarket,NumberProperties,NumberForeclosed,NumberPreforeclosures,AvgListPrice,AvgSalePrice,AvgListPricePerImprovedSqFt,AvgSalePricePerImprovedSqFt&directive.HistInterval=' + periodControlValue + '&directive.HistShowLocTree=false';
        var s = curAPIUrl + "&api_target=";
        var rr;

        if (isIE7) {
            rr = document.createElement('script');
            rr.async = true;
            rr.id = 'domainScript_' + randString;
            rr.type = "text/javascript";
            rr.src = s + encodeURIComponent(params + '&reportedDomain=' + window.location.hostname + '&authType=Basic');
            rr.onload = rr.onreadystatechange = function () {
                if (rr.readyState == "loaded") {
                    if (typeof window.error != 'undefined') {
                        alert('We had a problem authenticating this domain');
                    }
                    else {
                        var curObject = eval(window['mQ_' + curRandomString + randString]);
                        zWidgetsAdmin.checkForAreaDataCallback(curObject);
                    }
                }
            };
            document.getElementsByTagName('head')[0].appendChild(rr);
        }
        else {
            var invocation; // curURL = curAPIUrl + "Encrypt/?targetString=" + stringToEncrypt + "&targetObject=domain&authType=CORS&objectName_=error";
            s += encodeURIComponent(params + '&authType=CORS');
            invocation = new XMLHttpRequest(); ;
            if (invocation) {
                invocation.onreadystatechange = function (evtXHR) {
                    if (invocation.readyState == 4) {
                        if (invocation.status == 200) {
                            var curObject = eval(invocation.responseText);
                            if (typeof curObject['listingsError'] != 'undefined') {
                                alert(curObject['listingsError'][0]['Message']);
                            }
                            else {
                                zWidgetsAdmin.checkForAreaDataCallback(curObject);
                            }
                        }
                    }
                };
                invocation.open('GET', s + '&authType=CORS', true);
                invocation.send();
            }
        }
    },
    fetchEncryptedValue: function (stringToEncrypt, stringType, curControl, curAPIUrl) {
        var isIE7 = (navigator.userAgent.indexOf('MSIE 7.0') > -1) ? true : false, isIE8 = (navigator.userAgent.indexOf('MSIE 8.0') > -1 || navigator.userAgent.indexOf('MSIE 9.0') > -1) ? true : false;
        if (isIE7) {
            rr = document.createElement('script');
            rr.async = true;
            rr.id = 'domainScript';
            rr.type = "text/javascript";
            rr.src = curAPIUrl + "&api_target=" + encodeURIComponent("Encrypt/?targetString=" + stringToEncrypt + "&targetObject=domain&authType=Basic&curDomain=" + window.location.hostname + "&objectName_=error");
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
            var invocation, curURL = curAPIUrl + "&api_target=" + encodeURIComponent("Encrypt/?targetString=" + stringToEncrypt + "&targetObject=domain&authType=CORS&objectName_=error");
            invocation = new XMLHttpRequest();
            if (invocation) {
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
    }
}