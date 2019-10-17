<?php
$data = [
    //公用
    'common' => [
        //服务项创建
        'created' => [
            'sub' => '服务项已创建',
            'desc' => '时间:%s',
        ],
        //服务项已开启
        'opened' => [
            'sub' => '服务项已开启',
            'desc' => '时间:%s',
        ],
        //服务项已完成
        'finished' => [
            'sub' => '服务已完成',
            'desc' => '时间:%s',
        ],
        //用户已评价
        'evaluated' => [
            'sub' => '您已做出评价',
            'desc' => '%s',
        ],
        //用户发起投诉
        'complaint' => [
            'sub' => '您发起了投诉',
            'desc' => '投诉工单号:%s',
        ],
        //安排顾问
        'counselor_assigned' => [
            'sub' => '服务顾问指派',
            'desc' => '%s老师竭诚为您服务',
        ],
        //更换顾问
        'counselor_reassigned' => [
            'sub' => '更换顾问',
            'desc' => '系统为您更换了服务顾问,当前服务顾问:%s',
        ],
        //服务时间确定
        'service_time_decided' => [
            'sub' => '服务时间确定',
            'desc' => '服务时间确定为%s',
        ],
        //服务时间变更
        'service_time_changed' => [
            'sub' => '服务时间变更',
            'desc' => '服务时间变更为%s',
        ],
    ],
    'wzly' => [
        'date_planned' => [
            'sub' => '已为您排约',
            'desc' => '排约对象:%s',
        ],
    ],
    'aqqw' => [
        'emotional_consulting' => [
            'sub' => '情感咨询',
            'desc' => '您完成了一次情感咨询服务',
        ],
    ],
    'mlts' => [

    ],
];
return arrayToObject($data);
