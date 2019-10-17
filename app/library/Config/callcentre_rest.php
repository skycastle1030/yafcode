<?php
return [
    'phone_number_decode' => 'http://api.clink.cn/interfaceAction/customerInterface!decode.action', //手机号解码

    'client_online' => 'http://api.clink.cn/interface/client/ClientOnline', //坐席上线
    'client_offline' => 'http://api.clink.cn/interface/client/ClientOffline', //坐席下线
    "change_bind_tel" => "http://api.clink.cn/interface/client/ChangeBindTel", //修改座席绑定电话
    "query_bind_tel" => "http://api.clink.cn/interfaceAction/clientInterface!queryBindTel.action", //查询座席绑定电话
    "un_bind_current_tel" => "http://api.clink.cn/interfaceAction/clientInterface!unBindCurrentTel.action", //删除座席绑定电话
    "client_change_status" => "http://api.clink.cn/interface/client/ChangeStatus", //修改座席状态

    'call' => 'http://api.clink.cn/interface/PreviewOutcall', //外呼
    'get_all_clients' => 'http://api.clink.cn/interfaceAction/clientInterface!list.action', //拉取全部坐席
    'get_call_history' => 'http://api.clink.cn/interfaceAction/cdrObInterface!listCdrOb.action', //拉取通话记录
];
