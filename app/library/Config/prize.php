<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/12
 * Time: 17:37
 */
return [
    "platform" => [
        P_ANGEL=> "魅力天使",

    ],
    'bussiness_start_time'=> strtotime('2019-01-01 00:00:00'),

    "img_url"=>'',
    "exchange_type"=>[
        1 => "积分兑换",
        2 => "积分+钱",
        3 => "定制礼物"
    ],
    "prize_type"=>[
        1 =>"虚拟",
        2 =>"实物"
    ],
    "sent_type"=>[
        1 => "邮箱",
        2 => "邮寄",

    ],
    "status"=>[
        1 => "下架",
        2 => "上架"
    ],
    "order_status"=>[
        "-1"=>"拒绝",
        "0"=>"待审核",
        "1"=>"通过"
    ]
];