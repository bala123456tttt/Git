{"version":3,"sources":["script.js"],"names":["SonetGroupCardSlider","this","instance","currentUserId","userRole","canInitiate","canModify","groupId","isProject","waitPopup","waitTimeout","notifyHintPopup","notifyHintTimeout","notifyHintTime","favoritesValue","styles","urls","containerNodeId","subscribeButtonNodeId","menuButtonNodeId","editFeaturesAllowed","getInstance","prototype","init","params","parseInt","groupType","isOpened","canProcessRequestsIn","userIsMember","userIsAutoMember","BX","type","isNotEmptyString","i","tags","box","item","tagBlockList","findChildren","className","length","addEventListener","delegate","e","tagValue","getEventTarget","getAttribute","clickTag","preventDefault","users","userBlockList","userId","clickUser","fav","switch","favBlockList","setFavorites","bind","event","setSubscribe","sonetGroupMenu","SocialnetworkUICommon","SonetGroupMenu","showGroupMenuPopup","bindElement","perms","requestUser","message","edit","delete","features","members","requests","requestsOut","userRequestGroup","userLeaveGroup","addCustomEvent","getEventId","eventData","getData","code","data","group","ID","reload","util","in_array","window","top","SidePanel","Instance","getSliderByWindow","close","location","href","groupsList","eventParams","value","targetNode","findChild","switchFavorites","action","hasClass","switchSubscribe","ajax","url","method","dataType","groupID","sessid","bitrix_sessid","onsuccess","SUCCESS","RESULT","postMessageAll","ERROR","processAJAXError","onerror","_this","currentValue","newValue","setItemTitle","setFavoritesAjax","callback","success","NAME","URL","onCustomEvent","id","name","extranet","EXTRANET","failure","node","active","activeSwitch","addClass","removeClass","innerHTML","showNotifyHint","errorCode","indexOf","showError","showWait","timeout","setTimeout","PopupWindow","autoHide","lightShadow","zIndex","content","create","props","children","html","setBindElement","show","closeWait","clearTimeout","el","hint_text","style","display","closeByEsc","closeIcon","offsetLeft","offsetTop","TEXT","setAngle","errorText","errorPopup","Math","random","replace"],"mappings":"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","file":""}