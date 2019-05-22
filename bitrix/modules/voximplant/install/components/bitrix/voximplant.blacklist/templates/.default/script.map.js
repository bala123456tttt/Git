{"version":3,"sources":["script.js"],"names":["GRID_ID","BX","namespace","Voximplant","Blacklist","settingsPopup","numbersInput","elements","numbersContainer","numberInput","addButton","settingsForm","saveSettingsButton","init","config","this","showSettings","ajax","runComponentAction","mode","then","response","SettingsPopup","data","onDestroy","bind","onSave","saveSettings","show","e","showWait","settings","destroy","showNumberInput","NumbersSlider","onNumbersSliderDestroy","grid","Main","gridManager","getInstanceById","reload","deleteNumber","numberId","confirm","message","alert","popup","callbacks","type","isFunction","DoNothing","prototype","PopupWindow","autoHide","closeByEsc","closeIcon","contentNoPaddings","contentColor","events","onPopupClose","onPopupDestroy","titleBar","content","render","buttons","PopupWindowCustomButton","text","className","click","onSaveButtonClick","renderRingCountOption","count","create","props","value","attrs","selected","ringsCount","renderIntervalOption","interval","children","id","checked","autoBlock","bxchange","currentTarget","for","parseInt","button","addClass","buttonNode","slider","textarea","saveButton","cancelButton","SidePanel","Instance","open","onLoad","onSliderLoad","onClose","onSliderClose","onSliderCloseComplete","onSliderDestroy","contentCallback","promise","Promise","setTimeout","resolve","createFragment","cols","rows","placeholder","onCancelButtonClick","focus","denyAction","numbers","split","close"],"mappings":"CAAC,WAEA,IAAIA,EAAU,uBAEdC,GAAGC,UAAU,iBAEbD,GAAGE,WAAWC,WACbC,cAAe,KACfC,aAAc,KAEdC,UACCC,iBAAkB,KAClBC,YAAa,KACbC,UAAW,KACXC,aAAc,KACdC,mBAAoB,MAErBC,KAAM,SAAUC,GAEfC,KAAKV,cAAgB,MAGtBW,aAAc,WAEb,GAAGD,KAAKV,cACR,CACC,OAGDJ,GAAGgB,KAAKC,mBAAmB,8BAA+B,eAAgBC,KAAM,UAAUC,KAAK,SAASC,GAEvGN,KAAKV,cAAgB,IAAIiB,GACxBC,KAAMF,EAASE,KACfC,UAAW,WAEVT,KAAKV,cAAgB,MACpBoB,KAAKV,MACPW,OAAQX,KAAKY,aAAaF,KAAKV,QAEhCA,KAAKV,cAAcuB,QAClBH,KAAKV,QAGRY,aAAc,SAAUE,GAEvBd,KAAKV,cAAcyB,WAEnB7B,GAAGgB,KAAKC,mBAAmB,8BAA+B,eACzDC,KAAM,QACNI,MACCQ,SAAUF,EAAEN,QAEXH,KAAK,WAEPL,KAAKV,cAAc2B,WAClBP,KAAKV,QAGRkB,gBAAiB,WAEhB,GAAGlB,KAAKT,aACR,CACC,OAGDS,KAAKT,aAAe,IAAI4B,GACvBV,UAAWT,KAAKoB,uBAAuBV,KAAKV,QAE7CA,KAAKT,aAAasB,QAGnBO,uBAAwB,WAEvBpB,KAAKT,aAAe,KACpB,IAAI8B,EAAOnC,GAAGoC,KAAKC,YAAYC,gBAAgBvC,GAC/C,GAAGoC,EACH,CACCA,EAAKI,WAIPC,aAAc,SAAUC,GAEvBzC,GAAGE,WAAWwC,QAAQ,IAAK1C,GAAG2C,QAAQ,6BAA6BxB,KAAK,WAEvEnB,GAAGgB,KAAKC,mBAAmB,8BAA+B,gBACzDC,KAAM,QACNI,MACCmB,SAAUA,KAETtB,KACF,WAEC,IAAIgB,EAAOnC,GAAGoC,KAAKC,YAAYC,gBAAgBvC,GAC/C,GAAGoC,EACH,CACCA,EAAKI,WAELf,KAAKV,MACP,WAECd,GAAGE,WAAW0C,MAAM5C,GAAG2C,QAAQ,yBAA0B3C,GAAG2C,QAAQ,iCAOzE,IAAItB,EAAgB,SAAUR,GAE7BC,KAAKQ,KAAOT,EAAOS,KACnBR,KAAK+B,MAAQ,KAEb/B,KAAKgC,WACJvB,UAAWvB,GAAG+C,KAAKC,WAAWnC,EAAOU,WAAaV,EAAOU,UAAYvB,GAAGiD,UACxExB,OAAQzB,GAAG+C,KAAKC,WAAWnC,EAAOY,QAAUZ,EAAOY,OAASzB,GAAGiD,YAIjE5B,EAAc6B,WACbvB,KAAM,WAELb,KAAK+B,MAAQ,IAAI7C,GAAGmD,YAAY,gCAAiC,MAChEC,SAAU,KACVC,WAAY,KACZC,UAAW,KACXC,kBAAmB,KACnBC,aAAc,QACdC,QACCC,aAAc,WAEb5C,KAAKiB,WACJP,KAAKV,MACP6C,eAAgB,WAEf7C,KAAK+B,MAAQ,MACZrB,KAAKV,OAER8C,SAAU5D,GAAG2C,QAAQ,4BACrBkB,QAAS/C,KAAKgD,SACdC,SACC,IAAI/D,GAAGgE,yBACNC,KAAMjE,GAAG2C,QAAQ,kBACjBuB,UAAW,kCACXT,QACCU,MAAOrD,KAAKsD,kBAAkB5C,KAAKV,YAKvCA,KAAK+B,MAAMlB,QAGZ0C,sBAAuB,SAASC,GAE/B,OAAOtE,GAAGuE,OAAO,UAChBC,OAAQC,MAAOH,GACfI,OAAQC,SAAU7D,KAAKQ,KAAKsD,YAAcN,GAC1CL,KAAMK,KAIRO,qBAAsB,SAASC,GAE9B,OAAO9E,GAAGuE,OAAO,UAChBC,OAAQC,MAAOK,GACfJ,OAAQC,SAAU7D,KAAKQ,KAAKwD,UAAYA,GACxCb,KAAMa,KAIRhB,OAAQ,WAEP,OAAO9D,GAAGuE,OAAO,OAChBC,OAAQN,UAAW,8BACnBa,UACC/E,GAAGuE,OAAO,OACTC,OAAQN,UAAW,kCACnBa,UACC/E,GAAGuE,OAAO,SACTC,OACCQ,GAAI,2CACJd,UAAW,gCACXnB,KAAM,WACNkC,QAASnE,KAAKQ,KAAK4D,WAAa,KAEjCzB,QACC0B,SAAU,SAASvD,GAElBd,KAAKQ,KAAK4D,UAAYtD,EAAEwD,cAAcH,QAAU,IAAM,KACrDzD,KAAKV,SAGTd,GAAGuE,OAAO,SACTG,OAAQW,IAAK,4CACbb,OAAQN,UAAW,8BACnBD,KAAMjE,GAAG2C,QAAQ,yBAIpB3C,GAAGuE,OAAO,OACTC,OAAQN,UAAW,kCACnBa,UACC/E,GAAGuE,OAAO,OACTC,OAAQN,UAAW,0BACnBa,UACC/E,GAAGuE,OAAO,OACTC,OAAQN,UAAW,+BACnBD,KAAMjE,GAAG2C,QAAQ,+BAElB3C,GAAGuE,OAAO,UACTC,OAAQN,UAAW,6BACnBT,QACC0B,SAAU,SAASvD,GAElBd,KAAKQ,KAAKsD,WAAaU,SAAS1D,EAAEwD,cAAcX,QAC/CjD,KAAKV,OAERiE,UACCjE,KAAKuD,sBAAsB,GAC3BvD,KAAKuD,sBAAsB,GAC3BvD,KAAKuD,sBAAsB,GAC3BvD,KAAKuD,sBAAsB,GAC3BvD,KAAKuD,sBAAsB,GAC3BvD,KAAKuD,sBAAsB,GAC3BvD,KAAKuD,sBAAsB,GAC3BvD,KAAKuD,sBAAsB,GAC3BvD,KAAKuD,sBAAsB,GAC3BvD,KAAKuD,sBAAsB,UAK/BrE,GAAGuE,OAAO,OACTC,OAAQN,UAAW,0BACnBa,UACC/E,GAAGuE,OAAO,OACTC,OAAQN,UAAW,+BACnBD,KAAMjE,GAAG2C,QAAQ,uCAElB3C,GAAGuE,OAAO,UACTC,OAAQN,UAAW,6BACnBT,QACC0B,SAAU,SAASvD,GAElBd,KAAKQ,KAAKwD,SAAWQ,SAAS1D,EAAEwD,cAAcX,QAC7CjD,KAAKV,OAERiE,UACCjE,KAAK+D,qBAAqB,GAC1B/D,KAAK+D,qBAAqB,GAC1B/D,KAAK+D,qBAAqB,IAC1B/D,KAAK+D,qBAAqB,IAC1B/D,KAAK+D,qBAAqB,IAC1B/D,KAAK+D,qBAAqB,iBAWnChD,SAAU,WAET,IAAI0D,EAASzE,KAAK+B,MAAMkB,QAAQ,GAEhC/D,GAAGwF,SAASD,EAAOE,WAAY,gBAGhCrB,kBAAmB,SAASxC,GAE3Bd,KAAKgC,UAAUrB,QACdH,KAAMR,KAAKQ,QAIbS,QAAS,WAER,GAAGjB,KAAK+B,MACR,CACC/B,KAAK+B,MAAMd,UAGZjB,KAAK+B,MAAQ,KAEb/B,KAAKgC,UAAUvB,cAIjB,IAAIU,EAAgB,SAASpB,GAE5BC,KAAK4E,OAAS,KACd5E,KAAKR,UACJqF,SAAU,KACVC,WAAY,KACZC,aAAc,MAGf/E,KAAKgC,WACJvB,UAAWvB,GAAG+C,KAAKC,WAAWnC,EAAOU,WAAaV,EAAOU,UAAYvB,GAAGiD,YAI1EhB,EAAciB,WACbvB,KAAM,WAEL3B,GAAG8F,UAAUC,SAASC,KAAK,4BAC1BvC,QACCwC,OAAQnF,KAAKoF,aAAa1E,KAAKV,MAC/BqF,QAASrF,KAAKsF,cAAc5E,KAAKV,MACjCuF,sBAAuBvF,KAAKuF,sBAAsB7E,KAAKV,MACvDS,UAAWT,KAAKwF,gBAAgB9E,KAAKV,OAEtCyF,gBAAiB,SAASb,GACzB,IAAIc,EAAU,IAAIxG,GAAGyG,QACrB3F,KAAK4E,OAASA,EAEdgB,WAAW,WACVF,EAAQG,QAAQ7F,KAAKgD,WACpBtC,KAAKV,MAAO,GAEd,OAAO0F,GACNhF,KAAKV,SAITgD,OAAQ,WAEP,OAAO9D,GAAG4G,gBACT5G,GAAGuE,OAAO,OACTC,OAAQN,UAAW,oCACnBa,UACC/E,GAAGuE,OAAO,OACTC,OAAQN,UAAW,+BACnBa,UACC/E,GAAGuE,OAAO,QACTN,KAAMjE,GAAG2C,QAAQ,uCAMtB3C,GAAGuE,OAAO,OACTC,OAAQN,UAAW,wBACnBa,UACC/E,GAAGuE,OAAO,OACTC,OAAQN,UAAW,4BACnBa,UACC/E,GAAGuE,OAAO,OACTC,OAAQN,UAAW,iCACnBD,KAAMjE,GAAG2C,QAAQ,oCAElB7B,KAAKR,SAASqF,SAAW3F,GAAGuE,OAAO,YAClCC,OAAQN,UAAW,iCACnBQ,OACCmC,KAAM,GACNC,KAAM,GACNC,YAAa/G,GAAG2C,QAAQ,6BAK5B3C,GAAGuE,OAAO,OACTC,OAAQN,UAAW,6BACnBD,KAAMjE,GAAG2C,QAAQ,mCAIpB3C,GAAGuE,OAAO,OACTC,OAAQN,UAAW,2BACnBa,UACCjE,KAAKR,SAASsF,WAAa5F,GAAGuE,OAAO,UACpCC,OAAQN,UAAW,yBACnBD,KAAMjE,GAAG2C,QAAQ,kBACjBc,QACCU,MAAOrD,KAAKsD,kBAAkB5C,KAAKV,SAGrCA,KAAKR,SAASuF,aAAe7F,GAAGuE,OAAO,UACtCC,OAAQN,UAAW,sBACnBD,KAAMjE,GAAG2C,QAAQ,oBACjBc,QACCU,MAAOrD,KAAKkG,oBAAoBxF,KAAKV,eAQ3CoF,aAAc,WAEbpF,KAAKR,SAASqF,SAASsB,SAGxBb,cAAe,SAASxE,GAEvB,GAAGd,KAAKR,SAASqF,SAASlB,OAAS,GACnC,CACC7C,EAAEsF,iBAGH,CACCpG,KAAK4E,OAAO3D,YAIdsE,sBAAuB,SAASzE,GAE/Bd,KAAK4E,OAAO3D,WAGbuE,gBAAiB,SAAS1E,GAEzBd,KAAK4E,OAAS,KACd5E,KAAKgC,UAAUvB,aAGhB6C,kBAAmB,SAASxC,GAE3B,IAAIuF,EAAUrG,KAAKR,SAASqF,SAASlB,MAAM2C,MAAM,MAEjDpH,GAAGgB,KAAKC,mBAAmB,8BAA+B,cACzDC,KAAM,QACNI,MACC6F,QAASA,KAERhG,KAAK,SAASC,GAEhBN,KAAKR,SAASqF,SAASlB,MAAQ,GAC/B3D,KAAK4E,OAAO2B,SACX7F,KAAKV,QAGRkG,oBAAqB,SAASpF,GAE7Bd,KAAKR,SAASqF,SAASlB,MAAQ,GAC/B3D,KAAK4E,OAAO2B,WAxbd","file":"script.map.js"}