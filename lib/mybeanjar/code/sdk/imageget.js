var userimages = [];
var userimagesArray = [];
var numberOfImages;

function parse_userimages(userimages) {
    for (var i = 0; i < numberOfImages; i++) {
        push_to_userimages(userimages, i);
    }
    return userimages;
}

function push_to_userimages(userimages, i) {
    userimages.push({
        image: userimagesArray[i].imagename,
    });
}

function parse_userimageget_and_callback(data, callback) {

    if(data.indexOf(INTERNAL_SERVER_ERROR) > -1){
        result = INTERNAL_SERVER_ERROR;
        callback(result,userimages);
        return;
    }

    userimages = [];
    userimagesArray = [];
    var json = JSON.parse(data);
    var status = json.status;
    var result;
    if (status === 1) {    
        userimagesArray = json.response.userimages;
        numberOfImages = userimagesArray.length;
        parse_userimages(userimages);
        result = STATUS_SUCCESS;
        callback(result,userimages);
    } else {
        result = STATUS_FAIL;
        callback(result,userimages);
    }
}