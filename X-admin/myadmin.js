function myalert(msg) {
    switch (msg.state) {
        case "ignore":
            console.log(msg.msg);
            break;
        case "successful":
            layer.msg(msg.msg, {icon: 1, time: 1000});
            break;
        case "failed":
            layer.msg(msg.msg, {icon: 2, time: 1000});
            break;
    }
}