function parse_userimage_and_callback(data, callback) {
    if(data.indexOf(INTERNAL_SERVER_ERROR) > -1){
        result = INTERNAL_SERVER_ERROR;
        callback(result,message);
        return;
    }
    
    var json = JSON.parse(data);
    console.log("this is user image json ");
    console.log(json);
    var status = json.status;
    var message = json.response.message;
    var order_id = json.response.orderid;
    var result;

    if (status === 1) {
        result = STATUS_SUCCESS;
        callback(result,order_id);
    } else {
        result = STATUS_FAIL;
        callback(result,order_id);
    }
}